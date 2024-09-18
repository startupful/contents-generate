@vite('resources/css/app.css')
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

<div id="fullscreen-container" class="fixed inset-0 bg-white z-50 hidden flex flex-col">
    <div id="fullscreen-controls" class="bg-white p-4 flex justify-between items-center">
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
    <div id="fullscreen-preview" class="flex-grow overflow-auto"></div>
    <div id="fullscreen-code-container" class="hidden h-1/2 overflow-auto">
        <textarea id="fullscreen-code-editor"></textarea>
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

    const code = {!! json_encode($getRecord()->getCodeContent()) !!};

    function initCodeMirror() {
        if (!codeEditor) {
            codeEditor = CodeMirror.fromTextArea(codeEditorElement, {
                mode: 'htmlmixed',
                theme: 'dracula',
                lineNumbers: true,
                readOnly: true,
                lineWrapping: true
            });
            codeEditor.setValue(code);
        }

        if (!fullscreenCodeEditorInstance) {
            fullscreenCodeEditorInstance = CodeMirror.fromTextArea(fullscreenCodeEditor, {
                mode: 'htmlmixed',
                theme: 'dracula',
                lineNumbers: true,
                readOnly: true,
                lineWrapping: true
            });
            fullscreenCodeEditorInstance.setValue(code);
        }
    }

    // View mode buttons
    ['mobile', 'tablet', 'desktop'].forEach(mode => {
        document.getElementById(`${mode}-view`).addEventListener('click', () => setViewMode(mode));
        document.getElementById(`fullscreen-${mode}-view`).addEventListener('click', () => setViewMode(mode, true));
    });

    function setViewMode(mode, isFullscreen = false) {
        const frame = isFullscreen ? fullscreenPreview.querySelector('iframe') : previewFrame;
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

    // Toggle code view
    toggleCodeButton.addEventListener('click', () => toggleCodeView());
    document.getElementById('fullscreen-toggle-code').addEventListener('click', () => toggleCodeView(true));

    function toggleCodeView(isFullscreen = false) {
        initCodeMirror();
        const container = isFullscreen ? fullscreenCodeContainer : codeContainer;
        const text = isFullscreen ? document.getElementById('fullscreen-toggleText') : toggleText;
        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            text.textContent = '{{ __('button.hide_code') }}';
            (isFullscreen ? fullscreenCodeEditorInstance : codeEditor).refresh();
        } else {
            container.classList.add('hidden');
            text.textContent = '{{ __('button.view_code') }}';
        }
    }

    // Copy code
    copyCodeButton.addEventListener('click', () => copyCode());
    document.getElementById('fullscreen-copy-code').addEventListener('click', () => copyCode());

    function copyCode() {
        navigator.clipboard.writeText(getCode()).then(() => {
            alert('{{ __('button.code_copied') }}');
        }, (err) => {
            console.error('Could not copy text: ', err);
        });
    }

    // Fullscreen mode
    fullscreenButton.addEventListener('click', () => {
        fullscreenContainer.classList.remove('hidden');
        fullscreenPreview.innerHTML = `<iframe srcdoc="${getCode()}" class="w-full h-full"></iframe>`;
        document.body.style.overflow = 'hidden';
    });

    closeFullscreenButton.addEventListener('click', () => {
        fullscreenContainer.classList.add('hidden');
        document.body.style.overflow = 'auto';
    });
});
</script>