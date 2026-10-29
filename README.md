# Quiz

Aplikacja mobilna z quizem: pobiera pytania z API, wysyła odpowiedzi i wyświetla wynik obliczony przez backend.

## Struktura projektu

- `api/` — backend Laravel 13, baza SQLite, pytania i ocena odpowiedzi.
- `app/` — aplikacja React Native z Expo SDK 58 i Expo Router.

Poniższa instrukcja dotyczy lokalnego środowiska deweloperskiego. Komendy wykonuj z podanych katalogów; przykłady używają powłoki bash/zsh (macOS lub Linux).

## Wymagania

- PHP **8.4.1+** — wymagane przez obecny zestaw zależności deweloperskich w `composer.lock`.
- Composer **2** oraz rozszerzenia PHP wymagane przez zależności. Do lokalnej bazy potrzebne są PDO SQLite (`pdo_sqlite`) i `sqlite3`. `composer check-platform-reqs` sprawdzi wymagania zainstalowanych pakietów.
- Node.js **24.3.0 lub nowszy z linii 24.x**, wraz z npm.
- Telefon z Expo Go zgodnym z SDK 58 albo skonfigurowany emulator Androida / symulator iOS. Symulator iOS wymaga macOS i Xcode, emulator Androida — Android Studio z urządzeniem wirtualnym.

Wersje narzędzi sprawdzisz przez `php -v`, `composer --version`, `node -v` i `npm -v`.

## Pierwsza konfiguracja backendu

Z katalogu głównego projektu przejdź do backendu:

```bash
cd api
```

Na świeżej kopii projektu utwórz konfigurację środowiska:

```bash
cp .env.example .env
composer install
composer check-platform-reqs
php artisan key:generate
```

Jeśli masz już `.env`, zachowaj go zamiast nadpisywać. Klucz generuj podczas pierwszej konfiguracji, a nie przy każdym uruchomieniu.

Domyślny `.env.example` zawiera `DB_CONNECTION=sqlite`. Pozostaw tę wartość; `DB_DATABASE` nie musi być ustawione — aplikacja użyje pliku `api/database/database.sqlite`.

Utwórz bazę bez nadpisywania istniejącej, uruchom migracje i wypełnij ją pytaniami:

```bash
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate
php artisan db:seed
```

Seeder uruchom **jednorazowo na nowej bazie**. Kolejne `php artisan db:seed` dodaje ponownie pytania i odpowiedzi, tworząc duplikaty. Przy kolejnych uruchomieniach projektu nie powtarzaj seedowania.

Uruchom API i pozostaw terminal otwarty:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Adres `0.0.0.0` pozwala na dostęp z telefonu w sieci lokalnej. W aplikacji ustaw właściwy adres komputera, według tabeli poniżej.

Do obsługi mobilnego quizu nie trzeba instalować zależności npm w `api/` ani uruchamiać Vite. Dotyczą one warstwy WWW backendu.

## Pierwsza konfiguracja aplikacji mobilnej

W drugim terminalu, z katalogu głównego projektu:

```bash
cd app
npm ci
```

Utwórz ręcznie plik `app/.env`. Wybierz adres odpowiedni dla urządzenia, na którym uruchomisz aplikację:

| Urządzenie | Wartość `EXPO_PUBLIC_API_URL` |
| --- | --- |
| Telefon w tej samej sieci Wi-Fi | `http://<IP_KOMPUTERA>:8000/api` |
| Standardowy emulator Androida z Android Studio | `http://10.0.2.2:8000/api` |
| Symulator iOS na tym samym Macu | `http://localhost:8000/api` |

Przykładowa zawartość dla telefonu (zastąp przykładowy adres rzeczywistym adresem IPv4 komputera):

```dotenv
EXPO_PUBLIC_API_URL=http://192.168.1.100:8000/api
```

Adres IP znajdziesz w ustawieniach sieci komputera. Telefon i komputer muszą być w tej samej sieci, a zapora musi pozwalać na dostęp do portu 8000. Na telefonie `localhost` oznacza telefon, więc nie prowadzi do backendu na komputerze. Końcówka `/api` jest wymagana.

Pliki `.env` są ignorowane przez Git. Zmienne `EXPO_PUBLIC_*` trafiają do aplikacji klienckiej — nie umieszczaj w nich haseł ani sekretów. `APP_URL` w backendzie nie zastępuje `EXPO_PUBLIC_API_URL` w aplikacji.

