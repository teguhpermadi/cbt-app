<div
    x-data="{
        lines: [],
        pairs: @js($pairs),
        colors: ['#EF4444', '#F59E0B', '#10B981', '#3B82F6', '#6366F1', '#8B5CF6', '#EC4899', '#14B8A6'],
        init() {
            this.$nextTick(() => {
                this.drawLines();
                window.addEventListener('resize', () => this.drawLines());
            });
        },
        drawLines() {
            this.lines = [];
            const container = this.$el.getBoundingClientRect();
            
            this.pairs.forEach((pair, index) => {
                const leftEl = document.getElementById('option-' + pair.left);
                const rightEl = document.getElementById('option-' + pair.right);
                
                if (leftEl && rightEl) {
                    const leftRect = leftEl.getBoundingClientRect();
                    const rightRect = rightEl.getBoundingClientRect();
                    
                    // Calculate start point (right side of left option)
                    const x1 = leftRect.right - container.left;
                    const y1 = leftRect.top + (leftRect.height / 2) - container.top;
                    
                    // Calculate end point (left side of right option)
                    const x2 = rightRect.left - container.left;
                    const y2 = rightRect.top + (rightRect.height / 2) - container.top;
                    
                    // Calculate midpoint X for orthogonal turn
                    const midX = (x1 + x2) / 2;
                    
                    // Orthogonal Path: Move to Start -> Horizontal to Mid -> Vertical to Target Y -> Horizontal to End
                    const path = `M ${x1} ${y1} L ${midX} ${y1} L ${midX} ${y2} L ${x2} ${y2}`;
                    
                    this.lines.push({ 
                        path: path, 
                        color: this.colors[index % this.colors.length] 
                    });
                }
            });
        }
    }"
    class="relative">
    {{-- SVG Layer for Lines --}}
    <div class="absolute inset-0 w-full h-full pointer-events-none z-0">
        <template x-for="(line, index) in lines" :key="index">
            <svg class="absolute inset-0 w-full h-full">
                <path
                    :d="line.path"
                    :stroke="line.color"
                    stroke-width="2"
                    fill="none" />
                {{-- Optional: Add dots at endpoints for better visual connection --}}
                <circle :cx="line.path.split(' ')[1]" :cy="line.path.split(' ')[2]" r="3" :fill="line.color" />
                <circle :cx="line.path.split(' ')[10]" :cy="line.path.split(' ')[11]" r="3" :fill="line.color" />
            </svg>
        </template>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-32 relative z-10">
        <div class="space-y-3">
            @foreach($leftOptions as $leftOption)
            <div
                id="option-{{ $leftOption->id }}"
                class="flex items-start p-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
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

        <div class="space-y-3">
            @foreach($rightOptions as $rightOption)
            <div
                id="option-{{ $rightOption->id }}"
                class="flex items-start p-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
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
</div>