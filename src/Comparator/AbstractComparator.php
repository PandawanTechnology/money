<?php

declare(strict_types=1);

namespace PandawanTechnology\Money\Comparator;

use PandawanTechnology\Money\Model\Money;

abstract class AbstractComparator implements ComparatorInterface
{
    /**
     * {@inheritDoc}
     */
    public function isSameCurrency(Money $first, Money ...$collection): bool
    {
        $referenceCurrency = $first->getCurrency();

        while ($candidate = array_shift($collection)) {
            if ($candidate->getCurrency() !== $referenceCurrency) {
                return false;
            }
        }

        return true;
    }
}
