@extends('layouts.app')

@section('content')
<script src='https://cdn.tailwindcss.com'></script>
<div class="mx-auto px-4 sm:px-6 lg:px-8 py-2 lg:py-8">
    <div class="flex flex-col lg:flex-row">
        <!-- Main content area -->
        <div class="w-full">
            @if($content_summaries->thumbnail)
            <img src="{{ asset($content_summaries->thumbnail) }}" alt="Thumbnail" class="w-full h-[200px] md:h-[500px] object-cover rounded-lg shadow-lg mb-6">
            @endif
            
            <!-- Metadata section for mobile -->
            <div class="mb-8">
                @include('contents-summary::partials.metadata')
            </div>

            <div class="flex items-center mb-10">
                <h1 class="text-2xl font-bold text-black dark:text-white">{{ $content_summaries->title }}</h1>
            </div>
            <div class="prose max-w-none
                prose-h2:text-black dark:prose-h2:text-white prose-h2:text-2xl
                prose-h3:text-black dark:prose-h3:text-white prose-h3:text-xl
                prose-h4:text-black dark:prose-h4:text-white
                prose-h5:text-black dark:prose-h5:text-white
                prose-strong:text-black dark:prose-strong:text-white
                prose-p:text-gray-600 dark:prose-p:text-gray-300 prose-p:text-base
                prose-li:text-gray-600 dark:prose-li:text-gray-300 
                prose-ul:text-gray-600 dark:prose-ul:text-gray-300
                prose-li:text-sm prose-ul:text-sm
                prose-a:text-indigo-500 hover:prose-a:text-indigo-600
                prose-img:rounded-xl
                dark:prose-invert">
                {!! $content_summaries->content !!}
            </div>
        </div>

    </div>
</div>
@endsection