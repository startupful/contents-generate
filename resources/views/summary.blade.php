@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-2 lg:py-8">
    <div class="flex flex-col lg:flex-row">
        <!-- Main content area -->
        <div class="w-full lg:w-3/4 lg:pr-8">
            @if($content_summaries->thumbnail)
            <img src="{{ asset($content_summaries->thumbnail) }}" alt="Thumbnail" class="w-full h-[200px] md:h-[500px] object-cover rounded-lg shadow-lg mb-6">
            @endif
            
            <!-- Metadata section for mobile -->
            <div class="lg:hidden mb-8">
                @include('contents-summary::partials.metadata')
            </div>

    
            <div class="prose max-w-none
                prose-h1:text-black dark:prose-h1:text-white
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
                <div class="flex items-center my-8">
                    <h1 class="font-bold text-2xl lg:text-3xl">{{ $content_summaries->title }}</h1>
                </div>
                {!! $content_summaries->content !!}
            </div>
        </div>

        <!-- Metadata section for desktop -->
        <div class="hidden lg:block lg:w-1/4 mt-8 lg:mt-0 space-y-4 text-sm text-black dark:text-white">
            @include('contents-summary::partials.metadata')
        </div>
    </div>
</div>
@endsection