<?php

declare(strict_types=1);

use App\Data\Address;
use App\Data\Cod;
use App\Data\Dimensions;
use App\Data\DispatchOrder;
use App\Data\Enums\AdditionalService;
use App\Data\Enums\ServiceType;
use App\Data\CustomAttributes;
use App\Data\Insurance;
use App\Data\Parcel;
use App\Data\Receiver;
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
    // Based on https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731061/Tworzenie+przesy+ki+w+trybie+uproszczonym#Pojedyncza-paczka-dla-przesy%C5%82ki-kurierskiej%3A
    $shipment = new Shipment(
        service: ServiceType::INPOST_COURIER_STANDARD,
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
            firstName: 'Marek',
            lastName: 'InPostowy',
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
                    height: 50,
                    length: 80,
                    width: 60,
                    unit: 'mm'
                ),
                weight: new Weight(
                    amount: 5,
                    unit: 'kg'
                ),
                id: 'example-parcel-id',
                isNotStandard: false
            )
        ],
        insurance: new Insurance(
            amount: 100,
            currency: 'PLN'
        ),
        cod: new Cod(
            amount: 10,
            currency: 'PLN'
        ),
        additionalServices: [
            AdditionalService::SMS,
            AdditionalService::EMAIL
        ],
        reference: 'TestShipment123',
        comments: 'This is a test shipment',
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
            $logger->logJsonData('CURRENT SHIPMENT DATA', $shipmentData);
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
    $logger->error("Message: {$e->getMessage()}");
    $logger->logJsonData('API ERROR DETAILS', [
        'http_code'     => $e->getCode(),
        'error_message' => $e->getMessage(),
        'api_response'  => $e->getResponseBodyAsString(),
    ]);

    exit(1);
} catch (Throwable $e) {
    $logger->error('UNEXPECTED ERROR');
    $logger->error("Message: {$e->getMessage()}");
    $logger->logJsonData('UNEXPECTED ERROR DETAILS', [
        'error_type' => get_class($e),
        'message'    => $e->getMessage(),
        'file'       => $e->getFile(),
        'line'       => $e->getLine(),
        'trace'      => $e->getTraceAsString()
    ]);

    exit(1);
}