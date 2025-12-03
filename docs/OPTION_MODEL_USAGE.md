# Dokumentasi Model Option

## Overview

Model `Option` telah direfactor untuk mengakomodasi semua tipe soal yang didefinisikan dalam `QuestionTypeEnum`. Model ini menyimpan opsi jawaban untuk setiap pertanyaan dengan struktur yang fleksibel.

## Struktur Database

### Tabel `options`

| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| `id` | ULID | Primary key |
| `question_id` | ULID | Foreign key ke tabel `questions` |
| `option_key` | String(10) | Label opsi (A, B, C, D atau 1, 2, 3) |
| `content` | Text | Konten opsi (teks, HTML, atau referensi) |
| `media_path` | String | Path media atau ULID dari Spatie Media Library |
| `order` | Integer | Urutan tampilan opsi |
| `is_correct` | Boolean | Penanda apakah opsi ini jawaban benar |
| `metadata` | JSON | Data tambahan khusus per tipe soal |
| `created_at` | Timestamp | Waktu dibuat |
| `updated_at` | Timestamp | Waktu diupdate |
| `deleted_at` | Timestamp | Soft delete timestamp |

### Indexes
- `(question_id, order)` - Untuk query opsi berdasarkan pertanyaan dan urutan
- `(question_id, option_key)` - Untuk query opsi berdasarkan pertanyaan dan key

## Penggunaan Per Tipe Soal

### 1. Multiple Choice (Pilihan Ganda Tunggal)

```php
use App\Models\Option;

// Membuat opsi untuk multiple choice
$options = Option::createMultipleChoiceOptions($questionId, [
    ['key' => 'A', 'content' => 'Jakarta', 'is_correct' => true],
    ['key' => 'B', 'content' => 'Bandung', 'is_correct' => false],
    ['key' => 'C', 'content' => 'Surabaya', 'is_correct' => false],
    ['key' => 'D', 'content' => 'Medan', 'is_correct' => false],
]);

// Atau manual
Option::create([
    'question_id' => $questionId,
    'option_key' => 'A',
    'content' => 'Jakarta',
    'order' => 0,
    'is_correct' => true,
]);
```

### 2. True/False (Benar/Salah)

```php
// Membuat opsi True/False dengan jawaban benar adalah "True"
$options = Option::createTrueFalseOptions($questionId, true);

// Atau dengan jawaban benar adalah "False"
$options = Option::createTrueFalseOptions($questionId, false);
```

### 3. Multiple Selection (Pilihan Ganda Kompleks)

```php
// Sama seperti multiple choice, tapi bisa lebih dari satu is_correct = true
$options = Option::createMultipleChoiceOptions($questionId, [
    ['key' => 'A', 'content' => 'Merah', 'is_correct' => true],
    ['key' => 'B', 'content' => 'Biru', 'is_correct' => true],
    ['key' => 'C', 'content' => 'Hijau', 'is_correct' => false],
    ['key' => 'D', 'content' => 'Kuning', 'is_correct' => true],
]);
```

### 4. Matching (Menjodohkan)

```php
// Membuat opsi untuk matching
$options = Option::createMatchingOptions($questionId, [
    ['left' => 'Indonesia', 'right' => 'Jakarta'],
    ['left' => 'Malaysia', 'right' => 'Kuala Lumpur'],
    ['left' => 'Thailand', 'right' => 'Bangkok'],
]);

// Struktur metadata untuk matching:
// Left side: {side: 'left', pair_id: 1, match_with: 'R1'}
// Right side: {side: 'right', pair_id: 1, match_with: 'L1'}
```

### 5. Ordering (Mengurutkan)

```php
// Membuat opsi untuk ordering (urutan yang benar)
$options = Option::createOrderingOptions($questionId, [
    'Langkah pertama',
    'Langkah kedua',
    'Langkah ketiga',
    'Langkah keempat',
]);

// Metadata akan berisi: {correct_position: 1}, {correct_position: 2}, dst.
```

### 6. Numerical Input (Input Angka)

```php
// Membuat opsi untuk numerical input
$option = Option::createNumericalInputOption(
    questionId: $questionId,
    correctAnswer: 3.14,
    tolerance: 0.01,  // Toleransi ±0.01
    unit: 'cm'        // Satuan (opsional)
);

// Metadata akan berisi:
// {tolerance: 0.01, unit: 'cm', correct_answer: 3.14}
```

### 7. Essay (Uraian)

```php
// Essay tidak memerlukan opsi, tapi bisa menyimpan rubrik penilaian
// Rubrik bisa disimpan di field key_answer pada model Question
// Atau bisa membuat opsi dengan metadata rubrik

Option::create([
    'question_id' => $questionId,
    'option_key' => 'RUBRIC',
    'content' => 'Kriteria penilaian',
    'order' => 0,
    'metadata' => [
        'rubric' => [
            ['criteria' => 'Kelengkapan jawaban', 'max_score' => 40],
            ['criteria' => 'Ketepatan konsep', 'max_score' => 30],
            ['criteria' => 'Sistematika penulisan', 'max_score' => 30],
        ],
    ],
]);
```

## Relasi dengan Question

```php
// Mengakses opsi dari Question
$question = Question::find($questionId);
$options = $question->options; // Sudah terurut berdasarkan 'order'

// Mendapatkan opsi yang benar
$correctOptions = $question->options()->correct()->get();

// Mendapatkan opsi berdasarkan key
$optionA = $question->options()->byKey('A')->first();
```

