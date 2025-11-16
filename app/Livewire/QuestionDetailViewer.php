<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Question; 
use Illuminate\Support\Facades\Log;
// TIDAK perlu lagi mengimport model Question jika Anda hanya menerima array/object
// Namun, kita pertahankan untuk type hinting jika diperlukan di masa depan.

class QuestionDetailViewer extends Component
{
    public $question; 

    /**
     * Mount lifecycle hook.
     * Menerima objek Question secara langsung dari parent.
     * @param Question $question Objek Question yang sudah dimuat.
     */
    public function mount($question)
    {
        $this->question = $question;
    }

    /**
     * Listen for timer-updated, difficulty-updated, score-updated, and order-updated events
     */
    protected $listeners = [
        'timer-updated' => 'refreshQuestion',
        'difficulty-updated' => 'refreshQuestion',
        'score-updated' => 'refreshQuestion',
        'order-updated' => 'refreshQuestion'
    ];

    /**
     * Refresh question data when timer, difficulty, score, or order is updated
     */
    public function refreshQuestion()
    {
        Log::info('QuestionDetailViewer: refreshQuestion called', [
            'question_id' => $this->question->id,
            'current_order' => $this->question->order
        ]);
        
        // Refresh the question model to get updated timer, difficulty, score, and order values
        $this->question->refresh();
        
        Log::info('QuestionDetailViewer: question refreshed', [
            'question_id' => $this->question->id,
            'new_order' => $this->question->order
        ]);
    }

    public function getOptions()
    {
        return $this->question->options ?? [];
    }

    public function getCorrectAnswers()
    {
        $keyAnswer = $this->question->key_answer ?? [];
        
        // Handle structure like {"answers":["B","C"]} for multiple selection
        if (isset($keyAnswer['answers']) && is_array($keyAnswer['answers'])) {
            return $keyAnswer['answers'];
        }
        
        // Handle structure like {"pairs":{"L1":"R1","L2":"R2"}} for matching
        if (isset($keyAnswer['pairs']) && is_array($keyAnswer['pairs'])) {
            return $keyAnswer;
        }
        
        // Handle structure like {"order":["A","B","C"]} for ordering
        if (isset($keyAnswer['order']) && is_array($keyAnswer['order'])) {
            return $keyAnswer;
        }
        
        // Handle structure like {"answer":"42"} or {"answer":"\\frac{1}{2}"} for numerical_input
        if (isset($keyAnswer['answer'])) {
            return $keyAnswer['answer'];
        }
        
        // Handle simple array structure like ["B","C"]
        if (is_array($keyAnswer)) {
            return $keyAnswer;
        }
        
        // Handle simple string value
        if (is_string($keyAnswer)) {
            return $keyAnswer;
        }
        
        return [];
    }

    public function render()
    {
        return view('livewire.question-detail-viewer');
    }
}