<?php

declare(strict_types=1);

namespace PandawanTechnology\Money\Comparator;

use PandawanTechnology\Money\Model\Money;

interface ComparatorInterface
{
    public function isZeroAmount(Money $money): bool;

    public function isNegativeAmount(Money $money): bool;

    public function isPositiveAmount(Money $money): bool;

    public function isZero($number): bool;

    public function isPositive($number): bool;

    public function isNegative($number): bool;

    public function isSameCurrency(Money $first, Money ...$collection): bool;

    public function equals(Money $first, Money $challenge): bool;
}