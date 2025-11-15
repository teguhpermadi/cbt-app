<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Question;
use App\Enums\TimerEnum;

class TimerSelector extends Component
{
    public Question $question;
    
    public ?TimerEnum $selectedTimer = null;
    
    public bool $isLoading = false;
    
    /**
     * Mount the component.
     */
    public function mount(Question $question): void
    {
        $this->question = $question;
        $this->selectedTimer = $question->timer;
    }
    
    /**
     * Update the question timer when selection changes.
     */
    public function updatedSelectedTimer(): void
    {
        if ($this->selectedTimer) {
            try {
                // Set loading state
                $this->isLoading = true;
                
                // Simulate processing time untuk demo loading state
                usleep(1000000); // 1 second delay
                
                $this->question->timer = $this->selectedTimer;
                $this->question->save();
                
                $this->dispatch('timer-updated', timer: $this->selectedTimer);
                
                // Add success notification
                $this->dispatch('notify', message: 'Timer berhasil diperbarui', type: 'success');
                
            } catch (\Exception $e) {
                // Add error notification
                $this->dispatch('notify', message: 'Gagal memperbarui timer', type: 'error');
                
                // Reset to original value
                $this->selectedTimer = $this->question->timer;
                
            } finally {
                // Always remove loading state
                $this->isLoading = false;
            }
        }
    }
    
    /**
     * Get all available timer options.
     */
    public function getTimerOptions(): array
    {
        return collect(TimerEnum::cases())->mapWithKeys(fn (TimerEnum $timer) => [
            $timer->value => $timer->getLabel()
        ])->toArray();
    }
    
    public function render()
    {
        return view('livewire.timer-selector');
    }
}
