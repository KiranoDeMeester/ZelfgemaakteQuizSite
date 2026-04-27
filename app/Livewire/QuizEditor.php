<?php

namespace App\Livewire;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use Livewire\Component;

class QuizEditor extends Component
{
    public $quiz;
    public $editingQuestionId = null;
    public $questionText = '';
    public $imageUrl = '';
    public $timeLimit = 20;
    public $answers = [
        ['text' => '', 'is_correct' => true],
        ['text' => '', 'is_correct' => false],
        ['text' => '', 'is_correct' => false],
        ['text' => '', 'is_correct' => false],
    ];

    public function mount($id)
    {
        $this->quiz = Quiz::findOrFail($id);
    }

    public function saveQuestion(\App\Services\QuizService $service)
    {
        $this->validate([
            'questionText' => 'required|min:3',
            'imageUrl' => 'nullable|url',
            'answers.*.text' => 'required',
            'timeLimit' => 'required|integer|min:5'
        ]);

        if ($this->editingQuestionId) {
            $question = Question::findOrFail($this->editingQuestionId);
            $service->updateQuestion($question, $this->questionText, $this->timeLimit, $this->answers, $this->imageUrl);
            session()->flash('success', 'Vraag bijgewerkt!');
        } else {
            $service->addQuestion($this->quiz, $this->questionText, $this->timeLimit, $this->answers, $this->imageUrl);
            session()->flash('success', 'Vraag toegevoegd!');
        }

        $this->reset(['questionText', 'imageUrl', 'timeLimit', 'editingQuestionId']);
        $this->answers = [
            ['text' => '', 'is_correct' => true],
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
        ];
    }

    public function editQuestion($id)
    {
        $question = Question::with('answers')->findOrFail($id);
        $this->editingQuestionId = $id;
        $this->questionText = $question->question;
        $this->imageUrl = $question->image;
        $this->timeLimit = $question->time_limit;
        
        $this->answers = [];
        foreach ($question->answers as $answer) {
            $this->answers[] = [
                'text' => $answer->text,
                'is_correct' => $answer->is_correct
            ];
        }
    }

    public function cancelEdit()
    {
        $this->reset(['questionText', 'timeLimit', 'editingQuestionId']);
        $this->answers = [
            ['text' => '', 'is_correct' => true],
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
        ];
    }

    public function deleteQuestion($id, \App\Services\QuizService $service)
    {
        $service->deleteQuestion($id);
    }

    public function setCorrect($index)
    {
        foreach ($this->answers as $i => $answer) {
            $this->answers[$i]['is_correct'] = ($i === $index);
        }
    }

    public function render()
    {
        return view('livewire.quiz-editor', [
            'questions' => $this->quiz->questions()->with('answers')->get()
        ])->layout('layouts.app');
    }
}