Uruchom Expo:

```bash
npm start
```

Otwórz projekt w zgodnym Expo Go przez kod QR albo użyj `a` dla uruchomionego emulatora Androida lub `i` dla symulatora iOS. Jeśli Expo Go na iPhonie wymaga logowania, zaloguj się w nim oraz w CLI (`npx expo login`) na to samo konto. Szczegóły opisuje [instrukcja uruchamiania Expo](https://docs.expo.dev/get-started/start-developing/).

Jeśli Expo Go zgłasza niezgodny SDK, użyj wersji zgodnej z SDK 58; informacje o środowiskach uruchomieniowych są w [dokumentacji konfiguracji Expo](https://docs.expo.dev/get-started/set-up-your-environment/).

## Codzienne uruchamianie

Po pierwszej konfiguracji potrzebujesz dwóch działających procesów. Poniższe komendy zakładają, że każdy terminal zaczyna w katalogu głównym projektu.

Terminal 1 — API:

```bash
cd api
php artisan serve --host=0.0.0.0 --port=8000
```

Terminal 2 — aplikacja mobilna:

```bash
cd app
npm start
```

Oba procesy zatrzymasz przez `Ctrl+C`. Po pobraniu zmian zależności wykonaj odpowiednio `composer install` w `api/` i `npm ci` w `app/`. Nowe migracje zastosuj przez `php artisan migrate` w `api/`.

## Sprawdzenie działania

Na komputerze z uruchomionym backendem:

```bash
curl -i http://localhost:8000/up
curl -H 'Accept: application/json' 'http://localhost:8000/api/questions?limit=10'
```

Pierwsze żądanie powinno zwrócić HTTP 200. Drugie powinno zwrócić JSON z tablicą `data` zawierającą 10 pytań po poprawnym seedowaniu świeżej bazy.

Na telefonie otwórz również `http://<IP_KOMPUTERA>:8000/api/questions?limit=10` w przeglądarce, podstawiając adres komputera. Pozwala to sprawdzić dostępność API z urządzenia.

W aplikacji rozpocznij quiz, wybierz odpowiedzi, przejdź do końca i wyślij je. Powinien pojawić się ekran wyników. Aplikacja korzysta z `GET /api/questions` i `POST /api/quiz/submit`; te endpointy nie wymagają konta użytkownika.

## Rozwiązywanie problemów

| Objaw | Co sprawdzić |
| --- | --- |
| `EXPO_PUBLIC_API_URL is not configured` | Utwórz `app/.env` z dokładną nazwą zmiennej. Zatrzymaj Expo i uruchom `npm start -- --clear` w `app/`, a następnie ponownie otwórz aplikację. |
| `Network Error` lub timeout | Sprawdź adres według tabeli, działający backend, wspólną sieć, zaporę i port 8000. VPN lub izolacja urządzeń w sieci gościnnej mogą blokować połączenie. |
| Expo działa przez tunel, ale API jest niedostępne | Tunel Expo nie udostępnia automatycznie Laravel API. Telefon nadal musi mieć dostęp do adresu wskazanego w `EXPO_PUBLIC_API_URL`. |
| HTTP 404 z API | Sprawdź końcówkę `/api` i port w `EXPO_PUBLIC_API_URL`. |
| API zwraca `data: []` | Sprawdź, czy backend korzysta z właściwej bazy i czy wykonano seedowanie. Jeśli baza jest nowa i pusta, uruchom `php artisan db:seed` w `api/`. |
| Błąd bazy lub brak tabel | Sprawdź rozszerzenia SQLite, istnienie pliku bazy i uprawnienia zapisu. Uruchom `php artisan migrate` w `api/`. |
| Backend nie uwzględnia zmian `.env` | W `api/` wykonaj `php artisan config:clear` i ponownie uruchom serwer. |
| Brak klucza aplikacji | Przy pierwszej konfiguracji wykonaj `php artisan key:generate` w `api/`. |
| HTTP 500 | Sprawdź terminal backendu i `api/storage/logs/laravel.log`. |
| Port 8000 jest zajęty | Zatrzymaj zajmujący go proces lub wybierz inny `--port` i zaktualizuj adres w `app/.env`. |
