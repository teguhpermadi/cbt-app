<div class="px-4 py-6 sm:px-6 lg:px-8">
    <div class="space-y-6">
        {{-- Top Section: Info & Map --}}
        <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 items-start">
            <x-monitor.session-summary :record="$record" />

            <div class="xl:col-span-1">
                <x-monitor.question-map :record="$record" />
            </div>
        </div>

        {{-- Questions List --}}
        <div class="xl:w-3/4 space-y-8">
            @foreach($record->details as $index => $detail)
            <x-monitor.question-detail :detail="$detail" :index="$index" />
            @endforeach
        </div>
    </div>
</div>