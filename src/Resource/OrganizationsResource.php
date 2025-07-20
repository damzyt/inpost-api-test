<?php

declare(strict_types=1);

namespace App\Resource;

use App\Exception\InPostApiException;

/**
 * Class OrganizationsResource
 * Resource for managing organizations in the InPost API.
 * Extends AbstractResource to inherit common API request functionality.
 * 
 * @see https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731054/Organizacja
 * @package App\Resource
 */
class OrganizationsResource extends AbstractResource
{
    /**
     * Retrieve a list of all organizations.
     * 
     * Sends a GET request to fetch all organizations available in the InPost API.
     * Returns the complete list of organizations with their details.
     * 
     * @return array The response data containing the list of organizations
     */
    public function list(): array
    {
        return $this->getRequest('organizations');
    }

    /**
     * Get the ID of the first organization from the list.
     * 
     * Retrieves the list of organizations and returns the ID of the first one.
     * This is useful when you need a default organization ID for operations.
     * 
     * @return int The ID of the first organization
     * @throws InPostApiException When no organizations are found
     */
    public function getFirst(): int
    {
        $organizations = $this->list();

        if (empty($organizations['items'])) {
            throw new InPostApiException('No organizations found.');
        }

        return $organizations['items'][0]['id'];
    }
}