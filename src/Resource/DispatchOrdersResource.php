<?php

declare(strict_types=1);

namespace App\Resource;

/**
 * Class DispatchOrdersResource
 * Resource for managing dispatch orders in the InPost API.
 * Extends AbstractResource to inherit common API request functionality.
 * 
 * @package App\Resource
 */
final readonly class DispatchOrdersResource extends AbstractResource
{
    public function create(int $organizationId, array $payload): array
    {
        $uri = "organizations/{$organizationId}/dispatch-orders";

        return $this -> postRequest($uri, $payload);
    }
}