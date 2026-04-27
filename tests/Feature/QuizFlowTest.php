<?php

use App\Models\Quiz;
use App\Models\Room;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Player;
use App\Livewire\CreateRoom;
use App\Livewire\JoinRoom;
use App\Livewire\Lobby;
use App\Livewire\QuizPlay;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('host can create a room and see lobby', function () {
    $quiz = Quiz::create(['title' => 'General Knowledge']);

    Livewire::test(CreateRoom::class)
        ->set('selectedQuizId', $quiz->id)
        ->call('createRoom')
        ->assertHasNoErrors();

    $room = Room::first();
    expect($room)->not->toBeNull();
    
    Livewire::test(Lobby::class, ['code' => $room->code])
        ->assertSee($room->code);
});

test('player can join a room and answer a question', function () {
    $quiz = Quiz::create(['title' => 'General Knowledge']);
    $question = $quiz->questions()->create(['question' => 'What is 1+1?']);
    $answer = $question->answers()->create(['text' => '2', 'is_correct' => true]);

    $room = Room::create([
        'code' => '55555',
        'quiz_id' => $quiz->id,
        'status' => 'active',
        'current_question_id' => $question->id
    ]);

    Livewire::test(JoinRoom::class)
        ->set('roomCode', '55555')
        ->set('name', 'Player One')
        ->call('join')
        ->assertRedirect(route('room.lobby', ['code' => '55555']));

    $player = Player::where('name', 'Player One')->first();
    session(['player_id' => $player->id]);

    Livewire::test(QuizPlay::class, ['code' => '55555'])
        ->assertSee('What is 1+1?')
        ->call('submitAnswer', $answer->id)
        ->assertSet('hasAnswered', true);

    $player->refresh();
    expect($player->score)->toBe(100);
});
