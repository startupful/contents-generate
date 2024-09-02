@extends('layouts.app')

@section('content')
<script src='https://cdn.tailwindcss.com'></script>
<div class="flex">
    <!-- 좌측 사이드바 -->
    <div class="max-w-xs w-full p-4">
        @include('contents-summary::partials.sidebar')
    </div>

    <!-- 중앙 요약 결과 리스트 -->
    <div class="w-1/2 p-4">
        <h2 class="text-2xl font-bold mb-4">Explore Summaries</h2>
        <div id="summary-list">
        @foreach($summaries as $summary)
    <div class="summary-item cursor-pointer mb-4 p-4 border rounded flex gap-5 overflow-hidden" data-uuid="{{ $summary->uuid }}">
        <!-- 좌측 썸네일 또는 아이콘 -->
        <div class="w-48 h-24 flex-shrink-0 overflow-hidden">
            @if($summary->thumbnail)
            <div class="w-full h-full bg-gray-200 flex items-center justify-center overflow-hidden">
                <img src="{{ $summary->thumbnail }}" alt="{{ $summary->title }}" class="w-full h-full object-cover">
            </div>
            @else
                <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded">
                    @switch($summary->type)
                        @case('Article')
                            <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            @break
                        @case('Video')
                            <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            @break
                        @case('Audio')
                            <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                            @break
                        @case('PDF')
                            <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            @break
                        @case('PPT')
                            <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 13v-1m4 1v-3m4 3V8M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                            @break
                        @default
                            <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    @endswitch
                </div>
            @endif
        </div>
        <!-- 중앙 컨텐츠 -->
        <div class="flex-grow min-w-0">
            <!-- 최상단 뱃지 -->
            <div class="flex items-center space-x-2 mb-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    @switch($summary->type)
                        @case('Article')
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            @break
                        @case('Video')
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            @break
                        @case('Audio')
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                            @break
                        @case('PDF')
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            @break
                        @case('PPT')
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 13v-1m4 1v-3m4 3V8M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                            @break
                        @default
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    @endswitch
                    {{ $summary->type }}
                </span>
            </div>
            <h3 class="text-lg font-semibold mb-2 truncate">{{ $summary->title }}</h3>
            <p class="text-sm text-gray-600 line-clamp-2">
                {!! Str::limit(strip_tags($summary->content), 400) !!}
            </p>
        </div>
    </div>
@endforeach
        </div>
        {{ $summaries->links() }}
    </div>

    <!-- 우측 모바일 뷰 (처음에는 숨겨져 있음) -->
    <div id="mobile-view" class="max-w-md w-full p-4 hidden">
        <iframe id="summary-iframe" class="max-w-xl w-full h-full prose border-0" srcdoc="
            <html>
            <head>
                <script src='https://cdn.tailwindcss.com'></script>
                <script>
                    tailwind.config = {
                        plugins: [require('@tailwindcss/typography')],
                    }
                </script>
            </head>
            <body class='prose'>
            </body>
            </html>
        "></iframe>
    </div>
</div>

<script>
document.querySelectorAll('.summary-item').forEach(item => {
    item.addEventListener('click', function() {
        const uuid = this.dataset.uuid;
        fetch(`/contents-summary/partial/${uuid}`)
            .then(response => response.text())
            .then(html => {
                const iframe = document.getElementById('summary-iframe');
                const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                iframeDoc.body.innerHTML = html;
                document.getElementById('mobile-view').classList.remove('hidden');
                
                // Tailwind CSS와 prose 스타일이 적용되도록 클래스 추가
                iframeDoc.body.classList.add('prose', 'max-w-none', 'px-4', 'py-8');
                
                // 부모 페이지의 다크 모드 상태를 확인하고 iframe에 적용
                if (document.documentElement.classList.contains('dark')) {
                    iframeDoc.body.classList.add('dark');
                }

                // 다크 모드 토글 감지 및 적용
                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                            const isDark = document.documentElement.classList.contains('dark');
                            iframeDoc.body.classList.toggle('dark', isDark);
                        }
                    });
                });

                observer.observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ['class'],
                });
            });
    });
});
</script>
@endsection