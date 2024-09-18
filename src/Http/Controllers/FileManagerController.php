<?php

namespace Startupful\ContentsGenerate\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetIOFactory;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpPresentation\IOFactory as PresentationIOFactory;
use ZipArchive;
use OpenAI\Laravel\Facades\OpenAI;

class FileManagerController extends BaseController
{
    public function processFileInput($fileData)
    {
        Log::debug('Processing file input', ['fileData' => $fileData]);
        
        if (is_array($fileData)) {
            $fileInfo = reset($fileData);
            if (is_array($fileInfo) && isset($fileInfo['path'])) {
                try {
                    $absolutePath = $this->getAbsolutePath($fileInfo['path']);
                    
                    if (file_exists($absolutePath)) {
                        $url = $this->getPublicUrlForFile($fileInfo['path']);
                        
                        Log::info('File processed successfully', [
                            'original_path' => $fileInfo['path'],
                            'absolute_path' => $absolutePath,
                            'public_url' => $url
                        ]);
                        
                        return [
                            'path' => $fileInfo['path'],
                            'full_path' => $absolutePath,
                            'url' => $url,
                            'type' => $this->getFileType($absolutePath),
                            'original_name' => $fileInfo['original_name'] ?? basename($fileInfo['path']),
                            'mime_type' => $fileInfo['mime_type'] ?? mime_content_type($absolutePath)
                        ];
                    } else {
                        Log::warning('File not found', ['filePath' => $absolutePath]);
                        return null;
                    }
                } catch (\Exception $e) {
                    Log::error('Error processing file', ['error' => $e->getMessage()]);
                    return null;
                }
            }
        }
        
        Log::warning('Invalid file data', ['fileData' => $fileData]);
        return null;
    }

    private function getAbsolutePath($relativePath)
    {
        return Storage::disk('public')->path($relativePath);
    }

    public function getPublicUrlForFile($path)
    {
        return URL::to('/storage/' . $path);
    }

    public function isImageFile($path)
    {
        $mimeType = Storage::disk('public')->mimeType($path);
        return strpos($mimeType, 'image/') === 0;
    }

    public function getFileType($path)
    {
        $mimeType = Storage::disk('public')->mimeType($path);
        $fileExtension = pathinfo($path, PATHINFO_EXTENSION);

        if (strpos($mimeType, 'image/') === 0) {
            return 'image';
        } elseif (in_array($fileExtension, ['doc', 'docx', 'pdf', 'txt'])) {
            return 'document';
        } elseif (in_array($fileExtension, ['xls', 'xlsx', 'csv'])) {
            return 'spreadsheet';
        } elseif (in_array($fileExtension, ['ppt', 'pptx'])) {
            return 'presentation';
        } elseif (in_array($fileExtension, ['mp3', 'wav', 'ogg'])) {
            return 'audio';
        } elseif (in_array($fileExtension, ['mp4', 'avi', 'mov'])) {
            return 'video';
        } else {
            return 'other';
        }
    }

    public function getFileContent($path)
    {
        $absolutePath = $this->getAbsolutePath($path);
        
        if (!file_exists($absolutePath)) {
            Log::error('File not found', ['path' => $path, 'absolutePath' => $absolutePath]);
            return null;
        }

        $fileType = $this->getFileType($absolutePath);

        try {
            switch ($fileType) {
                case 'image':
                    return $this->getPublicUrlForFile($path);
                case 'document':
                    return $this->extractTextFromDocument($absolutePath);
                case 'spreadsheet':
                    return $this->extractTextFromSpreadsheet($absolutePath);
                case 'presentation':
                    return $this->extractTextFromPresentation($absolutePath);
                case 'audio':
                    return $this->transcribeAudio($absolutePath);
                case 'video':
                    return $this->getPublicUrlForFile($path);
                default:
                    return file_get_contents($absolutePath);
            }
        } catch (\Exception $e) {
            Log::error('Error processing file', [
                'error' => $e->getMessage(),
                'file' => $path,
                'fileType' => $fileType,
                'trace' => $e->getTraceAsString()
            ]);
            return "Error processing file: " . $e->getMessage();
        }
    }

    private function extractTextFromDocument($path)
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if ($extension === 'pdf') {
            return $this->extractTextFromPDF($path);
        } elseif (in_array($extension, ['doc', 'docx'])) {
            return $this->extractTextFromWord($path);
        } elseif ($extension === 'txt') {
            return file_get_contents($path);
        }

