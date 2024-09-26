@vite('resources/css/app.css')
<style>
    .CodeMirror-fullscreen {
        height: calc(100vh - 64px) !important;
    }
</style>
<div>
    <div class="mb-4 flex justify-between">
        <div class="hidden lg:block">
            <button id="mobile-view" class="btn btn-outline"><x-heroicon-o-device-phone-mobile class="h-6 w-6 mr-2" />{{ __('button.mobile') }}</button>
            <button id="tablet-view" class="btn btn-outline"><x-heroicon-o-device-tablet class="h-6 w-6 mr-2" />{{ __('button.tablet') }}</button>
            <button id="desktop-view" class="btn btn-outline"><x-heroicon-o-computer-desktop class="h-6 w-6 mr-2" />{{ __('button.desktop') }}</button>
        </div>
        <div>
            <button id="copy-code" class="btn btn-outline"><x-heroicon-o-clipboard class="h-6 w-6 mr-2" />{{ __('button.copy_code') }}</button>
            <button id="fullscreen-view" class="btn btn-outline"><x-heroicon-o-arrows-pointing-out class="h-6 w-6 mr-2" />{{ __('button.fullscreen') }}</button>
            <button id="toggle-code" class="btn btn-outline"><x-heroicon-o-code-bracket class="h-6 w-6 mr-2" /><span id="toggleText">{{ __('button.view_code') }}</span></button>
        </div>
    </div>
    
    <div id="code-container" class="border border-color-basic overflow-hidden rounded-xl mb-4 hidden">
        <textarea id="code-editor">{{ $getRecord()->getCodeContent() }}</textarea>
    </div>
    
    <div class="border border-color-basic rounded-xl">
        <div class="w-full h-11 rounded-t-lg bg-gray-200 flex justify-start items-center space-x-1.5 px-3">
            <span class="w-3 h-3 rounded-full bg-red-400"></span>
            <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
            <span class="w-3 h-3 rounded-full bg-green-400"></span>
        </div>
        <iframe id="preview-frame" srcdoc="{{ $getRecord()->getCodeContent() }}" class="w-full h-[70vh] rounded-b-lg"></iframe>
    </div>
</div>

