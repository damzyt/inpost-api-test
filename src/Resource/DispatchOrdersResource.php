<?php

declare(strict_types=1);

namespace App\Resource;

use App\Data\DispatchOrder;

/**
 * Class DispatchOrdersResource
 * Resource for managing dispatch orders in the InPost API.
 * Extends AbstractResource to inherit common API request functionality.
 * 
 * @see https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731045/Zlecenie+odbioru+Zamawianie+kuriera
 * @package App\Resource
 */
class DispatchOrdersResource extends AbstractResource
{
    /**
     * Create a new dispatch order for the specified organization.
     * 
     * Sends a POST request to create a new dispatch order within the given organization.
     * The dispatch order data is converted to an array format before sending.
     * 
     * @param int $organizationId The ID of the organization to create the dispatch order for
     * @param DispatchOrder $dispatchOrderData The dispatch order data object containing all order details
     * @return array The response data from the API containing the created dispatch order information
     */
    public function create(int $organizationId, DispatchOrder $dispatchOrderData): array
    {
        $uri = "organizations/{$organizationId}/dispatch_orders";

        return $this->postRequest($uri, $dispatchOrderData->toArray());
    }
}