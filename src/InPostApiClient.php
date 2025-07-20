<?php

declare(strict_types=1);

namespace App;

use App\Resource\DispatchOrdersResource;
use App\Resource\OrganizationsResource;
use App\Resource\ShipmentsResource;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Class InPostApiClient
 * Client for interacting with the InPost API.
 * 
 * @see https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/overview?homepageId=622760
 * @package App
 */
final class InPostApiClient
{
    private GuzzleClient $guzzleClient;

    /**
     * Create InPost API client instance with configuration array.
     * 
     * Factory method that creates client instance from configuration array.
     * Simplifies client creation and provides clear configuration structure.
     * 
     * @param array{apiToken: string, isSandbox?: bool} $config Configuration array
     * @return self New instance of InPostApiClient
     */
    public static function fromConfig(array $config): self
    {
        return new self(
            apiToken: $config['apiToken'],
            isSandbox: $config['isSandbox'] ?? false
        );
    }

    /**
     * Constructor for InPost API Client.
     * 
     * Initializes the Guzzle HTTP client with the provided API token.
     * Automatically selects the appropriate API URL based on sandbox mode.
     * Sets up default headers for authorization and content type.
     * 
     * @param string $apiToken The Bearer token for InPost API authentication
     * @param bool $isSandbox Whether to use sandbox environment (default: false)
     */
    public function __construct(string $apiToken, bool $isSandbox = false)
    {
        $baseUrl = $isSandbox 
            ? 'https://sandbox-api-shipx-pl.easypack24.net/v1/' 
            : 'https://api-shipx-pl.easypack24.net/v1/';

        $this->guzzleClient = new GuzzleClient([
            'base_uri' => $baseUrl,
            'headers' => [
                'Authorization' => 'Bearer ' . $apiToken,
                'Content-Type' => 'application/json',
            ],
            'timeout' => 10.0,
        ]);
    }

    /**
     * Get the Organizations resource for managing organization-related operations.
     * 
     * Provides access to organization endpoints of the InPost API,
     * allowing operations such as retrieving organization details.
     * 
     * @return OrganizationsResource Instance of OrganizationsResource for API operations
     */
    public function organizations(): OrganizationsResource
    {
        return new OrganizationsResource($this->guzzleClient);
    }

    /**
     * Get the Shipments resource for managing shipment-related operations.
     * 
     * Provides access to shipment endpoints of the InPost API,
     * allowing operations such as creating, updating, and retrieving shipment details.
     * 
     * @return ShipmentsResource Instance of ShipmentsResource for API operations
     */
    public function shipments(): ShipmentsResource
    {
        return new ShipmentsResource($this->guzzleClient);
    }

    /**
     * Get the Dispatch Orders resource for managing dispatch order operations.
     * 
     * Provides access to dispatch order endpoints of the InPost API,
     * allowing operations such as creating and managing dispatch orders for shipments.
     * 
     * @return DispatchOrdersResource Instance of DispatchOrdersResource for API operations
     */
    public function dispatchOrders(): DispatchOrdersResource
    {
        return new DispatchOrdersResource($this->guzzleClient);
    }
}
