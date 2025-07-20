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

Przejść w kontekst folderu /inpost_test i wywołać komende:
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

## Uwagi dotyczące środowiska testowego

Zgodnie z poleceniem, skrypt jest skonfigurowany do tworzenia przesyłki typu `inpost_courier_standard`.

Podczas testów zauważono, że środowisko sandbox API InPost może mieć ograniczenia w obsłudze tego typu usługi, co uniemożliwia pełne przetestowanie ścieżki kurierskiej. W celu weryfikacji poprawnego działania logiki i komunikacji z API, lokalne testy przeprowadzono z użyciem usługi `inpost_locker_standard`, która jest w pełni wspierana przez sandbox.

Zamieszczony kod zawiera docelową, wymaganą w zadaniu implementację.

Kod obiektu shipment wykorzystanego do testów:
```php
$shipment = new Shipment(
    service: ServiceType::INPOST_LOCKER_STANDARD,
    receiver: new Receiver(
        firstName: 'Jan',
        lastName: 'Kowalski',
        email: 'jan.kowalski@example.com',
        phone: '123456789',
        address: new Address(
            street: 'Pomysłowa',
            buidlingNumber: '10',
            city: 'Warszawa',
            postCode: '00-001',
            countryCode: 'PL'
        )   
    ),
    sender: new Sender(
        companyName: 'InPost',
        email: 'inpost@example.com',
        firstName: 'Marek',
        lastName: 'InPostowy',
        phone: '123456789',
        address: new Address(
            street: 'InPostowa',
            buidlingNumber: '1',
            city: 'Kraków',
            postCode: '30-001',
            countryCode: 'PL'
        )
    ),
    parcels: [
        new Parcel(
            dimensions: new Dimensions(
                height: 10,
                length: 20,
                width: 30
            ),
            weight: new Weight(
                amount: 2.5
            ),
            id: 'example-parcel-id',
        )
    ],
    customAttributes: new CustomAttributes(
        targetPoint: 'KRA010'
    ),
    insurance: new Insurance(
        amount: 100
    ),
    reference: 'TestShipment123',
    comments: 'This is a test shipment',
);
```

## Licencja

Proprietary
