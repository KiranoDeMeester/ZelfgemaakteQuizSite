<?php

use App\Models\Quiz;
use App\Livewire\QuizList;
use App\Livewire\QuizEditor;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('host can create a new quiz', function () {
    Livewire::test(QuizList::class)
        ->set('newQuizTitle', 'New Science Quiz')
        ->call('createQuiz')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('quizzes', ['title' => 'New Science Quiz']);
});

test('host can add a question to a quiz', function () {
    $quiz = Quiz::create(['title' => 'History Quiz']);

    Livewire::test(QuizEditor::class, ['id' => $quiz->id])
        ->set('questionText', 'Who was the first president?')
        ->set('timeLimit', 30)
        ->set('answers.0.text', 'George Washington')
        ->set('answers.0.is_correct', true)
        ->set('answers.1.text', 'Thomas Jefferson')
        ->set('answers.1.is_correct', false)
        ->set('answers.2.text', 'Abraham Lincoln')
        ->set('answers.2.is_correct', false)
        ->set('answers.3.text', 'John Adams')
        ->set('answers.3.is_correct', false)
        ->call('saveQuestion')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('questions', ['question' => 'Who was the first president?']);
    $this->assertDatabaseHas('answers', ['text' => 'George Washington', 'is_correct' => true]);
});

test('host can update an existing question', function () {
    $quiz = Quiz::create(['title' => 'History Quiz']);
    $question = $quiz->questions()->create(['question' => 'Old Question', 'time_limit' => 20]);
    $question->answers()->create(['text' => 'Old Answer', 'is_correct' => true]);
    $question->answers()->create(['text' => 'Wrong Answer', 'is_correct' => false]);
    $question->answers()->create(['text' => 'Wrong Answer 2', 'is_correct' => false]);
    $question->answers()->create(['text' => 'Wrong Answer 3', 'is_correct' => false]);

    Livewire::test(QuizEditor::class, ['id' => $quiz->id])
        ->call('editQuestion', $question->id)
        ->assertSet('questionText', 'Old Question')
        ->set('questionText', 'Updated Question')
        ->set('answers.0.text', 'New Correct Answer')
        ->call('saveQuestion')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('questions', ['id' => $question->id, 'question' => 'Updated Question']);
    $this->assertDatabaseHas('answers', ['question_id' => $question->id, 'text' => 'New Correct Answer', 'is_correct' => true]);
});
