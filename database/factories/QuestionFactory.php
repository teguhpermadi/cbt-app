<?php

namespace Database\Factories;

use App\Enums\DifficultyLevelEnum;
use App\Enums\QuestionScoreEnum;
use App\Enums\QuestionTypeEnum;
use App\Enums\TimerEnum;
use App\Models\Question;
use App\Models\QuestionBank;
use Illuminate\Database\Eloquent\Factories\Factory;
use Database\Factories\Traits\FillsWithMedia; // Import Trait

class QuestionFactory extends Factory
{
    use FillsWithMedia; // Gunakan Trait

    protected $model = Question::class;

    public function definition(): array
    {
        // 1. Ambil QuestionBank
        $questionBank = QuestionBank::inRandomOrder()->first() ?? QuestionBank::factory()->create();

        // 2. Buat Instance Question sementara untuk Spatie Media
        // Spatie memerlukan model instance dengan ID (ULID) yang valid untuk mengaitkan media.
        $question = Question::make([
            'id' => \Illuminate\Support\Str::ulid(), // ULID sementara
            'question_bank_id' => $questionBank->id,
            // Isi kolom wajib lainnya jika diperlukan
        ]);

        // 3. Generate data menggunakan instance question
        $type = $this->faker->randomElement(QuestionTypeEnum::cases());
        $data = $this->generateQuestionData($type, $question); 

        return [
            // Isi kolom dari $question yang sudah memiliki ID sementara
            'id' => $question->id, 
            'question_bank_id' => $questionBank->id,
            'reading_material_id' => null, 
            
            'question_type' => $type,
            'difficulty_level' => $this->faker->randomElement(DifficultyLevelEnum::cases()),
            'timer' => $this->faker->randomElement(TimerEnum::cases()),
            
            'content' => $data['content'],
            'options' => $data['options'],
            'key_answer' => $data['key_answer'],
            
            'score_value' => $this->faker->randomElement(QuestionScoreEnum::cases()),
            'is_active' => true,
            'is_approved' => $this->faker->boolean(50), 
        ];
    }
    
