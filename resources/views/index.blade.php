@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<style>
        .active-btn {
            background-color: #FFD700; /* Yellow color for active button */
        }
        input[type="text"], input[type="url"], input[type="file"] {
            border: none;
        }
</style>
<div class="mx-auto px-4 xl:h-[calc(100vh-5rem)] md:overflow-hidden">

<div class="mb-10 xl:mb-28 flex flex-col lg:flex-row justify-between items-center gap-10">
    <div class="w-full">
  <h2 class="text-left text-3xl font-bold tracking-wider md:leading-relaxed text-black md:text-7xl dark:text-white">
    Stay Ahead.
  </h2>
  <h2 class="text-left text-3xl font-bold tracking-wider md:leading-relaxed text-black md:text-7xl dark:text-white">
    Summarize Quickly.
  </h2>
    <h2 class="text-left text-3xl font-bold tracking-wider md:leading-relaxed text-black md:text-7xl dark:text-white">
    Accelerate.
  </h2>
</div>

<div class="max-w-lg w-full">
<div class="flex flex-col space-y-4">
    <div class="flex flex-row items-center space-x-2 mb-4">
        <button id="article-btn" class="flex items-center px-3 py-2 text-sm bg-white rounded-full shadow-md active-btn">
            <span class="">Website</span>
        </button>
        <button id="youtube-btn" class="flex items-center px-3 py-2 text-sm bg-white rounded-full shadow-md">
            <span class="">Youtube</span>
        </button>
        <button id="audio-btn" class="flex items-center px-3 py-2 text-sm bg-white rounded-full shadow-md">
                <span class="">Sound</span>
            </button>
        <button id="pdf-btn" class="flex items-center px-3 py-2 text-sm bg-white rounded-full shadow-md">
            <span class="">PDF</span>
        </button>
        <button id="ppt-btn" class="flex items-center px-3 py-2 text-sm bg-white rounded-full shadow-md">
            <span class="">PPT</span>
        </button>
    </div>

    <form id="article-form" action="{{ route('contents-summary.summarize', ['type' => 'article']) }}" method="POST" class="flex items-center mb-4">
        @csrf
        <input type="url" placeholder="요약할 웹사이트 URL 붙여넣기" class="rounded-lg appearance-none border border-gray-300 w-full py-2 px-4 bg-white dark:bg-[#1e2028] text-gray-700 dark:text-white placeholder-gray-400 shadow-sm text-base focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent" id="article-url" name="url" required>
        <button type="submit" class="ml-4 text-2xl text-blue-500">
            &rarr;
        </button>
    </form>

    <form id="youtube-form" action="{{ route('contents-summary.summarize', ['type' => 'youtube']) }}" method="POST" class="hidden flex items-center mb-4">
        @csrf
        <input type="text" placeholder="요약할 YouTube URL 붙여넣기" class="rounded-lg appearance-none border border-gray-300 w-full py-2 px-4 bg-white dark:bg-[#1e2028] text-gray-700 dark:text-white placeholder-gray-400 shadow-sm text-base focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent" id="youtube-id" name="video_id" required>
        <button type="submit" class="ml-4 text-2xl text-blue-500">
            &rarr;
        </button>
    </form>

    <form id="audio-form" action="{{ route('contents-summary.summarize', ['type' => 'audio']) }}" method="POST" enctype="multipart/form-data" class="hidden flex items-center mb-4 bg-white rounded-full shadow-md px-4 py-2">
            @csrf
            <input type="file" class="flex-grow p-2 bg-transparent outline-none" id="audio-file" name="audio" accept=".flac,.m4a,.mp3,.mp4,.mpeg,.mpga,.oga,.ogg,.wav,.webm" required>
            <button type="submit" class="ml-4 text-2xl text-blue-500">
                &rarr;
            </button>
        </form>

    <form id="pdf-form" action="{{ route('contents-summary.summarize', ['type' => 'pdf']) }}" method="POST" enctype="multipart/form-data" class="hidden flex items-center mb-4 bg-white rounded-full shadow-md px-4 py-2">
        @csrf
        <input type="file" class="flex-grow p-2 bg-transparent outline-none" id="pdf-file" name="pdf" accept=".pdf" required>
        <button type="submit" class="ml-4 text-2xl text-blue-500">
            &rarr;
        </button>
    </form>

    <form id="ppt-form" action="{{ route('contents-summary.summarize', ['type' => 'ppt']) }}" method="POST" enctype="multipart/form-data" class="hidden flex items-center mb-4 bg-white rounded-full shadow-md px-4 py-2">
        @csrf
        <input type="file" class="flex-grow p-2 bg-transparent outline-none" id="ppt-file" name="ppt" accept=".ppt,.pptx" required>
        <button type="submit" class="ml-4 text-2xl text-blue-500">
            &rarr;
        </button>
    </form>
