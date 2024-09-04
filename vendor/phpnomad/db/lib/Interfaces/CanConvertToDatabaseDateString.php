<?php

namespace PHPNomad\Database\Interfaces;

use DateTime;

interface CanConvertToDatabaseDateString
{
    /**
     * Converts the specified DateTime object into a string.
     *
     * @param DateTime $dateTime
     * @return string
     */
    public function toDatabaseDateString(DateTime $dateTime): string;
}