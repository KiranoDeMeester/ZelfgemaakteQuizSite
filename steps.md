# Laravel Quiz App - Stappenplan

Hier is het volledige stappenplan voor het bouwen van de Laravel 13 + Livewire 4 Quiz App. Je kunt de vinkjes bijhouden naarmate we vorderen!

## Fase 1: Setup & Configuratie
- [x] **Stap 1.1:** Installeer een nieuw Laravel 13 project in deze map (zonder de bestaande bestanden te overschrijven, we verplaatsen de inhoud of maken het project aan via `composer create-project`).
- [x] **Stap 1.2:** Installeer Livewire 4 (`composer require livewire/livewire`).
- [x] **Stap 1.3:** Configureer de database instellingen in `.env` (MySQL via WAMP).
- [x] **Stap 1.4:** Setup Pest voor testing (`php artisan pest:install`).

## Fase 2: Database & Modellen (Domeinen)
- [x] **Stap 2.1:** Migraties & Modellen voor `Quiz`, `Question`, `Answer`.
- [x] **Stap 2.2:** Migraties & Modellen voor `Room`, `Player`, `PlayerAnswer`.
- [x] **Stap 2.3:** Relaties instellen (HasMany, BelongsTo) in alle modellen.
- [x] **Stap 2.4:** Database seeders maken (bijv. een test-quiz aanmaken zodat we kunnen testen).

## Fase 3: Core Logica (Services)
- [x] **Stap 3.1:** `RoomService` schrijven (genereren van unieke 5-digit code).
- [x] **Stap 3.2:** `QrService` implementeren (genereren van QR code URL/SVG gebaseerd op de room code).
- [x] **Stap 3.3:** `QuizRunnerService` opzetten (ophalen van huidige vraag, valideren van antwoorden, score berekenen).

## Fase 4: Livewire Components (UI & Flow)
- [x] **Stap 4.1:** `CreateRoom` component (Host start een room en krijgt de QR/Code).
- [x] **Stap 4.2:** `JoinRoom` component (Speler vult 5-digit code in + naam).
- [x] **Stap 4.3:** `Lobby` component (Host ziet spelers binnenstromen via polling of events).
- [x] **Stap 4.4:** `QuizPlay` component (Livewire component voor het tonen van vragen, Alpine.js voor lichte transities).
- [x] **Stap 4.5:** `ScoreScreen` component (Resultaten tonen aan het einde).

## Fase 5: Export & Afronding
- [x] **Stap 5.1:** CSV Export functionaliteit toevoegen (Route & Controller/Livewire action om name, score en total te downloaden).
- [x] **Stap 5.2:** Layout strak trekken met Tailwind CSS (Dark mode / Kahoot style).
- [x] **Stap 5.3:** Pest integratietests schrijven voor room creation, join flow, en scoring.

## Fase 6: Extra Features
- [x] **Stap 6.1:** "Sluit Room" knop toevoegen voor de host (in Lobby & Quiz).
- [x] **Stap 6.2:** Spelers automatisch redirecten naar join-scherm als de room gesloten wordt.

---
**Project Status:** ✅ Voltooid (Laravel 13 + Livewire 4)

---
*Opmerking: Zodra je het implementatieplan goedkeurt, begin ik direct met Fase 1!*
