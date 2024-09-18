<!-- packages/startupful/contents-generate/resources/views/pages/audio-content.blade.php -->
<div>
    @if($getRecord()->file_name)
        @php
            $audioUrl = asset('storage/' . basename($getRecord()->file_name));
            $mimeType = Storage::mimeType('public/' . basename($getRecord()->file_name)) ?? 'audio/mpeg';
        @endphp
        <audio controls>
            <source src="{{ $audioUrl }}" type="{{ $mimeType }}">
            Your browser does not support the audio element.
        </audio>
    @else
        <p>Audio file not found.</p>
    @endif
</div>