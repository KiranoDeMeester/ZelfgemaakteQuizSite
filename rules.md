## Doel van dit document

Dit project is een Laravel 13 + Livewire 4 quiz applicatie met room-based multiplayer.

Geen webshop. Geen Stripe. Geen KICKZ.

Focus:
- Quiz rooms (zoals Kahoot)
- Join via 5-digit code of QR
- Score tracking (zoals Socrative)
- Export naar CSV (Excel)

---

## Stack

- Laravel 13
- Livewire 4
- Alpine.js (lichte interactie)
- MySQL
- Pest

---

## Hoofddoel

Bouw een quiz platform met:

- Host kan quiz starten
- Room code (5 cijfers)
- QR join systeem
- Spelers joinen live
- Vragen realtime tonen
- Scores bijhouden
- Export naar CSV

---

## Domeinen

Gebruik:

- Quiz
- Room
- Player
- Answer
- Result

---

## Database structuur

### quizzes
- id
- title

### questions
- id
- quiz_id
- question
- image (nullable)

### answers
- id
- question_id
- text
- is_correct

### rooms
- id
- code (5 digit unique)
- quiz_id
- status (waiting, active, finished)

### players
- id
- room_id
- name
- score

### player_answers
- id
- player_id
- question_id
- answer_id

---

## Belangrijke features

### Room systeem
- 5-digit code genereren
- unieke constraint
- QR code genereren via service

### Join flow
- via code input
- via QR link (?room=12345)

### Game flow
- host start quiz
- vraag per vraag
- realtime updates (polling of events)

---

## Livewire regels

Gebruik Livewire voor:

- lobby
- quiz play
- score screen

Geen zware logica in componenten.

---

## Services

- RoomService (code generatie)
- QrService (QR maken)
- QuizRunnerService (flow)

---

## Export

CSV export:

name, score, total

---

## Testing

- room creation
- join flow
- scoring
- export

---

## Anti-patterns

- geen business logic in views
- geen random JS hacks voor state
- geen polling zonder controle

---

## Definitie van klaar

- Room join werkt (code + QR)
- Quiz flow werkt
- Scores correct
- CSV export werkt

---

## Hoofdregel

Dit is een realtime quiz app, geen webshop.
