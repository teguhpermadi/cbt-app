<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

Route::get('/question-banks/{questionBank}', \App\Livewire\Question\QuestionBankComponent::class)
    ->name('question-banks.show');

Route::get('/questions/{question}/edit', \App\Livewire\Question\Form\EditQuestionComponent::class)
    ->name('questions.edit');

Route::get('/question-banks/{questionBank}/create-question', \App\Livewire\Question\Form\CreateQuestionComponent::class)
    ->name('questions.create');

Route::get('/exams/monitor-session/{record}', \App\Livewire\Exams\MonitorExamResultDetail::class)
    ->middleware(['auth'])
    ->name('exams.monitor-session.detail');
