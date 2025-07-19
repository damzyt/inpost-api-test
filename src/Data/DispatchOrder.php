<?php

declare(strict_types=1);

namespace App\Data;

final class DispatchOrder
{
    public function __construct(
        public readonly array $shipments,
        public readonly Address $address,
        public readonly string $name,
        public readonly string $phone,
        public readonly ?string $comment = null,
        public readonly ?string $officeHours = null,
        public readonly ?string $email = null
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'shipments'    => $this -> shipments,
            'address'      => $this -> address -> toArray(),
            'name'         => $this -> name,
            'phone'        => $this -> phone,
            'comment'      => $this -> comment,
            'office_hours' => $this -> officeHours,
            'email'        => $this -> email
        ]);
    }
}