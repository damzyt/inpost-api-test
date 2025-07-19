<?php

namespace App\Resource;

use App\Data\ShipmentData;

final readonly class ShipmentsResource extends AbstractResource
{
    public function create(int $organizationId, ShipmentData $shipmentData): array
    {
        $uri = "organizations/{$organizationId}/shipments";

        return $this -> executeRequest('POST', $uri, ['json' => $shipmentData -> toArray()]);
    }

    public function get(int $organizationId, string $shipmentId): array
    {
        $uri = "organizations/{$organizationId}/shipments/{$shipmentId}";

        return $this -> executeRequest('GET', $uri);
    }
}