<div>
    <div class="container mx-auto px-4 py-8">
        @if($questionBankId)
        <!-- Single Question Bank View -->
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                @if(!$embedded)
                <button
                    wire:click="loadQuestionBanks"
                    class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-4">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar Bank Soal
                </button>
                @endif
            </div>

            @if($selectedQuestionBank)
            @if(!$embedded)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ $selectedQuestionBank->name }}
                    </h1>
                    <div class="flex items-center space-x-2">
                        @if($selectedQuestionBank->is_public)
                        <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">
                            <i class="fas fa-globe mr-1"></i>
                            Publik
                        </span>
                        @else
                        <span class="px-3 py-1 text-sm font-medium bg-gray-100 text-gray-800 rounded-full">
                            <i class="fas fa-lock mr-1"></i>
                            Privat
                        </span>
                        @endif
                    </div>
                </div>

                @if($selectedQuestionBank->description)
                <p class="text-gray-600 mb-4">{{ $selectedQuestionBank->description }}</p>
                @endif

                <div class="flex items-center space-x-6 text-sm text-gray-500">
                    <span>
                        <i class="fas fa-question-circle"></i>
                        {{ $selectedQuestionBank->questions()->count() }} soal
                    </span>
                    @if($selectedQuestionBank->teacher)
                    <span>
                        <i class="fas fa-user"></i>
                        {{ $selectedQuestionBank->teacher->name }}
                    </span>
                    @endif
                    <span>
                        <i class="fas fa-calendar"></i>
                        {{ $selectedQuestionBank->created_at->format('d M Y') }}
                    </span>
                </div>
            </div>
            @endif

            <!-- Questions List -->
            <div class="space-y-6">
                @if($questions->count() > 0)
                @foreach($questions as $index => $question)
                <div id="question-{{ $question->id }}" class="bg-white rounded-lg shadow-md overflow-hidden scroll-mt-24">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Soal {{ $index + 1 }}
                            </h3>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                    {{ $question->question_type }}
                                </span>
                                <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                    {{ $question->difficulty_level }}
                                </span>
                                @if($question->timer)
                                <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                                    <i class="fas fa-clock"></i> {{ $question->timer }}s
                                </span>
                                @endif
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                    {{ $question->score_value }} poin
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Question Detail Viewer Component -->
                        <livewire:question-detail-viewer
                            :question="$question"
                            :key="'question-' . $question->id . '-' . $question->updated_at->timestamp" />
                    </div>
                </div>
                @endforeach
                @else
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <i class="fas fa-question-circle text-5xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Belum ada soal</h3>
                    <p class="text-gray-600">Bank soal ini belum memiliki pertanyaan.</p>
                </div>
                @endif
            </div>
            @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-exclamation-triangle text-5xl text-red-400 mb-4"></i>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Bank Soal Tidak Ditemukan</h3>
                <p class="text-gray-600 mb-4">Bank soal yang Anda cari tidak tersedia.</p>
                <button
                    wire:click="loadQuestionBanks"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Kembali ke Daftar
                </button>
            </div>
            @endif
        </div>
        @else
        <!-- List View - All Question Banks -->
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Daftar Bank Soal</h1>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        Cari Bank Soal
                    </label>
                    <input
                        type="text"
                        id="search"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Masukkan nama atau deskripsi..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="perPage" class="block text-sm font-medium text-gray-700 mb-2">
                        Tampilkan per halaman
                    </label>
                    <select
                        id="perPage"
                        wire:model.live="perPage"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Question Banks Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if($questionBanks->count() > 0)
            @foreach($questionBanks as $questionBank)
            <div
                class="bg-white rounded-lg shadow-md p-6 cursor-pointer transition-all hover:shadow-lg"
                wire:click="selectQuestionBank('{{ $questionBank->id }}')">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $questionBank->name }}</h3>
                    @if($questionBank->is_public)
                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                        Publik
                    </span>
                    @else
                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                        Privat
                    </span>
                    @endif
                </div>

                @if($questionBank->description)
                <p class="text-gray-600 text-sm mb-3">{{ Str::limit($questionBank->description, 100) }}</p>
                @endif

                <div class="flex items-center justify-between text-sm text-gray-500">
                    <div class="flex items-center space-x-4">
                        <span>
                            <i class="fas fa-question-circle"></i>
                            {{ $questionBank->questions()->count() }} soal
                        </span>
                        @if($questionBank->teacher)
                        <span>
                            <i class="fas fa-user"></i>
                            {{ Str::limit($questionBank->teacher->name, 10) }}
                        </span>
                        @endif
                    </div>
                    <span>
                        <i class="fas fa-calendar"></i>
                        {{ $questionBank->created_at->format('d M Y') }}
                    </span>
                </div>
            </div>
            @endforeach
            @else
            <div class="col-span-full bg-white rounded-lg shadow-md p-8 text-center">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada bank soal ditemukan</h3>
                <p class="text-gray-600">Coba ubah kata kunci pencarian atau buat bank soal baru.</p>
            </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($questionBanks->hasPages())
        <div class="mt-8">
            {{ $questionBanks->links() }}
        </div>
        @endif
        @endif
    </div>

    <!-- Script for URL updates -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('url-updated', (event) => {
                const url = new URL(window.location);
                url.searchParams.set('question_bank_id', event.questionBankId);
                window.history.pushState({}, '', url);
            });
        });
    </script>
</div>