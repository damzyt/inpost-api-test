# InPost API Test

Skrypt do tworzenia przesyłek oraz zamawiania kuriera InPost przez API.

## Wymagania

- PHP >= 8.1
- Composer
- Token dostępu do InPost API

## Instalacja

1. Pobierz lub sklonuj projekt
2. Zainstaluj zależności:
```bash
composer install
```

## Konfiguracja

Przed uruchomieniem skryptu wyedytuj plik `create_shipment.php` i ustaw swoją konfigurację:

```php
// ===== CONFIGURATION =====
$config = [
    'apiToken'       => 'YOUR_API_TOKEN_HERE',     // Your InPost API token
    'isSandbox'      => true,                      // Use sandbox environment (false for production)
    'organizationId' => null,                      // Organization ID (null = use first available)
];
// =========================
```

### Parametry konfiguracji:

- **apiToken** - Twój token dostępu do InPost API (wymagany)
- **isSandbox** - `true` dla środowiska testowego, `false` dla produkcji
- **organizationId** - ID organizacji lub `null` aby użyć pierwszej dostępnej

## Uruchomienie

```bash
php create_shipment.php
```

### 🔧 Jak użytkować:

1. **Ustaw swój token API** w tablicy `$config['apiToken']`
2. **Wybierz środowisko** - `$config['isSandbox']` (`true` = test, `false` = produkcja)
3. **Opcjonalnie ustaw organizationId** lub zostaw `null` dla automatycznego wyboru
4. **Uruchom skrypt** - `php create_shipment.php`

## Funkcjonalność

Skrypt wykonuje następujące operacje:

1. **Inicjalizacja API** - tworzy klienta InPost API
2. **Pobranie organizacji** - automatycznie pobiera pierwszą dostępną organizację (jeśli nie podano ID)
3. **Utworzenie przesyłki** - tworzy nową przesyłkę z przykładowymi danymi
4. **Oczekiwanie na potwierdzenie** - czeka aż przesyłka zostanie potwierdzona
5. **Zamówienie kuriera** - tworzy zlecenie odbioru dla kuriera

## Logowanie

Wszystkie operacje są logowane do pliku `log.txt` z timestampami.

## Obsługa błędów

Skrypt zawiera kompletną obsługę błędów:
- Błędy API InPost
- Błędy sieciowe z automatycznym retry
- Błędy konfiguracji
- Nieoczekiwane błędy

## Licencja

Proprietary
