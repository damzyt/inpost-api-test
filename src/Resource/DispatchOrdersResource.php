<?php

namespace App\Resource;

final readonly class DispatchOrdersResource extends AbstractResource
{
    public function create(int $organizationId, array $payload): array
    {
        $uri = "organizations/{$organizationId}/dispatch-orders";

        return $this -> executeRequest('POST', $uri, ['json' => $payload]);
    }
}