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

function logMessage(string $message): void
{
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents('log.txt', "[{$timestamp}] {$message}" . PHP_EOL, FILE_APPEND);
}

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv -> load();
    $dotenv -> required(['INPOST_API_TOKEN', 'INPOST_API_URL']);

    logMessage('Environment variables loaded successfully.');
} catch (Exception $e) {
    logMessage('Error loading environment variables: ' . $e -> getMessage());
    exit(1);
}

logMessage('Starting shipment creation process...');

try {
    $organizationId = 5477;
    $api = new InPostApiClient($_ENV['INPOST_API_TOKEN'], $_ENV['INPOST_API_URL']);

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

    logMessage('Shipment object created successfully.');
    $shipmentData = $api -> shipments() -> create($organizationId, $shipment);

    while($shipmentData['status'] !== 'confirmed') {
        logMessage('Shipment status: ' . $shipmentData['status'] . '. Retrying...');
        sleep(1);
        
        $shipmentData = $api -> shipments() -> get($shipmentData['id']);
    }

    logMessage('Shipment created successfully: ' . json_encode($shipmentData, JSON_PRETTY_PRINT));
    logMessage('Dispatching shipment with ID: ' . $shipmentData['id']);
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

    logMessage('Dispatch order created successfully: ' . json_encode($dispatchResponse, JSON_PRETTY_PRINT));
} catch(InPostApiException $e) {
    logMessage('InPost API Exception: ' . $e -> getMessage());
    logMessage('Response: ' . $e -> getResponseBodyAsString());
    exit(1);
} catch(Throwable $e) {
    logMessage('Script execution completed.' . "\n" . $e -> getTraceAsString());
    exit(1);
}