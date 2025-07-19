<?php

namespace App\Resource;

use App\Exception\InPostApiException;

final readonly class OrganizationsResource extends AbstractResource
{
    public function list(): array
    {
        return $this -> executeRequest('GET', 'organizations');
    }

    public function getFirst(): int
    {
        $organizations = $this -> list();

        if(empty($organizations['items'])) {
            throw new InPostApiException('
                Nie znaleziono żadnej organizacji dla podanego tokenu API.
            ');
        }

        return $organizations['items'][0]['id'];
    }
}