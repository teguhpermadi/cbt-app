<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Left Column --}}
    <div class="space-y-3">
        @foreach($leftOptions as $leftOption)
        <div class="flex items-start p-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-600 font-bold rounded-full mr-3 mt-1">
                {{ $leftOption->option_key }}
            </div>
            <div class="flex-grow text-gray-700 prose">
                {!! $leftOption->content !!}
                @if($leftOption->hasOptionMedia())
                <div class="mt-2">
                    <img src="{{ $leftOption->getMediaUrl() }}" alt="Option Media" class="max-w-xs rounded-lg shadow-sm">
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Right Column --}}
    <div class="space-y-3">
        @foreach($rightOptions as $rightOption)
        <div class="flex items-start p-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-green-100 text-green-600 font-bold rounded-full mr-3 mt-1">
                {{ $rightOption->option_key }}
            </div>
            <div class="flex-grow text-gray-700 prose">
                {!! $rightOption->content !!}
                @if($rightOption->hasOptionMedia())
                <div class="mt-2">
                    <img src="{{ $rightOption->getMediaUrl() }}" alt="Option Media" class="max-w-xs rounded-lg shadow-sm">
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>