<?php

namespace App\Livewire;

use App\Models\QuestionBank;
use App\Models\Question;
use Livewire\Component;
use Livewire\WithPagination;

class QuestionBankList extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedQuestionBank = null;
    public $perPage = 10;
    public $questionBankId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'questionBankId' => ['except' => null],
    ];

    /**
     * Listen for events from child components
     */
    protected $listeners = [
        'question-updated' => 'rerender',
        'question-deleted' => 'rerender',
        'refresh-parent' => 'rerender',
        'order-updated' => 'rerender',
        'page-refreshed' => 'rerender',
    ];

    public function mount($question_bank_id = null)
    {
        $this->questionBankId = $question_bank_id;
        
        if ($this->questionBankId) {
            $this->selectedQuestionBank = QuestionBank::findOrFail($this->questionBankId);
        }
    }

    public function selectQuestionBank($questionBankId)
    {
        $this->selectedQuestionBank = QuestionBank::findOrFail($questionBankId);
        $this->questionBankId = $questionBankId;
        
        // Update URL to reflect the selected question bank
        $this->dispatch('url-updated', questionBankId: $questionBankId);
    }

    public function loadQuestionBanks()
    {
        $this->selectedQuestionBank = null;
        $this->questionBankId = null;
    }

    /**
     * Rerender the component - can be called from child components
     * This will refresh the selected question bank and questions
     */
    public function rerender()
    {
        // Refresh the selected question bank if exists
        if ($this->selectedQuestionBank) {
            $this->selectedQuestionBank = QuestionBank::find($this->selectedQuestionBank->id);
        }
        
        // Trigger component re-render
        $this->render();
    }

    public function getQuestionBanksProperty()
    {
        return QuestionBank::with(['teacher', 'subject'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }

    public function getQuestionsProperty()
    {
        if (!$this->selectedQuestionBank) {
            return collect();
        }

        return $this->selectedQuestionBank->questions()
            ->with(['readingMaterial'])
            ->orderBy('order', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.question-bank-list', [
            'questionBanks' => $this->questionBanks,
            'questions' => $this->questions,
        ]);
    }
}
