<?php

// Test script untuk verifikasi helper methods
use App\Models\Question;
use App\Enums\QuestionTypeEnum;

echo "=== Testing JSON Conversion Helpers ===\n\n";

// Helper function to print result
function printResult($type, $question)
{
    echo "Testing {$type}...\n";
    echo "Options JSON:\n";
    print_r($question->getOptionsForExam());
    echo "Key Answer JSON:\n";
    print_r($question->getKeyAnswerForExam());
    echo "\n-------------------\n\n";
}

// 1. Multiple Choice
$mc = Question::factory()->withType(QuestionTypeEnum::MultipleChoice)->create();
printResult('Multiple Choice', $mc);

// 2. Matching
$match = Question::factory()->withType(QuestionTypeEnum::Matching)->create();
printResult('Matching', $match);

// 3. Numerical Input
$num = Question::factory()->withType(QuestionTypeEnum::NumericalInput)->create();
printResult('Numerical Input', $num);

echo "=== All Tests Completed ===\n";
