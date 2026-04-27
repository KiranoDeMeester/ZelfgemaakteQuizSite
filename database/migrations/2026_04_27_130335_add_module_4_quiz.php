<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $quiz = \App\Models\Quiz::create(['title' => 'Module 4: Intake & Offerte 💼']);

        // Kennisvraag 1
        $q1 = $quiz->questions()->create([
            'question' => 'Wat is het hoofddoel van een intakegesprek met een nieuwe klant?',
            'time_limit' => 30
        ]);
        $q1->answers()->createMany([
            ['text' => 'Meteen een prijs geven', 'is_correct' => false],
            ['text' => 'Begrijpen wat de klant nodig heeft', 'is_correct' => true],
            ['text' => 'Zo snel mogelijk verkopen', 'is_correct' => false],
            ['text' => 'De klant overtuigen van het duurste pakket', 'is_correct' => false],
        ]);

        // Kennisvraag 2
        $q2 = $quiz->questions()->create([
            'question' => 'Wat gebeurt er juridisch wanneer een klant een offerte ondertekent?',
            'time_limit' => 30
        ]);
        $q2->answers()->createMany([
            ['text' => 'Niets, het blijft een voorstel', 'is_correct' => false],
            ['text' => 'Enkel de klant is gebonden', 'is_correct' => false],
            ['text' => 'Het wordt een bindend contract', 'is_correct' => true],
            ['text' => 'De prijs kan nog aangepast worden', 'is_correct' => false],
        ]);

        // Toepassingsvraag 1
        $q3 = $quiz->questions()->create([
            'question' => 'Een kapper in Torhout heeft geen logo en enkel een Facebook-pagina. Welke offerte kies je best?',
            'time_limit' => 30
        ]);
        $q3->answers()->createMany([
            ['text' => 'Model A', 'is_correct' => true],
            ['text' => 'Model B', 'is_correct' => false],
            ['text' => 'Model C', 'is_correct' => false],
            ['text' => 'Geen van de drie', 'is_correct' => false],
        ]);

        // Toepassingsvraag 2
        $q4 = $quiz->questions()->create([
            'question' => 'Een bedrijf wil een unieke website met sterke branding en professionele uitstraling. Welke keuze past het best?',
            'time_limit' => 30
        ]);
        $q4->answers()->createMany([
            ['text' => 'Model A', 'is_correct' => false],
            ['text' => 'Model B', 'is_correct' => true],
            ['text' => 'Model C', 'is_correct' => false],
            ['text' => 'Geen offerte nodig', 'is_correct' => false],
        ]);

        // Misleidende vraag
        $q5 = $quiz->questions()->create([
            'question' => 'Welke uitspraak over intakegesprekken is correct?',
            'time_limit' => 30
        ]);
        $q5->answers()->createMany([
            ['text' => 'Je moet zo snel mogelijk een prijs geven', 'is_correct' => false],
            ['text' => 'De klant weet altijd exact wat hij nodig heeft', 'is_correct' => false],
            ['text' => 'Je moet eerst de noden begrijpen voor je een offerte maakt', 'is_correct' => true],
            ['text' => 'Hoe minder vragen je stelt, hoe beter', 'is_correct' => false],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
