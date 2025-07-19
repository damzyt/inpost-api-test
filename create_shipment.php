<?php

use App\Data\Address;
use App\Data\CustomAttributes;
use App\Data\Dimensions;
use App\Data\DispatchOrder;
use App\Data\Enums\ServiceType;
use App\Data\Insurance;
use App\Data\Parcel;
use App\Data\Recipient;
use App\Data\Sender;
use App\Data\Shipment;
use App\Data\Weight;
use App\InPostApiClient;
use App\Exception\InPostApiException;

require __DIR__ . '/vendor/autoload.php';

function logMessage(string $message, string $fileName = 'log.txt'): void
{
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($fileName, "[{$timestamp}] {$message}" . PHP_EOL, FILE_APPEND);
}

echo '[PROCESS] Loading environment variables...' . PHP_EOL;

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv -> load();
    $dotenv -> required(['INPOST_API_TOKEN', 'INPOST_API_URL']);
} catch (Exception $e) {
    echo "[ERROR] Configuration error: {$e->getMessage()}" . PHP_EOL;
    echo "[ERROR] Please ensure the .env file exists and contains INPOST_API_TOKEN and INPOST_API_URL." . PHP_EOL;
    exit(1);
}

echo "[SUCCESS] Configuration loaded successfully." . PHP_EOL;
logMessage("--- Starting new shipment creation process ---");

try {
    $api = new InPostApiClient($_ENV['INPOST_API_TOKEN'], $_ENV['INPOST_API_URL']);

    echo "[PROCESS] Fetching organization ID..." . PHP_EOL;
    $organizationId = $api -> organizations() -> getFirst();
    echo "[SUCCESS] Organization ID fetched: {$organizationId}" . PHP_EOL;

    $shipment = new Shipment(
        receiver: new Recipient(
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
        service: ServiceType::INPOST_LOCKER_STANDARD
    );

    echo "[PROCESS] Creating shipment in InPost system..." . PHP_EOL;
    $shipmentData = $api -> shipments() -> create($organizationId, $shipment);

    $maxAttempts = 10;
    $attempt = 0;
    $waitTime = 1;
    
    while($shipmentData['status'] !== 'confirmed' && $attempt < $maxAttempts) {
        $attempt++;
        echo "[PROCESS] Attempt {$attempt}/{$maxAttempts}: Shipment status: {$shipmentData['status']}." . PHP_EOL;
        echo "[PROCESS] Status needed: confirmed." . PHP_EOL;
        
        if($attempt < $maxAttempts) {
            echo "[PROCESS] Waiting for {$waitTime} seconds before next check..." . PHP_EOL;
            sleep($waitTime);
            
            $waitTime *= 2;
            
            $shipmentData = $api -> shipments() -> get($shipmentData['id']);
        }
    }
    
    if($shipmentData['status'] !== 'confirmed') {
        throw new Exception("Shipment confirmation failed after {$maxAttempts} attempts. Last status: {$shipmentData['status']}");
    }

    echo "[SUCCESS] Shipment created successfully! ID: {$shipmentData['id']}" . PHP_EOL;
    logMessage("[SUCCESS] Shipment created." . PHP_EOL . json_encode($shipmentData, JSON_PRETTY_PRINT));
    echo "[PROCESS] Ordering courier for shipment ID: {$shipmentData['id']}..." . PHP_EOL;

    $dispatchResponse = $api -> dispatchOrders() -> create($organizationId, new DispatchOrder(
        shipments: [$shipmentData['id']],
        address: new Address(
            street: 'InPostowa',
            buidlingNumber: '1',
            city: 'Kraków',
            postCode: '30-001',
            countryCode: 'PL'
        ),
        name: 'Test Dispatch',
        phone: '123456789'
    ));

    echo "[SUCCESS] Courier ordered successfully!" . PHP_EOL;
    logMessage("[SUCCES] Courier ordered." . PHP_EOL . json_encode($dispatchResponse, JSON_PRETTY_PRINT));
    echo "[SUCCESS] Process finished successfully. Check log.txt for details." . PHP_EOL;
} catch (InPostApiException $e) {
    echo "[ERROR] CRITICAL API ERROR" . PHP_EOL;
    echo "[ERROR] Message: " . $e -> getMessage() . PHP_EOL;
    echo "[ERROR] HTTP Code: " . $e -> getCode() . PHP_EOL;
    logMessage("[ERROR] API ERROR: " . $e -> getMessage());
    logMessage("[ERROR] Server Response: " . $e -> getResponseBodyAsString());

    exit(1);
} catch (Throwable $e) {
    echo "[ERROR] UNEXPECTED ERROR" . PHP_EOL;
    echo "[ERROR] Message: " . $e -> getMessage() . PHP_EOL;
    logMessage("[ERROR] UNEXPECTED ERROR: " . $e -> getMessage() . PHP_EOL . $e -> getTraceAsString());

    exit(1);
}