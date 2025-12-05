<div class="grid gap-3 w-full">
    @foreach($options as $option)
    <!-- card -->
    <x-mary-card shadow>
        <p>{{ $option->option_key }} - {{ $option->content }}</p>
    </x-mary-card>
    @endforeach
</div>
