<?php

declare(strict_types=1);

namespace App\Resource;

use App\Data\Shipment;

/**
 * Class ShipmentsResource
 * Resource for managing shipments in the InPost API.
 * Extends AbstractResource to inherit common API request functionality.
 * 
 * @package App\Resource
 */
class ShipmentsResource extends AbstractResource
{
    public function create(int $organizationId, Shipment $shipmentData): array
    {
        $uri = "organizations/{$organizationId}/shipments";

        return $this -> postRequest($uri, $shipmentData -> toArray());
    }

    public function get(string $shipmentId): array
    {
        $uri = "shipments/{$shipmentId}";

        return $this -> getRequest($uri);
    }
}