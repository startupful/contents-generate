@vite('resources/css/app.css')
@push('styles')
<style>
    .excel-table {
        border-collapse: collapse;
        width: 100%;
    }
    .excel-table th, .excel-table td {
        border: 1px solid #e2e8f0;
        padding: 0.75rem;
    }
</style>
@endpush

<div class="overflow-x-auto">
    <table class="excel-table">
        @php
            $excelContent = $this->getExcelContent();
            $data = $excelContent['data'];
            $mergedCells = $excelContent['mergedCells'];
        @endphp
        @foreach ($data as $rowIndex => $row)
            <tr>
                @foreach ($row as $colIndex => $cell)
                    @php
                        $style = $cell['style'];
                        $cellStyle = "";
                        if ($style['font']['bold']) {
                            $cellStyle .= "font-weight: bold;";
                        }
                        if ($style['font']['color']) {
                            $cellStyle .= "color: #{$style['font']['color']};";
                        }
                        if ($style['fill']['color']) {
                            $cellStyle .= "background-color: #{$style['fill']['color']};";
                        }
                        if (isset($style['alignment']['horizontal'])) {
                            $cellStyle .= "text-align: {$style['alignment']['horizontal']};";
                        }
                        if (isset($style['alignment']['vertical'])) {
                            $cellStyle .= "vertical-align: {$style['alignment']['vertical']};";
                        }

                        $colspan = 1;
                        $rowspan = 1;
                        foreach ($mergedCells as $range) {
                            list($startCell, $endCell) = explode(':', $range);
                            $startCoords = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::coordinateFromString($startCell);
                            $endCoords = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::coordinateFromString($endCell);
                            
                            $startCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($startCoords[0]);
                            $startRow = $startCoords[1];
                            $endCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($endCoords[0]);
                            $endRow = $endCoords[1];
                            
                            if ($rowIndex + 1 == $startRow && $colIndex + 1 == $startCol) {
                                $colspan = $endCol - $startCol + 1;
                                $rowspan = $endRow - $startRow + 1;
                                break;
                            } elseif ($rowIndex + 1 > $startRow && $rowIndex + 1 <= $endRow && $colIndex + 1 >= $startCol && $colIndex + 1 <= $endCol) {
                                $colspan = 0;
                                $rowspan = 0;
                                break;
                            }
                        }
                    @endphp
                    @if ($colspan > 0 && $rowspan > 0)
                        @if ($rowIndex === 0)
                            <th style="{{ $cellStyle }}" colspan="{{ $colspan }}" rowspan="{{ $rowspan }}">
                                {{ $cell['value'] }}
                            </th>
                        @else
                            <td style="{{ $cellStyle }}" colspan="{{ $colspan }}" rowspan="{{ $rowspan }}">
                                {{ $cell['value'] }}
                            </td>
                        @endif
                    @endif
                @endforeach
            </tr>
        @endforeach
    </table>
</div>