        return "Unsupported document type: $extension";
    }

    private function extractTextFromPDF($filePath)
    {
        $output = shell_exec("pdftotext '$filePath' -");
        if ($output === null) {
            Log::error('Failed to extract text from PDF', ['filePath' => $filePath]);
            throw new \Exception('Failed to extract text from PDF');
        }
        return $output;
    }

    private function extractTextFromWord($filePath)
    {
        $phpWord = WordIOFactory::load($filePath);
        $text = '';
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $text .= $element->getText() . "\n";
                }
            }
        }
        return $text;
    }

    private function extractTextFromSpreadsheet($path)
    {
        $spreadsheet = SpreadsheetIOFactory::load($path);
        $text = '';
        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $rowText = '';
                foreach ($cellIterator as $cell) {
                    $cellValue = trim($cell->getValue());  // 셀 값에서 앞뒤 공백 제거
                    if (!empty($cellValue)) {  // 셀이 빈 값이 아닌 경우에만 추가
                        $rowText .= $cellValue . "\t";
                    }
                }
                if (!empty(trim($rowText))) {  // 행 전체가 공백 또는 탭만 있는 경우 건너뜀
                    $text .= rtrim($rowText, "\t") . "\n";  // 마지막 탭 제거 후 추가
                }
            }
            $text .= "\n";
        }
        return $text;
    }

    private function extractTextFromPresentation($path)
    {
        try {
            $presentation = PresentationIOFactory::load($path);
            $text = '';
            foreach ($presentation->getAllSlides() as $slide) {
                foreach ($slide->getShapeCollection() as $shape) {
                    try {
                        if (method_exists($shape, 'getText')) {
                            $text .= $shape->getText() . "\n";
                        } elseif (method_exists($shape, 'getRichTextElements')) {
                            foreach ($shape->getRichTextElements() as $richText) {
                                $text .= $richText->getText() . "\n";
                            }
                        }
                    } catch (\Exception $shapeError) {
                        // 문제가 있는 도형을 건너뛰고 로그를 남김
                        Log::error('Error processing shape', [
                            'error' => $shapeError->getMessage(),
                            'shape' => $shape,
                            'slide_number' => $slide->getSlideNumber(),
                        ]);
                        continue; // 해당 도형 처리 건너뛰기
                    }
                }
                $text .= "\n--- New Slide ---\n\n";
            }
            return $text;
        } catch (\Exception $e) {
            Log::error('Error extracting text from PowerPoint file', [
                'error' => $e->getMessage(),
                'file' => $path,
                'trace' => $e->getTraceAsString()
            ]);

            // 대체 방법으로 텍스트 추출 시도
            return $this->extractTextFromPresentationAlternative($path);
        }
    }

    private function extractTextFromPresentationAlternative($path)
    {
        $zip = new ZipArchive;
        $text = '';

        if ($zip->open($path) === TRUE) {
            for ($i = 1; $i <= $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                if (strpos($filename, 'ppt/slides/slide') === 0) {
                    $content = $zip->getFromIndex($i);
                    $text .= strip_tags($content) . "\n--- New Slide ---\n\n";
                }
            }
            $zip->close();
        }

        if (empty($text)) {
            $fileInfo = $this->getFileInfo($path);
            return "Unable to extract text. File information: " . json_encode($fileInfo);
        }

        return $text;
    }

    private function getFileInfo($path)
    {
        $fileInfo = [
            'name' => basename($path),
            'size' => filesize($path),
            'type' => mime_content_type($path),
            'last_modified' => date("Y-m-d H:i:s", filemtime($path)),
        ];

        return $fileInfo;
    }

    private function transcribeAudio($path)
    {
        try {
            $response = OpenAI::audio()->transcribe([
                'model' => 'whisper-1',
                'file' => fopen($path, 'r'),
                'response_format' => 'text'
            ]);

            Log::info('Audio transcription completed', [
                'file' => $path,
                'transcription_length' => strlen($response->text)
            ]);

            return $response->text;
        } catch (\Exception $e) {
            Log::error('Error transcribing audio with Whisper', [
                'error' => $e->getMessage(),
                'file' => $path,
                'trace' => $e->getTraceAsString()
            ]);
            return "Error transcribing audio: " . $e->getMessage();
        }
    }

    public function deleteFile($path)
    {
        $absolutePath = $this->getAbsolutePath($path);
        if (file_exists($absolutePath)) {
            unlink($absolutePath);
            Log::info('File deleted successfully', ['path' => $path]);
            return true;
        } else {
            Log::warning('File not found for deletion', ['path' => $path]);
            return false;
        }
    }

    public function moveFile($oldPath, $newPath)
    {
        $absoluteOldPath = $this->getAbsolutePath($oldPath);
        $absoluteNewPath = $this->getAbsolutePath($newPath);
        if (file_exists($absoluteOldPath)) {
            rename($absoluteOldPath, $absoluteNewPath);
            Log::info('File moved successfully', ['from' => $oldPath, 'to' => $newPath]);
            return true;
        } else {
            Log::warning('File not found for moving', ['path' => $oldPath]);
            return false;
        }
    }
}