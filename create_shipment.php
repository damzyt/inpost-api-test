<?php

declare(strict_types=1);

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
use App\Utils\Logger;

require __DIR__ . '/vendor/autoload.php';

// ===== CONFIGURATION =====
$config = [
    'apiToken'       => 'YOUR_API_TOKEN_HERE',     // Your InPost API token
    'isSandbox'      => true,                      // Use sandbox environment (false for production)
    'organizationId' => null,                      // Organization ID (null = use first available)
];
// =========================

$logger = new Logger();

$logger->separator('Starting new shipment creation process');

try {
    $api = InPostApiClient::fromConfig($config);

    // Get organization ID if not specified
    if ($config['organizationId'] === null) {
        $logger->process('No organization ID specified, fetching first available...');
        $organizationId = $api->organizations()->getFirst();
        $logger->success("Using organization ID: {$organizationId}");
    } else {
        $organizationId = $config['organizationId'];
        $logger->info("Using specified organization ID: {$organizationId}");
    }

    // Create shipment with example data
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

    $logger->process('Creating shipment in InPost system...');
    $shipmentData = $api->shipments()->create($organizationId, $shipment);

    $maxAttempts = 10;
    $attempt = 0;
    $waitTime = 1;
    
    while ($shipmentData['status'] !== 'confirmed' && $attempt < $maxAttempts) {
        $attempt++;
        $logger->process("Attempt {$attempt}/{$maxAttempts}: Shipment status: {$shipmentData['status']}");
        $logger->process('Status needed: confirmed');
        
        if ($attempt < $maxAttempts) {
            $logger->process("Waiting for {$waitTime} seconds before next check...");
            sleep($waitTime);
            
            $waitTime *= 2;
            
            $shipmentData = $api->shipments()->get($shipmentData['id']);
        }
    }
    
    if ($shipmentData['status'] !== 'confirmed') {
        throw new Exception("Shipment confirmation failed after {$maxAttempts} attempts. Last status: {$shipmentData['status']}");
    }

    $logger->success("Shipment created successfully! ID: {$shipmentData['id']}", $shipmentData);
    $logger->process("Ordering courier for shipment ID: {$shipmentData['id']}...");

    $dispatchResponse = $api->dispatchOrders()->create($organizationId, new DispatchOrder(
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

    $logger->success('Courier ordered successfully!', $dispatchResponse);
    $logger->success('Process finished successfully. Check log.txt for details.');
} catch (InPostApiException $e) {
    $logger->error('CRITICAL API ERROR');
    $logger->error("HTTP Code: {$e->getCode()}");
    $logger->error("API ERROR: {$e->getMessage()}");
    $logger->error("Server Response: {$e->getResponseBodyAsString()}");

    exit(1);
} catch (Throwable $e) {
    $logger->error('UNEXPECTED ERROR');
    $logger->error("Message: {$e->getMessage()}");
    $logger->error("UNEXPECTED ERROR: {$e->getMessage()}" . PHP_EOL . $e->getTraceAsString());

    exit(1);
}