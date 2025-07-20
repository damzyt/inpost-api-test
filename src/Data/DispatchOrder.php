<?php

declare(strict_types=1);

namespace App\Data;

/**
 * Class DispatchOrder
 * Represents a dispatch order as defined in the InPost API.
 * 
 * @see https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731045/Zlecenie+odbioru+Zamawianie+kuriera#Struktura
 * @package App\Data
 */
final class DispatchOrder
{
    /**
     * DispatchOrder constructor.
     * 
     * @param array $shipments Array of shipment IDs to be included in the dispatch order
     * @param Address $address The pickup address for the dispatch order
     * @param string $name Contact person name for the pickup
     * @param string $phone Contact phone number for the pickup
     * @param string|null $comment Optional comment for the dispatch order
     * @param string|null $officeHours Optional office hours information
     * @param string|null $email Optional contact email address
     */
    public function __construct(
        public readonly array $shipments,
        public readonly Address $address,
        public readonly string $name,
        public readonly string $phone,
        public readonly ?string $comment = null,
        public readonly ?string $officeHours = null,
        public readonly ?string $email = null
    ) {}

    /**
     * Converts the DispatchOrder object to an associative array.
     * 
     * Transforms dispatch order properties into an array format suitable for API requests.
     * Filters out null values and handles nested address object conversion.
     * 
     * @return array The dispatch order data as an associative array with API-compatible structure
     */
    public function toArray(): array
    {
        return array_filter([
            'shipments'    => $this->shipments,
            'address'      => $this->address->toArray(),
            'name'         => $this->name,
            'phone'        => $this->phone,
            'comment'      => $this->comment,
            'office_hours' => $this->officeHours,
            'email'        => $this->email
        ]);
    }
}