<?php

declare(strict_types=1);

namespace App\Resource;

use App\Data\Shipment;

/**
 * Class ShipmentsResource
 * Resource for managing shipments in the InPost API.
 * Extends AbstractResource to inherit common API request functionality.
 * 
 * @see https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731046/Przesy+ka#Struktura
 * @package App\Resource
 */
class ShipmentsResource extends AbstractResource
{
    /**
     * Create a new shipment for the specified organization.
     * 
     * Sends a POST request to create a new shipment within the given organization.
     * The shipment data is converted to an array format before sending.
     * 
     * @param int $organizationId The ID of the organization to create the shipment for
     * @param Shipment $shipmentData The shipment data object containing all shipment details
     * @return array The response data from the API containing the created shipment information
     */
    public function create(int $organizationId, Shipment $shipmentData): array
    {
        $uri = "organizations/{$organizationId}/shipments";

        return $this->postRequest($uri, $shipmentData->toArray());
    }

    /**
     * Retrieve a specific shipment by its ID.
     * 
     * Sends a GET request to fetch detailed information about a shipment
     * using the provided shipment identifier.
     * 
     * @param int $shipmentId The unique identifier of the shipment to retrieve
     * @return array The response data from the API containing the shipment details
     */
    public function get(int $shipmentId): array
    {
        $uri = "shipments/{$shipmentId}";

        return $this->getRequest($uri);
    }
}