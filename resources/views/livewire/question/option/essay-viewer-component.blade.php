<div class="w-full">
    @if($options->isNotEmpty())
    @php
    $option = $options->first();
    @endphp
    <div class="p-4 border rounded-lg bg-base-200/50">
        <h4 class="font-semibold mb-2 text-sm uppercase tracking-wider text-base-content/70">Kunci Jawaban / Referensi</h4>
        <div class="prose max-w-none">
            {!! $option->content !!}
        </div>

        @if($option->hasOptionMedia())
        <div class="mt-3">
            <img src="{{ $option->getMediaUrl() }}" alt="Reference Media" class="max-w-md rounded-lg shadow-sm">
        </div>
        @endif
    </div>
    @else
    <div class="text-sm text-gray-400 italic">
        Belum ada kunci jawaban.
    </div>
    @endif
</div>