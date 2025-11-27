<div
    x-data="{ loading: false }"
    x-on:question-loading-start.window="loading = true"
    x-on:question-loading-end.window="loading = false"
    x-show="loading"
    x-transition
    class="mb-4 p-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 border border-blue-200 dark:border-blue-800"
    role="alert"
    style="display: none;">
    <div class="flex items-center justify-center w-full">
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="font-medium">Updating question...</span>
    </div>
</div>