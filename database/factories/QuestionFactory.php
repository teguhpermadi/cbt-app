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
     * Create a question with specific type
     */
    public function withType(QuestionTypeEnum $type): static
    {
        return $this->state(fn (array $attributes) => [
            'question_type' => $type,
            'content' => $this->generateQuestionData($type, Question::make($attributes))['content'],
            'options' => $this->generateQuestionData($type, Question::make($attributes))['options'],
            'key_answer' => $this->generateQuestionData($type, Question::make($attributes))['key_answer'],
        ]);
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
            'options' => [],
            'key_answer' => [],
        ];

        switch ($type) {
            case QuestionTypeEnum::MultipleChoice:
                $options = ['A', 'B', 'C', 'D'];
                $correct = $this->faker->randomElement($options);
                
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
                
                $data['key_answer'] = ['answer' => $correct];
                $data['content'] = 'Pilih satu jawaban yang paling tepat dari pilihan yang tersedia.';
                break;

            case QuestionTypeEnum::MultipleSelection:
                $options = ['A', 'B', 'C', 'D'];
                $correct = $this->faker->randomElements($options, $this->faker->numberBetween(2, 3));
                
                foreach ($options as $option) {
                    $hasMedia = $this->faker->boolean(20); // 20% kemungkinan memiliki media
                    
                    $mediaId = $hasMedia 
                               ? $this->createDummyMedia($question, 'option_media', "MS_Opt_{$option}_{$this->faker->uuid()}.png")
                               : null;
                               
                    $data['options'][$option] = [
                        'text' => $hasMedia ? "Lihat Gambar Opsi {$option}" : $this->faker->sentence(3), 
                        'media_id' => $mediaId
                    ];
                }
                
                $data['key_answer'] = ['answers' => $correct];
                $data['content'] = 'Pilih semua jawaban yang benar (bisa lebih dari satu).';
                break;

            case QuestionTypeEnum::Essay:
                $data['key_answer'] = [
                    ['poin' => 'Ketepatan Definisi', 'max_score' => 5],
                    ['poin' => 'Kelengkapan Contoh', 'max_score' => 5],
                ];
                $data['content'] = 'Jelaskan secara mendalam mengenai konsep berikut dan berikan contoh yang relevan.';
                break;
            
            case QuestionTypeEnum::TrueFalse:
                $data['options'] = [
                    'True' => ['text' => 'Benar', 'media_id' => null],
                    'False' => ['text' => 'Salah', 'media_id' => null]
                ];
                $data['key_answer'] = ['answer' => $this->faker->randomElement(['True', 'False'])];
                $data['content'] = 'Tentukan apakah pernyataan berikut benar atau salah.';
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
                $itemKeys = ['1', '2', '3', '4'];
                
                $optionsData = [];
                // Map untuk memetakan konten asli (correct step) ke key yang diacak (1, 2, 3, 4)
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
                    
                    // Simpan pemetaan: Konten Asli -> Key tampilan (1, 2, 3, 4)
                    $contentToKeyMap[$originalContent] = $key;
                }
                
                $data['options'] = $optionsData;

                // Kunci Jawaban: Iterasi melalui urutan yang benar ($steps) dan temukan key yang sesuai dari map
                $correctOrder = [];
                foreach ($steps as $correctStep) { 
                    // Ambil key (1, 2, 3, 4) yang menyimpan konten yang benar untuk urutan ini
                    $correctOrder[] = (int) $contentToKeyMap[$correctStep];
                }
                
                $data['key_answer'] = ['order' => $correctOrder];
                $data['content'] = 'Urutkan langkah-langkah berikut secara kronologis.';
                break;

            case QuestionTypeEnum::NumericalInput:
                // Generate mathematical expressions with LaTeX for numerical input questions
                $mathExpressions = [
                    [
                        'question' => 'Hitung nilai dari $$\\frac{3}{4} + \\frac{2}{5}$$',
                        'answer' => 1.15,  // 3/4 + 2/5 = 15/20 + 8/20 = 23/20 = 1.15
                        'tolerance' => 0.01,
                        'unit' => null
                    ],
                    [
                        'question' => 'Jika $$x = 2$$, hitung nilai dari $$2x^2 + 3x - 5$$',
                        'answer' => 9,  // 2(2)^2 + 3(2) - 5 = 8 + 6 - 5 = 9
                        'tolerance' => 0.01,
                        'unit' => null
                    ],
                    [
                        'question' => 'Hitung luas persegi panjang dengan panjang $$\\sqrt{16}$$ cm dan lebar $$\\frac{3}{2}$$ cm',
                        'answer' => 6,  // sqrt(16) * 3/2 = 4 * 1.5 = 6
                        'tolerance' => 0.01,
                        'unit' => 'cm²'
                    ],
                    [
                        'question' => 'Hitung nilai dari $$\\sin(30°) + \\cos(60°)$$',
                        'answer' => 1,  // sin(30°) + cos(60°) = 0.5 + 0.5 = 1
                        'tolerance' => 0.01,
                        'unit' => null
                    ],
                    [
                        'question' => 'Jika $$a = 3$$ dan $$b = 4$$, hitung $$\\sqrt{a^2 + b^2}$$',
                        'answer' => 5,  // sqrt(3^2 + 4^2) = sqrt(9 + 16) = sqrt(25) = 5
                        'tolerance' => 0.01,
                        'unit' => null
                    ],
                    [
                        'question' => 'Hitung volume kubus dengan rusuk $$\\sqrt[3]{27}$$ cm',
                        'answer' => 27,  // (cube_root(27))^3 = 3^3 = 27
                        'tolerance' => 0.01,
                        'unit' => 'cm³'
                    ],
                    [
                        'question' => 'Hitung nilai dari $$\\sum_{i=1}^{5} i = 1 + 2 + 3 + 4 + 5$$',
                        'answer' => 15,  // sum of 1 to 5 = 15
                        'tolerance' => 0.01,
                        'unit' => null
                    ],
                    [
                        'question' => 'Jika $$\\pi \\approx 3.14$$, hitung luas lingkaran dengan jari-jari $$r = 2$$ cm menggunakan rumus $$L = \\pi r^2$$',
                        'answer' => 12.56,  // 3.14 * 2^2 = 3.14 * 4 = 12.56
                        'tolerance' => 0.01,
                        'unit' => 'cm²'
                    ],
                    [
                        'question' => 'Sederhanakan ekspresi $$\\frac{x^2 - 4}{x - 2}$$ untuk $$x \\neq 2$$',
                        'answer' => 4,  // (x^2 - 4)/(x - 2) = (x - 2)(x + 2)/(x - 2) = x + 2, untuk x ≠ 2
                        'tolerance' => 0.01,
                        'unit' => null
                    ],
                    [
                        'question' => 'Hitung integral $$\\int_0^2 3x^2 dx$$',
                        'answer' => 8,  // ∫3x² dx from 0 to 2 = [x³] from 0 to 2 = 2³ - 0³ = 8
                        'tolerance' => 0.01,
                        'unit' => null
                    ]
                ];
                
                // Randomly select one mathematical expression
                $selectedMath = $this->faker->randomElement($mathExpressions);
                
                $data['options'] = []; 
                $data['key_answer'] = [
                    'answer' => $selectedMath['answer'],
                    'tolerance' => $selectedMath['tolerance'], 
                    'unit' => $selectedMath['unit']
                ];
                $data['content'] = $selectedMath['question'] . ' (Masukkan jawaban numerik)';
                break;
        }

        return $data;
    }
}