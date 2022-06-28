<?php

declare(strict_types=1);

namespace PandawanTechnology\Money\Comparator;

use PandawanTechnology\Money\Model\Money;

class BcMathComparator extends AbstractComparator
{
    /**
     * @inheritDoc
     */
    public function isZeroAmount(Money $money): bool
    {
        return $this->isZero($money->getAmount());
    }

    /**
     * @inheritDoc
     */
    public function isNegativeAmount(Money $money): bool
    {
        return $this->isNegative($money->getAmount());
    }

    /**
     * @inheritDoc
     */
    public function isPositiveAmount(Money $money): bool
    {
        return !$this->isNegativeAmount($money);
    }

    /**
     * @inheritDoc
     */
    public function isZero($number): bool
    {
        return 0 === bccomp('0', (string) $number);
    }

    /**
     * @inheritDoc
     */
    public function isNegative($number): bool
    {
        return -1 === bccomp((string) $number, '0');
    }

    /**
     * @inheritDoc
     */
    public function isPositive($number): bool
    {
        return !$this->isNegative($number);
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
}