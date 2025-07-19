<?php

declare(strict_types=1);

namespace App\Resource;

use App\Data\DispatchOrder;

/**
 * Class DispatchOrdersResource
 * Resource for managing dispatch orders in the InPost API.
 * Extends AbstractResource to inherit common API request functionality.
 * 
 * @package App\Resource
 */
class DispatchOrdersResource extends AbstractResource
{
    public function create(int $organizationId, DispatchOrder $dispatchOrderData): array
    {
        $uri = "organizations/{$organizationId}/dispatch_orders";

        return $this -> postRequest($uri, $dispatchOrderData -> toArray());
    }
}