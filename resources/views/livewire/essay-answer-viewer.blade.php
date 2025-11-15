@if($showCorrectAnswers && !empty($this->getRubricPoints()))
    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
        <!-- Header dengan AI Icon -->
        <!-- <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-2">
                <div class="flex items-center space-x-1 text-blue-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <span class="text-sm font-medium text-blue-600">AI-Checked</span>
                </div>
            </div>
            <div class="text-sm text-gray-600">
                Total Skor: <span class="font-medium text-gray-800">{{ $this->getTotalMaxScore() }}</span>
            </div>
        </div> -->
        
        <!-- Daftar Poin Penilaian -->
        <div class="space-y-3">
            @foreach($this->getRubricPoints() as $index => $point)
                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                    <div class="flex items-start space-x-3">
                        <!-- Nomor Urut -->
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center text-xs font-medium">
                            {{ $index + 1 }}
                        </div>
                        
                        <!-- Poin Penilaian -->
                        <div class="flex-1">
                            <p class="text-gray-800 font-medium">{{ $point['poin'] }}</p>
                        </div>
                    </div>
                    
                    <!-- Max Score Badge -->
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $point['max_score'] }} poin
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Footer Information -->
        <div class="mt-4 pt-3 border-t border-gray-200">
            <div class="flex items-center justify-between text-xs text-gray-500">
                <div class="flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                    </svg>
                    <span>Jawaban dikoreksi secara otomatis menggunakan AI</span>
                </div>
                <div>
                    {{ count($this->getRubricPoints()) }} kriteria penilaian
                </div>
                <div>
                    Total Skor: <span class="font-medium text-gray-800">{{ $this->getTotalMaxScore() }}</span>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Empty state jika tidak ada rubrik atau showCorrectAnswers false -->
    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
        <div class="text-center text-gray-500 py-6">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
            </svg>
            <p class="text-sm">Rubrik penilaian tidak tersedia</p>
        </div>
    </div>
@endif
