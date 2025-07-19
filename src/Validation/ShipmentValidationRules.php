<?php

namespace App\Validation;

use App\Data\Enums\ServiceType;
use App\Data\Enums\SendingMethod;
use App\Data\Enums\ParcelTemplate;
use App\Data\Enums\AdditionalService;

final class ShipmentValidationRules
{
    public const SERVICES_REQUIRING_TARGET_POINT = [
        ServiceType::INPOST_LOCKER_STANDARD,
        ServiceType::INPOST_LOCKER_ALLEGRO,
        ServiceType::INPOST_LOCKER_PASS_THRU,
        ServiceType::INPOST_LETTER_ALLEGRO
    ];

    public const SERVICES_REQUIRING_SENDING_METHOD = [
        ServiceType::INPOST_LOCKER_ALLEGRO,
        ServiceType::INPOST_LETTER_ALLEGRO,
        ServiceType::INPOST_COURIER_ALLEGRO
    ];

    public const SERVICES_REQUIRING_DROPOFF_POINT = [
        ServiceType::INPOST_LOCKER_PASS_THRU,
        ServiceType::INPOST_LETTER_ALLEGRO
    ];

    public const SENDING_METHODS_REQUIRING_DROPOFF_POINT = [
        SendingMethod::PARCEL_LOCKER,
        SendingMethod::POK,
        SendingMethod::COURIER_POK
    ];

    public const SERVICES_REQUIRING_RECEIVER_ADDRESS = [
        ServiceType::INPOST_COURIER_STANDARD,
        ServiceType::INPOST_COURIER_EXPRESS_1000,
        ServiceType::INPOST_COURIER_EXPRESS_1200,
        ServiceType::INPOST_COURIER_EXPRESS_1700,
        ServiceType::INPOST_COURIER_PALETTE,
        ServiceType::INPOST_LETTER_ALLEGRO,
        ServiceType::INPOST_COURIER_ALLEGRO,
        ServiceType::INPOST_COURIER_C2C
    ];

    // public const SERVICES_REQUIRING_SENDER_ADDRESS = [
    //     ServiceType::INPOST_COURIER_STANDARD,
    //     ServiceType::INPOST_COURIER_EXPRESS_1000,
    //     ServiceType::INPOST_COURIER_EXPRESS_1200,
    //     ServiceType::INPOST_COURIER_EXPRESS_1700,
    //     ServiceType::INPOST_COURIER_PALETTE
    // ];

    public const PARCEL_STANDARD_TEMPLATES = [
        ParcelTemplate::SMALL,
        ParcelTemplate::MEDIUM,
        ParcelTemplate::LARGE
    ];

    public const PARCEL_LETTER_TEMPLATES = [
        ParcelTemplate::LETTER_A,
        ParcelTemplate::LETTER_B,
        ParcelTemplate::LETTER_C
    ];

    public const SERVICES_FOR_STANDARD_PARCEL_TEMPLATES = [
        ServiceType::INPOST_LOCKER_STANDARD,
        ServiceType::INPOST_LOCKER_ALLEGRO,
        ServiceType::INPOST_LOCKER_PASS_THRU,
        ServiceType::INPOST_COURIER_C2C
    ];

    public const INPOST_PALETTE_CONTRAINTS = [
        'dimensions' => [
            'max_length' => 1200,
            'max_width'  => 800,
            'max_height' => 800
        ],
        'weight' => [
            'max_weight' => 800,
            'min_weight' => 50
        ]
    ];

    public const INPOST_PARCEL_CONTRAINTS = [
        'dimensions' => [
            'max_length' => 350,
            'max_width'  => 240,
            'max_height' => 240
        ],
        'weight' => [
            'max_weight' => 50,
            'min_weight' => 0
        ]
    ];

    public const SERVICES_REQUIRING_INSURANCE = [
        ServiceType::INPOST_COURIER_STANDARD,
        ServiceType::INPOST_COURIER_EXPRESS_1000,
        ServiceType::INPOST_COURIER_EXPRESS_1200,
        ServiceType::INPOST_COURIER_EXPRESS_1700
    ];

    public const LOCKER_ALLOWED_ADDITIONAL_SERVICES = [
        AdditionalService::COD,
        AdditionalService::INSURANCE
    ];

    public const COURIER_ALLOWED_ADDITIONAL_SERVICES = [
        ServiceType::INPOST_COURIER_STANDARD => [
            AdditionalService::INSURANCE,
            AdditionalService::COD,
            AdditionalService::SMS,
            AdditionalService::EMAIL,
            AdditionalService::SATURDAY,
            AdditionalService::EVENING_DELIVERY,
            AdditionalService::RETURN_DOCUMENTS
        ],
        ServiceType::INPOST_COURIER_EXPRESS_1000 => [
            AdditionalService::INSURANCE,
            AdditionalService::COD,
            AdditionalService::SMS,
            AdditionalService::EMAIL,
            AdditionalService::SATURDAY,
            AdditionalService::RETURN_DOCUMENTS,
            AdditionalService::FOR_HOUR_9,
            AdditionalService::FOR_HOUR_10,
        ],
        ServiceType::INPOST_COURIER_EXPRESS_1200 => [
            AdditionalService::INSURANCE,
            AdditionalService::COD,
            AdditionalService::SMS,
            AdditionalService::EMAIL,
            AdditionalService::SATURDAY,
            AdditionalService::RETURN_DOCUMENTS,
            AdditionalService::FOR_HOUR_11,
            AdditionalService::FOR_HOUR_12,
        ],
        ServiceType::INPOST_COURIER_EXPRESS_1700 => [
            AdditionalService::INSURANCE,
            AdditionalService::COD,
            AdditionalService::SMS,
            AdditionalService::EMAIL,
            AdditionalService::SATURDAY,
            AdditionalService::RETURN_DOCUMENTS,
            AdditionalService::FOR_HOUR_13,
            AdditionalService::FOR_HOUR_14,
            AdditionalService::FOR_HOUR_15,
            AdditionalService::FOR_HOUR_16,
            AdditionalService::FOR_HOUR_17
        ],
        ServiceType::INPOST_COURIER_PALETTE => [
            AdditionalService::INSURANCE,
            AdditionalService::COD,
            AdditionalService::SMS,
            AdditionalService::EMAIL,
            AdditionalService::RETURN_DOCUMENTS
        ]
    ];
}