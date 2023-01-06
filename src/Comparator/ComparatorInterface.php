<?php

declare(strict_types=1);

namespace PandawanTechnology\Money\Comparator;

use PandawanTechnology\Money\Model\Money;

interface ComparatorInterface
{
    public function isZero(int|string|float $input): bool;

    public function isZeroAmount(Money $money): bool;

    public function isPositive(Money $money): bool;

    public function isNegative(Money $money): bool;

    public function isSameCurrency(Money $first, Money ...$collection): bool;

    public function equals(Money $first, Money $challenge): bool;

    /**
     * Checks whether the value represented by this object is greater than the other.
     */
    public function greaterThan(Money $first, Money $challenge): bool;

    public function greaterThanOrEqual(Money $first, Money $challenge): bool;

    /**
     * Checks whether the value represented by this object is less than the other.
     */
    public function lessThan(Money $first, Money $challenge): bool;

    public function lessThanOrEqual(Money $first, Money $challenge): bool;
}
