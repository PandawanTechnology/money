<?php

declare(strict_types=1);

namespace PandawanTechnology\Money\Comparator;

use PandawanTechnology\Money\Model\Money;

class BcMathComparator extends AbstractComparator
{
    /**
     * @inheritDoc
     */
    public function isZero(Money $money): bool
    {
        return 0 === bccomp('0', $money->getAmount());
    }

    /**
     * @inheritDoc
     */
    public function isNegative(Money $money): bool
    {
        return -1 === bccomp($money->getAmount(), '0');
    }

    /**
     * @inheritDoc
     */
    public function isPositive(Money $money): bool
    {
        return !$this->isNegative($money);
    }

    /**
     * @inheritDoc
     */
    public function equals(Money $first, Money $challenge): bool
    {
        if (!$this->isSameCurrency($first, $challenge)) {
            return false;
        }

        return 0 === bccomp($first->getAmount(), $challenge->getAmount());
    }
    /**
     * Checks whether the value represented by this object is greater than the other.
     */
    public function greaterThan(Money $first, Money $challenge): bool
    {
        if (!$this->isSameCurrency($first, $challenge)) {
            return false;
        }

        return 1 === bccomp($first->getAmount(), $challenge->getAmount());
    }

    public function greaterThanOrEqual(Money $first, Money $challenge): bool
    {
        return $this->greaterThan($first, $challenge) || $this->equals($first, $challenge);
    }

    /**
     * Checks whether the value represented by this object is less than the other.
     */
    public function lessThan(Money $first, Money $challenge): bool
    {
        if (!$this->isSameCurrency($first, $challenge)) {
            return false;
        }

        return -1 === bccomp($first->getAmount(), $challenge->getAmount());
    }

    public function lessThanOrEqual(Money $first, Money $challenge): bool
    {
        return $this->lessThan($first, $challenge) || $this->equals($first, $challenge);
    }
}