    /**
     * Menghasilkan data options dan key_answer berdasarkan tipe soal
     * @param QuestionTypeEnum $type
     * @param Question $question
     * @return array
     */
    private function generateQuestionData(QuestionTypeEnum $type, Question $question): array
    {
        $data = [
            'content' => $this->faker->paragraph(),
            'options' => [],
            'key_answer' => [],
        ];

        switch ($type) {
            case QuestionTypeEnum::MultipleChoice:
            case QuestionTypeEnum::MultipleSelection:
                $options = ['A', 'B', 'C', 'D'];
                $correct = ($type === QuestionTypeEnum::MultipleSelection) 
                           ? $this->faker->randomElements($options, $this->faker->numberBetween(2, 3))
                           : $this->faker->randomElement($options);
                
                foreach ($options as $option) {
                    $hasMedia = $this->faker->boolean(20); // 20% kemungkinan memiliki media
                    
                    $mediaId = $hasMedia 
                               ? $this->createDummyMedia($question, 'option_media', "MC_Opt_{$option}_{$this->faker->uuid()}.png")
                               : null;
                               
                    $data['options'][$option] = [
                        'text' => $hasMedia ? "Lihat Gambar Opsi {$option}" : $this->faker->sentence(3), 
                        'media_id' => $mediaId
                    ];
                }
                
                $data['key_answer'] = ($type === QuestionTypeEnum::MultipleSelection) 
                                      ? ['answers' => $correct] 
                                      : ['answer' => $correct];
                break;

            case QuestionTypeEnum::Essay:
                $data['key_answer'] = [
                    ['poin' => 'Ketepatan Definisi', 'max_score' => 5],
                    ['poin' => 'Kelengkapan Contoh', 'max_score' => 5],
                ];
                break;
            
            case QuestionTypeEnum::TrueFalse:
                $data['key_answer'] = ['answer' => $this->faker->randomElement(['True', 'False'])];
                break;
                
            case QuestionTypeEnum::Matching:
                $numPairs = 4;
                $leftKeys = ['L1', 'L2', 'L3', 'L4'];
                $rightKeys = ['R1', 'R2', 'R3', 'R4'];
                $correctPairs = [];
                
                shuffle($rightKeys); // Acak kunci jawaban kanan

                for ($i = 0; $i < $numPairs; $i++) {
                    $leftKey = $leftKeys[$i];
                    $rightKey = $rightKeys[$i]; 
                    
                    // Opsi Kiri
                    $hasMediaL = $this->faker->boolean(20);
                    $mediaIdL = $hasMediaL 
                                ? $this->createDummyMedia($question, 'option_media', "Match_L{$i}_{$this->faker->uuid()}.png")
                                : null;

                    $data['options'][$leftKey] = [
                        'text' => $hasMediaL ? 'Gambar Kolom Kiri' : 'Definisi ' . ($i + 1) . ': ' . $this->faker->word(), 
                        'media_id' => $mediaIdL
                    ];

                    // Opsi Kanan
                    $hasMediaR = $this->faker->boolean(20);
                    $mediaIdR = $hasMediaR 
                                ? $this->createDummyMedia($question, 'option_media', "Match_R{$i}_{$this->faker->uuid()}.png")
                                : null;
                                
                    $data['options'][$rightKey] = [
                        'text' => $hasMediaR ? 'Gambar Jawaban' : 'Jawaban ' . ($i + 1) . ': ' . $this->faker->word(), 
                        'media_id' => $mediaIdR
                    ];
                    
                    $correctPairs[$leftKey] = $rightKey;
                }
                
                $data['key_answer'] = ['pairs' => $correctPairs];
                $data['content'] = 'Jodohkan item di Kolom Kiri dengan item yang tepat di Kolom Kanan. Beberapa item mungkin berupa gambar.';
                break;

            case QuestionTypeEnum::Ordering:
                $steps = [
                    'Siapkan bahan-bahan yang diperlukan.',
                    'Aduk rata telur dan gula.',
                    'Masukkan terigu secara bertahap.',
                    'Panggang selama 30 menit.'
                ];
                
                $shuffledSteps = $steps;
                shuffle($shuffledSteps);
                $itemKeys = ['A', 'B', 'C', 'D'];
                
                $optionsData = [];
                // Map untuk memetakan konten asli (correct step) ke key yang diacak (A, B, C, D)
                $contentToKeyMap = []; 
                
                for ($i = 0; $i < count($shuffledSteps); $i++) {
                    $originalContent = $shuffledSteps[$i]; // Konten yang diacak
                    $key = $itemKeys[$i];
                    
                    $hasMedia = $this->faker->boolean(100);
                    $mediaId = $hasMedia 
                               ? $this->createDummyMedia($question, 'option_media', "Order_{$key}_{$this->faker->uuid()}.png")
                               : null;
                               
                    $optionsData[$key] = [
                        'text' => $hasMedia ? 'Langkah Bergambar' : $originalContent, 
                        'media_id' => $mediaId,
                    ];
                    
                    // Simpan pemetaan: Konten Asli -> Key tampilan (A, B, C, D)
                    $contentToKeyMap[$originalContent] = $key;
                }
                
                $data['options'] = $optionsData;

                // Kunci Jawaban: Iterasi melalui urutan yang benar ($steps) dan temukan key yang sesuai dari map
                $correctOrder = [];
                foreach ($steps as $correctStep) { 
                    // Ambil key (A, B, C, D) yang menyimpan konten yang benar untuk urutan ini
                    $correctOrder[] = $contentToKeyMap[$correctStep];
                }
                
                $data['key_answer'] = ['order' => $correctOrder];
                $data['content'] = 'Urutkan langkah-langkah berikut secara kronologis.';
                break;

            case QuestionTypeEnum::NumericalInput:
                $answer = $this->faker->randomFloat(2, 1, 100);
                
                $data['options'] = []; 
                $data['key_answer'] = [
                    'value' => $answer,
                    'tolerance' => 0.05, 
                    'unit' => 'kg'
                ];
                $data['content'] = 'Berapakah nilai rata-rata dari 5, 8, dan ' . (round($answer) - 13) . '? (Masukkan hanya angka)';
                break;
        }

        return $data;
    }
}