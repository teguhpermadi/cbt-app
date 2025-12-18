<?php

namespace App\Livewire\Exams;

use App\Models\ExamSession;
use Livewire\Component;

class MonitorExamResultDetail extends Component
{
    public ExamSession $record;

    public function mount(ExamSession $record)
    {
        $this->record = $record->load(['details.examQuestion', 'user', 'exam']);
    }

    public function render()
    {
        return view('livewire.exams.monitor-exam-result-detail')
            ->layout('components.layouts.app', ['title' => 'Detail Hasil Ujian - ' . ($this->record->user->name ?? '')]);
    }
}
