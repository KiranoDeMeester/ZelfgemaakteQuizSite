<?php

use App\Models\Quiz;
use App\Models\Room;
use App\Models\Player;
use App\Livewire\ScoreScreen;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('host can export csv', function () {
    $quiz = Quiz::create(['title' => 'Test Quiz']);
    
    $room = Room::create([
        'code' => '12345',
        'quiz_id' => $quiz->id,
        'status' => 'finished'
    ]);

    $player = $room->players()->create([
        'name' => 'John Doe',
        'score' => 100
    ]);

    session(['host_room_12345' => true]);

    Livewire::test(ScoreScreen::class, ['code' => '12345'])
        ->call('exportCsv')
        ->assertFileDownloaded("results_12345.csv");
});
