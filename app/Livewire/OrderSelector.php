<?php

namespace App\Livewire;

use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class OrderSelector extends Component
{
    public string $questionId;

    public int $order;

    public int $maxOrder = 1;

    public bool $isLoading = false;

    private ?Question $question = null;

    private function getQuestion(): Question
    {
        if ($this->question === null) {
            $this->question = Question::findOrFail($this->questionId);
        }
        return $this->question;
    }

    public function mount(Question $question): void
    {
        Log::info('OrderSelector: Mount called', [
            'question_id' => $question->id,
            'question_bank_id' => $question->question_bank_id,
            'question_order' => $question->order
        ]);

        $this->questionId = $question->id;
        $this->question = $question; // Cache untuk mount
        $this->order = (int) ($question->order ?? 1);

        $this->maxOrder = Question::where('question_bank_id', $question->question_bank_id)
            ->count();

        if ($this->maxOrder < 1) {
            $this->maxOrder = 1;
        }

        Log::info('OrderSelector: Mount completed', [
            'order' => $this->order,
            'maxOrder' => $this->maxOrder
        ]);
    }

    public function updatedOrder(): void
    {
        Log::info('OrderSelector: updatedOrder called', [
            'new_order_value' => $this->order
        ]);
        
        $this->updateOrder();
    }

    public function updateOrder(): void
    {
        $question = $this->getQuestion();
        
        Log::info('OrderSelector: updateOrder called', [
            'current_order' => $this->order,
            'question_id' => $question->id,
            'question_bank_id' => $question->question_bank_id,
            'original_question_order' => $question->order,
            'maxOrder' => $this->maxOrder
        ]);

        $this->order = (int) $this->order;

        // Manual validation to avoid serialization issues
        $validator = Validator::make(
            ['order' => $this->order],
            ['order' => 'required|integer|min:1|max:' . $this->maxOrder]
        );

        if ($validator->fails()) {
            Log::info('OrderSelector: Validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);

            $this->dispatch('notify', message: 'Urutan harus di antara 1 dan ' . $this->maxOrder, type: 'error');
            $this->order = (int) ($question->order ?? 1);
            return;
        }

        $originalOrder = (int) ($question->order ?? 1);
        $newOrder = $this->order;

        Log::info('OrderSelector: After validation', [
            'original_order' => $originalOrder,
            'new_order' => $newOrder,
            'orders_equal' => ($newOrder === $originalOrder)
        ]);

        if ($newOrder === $originalOrder) {
            Log::info('OrderSelector: Orders are equal, returning early');
            return;
        }

        try {
            $this->isLoading = true;
            
            Log::info('OrderSelector: Starting database transaction', [
                'original_order' => $originalOrder,
                'new_order' => $newOrder
            ]);

            // Simulate processing time untuk konsistensi dengan selector lain
            // usleep(1000000);

            DB::transaction(function () use ($originalOrder, $newOrder, $question) {
                $query = Question::where('question_bank_id', $question->question_bank_id);

                if ($newOrder < $originalOrder) {
                    // Geser ke bawah semua soal di rentang [newOrder, originalOrder - 1]
                    $questionsToShift = $query->whereBetween('order', [$newOrder, $originalOrder - 1])
                        ->where('id', '!=', $question->id)
                        ->orderBy('order')
                        ->get();
                    
                    Log::info('OrderSelector: Shifting questions down', [
                        'questions_count' => $questionsToShift->count(),
                        'range' => [$newOrder, $originalOrder - 1]
                    ]);

                    $questionsToShift->each(function (Question $questionToShift) {
                        Log::info('OrderSelector: Incrementing order', [
                            'question_id' => $questionToShift->id,
                            'old_order' => $questionToShift->order,
                            'new_order' => $questionToShift->order + 1
                        ]);
                        $questionToShift->increment('order');
                    });
                } else {
                    // Geser ke atas semua soal di rentang [originalOrder + 1, newOrder]
                    $questionsToShift = $query->whereBetween('order', [$originalOrder + 1, $newOrder])
                        ->where('id', '!=', $question->id)
                        ->orderBy('order')
                        ->get();
                    
                    Log::info('OrderSelector: Shifting questions up', [
                        'questions_count' => $questionsToShift->count(),
                        'range' => [$originalOrder + 1, $newOrder]
                    ]);

                    $questionsToShift->each(function (Question $questionToShift) {
                        Log::info('OrderSelector: Decrementing order', [
                            'question_id' => $questionToShift->id,
                            'old_order' => $questionToShift->order,
                            'new_order' => $questionToShift->order - 1
                        ]);
                        $questionToShift->decrement('order');
                    });
                }

                Log::info('OrderSelector: Updating main question order', [
                    'question_id' => $question->id,
                    'old_order' => $question->order,
                    'new_order' => $newOrder
                ]);

                $question->order = $newOrder;
                $question->save();
            });

            $question->refresh();

            $this->maxOrder = Question::where('question_bank_id', $question->question_bank_id)
                ->count();

            Log::info('OrderSelector: Transaction completed successfully', [
                'final_question_order' => $question->order,
                'final_maxOrder' => $this->maxOrder
            ]);

            $this->dispatch('order-updated', order: $question->order)->to('question-detail-viewer');
            
            // Trigger page refresh and scroll to updated question
            $this->dispatch('page-refreshed', questionId: $question->id);

            $this->dispatch('notify', message: 'Urutan soal berhasil diperbarui', type: 'success');
        } catch (\Throwable $e) {
            Log::error('OrderSelector: Error during update', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'question_id' => $question->id,
                'original_order' => $originalOrder,
                'new_order' => $newOrder
            ]);

            $this->dispatch('notify', message: 'Gagal memperbarui urutan soal: ' . $e->getMessage(), type: 'error');

            $this->order = (int) ($question->order ?? 1);
        } finally {
            $this->isLoading = false;
            
            Log::info('OrderSelector: Loading state set to false');
        }
    }

    public function render()
    {
        return view('livewire.order-selector');
    }
}
