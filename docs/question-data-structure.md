# Struktur Data Question Options dan Key Answer

Dokumen ini menjelaskan struktur data untuk kolom `options` dan `key_answer` pada setiap tipe soal dalam aplikasi CBT.

## Daftar Tipe Soal

1. [Multiple Choice](#multiple-choice)
2. [Multiple Selection](#multiple-selection)
3. [True/False](#truefalse)
4. [Essay](#essay)
5. [Matching](#matching)
6. [Ordering](#ordering)
7. [Numerical Input](#numerical-input)

---

## Multiple Choice

**Tipe:** `multiple_choice`  
**Deskripsi:** Pilihan ganda dengan satu jawaban benar

### Options Structure
```json
{
    "A": {
        "text": "Teks opsi A",
        "media_id": "uuid_media_atau_null"
    },
    "B": {
        "text": "Teks opsi B", 
        "media_id": "uuid_media_atau_null"
    },
    "C": {
        "text": "Teks opsi C",
        "media_id": "uuid_media_atau_null"
    },
    "D": {
        "text": "Teks opsi D",
        "media_id": "uuid_media_atau_null"
    }
}
```

### Key Answer Structure
```json
{
    "answer": "A"
}
```

**Catatan:**
- memiliki 3 sampai 5 opsi (A, B, C, D, E) secara default memiliki 4 opsi (A, B, C, D)
- Hanya satu jawaban benar
- `media_id` opsional, null jika tidak ada media

---

## Multiple Selection

**Tipe:** `multiple_selection`  
**Deskripsi:** Pilihan ganda dengan lebih dari satu jawaban benar

### Options Structure
```json
{
    "A": {
        "text": "Teks opsi A",
        "media_id": "uuid_media_atau_null"
    },
    "B": {
        "text": "Teks opsi B",
        "media_id": "uuid_media_atau_null"
    },
    "C": {
        "text": "Teks opsi C",
        "media_id": "uuid_media_atau_null"
    },
    "D": {
        "text": "Teks opsi D",
        "media_id": "uuid_media_atau_null"
    }
}
```

### Key Answer Structure
```json
{
    "answers": ["A", "C"]
}
```

**Catatan:**
- memiliki 3 sampai 5 opsi (A, B, C, D, E) secara default memiliki 4 opsi (A, B, C, D)
- Bisa memiliki 2-4 jawaban benar
- `media_id` opsional, null jika tidak ada media

---

## True/False

**Tipe:** `true_false`  
**Deskripsi:** Pernyataan Benar/Salah

### Options Structure
```json
{
    "True": {
        "text": "Benar",
        "media_id": null
    },
    "False": {
        "text": "Salah",
        "media_id": null
    }
}
```

### Key Answer Structure
```json
{
    "answer": "True"
}
```

**Catatan:**
- Selalu memiliki tepat 2 opsi yang bernilai (True, False) dengan custom opsi True dan False. Tetapi secara default memiliki opsi True dan False dengan text "Benar" dan "Salah"
- Hanya satu jawaban benar
- Tidak menggunakan media (media_id selalu null)

---

## Essay

**Tipe:** `essay`  
**Deskripsi:** Soal uraian dengan koreksi manual/rubrik

### Options Structure
```json
{}
```
atau
```json
null
```

### Key Answer Structure
```json
[
    {
        "poin": "Ketepatan Definisi",
        "max_score": 5
    },
    {
        "poin": "Kelengkapan Contoh", 
        "max_score": 5
    }
]
```

**Catatan:**
- Tidak memiliki opsi (kosong atau null)
- Key answer berupa array rubrik penilaian
- Setiap rubrik memiliki poin penilaian dan skor maksimal

---

## Matching

**Tipe:** `matching`  
**Deskripsi:** Soal menjodohkan antara dua kolom

### Options Structure
```json
{
    "L1": {
        "text": "Definisi 1: kata_kunci_1",
        "media_id": "uuid_media_atau_null"
    },
    "L2": {
        "text": "Definisi 2: kata_kunci_2",
        "media_id": "uuid_media_atau_null"
    },
    "L3": {
        "text": "Definisi 3: kata_kunci_3",
        "media_id": "uuid_media_atau_null"
    },
    "L4": {
        "text": "Definisi 4: kata_kunci_4",
        "media_id": "uuid_media_atau_null"
    },
    "R1": {
        "text": "Jawaban 1: kata_kunci_5",
        "media_id": "uuid_media_atau_null"
    },
    "R2": {
        "text": "Jawaban 2: kata_kunci_6",
        "media_id": "uuid_media_atau_null"
    },
    "R3": {
        "text": "Jawaban 3: kata_kunci_7",
        "media_id": "uuid_media_atau_null"
    },
    "R4": {
        "text": "Jawaban 4: kata_kunci_8",
        "media_id": "uuid_media_atau_null"
    }
}
```

### Key Answer Structure
```json
{
    "pairs": {
        "L1": "R3",
        "L2": "R1", 
        "L3": "R4",
        "L4": "R2"
    }
}
```

**Catatan:**
- Pada umumnya memiliki 8 opsi: 4 di kolom kiri (L1-L4) dan 4 di kolom kanan (R1-R4), tetapi bisa diatur sendiri sesuai dengan kebutuhan
- Key answer memetakan setiap item kiri ke item kanan yang benar
- Urutan item kanan diacak untuk meningkatkan kesulitan
- `media_id` opsional untuk setiap item

---

## Ordering

**Tipe:** `ordering`  
**Deskripsi:** Soal mengurutkan langkah-langkah

### Options Structure
```json
{
    "1": {
        "text": "Langkah yang diacak 1",
        "media_id": "uuid_media_atau_null"
    },
    "2": {
        "text": "Langkah yang diacak 2",
        "media_id": "uuid_media_atau_null"
    },
    "3": {
        "text": "Langkah yang diacak 3", 
        "media_id": "uuid_media_atau_null"
    },
    "4": {
        "text": "Langkah yang diacak 4",
        "media_id": "uuid_media_atau_null"
    }
}
```

### Key Answer Structure
```json
{
    "order": [1, 2, 3, 4]
}
```

**Catatan:**
- Pada umumnya memiliki 4 opsi (1, 2, 3, 4), tetapi bisa diatur sendiri sesuai dengan kebutuhan
- Key answer berupa array urutan yang benar (dalam format key)
- Konten opsi diacak, tetapi key_answer menyimpan urutan yang benar
- `media_id` opsional untuk setiap item

---

## Numerical Input

**Tipe:** `numerical_input`  
**Deskripsi:** Soal input angka dengan toleransi

### Options Structure
```json
{}
```
atau
```json
null
```

### Key Answer Structure
```json
{
    "value": 25.67,
    "tolerance": 0.05,
    "unit": "kg"
}
```

**Catatan:**
- Tidak memiliki opsi (kosong atau null)
- `value`: nilai jawaban benar yang tepat
- `tolerance`: toleransi kesalahan (contoh: 0.05 = ±5%)
- `unit`: satuan dari jawaban (opsional)

---

## Panduan Penggunaan Factory

### Membuat Soal dengan Tipe Spesifik

```php
use App\Enums\QuestionTypeEnum;
use App\Models\Question;

// Membuat soal Multiple Choice
$question = Question::factory()->withType(QuestionTypeEnum::MultipleChoice)->create();

// Membuat soal Essay
$question = Question::factory()->withType(QuestionTypeEnum::Essay)->create();

// Membuat soal dengan data kustom
$question = Question::factory()->withType(QuestionTypeEnum::Matching)->create([
    'question_bank_id' => $bankId,
    'score_value' => 20,
]);
```

### Membuat Soal Acak

```php
// Membuat soal dengan tipe acak
$question = Question::factory()->create();
```

---

## Validasi Data

Pastikan data yang disimpan ke database mengikuti struktur di atas. Berikut adalah checklist validasi untuk setiap tipe:

- [ ] MultipleChoice: 4 opsi (A-D), 1 jawaban
- [ ] MultipleSelection: 4 opsi (A-D), 2-3 jawaban  
- [ ] TrueFalse: 2 opsi (True/False), 1 jawaban
- [ ] Essay: Tanpa opsi, array rubrik
- [ ] Matching: 8 opsi (L1-L4, R1-R4), 4 pasangan
- [ ] Ordering: 4 opsi (A-D), array urutan
- [ ] NumericalInput: Tanpa opsi, value + tolerance + unit

---

## Catatan Tambahan

1. **Media Handling**: Semua opsi kecuali TrueFalse dapat memiliki media (gambar, audio, video)
2. **Consistency**: Pastikan struktur data konsisten dengan tipe soal yang dipilih
3. **Factory Usage**: Gunakan method `withType()` untuk membuat soal dengan tipe spesifik
4. **Testing**: Selalu test data yang dihasilkan factory untuk memastikan konsistensi
