<div>
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body p-4">
            <div class="mb-4">
                <h3 class="font-bold text-lg">Kunci Jawaban / Rubrik Penilaian</h3>
                <p class="text-xs text-gray-500">Masukkan jawaban referensi atau poin-poin penilaian untuk soal uraian ini.</p>
            </div>

            <x-mary-textarea
                wire:model="referenceAnswer"
                placeholder="Tuliskan jawaban referensi atau rubrik penilaian di sini..."
                rows="6"
                hint="Informasi ini akan digunakan sebagai panduan penilaian manual." />
        </div>
    </div>
</div>