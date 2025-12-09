<div>
    <h1>Edit Soal</h1>

    @if ($errors->any())
    <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px;">
        <strong>Terdapat kesalahan:</strong>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form wire:submit.prevent="update">
        <!-- Selector Section - Horizontal Layout -->
        <div style="display: flex; gap: 15px; margin-bottom: 30px; flex-wrap: wrap;">
            <!-- Question Type Selector -->
            <div style="flex: 1; min-width: 200px;">
                <label for="questionType">
                    <strong>Tipe Soal:</strong>
                </label>
                <br>
                <select id="questionType" wire:model="questionType" required style="width: 100%; padding: 8px; margin-top: 5px;">
                    <option value="">-- Pilih Tipe --</option>
                    @foreach($questionTypes as $type)
                    <option value="{{ $type->value }}">{{ $type->getLabel() }}</option>
                    @endforeach
                </select>
                @error('questionType') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <!-- Difficulty Level Selector -->
            <div style="flex: 1; min-width: 150px;">
                <label for="difficultyLevel">
                    <strong>Kesulitan:</strong>
                </label>
                <br>
                <select id="difficultyLevel" wire:model="difficultyLevel" required style="width: 100%; padding: 8px; margin-top: 5px;">
                    <option value="">-- Pilih --</option>
                    @foreach($difficultyLevels as $level)
                    <option value="{{ $level->value }}">{{ $level->getLabel() }}</option>
                    @endforeach
                </select>
                @error('difficultyLevel') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <!-- Timer Selector -->
            <div style="flex: 1; min-width: 150px;">
                <label for="timer">
                    <strong>Timer:</strong>
                </label>
                <br>
                <select id="timer" wire:model="timer" required style="width: 100%; padding: 8px; margin-top: 5px;">
                    <option value="">-- Pilih --</option>
                    @foreach($timers as $time)
                    <option value="{{ $time->value }}">{{ $time->getLabel() }}</option>
                    @endforeach
                </select>
                @error('timer') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <!-- Score Selector -->
            <div style="flex: 0.5; min-width: 100px;">
                <label for="scoreValue">
                    <strong>Skor:</strong>
                </label>
                <br>
                <input
                    type="number"
                    id="scoreValue"
                    wire:model="scoreValue"
                    step="0.01"
                    min="0"
                    required
                    style="width: 100%; padding: 8px; margin-top: 5px;">
                @error('scoreValue') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <!-- Order Selector -->
            <div style="flex: 0.5; min-width: 100px;">
                <label for="order">
                    <strong>Urutan:</strong>
                </label>
                <br>
                <input
                    type="number"
                    id="order"
                    wire:model="order"
                    min="1"
                    required
                    style="width: 100%; padding: 8px; margin-top: 5px;">
                @error('order') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Content Section -->
        <div style="margin-bottom: 20px;">
            <label for="content">
                <strong>Konten Soal:</strong>
            </label>
            <br>
            <textarea
                id="content"
                wire:model="content"
                rows="10"
                required
                style="width: 100%; padding: 10px; margin-top: 5px; font-family: inherit; font-size: 14px;"
                placeholder="Masukkan konten soal di sini..."></textarea>
            @error('content') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
        </div>

        <!-- Action Buttons -->
        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <button type="submit" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; cursor: pointer; border-radius: 4px; font-weight: bold;">
                Simpan Perubahan
            </button>
            <button type="button" wire:click="cancel" style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; cursor: pointer; border-radius: 4px;">
                Batal
            </button>
        </div>
    </form>
</div>