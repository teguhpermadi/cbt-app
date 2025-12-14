<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Livewire\Livewire::component('app.filament.resources.question-banks.pages.page-question-banks', \App\Filament\Resources\QuestionBanks\Pages\PageQuestionBanks::class);
    }
}
