<?php

namespace App\Livewire;

use App\Models\Quiz;
use Livewire\Component;

class QuizList extends Component
{
    public $newQuizTitle = '';

    public function createQuiz()
    {
        $this->validate([
            'newQuizTitle' => 'required|min:3|max:255'
        ]);

        $quiz = Quiz::create(['title' => $this->newQuizTitle]);
        $this->newQuizTitle = '';
        
        return redirect()->route('quizzes.edit', ['id' => $quiz->id]);
    }

    public function deleteQuiz($id)
    {
        Quiz::destroy($id);
    }

    public function render()
    {
        return view('livewire.quiz-list', [
            'quizzes' => Quiz::withCount('questions')->get()
        ])->layout('layouts.app');
    }
}
