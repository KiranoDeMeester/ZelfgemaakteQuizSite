<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;

class QuizService
{
    public function createQuiz(string $title): Quiz
    {
        return Quiz::create(['title' => $title]);
    }

    public function deleteQuiz(int $id): void
    {
        Quiz::destroy($id);
    }

    public function addQuestion(Quiz $quiz, string $text, int $timeLimit, array $answers, ?string $image = null): Question
    {
        $question = $quiz->questions()->create([
            'question' => $text,
            'time_limit' => $timeLimit,
            'image' => $image
        ]);

        foreach ($answers as $answerData) {
            $question->answers()->create([
                'text' => $answerData['text'],
                'is_correct' => $answerData['is_correct']
            ]);
        }

        return $question;
    }

    public function updateQuestion(Question $question, string $text, int $timeLimit, array $answers, ?string $image = null): void
    {
        $question->update([
            'question' => $text,
            'time_limit' => $timeLimit,
            'image' => $image
        ]);

        // Simple approach: delete old answers and create new ones to maintain order and simplicity
        $question->answers()->delete();

        foreach ($answers as $answerData) {
            $question->answers()->create([
                'text' => $answerData['text'],
                'is_correct' => $answerData['is_correct']
            ]);
        }
    }

    public function deleteQuestion(int $id): void
    {
        Question::destroy($id);
    }
}
