<?php

namespace App\Enums;

enum QuestionTypeEnum: string
{
    case MultipleChoice = 'multiple_choice';
    case Essay = 'essay';
    case TrueFalse = 'true_false';
    case Matching = 'matching'; // Untuk mencocokkan
    case Ordering = 'ordering'; // Untuk mengurutkan
}