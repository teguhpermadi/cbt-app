<x-filament-panels::page>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('page-refreshed', (event) => {
                // Store the question ID in sessionStorage before reload
                if (event.questionId) {
                    sessionStorage.setItem('scrollToQuestion', event.questionId);
                }
                // Refresh the page to re-render with updated order
                window.location.reload();
            });
        });
        
        // After page reload, check if we need to scroll to a specific question
        document.addEventListener('DOMContentLoaded', () => {
            const questionId = sessionStorage.getItem('scrollToQuestion');
            if (questionId) {
                // Clear the stored ID
                sessionStorage.removeItem('scrollToQuestion');
                
                // Wait a bit for Livewire to fully initialize
                setTimeout(() => {
                    const targetElement = document.getElementById(`question-${questionId}`);
                    if (targetElement) {
                        targetElement.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center' 
                        });
                        
                        // Add highlight effect
                        targetElement.classList.add('ring-2', 'ring-blue-500', 'ring-offset-2');
                        setTimeout(() => {
                            targetElement.classList.remove('ring-2', 'ring-blue-500', 'ring-offset-2');
                        }, 2000);
                    }
                }, 500);
            }
        });
    </script>
    
    @foreach ($questions as $question)
        <div id="question-{{ $question->id }}" class="transition-all duration-300">
            <livewire:question-detail-viewer :question="$question" :key="$question->id" />
        </div>
    @endforeach
</x-filament-panels::page>
