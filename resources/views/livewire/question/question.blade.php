<div>
    <x-mary-card shadow>
        <!-- header -->
        <div class="flex items-center justify-between">
            <p>{{ $question->question_type }}</p>
            <p>{{ $question->id }}</p>
        </div>
        <!-- body -->
        <div class="p-4">
            <p>{{ $question->content }}</p>
        </div>
        <!-- footer -->
        <div class="flex items-center justify-between">
            <p>{{ $question->created_at }}</p>
            <p>{{ $question->updated_at }}</p>
        </div>
    </x-mary-card>
</div>