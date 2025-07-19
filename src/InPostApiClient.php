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
 * @package App
 */
final class InPostApiClient
{
    private GuzzleClient $guzzleClient;

    public function __construct(string $apiToken, string $baseUrl)
    {
        $this -> guzzleClient = new GuzzleClient([
            'base_uri' => $baseUrl,
            'headers' => [
                'Authorization' => 'Bearer ' . $apiToken,
                'Content-Type' => 'application/json',
            ],
            'timeout' => 10.0,
        ]);
    }

    public function organizations(): OrganizationsResource
    {
        return new OrganizationsResource($this -> guzzleClient);
    }

    public function shipments(): ShipmentsResource
    {
        return new ShipmentsResource($this -> guzzleClient);
    }

    public function dispatchOrders(): DispatchOrdersResource
    {
        return new DispatchOrdersResource($this -> guzzleClient);
    }
}
