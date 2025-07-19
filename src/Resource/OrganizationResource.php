<?php

declare(strict_types=1);

namespace App\Resource;

use App\Exception\InPostApiException;

/**
 * Class OrganizationsResource
 * Resource for managing organizations in the InPost API.
 * Extends AbstractResource to inherit common API request functionality.
 * 
 * @package App\Resource
 */
final readonly class OrganizationsResource extends AbstractResource
{
    public function list(): array
    {
        return $this -> getRequest('organizations');
    }

    public function getFirst(): int
    {
        $organizations = $this -> list();

        if(empty($organizations['items'])) {
            throw new InPostApiException('No organizations found.');
        }

        return $organizations['items'][0]['id'];
    }
}