<div
    x-data="{
        lines: [],
        pairs: @js($pairs),
        init() {
            this.$nextTick(() => {
                this.drawLines();
                window.addEventListener('resize', () => this.drawLines());
            });
        },
        drawLines() {
            this.lines = [];
            const container = this.$el.getBoundingClientRect();
            
            this.pairs.forEach(pair => {
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
                    
                    // Calculate length and angle
                    const length = Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
                    const angle = Math.atan2(y2 - y1, x2 - x1) * 180 / Math.PI;
                    
                    this.lines.push({ x: x1, y: y1, length, angle });
                }
            });
        }
    }"
    class="relative">
    {{-- CSS Lines Layer --}}
    <div class="absolute inset-0 w-full h-full pointer-events-none z-0">
        <template x-for="(line, index) in lines" :key="index">
            <div
                class="absolute border-t-2 border-green-400"
                :style="`
                    top: ${line.y}px; 
                    left: ${line.x}px; 
                    width: ${line.length}px; 
                    transform: rotate(${line.angle}deg); 
                    transform-origin: 0 0;
                `"></div>
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