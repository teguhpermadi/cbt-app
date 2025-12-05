<div>
    <!-- card -->
    @foreach ($options as $option)
        <x-mary-card shadow>
            <p>{{ $option->content }}</p>
        </x-mary-card>
    @endforeach
</div>
