<script>
// Alpine.js global functions for CBT Application
window.matchingViewer = function(correctPairs, showCorrectAnswers) {
    return {
        correctPairs: correctPairs,
        showCorrectAnswers: showCorrectAnswers,
        
        init() {
            // Initialize matching viewer component
        }
    }
}

// Global event listeners for modals and interactions
window.addEventListener('open-delete-modal', (event) => {
    // This event will be handled by individual components
    // Components should listen for this event and handle their own modal logic
});

// Utility functions for Alpine.js
window.AlpineUtils = {
    // Helper for dispatching events
    dispatch: function(eventName, data = null) {
        window.dispatchEvent(new CustomEvent(eventName, { detail: data }));
    },
    
    // Helper for showing notifications
    notify: function(type, message) {
        // This can be integrated with your notification system
        console.log(`[${type}] ${message}`);
    }
}
</script>
