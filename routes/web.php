<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\CreateRoom;
use App\Livewire\JoinRoom;
use App\Livewire\Lobby;
use App\Livewire\QuizPlay;
use App\Livewire\ScoreScreen;
use App\Livewire\QuizList;
use App\Livewire\QuizEditor;

Route::get('/', function () {
    return redirect()->route('join');
});

Route::get('/join', JoinRoom::class)->name('join');
Route::get('/host', CreateRoom::class)->name('host');
Route::get('/room/{code}', Lobby::class)->name('room.lobby');
Route::get('/play/{code}', QuizPlay::class)->name('room.play');
Route::get('/results/{code}', ScoreScreen::class)->name('room.results');

Route::get('/quizzes', QuizList::class)->name('quizzes.index');
Route::get('/quizzes/{id}/edit', QuizEditor::class)->name('quizzes.edit');
