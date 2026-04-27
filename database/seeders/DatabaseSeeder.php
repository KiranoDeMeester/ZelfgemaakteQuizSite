<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $quiz = \App\Models\Quiz::create(['title' => 'Algemene Kennis Quiz']);

        $q1 = $quiz->questions()->create([
            'question' => 'Wat is de hoofdstad van Frankrijk?',
            'time_limit' => 30
        ]);
        $q1->answers()->createMany([
            ['text' => 'Londen', 'is_correct' => false],
            ['text' => 'Parijs', 'is_correct' => true],
            ['text' => 'Berlijn', 'is_correct' => false],
            ['text' => 'Madrid', 'is_correct' => false],
        ]);

        $q2 = $quiz->questions()->create([
            'question' => 'Welke planeet is het dichtst bij de zon?',
            'time_limit' => 20
        ]);
        $q2->answers()->createMany([
            ['text' => 'Venus', 'is_correct' => false],
            ['text' => 'Mercurius', 'is_correct' => true],
            ['text' => 'Aarde', 'is_correct' => false],
            ['text' => 'Mars', 'is_correct' => false],
        ]);

        // Nieuwe Quiz: MODULE 4 — OFFERTES EN KLANTGESPREKKEN
        $module4 = \App\Models\Quiz::create(['title' => 'MODULE 4 — OFFERTES EN KLANTGESPREKKEN']);

        // Vraag 1
        $mq1 = $module4->questions()->create([
            'question' => 'Wat is het hoofddoel van een intakegesprek met een nieuwe klant?',
            'time_limit' => 30
        ]);
        $mq1->answers()->createMany([
            ['text' => 'Meteen een prijs geven', 'is_correct' => false],
            ['text' => 'Begrijpen wat de klant nodig heeft', 'is_correct' => true],
            ['text' => 'Zo snel mogelijk verkopen', 'is_correct' => false],
            ['text' => 'De klant overtuigen van het duurste pakket', 'is_correct' => false],
        ]);

        // Vraag 2
        $mq2 = $module4->questions()->create([
            'question' => 'Wat gebeurt er juridisch wanneer een klant een offerte ondertekent?',
            'time_limit' => 30
        ]);
        $mq2->answers()->createMany([
            ['text' => 'Niets, het blijft een voorstel', 'is_correct' => false],
            ['text' => 'Enkel de klant is gebonden', 'is_correct' => false],
            ['text' => 'Het wordt een bindend contract', 'is_correct' => true],
            ['text' => 'De prijs kan nog aangepast worden', 'is_correct' => false],
        ]);

        // Vraag 3
        $mq3 = $module4->questions()->create([
            'question' => 'Een kapper in Torhout heeft geen logo en enkel een Facebook-pagina. Welke offerte kies je best?',
            'time_limit' => 30
        ]);
        $mq3->answers()->createMany([
            ['text' => 'Model A', 'is_correct' => true],
            ['text' => 'Model B', 'is_correct' => false],
            ['text' => 'Model C', 'is_correct' => false],
            ['text' => 'Geen van de drie', 'is_correct' => false],
        ]);

        // Vraag 4
        $mq4 = $module4->questions()->create([
            'question' => 'Een bedrijf wil een unieke website met sterke branding en professionele uitstraling. Welke keuze past het best?',
            'time_limit' => 30
        ]);
        $mq4->answers()->createMany([
            ['text' => 'Model A', 'is_correct' => false],
            ['text' => 'Model B', 'is_correct' => true],
            ['text' => 'Model C', 'is_correct' => false],
            ['text' => 'Geen offerte nodig', 'is_correct' => false],
        ]);

        // Vraag 5
        $mq5 = $module4->questions()->create([
            'question' => 'Welke uitspraak over intakegesprekken is correct?',
            'time_limit' => 30
        ]);
        $mq5->answers()->createMany([
            ['text' => 'Je moet zo snel mogelijk een prijs geven', 'is_correct' => false],
            ['text' => 'De klant weet altijd exact wat hij nodig heeft', 'is_correct' => false],
            ['text' => 'Je moet eerst de noden begrijpen voor je een offerte maakt', 'is_correct' => true],
            ['text' => 'Hoe minder vragen je stelt, hoe beter', 'is_correct' => false],
        ]);
    }
}
