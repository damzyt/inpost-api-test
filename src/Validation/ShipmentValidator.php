<?php

namespace App\Validation;

use App\Data\ShipmentData;
use App\Data\Parcel;

use App\Data\Enums\ParcelTemplate;
use App\Data\Enums\ServiceType;
use App\Data\Enums\ServiceCategory;

use App\Validation\ShipmentValidationRules as ValidationRules;

use InvalidArgumentException;

class ShipmentValidator
{
    public function validate(ShipmentData $shipment): void
    {   
        $this -> validateInternalObjects($shipment);
        $this -> validateShipmentContext($shipment);
    }

    private function validateInternalObjects(ShipmentData $shipment): void
    {
        $properties = get_object_vars($shipment);

        foreach ($properties as $property) {
            if(is_object($property) && $property instanceof ValidatableInterface) {
                $property -> validate();
            } elseif(is_array($property)) {
                foreach($property as $item) {
                    if(is_object($item) && $item instanceof ValidatableInterface) {
                        $item -> validate();
                    }
                }
            }
        }
    }

    private function validateShipmentContext(ShipmentData $shipment): void
    {
        $this -> validateRecipientContext($shipment);
        $this -> validateParcelsContext($shipment);
        $this -> validateCustomAttributesContext($shipment);
        $this -> validateInsuranceContext($shipment);
        $this -> validateReferenceContext($shipment);
        $this -> validateIsReturnContext($shipment);
        $this -> validateAdditionalServicesContext($shipment);
    }

    private function validateRecipientContext(ShipmentData $shipment): void
    {   
        if(empty($shipment -> recipient)) {
            if(empty($shipment -> isReturn) && !$shipment -> isReturn) {
                throw new InvalidArgumentException('
                    recipient is required for non-return shipments.'
                );
            }
        }

        if(in_array($shipment -> service, ValidationRules::SERVICES_REQUIRING_RECEIVER_ADDRESS) && empty($shipment -> recipient -> address)) {
            throw new InvalidArgumentException('
                reciver address is required for service: ' . $shipment -> service -> value . '.'
            );
        }
    }

