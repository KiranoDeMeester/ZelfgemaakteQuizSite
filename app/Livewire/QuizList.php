<?php

namespace App\Livewire;

use App\Models\Quiz;
use App\Services\QuizService;
use Livewire\Component;

class QuizList extends Component
{
    public $newQuizTitle = '';
    public $quizIdToDelete = null;

    public function confirmDelete($id)
    {
        $this->quizIdToDelete = $id;
    }

    public function cancelDelete()
    {
        $this->quizIdToDelete = null;
    }

    public function createQuiz()
    {
        $this->validate([
            'newQuizTitle' => 'required|min:3|max:255'
        ]);

        $quiz = app(QuizService::class)->createQuiz($this->newQuizTitle);
        $this->newQuizTitle = '';
        
        return $this->redirect(route('quizzes.edit', ['id' => $quiz->id]), navigate: true);
    }

    public function deleteQuiz()
    {
        if ($this->quizIdToDelete) {
            app(QuizService::class)->deleteQuiz($this->quizIdToDelete);
            $this->quizIdToDelete = null;
            $this->dispatch('toast', message: 'Quiz succesvol verwijderd.', type: 'success');
        }
    }

    public function render()
    {
        return view('livewire.quiz-list', [
            'quizzes' => Quiz::withCount('questions')->get()
        ])->layout('layouts.app');
    }
}