<div id="fullscreen-container" class="fixed inset-0 bg-basic z-50 hidden">
    <div id="fullscreen-controls" class="bg-basic p-4 flex justify-between items-center">
        <div>
            <button id="fullscreen-mobile-view" class="btn btn-outline"><x-heroicon-o-device-phone-mobile class="h-6 w-6 mr-2" />{{ __('button.mobile') }}</button>
            <button id="fullscreen-tablet-view" class="btn btn-outline"><x-heroicon-o-device-tablet class="h-6 w-6 mr-2" />{{ __('button.tablet') }}</button>
            <button id="fullscreen-desktop-view" class="btn btn-outline"><x-heroicon-o-computer-desktop class="h-6 w-6 mr-2" />{{ __('button.desktop') }}</button>
        </div>
        <div>
            <button id="fullscreen-copy-code" class="btn btn-outline"><x-heroicon-o-clipboard class="h-6 w-6 mr-2" />{{ __('button.copy_code') }}</button>
            <button id="fullscreen-toggle-code" class="btn btn-outline"><x-heroicon-o-code-bracket class="h-6 w-6 mr-2" /><span id="fullscreen-toggleText">{{ __('button.view_code') }}</span></button>
            <button id="close-fullscreen" class="btn btn-outline"><x-heroicon-o-x-mark class="h-6 w-6 mr-2" />Close</button>
        </div>
    </div>
    <div class="flex h-[calc(100%-64px)]"> <!-- 상단 컨트롤 높이를 뺀 나머지 높이 -->
        <div id="fullscreen-preview" class="flex-grow overflow-auto"></div>
        <div id="fullscreen-code-container" class="hidden h-full w-1/3 overflow-auto border-l border-gray-200">
            <textarea id="fullscreen-code-editor" class="h-full"></textarea>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/dracula.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const previewFrame = document.getElementById('preview-frame');
    const codeContainer = document.getElementById('code-container');
    const toggleCodeButton = document.getElementById('toggle-code');
    const copyCodeButton = document.getElementById('copy-code');
    const fullscreenButton = document.getElementById('fullscreen-view');
    const codeEditorElement = document.getElementById('code-editor');
    const toggleText = document.getElementById('toggleText');

    const fullscreenContainer = document.getElementById('fullscreen-container');
    const fullscreenPreview = document.getElementById('fullscreen-preview');
    const fullscreenCodeContainer = document.getElementById('fullscreen-code-container');
    const fullscreenCodeEditor = document.getElementById('fullscreen-code-editor');
    const closeFullscreenButton = document.getElementById('close-fullscreen');

    let codeEditor, fullscreenCodeEditorInstance;

    // 코드 내용을 안전하게 가져오기
    const code = codeEditorElement ? codeEditorElement.value : '';

    // CodeMirror 인스턴스 초기화
    function initCodeMirror() {
        if (!codeEditor && codeEditorElement) {
            codeEditor = CodeMirror.fromTextArea(codeEditorElement, {
                mode: 'htmlmixed',
                theme: 'dracula',
                lineNumbers: true,
                readOnly: true,
                lineWrapping: true
            });
            if (code) {
                codeEditor.setValue(code);
            }
        }

        if (!fullscreenCodeEditorInstance && fullscreenCodeEditor) {
            fullscreenCodeEditorInstance = CodeMirror.fromTextArea(fullscreenCodeEditor, {
                mode: 'htmlmixed',
                theme: 'dracula',
                lineNumbers: true,
                readOnly: true,
                lineWrapping: true
            });
            if (code) {
                fullscreenCodeEditorInstance.setValue(code);
            }
        }
    }

    // 페이지 로드 시 CodeMirror 초기화
    initCodeMirror();

    // 뷰 모드 버튼
    ['mobile', 'tablet', 'desktop'].forEach(mode => {
        const button = document.getElementById(`${mode}-view`);
        if (button) {
            button.addEventListener('click', () => setViewMode(mode));
        }
        const fullscreenButton = document.getElementById(`fullscreen-${mode}-view`);
        if (fullscreenButton) {
            fullscreenButton.addEventListener('click', () => setViewMode(mode, true));
        }
    });

    function setViewMode(mode, isFullscreen = false) {
        const frame = isFullscreen ? fullscreenPreview.querySelector('iframe') : previewFrame;
        if (frame) {
            switch(mode) {
                case 'mobile':
                    frame.style.width = '375px';
                    break;
                case 'tablet':
                    frame.style.width = '768px';
                    break;
                case 'desktop':
                    frame.style.width = '100%';
                    break;
            }
            frame.style.margin = mode !== 'desktop' ? '0 auto' : '0';
        }
    }

    // 코드 보기 토글
    if (toggleCodeButton) {
        toggleCodeButton.addEventListener('click', () => toggleCodeView());
    }

    function toggleCodeView(isFullscreen = false) {
        const container = isFullscreen ? fullscreenCodeContainer : codeContainer;
        const text = isFullscreen ? document.getElementById('fullscreen-toggleText') : toggleText;
        const preview = isFullscreen ? fullscreenPreview : previewFrame.parentElement;
        
        if (container && text) {
            if (container.classList.contains('hidden')) {
                container.classList.remove('hidden');
                text.textContent = '코드 숨기기';
                if (isFullscreen) {
                    preview.classList.remove('w-full');
                    preview.classList.add('w-2/3');
                    if (fullscreenCodeEditorInstance) {
                        fullscreenCodeEditorInstance.getWrapperElement().classList.add('CodeMirror-fullscreen');
                        fullscreenCodeEditorInstance.refresh();
                    }
                }
            } else {
                container.classList.add('hidden');
                text.textContent = '코드 보기';
                if (isFullscreen) {
                    preview.classList.remove('w-2/3');
                    preview.classList.add('w-full');
                    if (fullscreenCodeEditorInstance) {
                        fullscreenCodeEditorInstance.getWrapperElement().classList.remove('CodeMirror-fullscreen');
                    }
                }
            }
        }
    }

    // 풀스크린 토글 코드 버튼 이벤트 리스너
    const fullscreenToggleCode = document.getElementById('fullscreen-toggle-code');
    if (fullscreenToggleCode) {
        fullscreenToggleCode.addEventListener('click', () => toggleCodeView(true));
    }

    // 코드 복사
    if (copyCodeButton) {
        copyCodeButton.addEventListener('click', copyCode);
    }

    function copyCode() {
        navigator.clipboard.writeText(code).then(() => {
            alert('코드가 복사되었습니다.');
        }, (err) => {
            console.error('텍스트를 복사할 수 없습니다: ', err);
        });
    }

    // 전체 화면 모드
    if (fullscreenButton) {
        fullscreenButton.addEventListener('click', () => {
            fullscreenContainer.classList.remove('hidden');
            
            // 기존 iframe을 fullscreenPreview로 이동
            if (previewFrame && fullscreenPreview) {
                fullscreenPreview.innerHTML = '';
                const clonedFrame = previewFrame.cloneNode(true);
                clonedFrame.style.height = '100%';
                clonedFrame.style.width = '100%';
                clonedFrame.classList.remove('rounded-b-lg');
                fullscreenPreview.appendChild(clonedFrame);
            }
            
            document.body.style.overflow = 'hidden';

            // 코드 에디터 초기화
            initCodeMirror();
        });
    }

    if (closeFullscreenButton) {
        closeFullscreenButton.addEventListener('click', () => {
            fullscreenContainer.classList.add('hidden');
            document.body.style.overflow = 'auto';
            
            // fullscreenPreview에서 iframe 제거
            if (fullscreenPreview) {
                fullscreenPreview.innerHTML = '';
            }

            // 코드 보기가 열려있었다면 닫기
            if (!fullscreenCodeContainer.classList.contains('hidden')) {
                toggleCodeView(true);
            }
        });
    }

    

    // 메인 문서에서 모든 스타일을 가져오는 헬퍼 함수
    function getStyles() {
        let styles = '';
        for (let i = 0; i < document.styleSheets.length; i++) {
            const sheet = document.styleSheets[i];
            try {
                const rules = sheet.cssRules || sheet.rules;
                for (let j = 0; j < rules.length; j++) {
                    styles += rules[j].cssText + '\n';
                }
            } catch (e) {
                console.log('스타일시트 접근 오류 (CORS 제한일 수 있음):', e);
            }
        }
        return styles;
    }
});
</script>