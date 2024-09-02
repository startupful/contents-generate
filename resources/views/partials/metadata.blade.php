<!-- partials/metadata.blade.php -->
<!-- Type -->
<div class="flex justify-between items-center text-black dark:text-white rounded-full p-2">
    <div class="flex items-center">
        <x-heroicon-o-document-text class="h-4 w-4 mr-2" />
        <span class="font-semibold">Type</span>
    </div>
    <span>{{ $content_summaries->type }}</span>
</div>

<!-- Channel -->
<div class="flex justify-between items-center text-black dark:text-white rounded-full p-2">
    <div class="flex items-center">
        <x-heroicon-o-globe-alt class="h-4 w-4 mr-2" />
        <span class="font-semibold">Channel</span>
    </div>
    <div class="flex items-center">
    @if($content_summaries->favicon)
        <img src="{{  asset($content_summaries->favicon) }}" alt="Favicon" class="w-4 h-4 mr-1">
        @endif
        @if($content_summaries->brand)
        <span>{{ $content_summaries->brand }}</span>
        @endif
    </div>
</div>

<!-- Author -->
<div class="flex justify-between items-center text-black dark:text-white rounded-full p-2">
    <div class="flex items-center">
        <x-heroicon-o-user class="h-4 w-4 mr-2" />
        <span class="font-semibold">Author</span>
    </div>
    <div class="flex items-center">
    @if(isset($profileIcon) && $profileIcon)
        <img src="{{ $profileIcon }}" alt="{{ $author }}'s profile" class="w-4 h-4 mr-1 rounded-full">
    @endif
    <span>{{ $content_summaries->author }}</span>
    </div>
</div>

<!-- Date -->
<div class="flex justify-between items-center text-black dark:text-white rounded-full p-2">
    <div class="flex items-center">
        <x-heroicon-o-calendar class="h-4 w-4 mr-2" />
        <span class="font-semibold">Date</span>
    </div>
    <span>{{ $content_summaries->published_date }}</span>
</div>