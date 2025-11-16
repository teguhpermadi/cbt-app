<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Question; 
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
     * Listen for timer-updated, difficulty-updated, and score-updated events
     */
    protected $listeners = [
        'timer-updated' => 'refreshQuestion',
        'difficulty-updated' => 'refreshQuestion',
        'score-updated' => 'refreshQuestion'
    ];

    /**
     * Refresh question data when timer, difficulty, or score is updated
     */
    public function refreshQuestion()
    {
        // Refresh the question model to get updated timer, difficulty, and score values
        $this->question->refresh();
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
        
        // Handle simple array structure like ["B","C"]
        if (is_array($keyAnswer)) {
            return $keyAnswer;
        }
        
        return [];
    }

    public function render()
    {
        return view('livewire.question-detail-viewer');
    }
}