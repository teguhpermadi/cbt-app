<div class="grid gap-3 w-full">
    @foreach($options as $option)
    <!-- card -->
    <x-mary-card shadow class="flex-row items-center gap-4 p-3">
        <div class="flex items-center gap-4">
            <span class="font-bold text-lg min-w-[2rem] text-center">{{ $option->option_key }}</span>
            <div class="flex-1">
                @if($option->getMediaUrl())
                <img src="{{ $option->getMediaUrl() }}" alt="Option Image" class="max-w-xs h-auto rounded-lg mb-2 border border-gray-200" />
                @endif
                <p>{{ $option->content }}</p>
            </div>
        </div>
    </x-mary-card>
    @endforeach
</div>