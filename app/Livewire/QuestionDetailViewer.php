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
     * Listen for timer-updated and difficulty-updated events
     */
    protected $listeners = [
        'timer-updated' => 'refreshQuestion',
        'difficulty-updated' => 'refreshQuestion'
    ];

    /**
     * Refresh question data when timer or difficulty is updated
     */
    public function refreshQuestion()
    {
        // Refresh the question model to get updated timer and difficulty values
        $this->question->refresh();
    }

    public function getOptions()
    {
        if ($this->question->question_type === 'multiple choice') {
            return [
                'A' => [
                    'text' => 'Semua keragaman harus disatukan dan disamakan agar tidak ada perbedaan.',
                    'is_correct' => false
                ],
                'B' => [
                    'text' => 'Perbedaan adalah sumber konflik yang harus dihindari dalam kehidupan bermasyarakat.',
                    'is_correct' => false
                ],
                'C' => [
                    'text' => 'Walaupun berbeda-beda suku dan budaya, kita harus tetap menjaga keutuhan negara.',
                    'is_correct' => true
                ],
                'D' => [
                    'text' => 'Setiap daerah harus fokus pada budayanya sendiri tanpa perlu berinteraksi dengan daerah lain.',
                    'is_correct' => false
                ],
            ];
        }
        return [];
    }

    public function render()
    {
        return view('livewire.question-detail-viewer');
    }
}