## Scopes

```php
// Mendapatkan opsi yang benar
$correctOptions = Option::where('question_id', $questionId)
    ->correct()
    ->get();

// Mendapatkan opsi terurut
$orderedOptions = Option::where('question_id', $questionId)
    ->ordered()
    ->get();

// Mendapatkan opsi berdasarkan key
$option = Option::where('question_id', $questionId)
    ->byKey('A')
    ->first();
```

## Helper Methods

```php
$option = Option::find($optionId);

// Cek apakah opsi benar
if ($option->isCorrect()) {
    // ...
}

// Mark sebagai jawaban benar
$option->markAsCorrect();

// Mark sebagai jawaban salah
$option->markAsIncorrect();

// Get metadata
$tolerance = $option->getMetadata('tolerance', 0);
$unit = $option->getMetadata('unit');

// Set metadata
$option->setMetadata('tolerance', 0.05);
$option->setMetadata('custom_data.nested', 'value');

// Cek apakah opsi memiliki media
if ($option->hasOptionMedia()) {
    $mediaUrl = $option->getMediaUrl();
}
```

## Media Management (Spatie Media Library)

```php
// Upload media ke opsi
$option->addMedia($request->file('image'))
    ->toMediaCollection('option_media');

// Get media URL
$mediaUrl = $option->getMediaUrl();

// Get first media
$media = $option->getFirstMedia('option_media');

// Delete media
$option->clearMediaCollection('option_media');
```

## Contoh Penggunaan Lengkap

### Membuat Soal Multiple Choice dengan Opsi

```php
use App\Models\Question;
use App\Models\Option;
use App\Enums\QuestionTypeEnum;

// Buat pertanyaan
$question = Question::create([
    'question_bank_id' => $bankId,
    'question_type' => QuestionTypeEnum::MultipleChoice,
    'content' => 'Apa ibukota Indonesia?',
    'score_value' => 10,
    'order' => 1,
]);

// Buat opsi
Option::createMultipleChoiceOptions($question->id, [
    ['key' => 'A', 'content' => 'Jakarta', 'is_correct' => true],
    ['key' => 'B', 'content' => 'Bandung', 'is_correct' => false],
    ['key' => 'C', 'content' => 'Surabaya', 'is_correct' => false],
    ['key' => 'D', 'content' => 'Medan', 'is_correct' => false],
]);
```

### Membuat Soal Matching dengan Opsi

```php
$question = Question::create([
    'question_bank_id' => $bankId,
    'question_type' => QuestionTypeEnum::Matching,
    'content' => 'Jodohkan negara dengan ibukotanya',
    'score_value' => 15,
    'order' => 2,
]);

Option::createMatchingOptions($question->id, [
    ['left' => 'Indonesia', 'right' => 'Jakarta'],
    ['left' => 'Malaysia', 'right' => 'Kuala Lumpur'],
    ['left' => 'Thailand', 'right' => 'Bangkok'],
    ['left' => 'Singapura', 'right' => 'Singapura'],
]);
```

### Validasi Jawaban Siswa

```php
// Multiple Choice
$studentAnswer = 'A'; // Jawaban siswa
$correctOption = $question->options()->byKey($studentAnswer)->first();
$isCorrect = $correctOption && $correctOption->isCorrect();

// Multiple Selection
$studentAnswers = ['A', 'B', 'D']; // Jawaban siswa
$correctKeys = $question->options()->correct()->pluck('option_key')->toArray();
$isCorrect = empty(array_diff($studentAnswers, $correctKeys)) && 
             empty(array_diff($correctKeys, $studentAnswers));

// Numerical Input
$studentAnswer = 3.15;
$option = $question->options()->first();
$correctAnswer = $option->getMetadata('correct_answer');
$tolerance = $option->getMetadata('tolerance', 0);
$isCorrect = abs($studentAnswer - $correctAnswer) <= $tolerance;

// Ordering
$studentOrder = ['2', '1', '3', '4']; // Urutan yang dipilih siswa
$correctOrder = $question->options()
    ->ordered()
    ->pluck('option_key')
    ->toArray();
$isCorrect = $studentOrder === $correctOrder;
```

## Migration

Untuk menjalankan migration:

```bash
php artisan migrate
```

Untuk rollback:

```bash
php artisan migrate:rollback
```

## Catatan Penting

1. **Soft Deletes**: Model menggunakan soft deletes, jadi data tidak benar-benar dihapus dari database
2. **ULID**: Menggunakan ULID sebagai primary key untuk keamanan dan performa
3. **Media**: Mendukung Spatie Media Library untuk upload gambar/audio/video pada opsi
4. **Metadata**: Field JSON `metadata` sangat fleksibel untuk menyimpan data tambahan per tipe soal
5. **Relasi**: Cascade delete, jika Question dihapus, semua Option terkait juga akan dihapus

## Best Practices

1. Selalu gunakan helper method static untuk membuat opsi sesuai tipe soal
2. Gunakan scope untuk query yang lebih readable
3. Manfaatkan metadata untuk data tambahan yang spesifik per tipe soal
4. Gunakan Spatie Media Library untuk media management
5. Validasi jawaban siswa sesuai dengan tipe soal yang berbeda
