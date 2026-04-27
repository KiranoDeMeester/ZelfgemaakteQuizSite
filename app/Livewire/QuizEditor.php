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

    public function addQuestion()
    {
        $this->validate([
            'questionText' => 'required|min:3',
            'answers.*.text' => 'required',
            'timeLimit' => 'required|integer|min:5'
        ]);

        $question = $this->quiz->questions()->create([
            'question' => $this->questionText,
            'time_limit' => $this->timeLimit
        ]);

        foreach ($this->answers as $answerData) {
            $question->answers()->create($answerData);
        }

        $this->reset(['questionText', 'timeLimit', 'editingQuestionId']);
        $this->answers = [
            ['text' => '', 'is_correct' => true],
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
        ];

        session()->flash('success', 'Vraag toegevoegd!');
    }

    public function deleteQuestion($id)
    {
        Question::destroy($id);
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
