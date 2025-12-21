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
            ->layout('components.layouts.monitor-detail', [
                'title' => 'Detail Hasil Ujian - ' . ($this->record->user->name ?? ''),
                'backUrl' => route('filament.admin.resources.exams.monitor', ['record' => $this->record->exam_id]),
            ]);
    }
}
