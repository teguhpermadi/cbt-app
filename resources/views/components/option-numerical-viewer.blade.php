@props(['options'])

<div>
    @foreach ($options as $option)
    <x-mary-card shadow class="mb-3">
        <p>{{ $option->content }}</p>
    </x-mary-card>
    @endforeach
</div>