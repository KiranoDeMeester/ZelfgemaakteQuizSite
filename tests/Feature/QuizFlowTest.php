<?php

use App\Models\Quiz;
use App\Models\Room;
use App\Models\Player;
use Livewire\Livewire;
use App\Livewire\CreateRoom;
use App\Livewire\JoinRoom;
use App\Livewire\Lobby;
use App\Livewire\QuizPlay;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->quiz = Quiz::create(['title' => 'Test Quiz']);
    $this->question = $this->quiz->questions()->create([
        'question' => 'Wat is 1+1?',
        'time_limit' => 30
    ]);
    $this->correctAnswer = $this->question->answers()->create(['text' => '2', 'is_correct' => true]);
    $this->wrongAnswer = $this->question->answers()->create(['text' => '3', 'is_correct' => false]);
});

test('host can create a room and see lobby', function () {
    Livewire::test(CreateRoom::class)
        ->set('selectedQuizId', $this->quiz->id)
        ->call('createRoom')
        ->assertRedirect();
        
    $room = Room::first();
    expect($room)->not->toBeNull();
    expect($room->code)->toHaveLength(5);
    
    Livewire::test(Lobby::class, ['code' => $room->code])
        ->assertSee($room->code);
});

test('player can join a room and answer a question', function () {
    $room = Room::create(['code' => '12345', 'quiz_id' => $this->quiz->id, 'status' => 'waiting']);
    
    // Join
    Livewire::test(JoinRoom::class)
        ->set('roomCode', '12345')
        ->set('name', 'Gamer123')
        ->call('join')
        ->assertRedirect();
        
    $player = Player::where('name', 'Gamer123')->first();
    expect($player)->not->toBeNull();
    
    // Start Quiz (as host)
    $room->update(['status' => 'active', 'current_question_id' => $this->question->id]);
    
    // Answer (as player)
    session(['player_id' => $player->id]);
    Livewire::test(QuizPlay::class, ['code' => '12345'])
        ->assertSee('Wat is 1+1?')
        ->call('submitAnswer', $this->correctAnswer->id)
        ->assertSet('hasAnswered', true);
        
    $player->refresh();
    expect($player->score)->toBe(100);
});
