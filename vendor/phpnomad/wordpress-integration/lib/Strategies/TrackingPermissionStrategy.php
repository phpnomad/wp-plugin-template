<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPNomad\Privacy\Interfaces\TrackingPermissionStrategy as TrackingServiceInterface;

class TrackingPermissionStrategy implements TrackingServiceInterface
{
    public function canTrack(): bool
    {
        //TODO: THIS SHOULD PROBABLY BE CONFIGURE-ABLE SO THAT THE STRATEGY CAN BE OVERRIDDEN SOMEHOW.
        // A POTENTIAL USE-CASE IS IF THE CUSTOMER ALREADY HAS A COOKIE NOTICE ON THEIR SITE, THIS COULD USE THE RESULT
        // OF THAT NOTICE INSTEAD OF THE DNT HEADER.
        return is_user_logged_in();
    }
}