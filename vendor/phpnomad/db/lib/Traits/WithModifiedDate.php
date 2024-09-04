<?php

namespace PHPNomad\Database\Traits;

use DateTime;

trait WithModifiedDate
{
    protected DateTime $modifiedDate;

    /**
     * Gets the item's modified date.
     *
     * @return DateTime
     */
    public function getModifiedDate(): DateTime
    {
        return $this->modifiedDate;
    }
}