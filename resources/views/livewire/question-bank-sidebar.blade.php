<div>
    <div class="px-4 mb-4">
        <h3 class="text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-2">Daftar Soal</h3>
        <div class="text-xs text-zinc-500 dark:text-zinc-400">
            Total: {{ $questions->count() }} Soal
        </div>
    </div>

    <flux:navlist variant="outline" class="px-2">
        @foreach($questions as $index => $question)
        <flux:navlist.item
            href="#question-{{ $question->id }}"
            class="text-sm"
            :current="false">
            <span class="truncate">Soal {{ $index + 1 }}</span>
            <span class="ml-auto text-xs text-zinc-400">{{ $question->type_label ?? $question->question_type }}</span>
        </flux:navlist.item>
        @endforeach
    </flux:navlist>
</div>