</div>
</div>

<script>
    document.getElementById('article-btn').addEventListener('click', function() {
        showForm('article-form');
        setActiveButton('article-btn');
    });

    document.getElementById('youtube-btn').addEventListener('click', function() {
        showForm('youtube-form');
        setActiveButton('youtube-btn');
    });

    document.getElementById('audio-btn').addEventListener('click', function() {
            showForm('audio-form');
            setActiveButton('audio-btn');
        });

    document.getElementById('pdf-btn').addEventListener('click', function() {
        showForm('pdf-form');
        setActiveButton('pdf-btn');
    });

    document.getElementById('ppt-btn').addEventListener('click', function() {
        showForm('ppt-form');
        setActiveButton('ppt-btn');
    });

    function showForm(formId) {
        document.getElementById('article-form').classList.add('hidden');
        document.getElementById('youtube-form').classList.add('hidden');
        document.getElementById('audio-form').classList.add('hidden');
        document.getElementById('pdf-form').classList.add('hidden');
        document.getElementById('ppt-form').classList.add('hidden');
        document.getElementById(formId).classList.remove('hidden');
    }

    function setActiveButton(buttonId) {
        document.getElementById('article-btn').classList.remove('active-btn');
        document.getElementById('youtube-btn').classList.remove('active-btn');
        document.getElementById('audio-btn').classList.remove('active-btn');
        document.getElementById('pdf-btn').classList.remove('active-btn');
        document.getElementById('ppt-btn').classList.remove('active-btn');
        document.getElementById(buttonId).classList.add('active-btn');
    }
</script>


</div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-10 mb-20">
            <div class="rounded-lg  flex">
                <img src="https://via.placeholder.com/80" alt="Placeholder" class="rounded-lg mr-4">
                <div>
                    <p class="text-lg md:text-2xl font-bold text-black dark:text-white">How to manage information as a Proje...</p>
                    <p class="text-sm md:text-base text-gray-500">From our blog</p>
                </div>
            </div>
            <div class="rounded-lg flex">
                <img src="https://via.placeholder.com/80" alt="Placeholder" class="rounded-lg mr-4">
                <div>
                    <p class="text-lg md:text-2xl font-bold text-black dark:text-white">Dia. An app to empower women in their fertility...</p>
                    <p class="text-sm md:text-base text-gray-500">From our projects</p>
                </div>
            </div>
            <div class="rounded-lg flex">
                <img src="https://via.placeholder.com/80" alt="Placeholder" class="rounded-lg mr-4">
                <div>
                    <p class="text-lg md:text-2xl font-bold text-black dark:text-white">allO. Supercharging restaurants.</p>
                    <p class="text-sm md:text-base text-gray-500">From our projects</p>
                </div>
            </div>
            <div class="rounded-lg flex">
                <img src="https://via.placeholder.com/80" alt="Placeholder" class="rounded-lg mr-4">
                <div>
                    <p class="text-lg md:text-2xl font-bold text-black dark:text-white">We needed a miracle to launch our new website.</p>
                    <p class="text-sm md:text-base text-gray-500">From our blog</p>
                </div>
            </div>
        </div>



        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 h-full">
            <!-- Image 1 -->
            <img src="https://cdn.startupful.io/img/bg/black_blue1.jpg" alt="Placeholder 1" class="w-auto h-full xl:rounded-tl-lg">
            <!-- Image 2 -->
            <img src="https://cdn.startupful.io/img/bg/blue_pink1.jpg" alt="Placeholder 2" class="w-auto h-full">
            <!-- Image 3 -->
            <img src="https://cdn.startupful.io/img/bg/darkblue_purple1.jpg" alt="Placeholder 3" class="w-auto h-full">
            <!-- Image 4 -->
            <img src="https://cdn.startupful.io/img/bg/blue_blur1.jpg" alt="Placeholder 4" class="w-auto h-full xl:rounded-tr-xl">
        </div>



</div>
@endsection