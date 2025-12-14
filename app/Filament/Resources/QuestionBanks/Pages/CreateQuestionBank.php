<?php

namespace App\Filament\Resources\QuestionBanks\Pages;

use App\Filament\Resources\QuestionBanks\QuestionBankResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQuestionBank extends CreateRecord
{
    protected static string $resource = QuestionBankResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('page-question-banks', ['record' => $this->record->id]);
    }
}
