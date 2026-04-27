<?php

namespace App\Livewire;

use App\Models\Quiz;
use Livewire\Component;

class QuizList extends Component
{
    public $newQuizTitle = '';

    public function createQuiz(\App\Services\QuizService $service)
    {
        $this->validate([
            'newQuizTitle' => 'required|min:3|max:255'
        ]);

        $quiz = $service->createQuiz($this->newQuizTitle);
        $this->newQuizTitle = '';
        
        return $this->redirect(route('quizzes.edit', ['id' => $quiz->id]), navigate: true);
    }

    public function deleteQuiz($id, \App\Services\QuizService $service)
    {
        $service->deleteQuiz($id);
    }

    public function render()
    {
        return view('livewire.quiz-list', [
            'quizzes' => Quiz::withCount('questions')->get()
        ])->layout('layouts.app');
    }
}
