<div class="bg-white shadow-sm rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">{{ $contentSummary->title }}</h2>
    <div class="mb-4 text-sm text-gray-600">
        <span>{{ $contentSummary->type }} | {{ $contentSummary->author }} | {{ $contentSummary->published_date }}</span>
    </div>
    @if($contentSummary->thumbnail)
        <img src="{{ $contentSummary->thumbnail }}" alt="{{ $contentSummary->title }}" class="mb-4 rounded-lg w-full h-48 object-cover">
    @endif
    <div class="prose max-w-none">
        {!! $contentSummary->content !!}
    </div>
    <div class="mt-6 text-sm text-gray-500">
        <a href="{{ $contentSummary->original_url }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">Original Source</a>
    </div>
</div>