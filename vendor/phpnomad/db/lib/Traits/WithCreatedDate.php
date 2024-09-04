<?php

namespace PHPNomad\Database\Traits;

use DateTime;

trait WithCreatedDate
{
    protected DateTime $createdDate;

    /**
     * Gets the item's created date.
     *
     * @return DateTime
     */
    public function getCreatedDate(): DateTime
    {
        return $this->createdDate;
    }
}