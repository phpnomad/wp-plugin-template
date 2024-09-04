<?php

namespace PHPNomad\Utils\Helpers;

class Num
{
    public static function toPercentageFloat(int $percent): float
    {
        return $percent / 100;
    }

    public static function calculatePercentage(int $amount, int $percent): float
    {
        return $amount * static::toPercentageFloat($percent);
    }

    /**
     * Divides the specified number, rounds it up or down, and returns the integer.
     *
     * @param numeric $amount
     * @param numeric $dividedBy
     * @param int $mode
     * @return int
     */
    public static function getDividedInt($amount, $dividedBy, int $mode = PHP_ROUND_HALF_UP): int
    {
        $product = $amount / $dividedBy;

        return static::floatToInt($product, $mode);
    }

    public static function floatToInt(float $input, int $mode = PHP_ROUND_HALF_UP): int
    {
        return (int) round($input, 0, $mode);
    }
}