    private function validateParcelsContext(ShipmentData $shipment): void
    {
        if(empty($shipment -> parcels)) {
            throw new InvalidArgumentException('
                parcels is required.'
            );
        }

        if(count($shipment -> parcels) > 99) {
            throw new InvalidArgumentException('
                Maximum 99 parcels are allowed.'
            );
        }

        $parcelIds = array_map(fn(Parcel $parcel) => $parcel -> id, $shipment -> parcels);
        if(count($parcelIds) !== count(array_unique($parcelIds))) {
            throw new InvalidArgumentException('
                Each parcel must have a unique ID.
            ');
        }

        foreach($shipment -> parcels as $parcel) {
            if(!$parcel instanceof Parcel) {
                throw new InvalidArgumentException('
                    Each parcel must be an instance of Parcel class.'
                );
            }

            if(!empty($parcel -> template)) {
                $this -> validateParcelTemplate($parcel, $shipment);
            }

            if(!empty($parcel -> dimensions) && !empty($parcel -> weight)) {
                $this -> validateParcelAgainstService($parcel, $shipment);
            }
        }
    }
    
    private function validateParcelTemplate(Parcel $parcel, ShipmentData $shipment): void
    {
        if($parcel -> template === ParcelTemplate::XLARGE && $shipment -> service !== ServiceType::INPOST_COURIER_C2C) {
            throw new InvalidArgumentException('
                ' . ParcelTemplate::XLARGE . ' template can only be used with ' . ServiceType::INPOST_COURIER_C2C . ' service.'
            );
        }

        if(in_array($shipment -> service, ValidationRules::SERVICES_FOR_STANDARD_PARCEL_TEMPLATES) && !in_array($parcel -> template, ValidationRules::PARCEL_STANDARD_TEMPLATES)) {
            throw new InvalidArgumentException('
                ' . $shipment -> service -> value . ' service requires standard parcel templates: ' . implode(', ', array_map(fn($template) => $template -> value, ValidationRules::PARCEL_STANDARD_TEMPLATES)) . '.'
            );
        }

        if($shipment -> service === ServiceType::INPOST_LETTER_ALLEGRO && !in_array($parcel -> template, ValidationRules::PARCEL_LETTER_TEMPLATES)) {
            throw new InvalidArgumentException('
                ' . $shipment -> service -> value . ' service requires letter parcel templates: ' . implode(', ', array_map(fn($template) => $template -> value, ValidationRules::PARCEL_LETTER_TEMPLATES)) . '.'
            );
        }
    }

    private function validateParcelAgainstService(Parcel $parcel, ShipmentData $shipment): void
    {
        if($shipment -> service === ServiceType::INPOST_COURIER_PALETTE) {
            $constraints = ValidationRules::INPOST_PALETTE_CONTRAINTS;
        } else {
            $constraints = ValidationRules::INPOST_PARCEL_CONTRAINTS;
        }

        if(!empty($parcel -> dimensions)) {
            $givenDimensions = [$parcel -> dimensions -> length, $parcel -> dimensions -> width, $parcel -> dimensions -> height];
            $maxDimensions = [$constraints['dimensions']['max_length'], $constraints['dimensions']['max_width'], $constraints['dimensions']['max_height']];

            if($givenDimensions[0] > $maxDimensions[0] || $givenDimensions[1] > $maxDimensions[1] || $givenDimensions[2] > $maxDimensions[2]) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Parcel "%s" (%i x %i x %i) dimensions exceed maximum allowed: %i x %i x %i cm for service: %s.',
                        $parcel -> id,
                        $givenDimensions[0],
                        $givenDimensions[1],
                        $givenDimensions[2],
                        $maxDimensions[0],
                        $maxDimensions[1],
                        $maxDimensions[2],
                        $shipment -> service -> value
                    )
                );
            }
        }

        if(!empty($parcel -> weight)) {
            if($parcel -> weight -> amount < $constraints['weight']['min_weight'] || $parcel -> weight -> amount > $constraints['weight']['max_weight']) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Parcel "%s" weight (%i kg) must be between %i and %i g for service: %s.',
                        $parcel -> id,
                        $parcel -> weight -> amount,
                        $constraints['weight']['min_weight'],
                        $constraints['weight']['max_weight'],
                        $shipment -> service -> value
                    )
                );
            }
        }
    }

    private function validateCustomAttributesContext(ShipmentData $shipment): void
    {
        if(empty($shipment -> customAttributes)) {
            return;
        }

        if(in_array($shipment -> service, ValidationRules::SERVICES_REQUIRING_TARGET_POINT) && empty($shipment -> customAttributes -> targetPoint)) {
            throw new InvalidArgumentException('
                target_point is required for service: ' . $shipment -> service -> value . '.'
            );
        }

        if(in_array($shipment -> service, ValidationRules::SERVICES_REQUIRING_SENDING_METHOD) && empty($shipment -> customAttributes -> sendingMethod)) {
            throw new InvalidArgumentException('
                sending_method is required for service: ' . $shipment -> service -> value . '.'
            );
        }

        $isDropoffRequiredByService = in_array($shipment -> service, ValidationRules::SERVICES_REQUIRING_DROPOFF_POINT);
        $isDropoffRequiredBySendingMethod = !empty($shipment -> customAttributes -> sendingMethod) && in_array($shipment -> customAttributes -> sendingMethod, ValidationRules::SENDING_METHODS_REQUIRING_DROPOFF_POINT);

        if(($isDropoffRequiredByService || $isDropoffRequiredBySendingMethod) && empty($shipment -> customAttributes -> dropoffPoint)) {
            throw new InvalidArgumentException('
                dropoff_point is required for service: ' . $shipment -> service -> value . ' or sending_method: ' . ($shipment -> customAttributes -> sendingMethod ?-> value ?? 'null') . '.'
            );
        }
    }

    private function validateInsuranceContext(ShipmentData $shipment): void
    {
        if(in_array($shipment -> service, ValidationRules::SERVICES_REQUIRING_INSURANCE) && empty($shipment -> insurance)) {
            throw new InvalidArgumentException('
                insurance is required for service: ' . $shipment -> service -> value . '.'
            );
        }

        if(!empty($shipment -> cod) && $shipment -> service === ServiceType::INPOST_COURIER_PALETTE && empty($shipment -> insurance)) {
            throw new InvalidArgumentException('
                insurance is required for service: ' . $shipment -> service -> value . ' when cod is provided.'
            );
        }
    }

    private function validateReferenceContext(ShipmentData $shipment): void
    {
        if(!empty($shipment -> reference)) {
            if(mb_strlen($shipment -> reference) < 3) {
                throw new InvalidArgumentException('
                    reference must be at least 3 characters long.
                ');
            }

            if(mb_strlen($shipment -> reference) > 100) {
                throw new InvalidArgumentException('
                    reference must be less or equal 100 characters.
                ');
            }
        }
    }

    private function validateIsReturnContext(ShipmentData $shipment): void
    {
        if(!empty($shipment -> isReturn) && !is_bool($shipment -> isReturn)) {
            throw new InvalidArgumentException('
                isReturn must be a boolean value.
            ');
        }
    }

    private function validateAdditionalServicesContext(ShipmentData $shipment): void
    {
        if(empty($shipment -> additionalServices)) {
            return;
        }
        
        if($shipment -> service -> getCategory() === ServiceCategory::Locker && !empty(array_diff($shipment -> additionalServices, ValidationRules::LOCKER_ALLOWED_ADDITIONAL_SERVICES))) {
            throw new InvalidArgumentException('
                Additional services for locker service must be one of: ' . implode(', ', array_map(fn($service) => $service -> value, ValidationRules::LOCKER_ALLOWED_ADDITIONAL_SERVICES)) . '.'
            );
        }

        if($shipment -> service -> getCategory() === ServiceCategory::Courier && !empty(array_diff($shipment -> additionalServices, ValidationRules::COURIER_ALLOWED_ADDITIONAL_SERVICES[$shipment -> service]))) {
            throw new InvalidArgumentException('
                Additional services for courier service must be one of: ' . implode(', ', array_map(fn($service) => $service -> value, ValidationRules::COURIER_ALLOWED_ADDITIONAL_SERVICES[$shipment -> service])) . '.'
            );
        }
    }
}