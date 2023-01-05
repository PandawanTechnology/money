<?php

declare(strict_types=1);

namespace PandawanTechnology\Money\Calculator;

use PandawanTechnology\Money\Comparator\BcMathComparator;
use PandawanTechnology\Money\Model\Money;
use PandawanTechnology\Money\Exception\CurrencyMismatchException;
use PandawanTechnology\Money\Exception\DivisionByZeroException;
use PandawanTechnology\Money\Factory\MoneyFactory;
use PandawanTechnology\Money\Manager\CurrencyManager;

class BcMathCalculator implements CalculatorInterface
{
    public function __construct(
        private BcMathComparator $comparator,
        private CurrencyManager $currencyManager,
        private MoneyFactory $moneyFactory,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function add(Money $first, Money ...$addends): Money
    {
        $result = clone $first;

        while ($addend = array_shift($addends)) {
            if (!$this->comparator->isSameCurrency($first, $addend)) {
                throw new CurrencyMismatchException();
            }

            $result = $this->moneyFactory->createMoney(
                bcadd($result->getAmount(), $addend->getAmount(), CalculatorInterface::PRECISION),
                $first->getCurrency()
            );
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function subtract(Money $first, Money ...$addends): Money
    {
        $result = clone $first;

        while ($addend = array_shift($addends)) {
            if (!$this->comparator->isSameCurrency($first, $addend)) {
                throw new CurrencyMismatchException();
            }

            $result = $this->moneyFactory->createMoney(
                bcsub($result->getAmount(), $addend->getAmount(), CalculatorInterface::PRECISION),
                $first->getCurrency()
            );
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function multiply(Money $money, int|string|float $multiplier): Money
    {
        return $this->moneyFactory->createMoney(
            bcmul($money->getAmount(), (string) $multiplier, CalculatorInterface::PRECISION),
            $money->getCurrency()
        );
    }

    /**
     * @inheritDoc
     */
    public function divide(Money $money, int|string|float $divisor): Money
    {
        $divisor = (string) $divisor;

        if ($this->comparator->isZero($divisor)) {
            throw new DivisionByZeroException();
        }

        return $this->moneyFactory->createMoney(
            bcdiv($money->getAmount(), $divisor, CalculatorInterface::PRECISION),
            $money->getCurrency()
        );
    }

    /**
     * @inheritDoc
     */
    public function allocate(Money $money, array $ratios): array
    {
        $remainder = $money->getAmount();
        $currency = $money->getCurrency();

        $results   = [];
        $total     = array_sum($ratios);

        if ($total <= 0) {
            throw new \InvalidArgumentException('Cannot allocate to none, sum of ratios must be greater than zero');
        }

        foreach ($ratios as $key => $ratio) {
            if ($ratio < 0) {
                throw new \InvalidArgumentException('Cannot allocate to none, ratio must be zero or positive');
            }

            $share = $this->share($money, (string) $ratio, (string) $total);
            $results[$key] = $share;
            $remainder     = $this->subtract($remainder, $share);
        }

        if ($this->comparator->isZero($remainder)) {
            return $results;
        }

        $amount = $money->getAmount();
        $fractions = array_map(static function (float|int $ratio) use ($total, $amount) {
            $share = (float) $ratio / $total * (float) $amount;

            return $share - floor($share);
        }, $ratios);

        while (!$this->comparator->isZero($remainder)) {
            $index = $fractions !== [] ? array_keys($fractions, max($fractions))[0] : 0;
            $results[$index] = $this->moneyFactory->createMoney(
                $this->add($results[$index], $this->moneyFactory->createMoney('1', $currency))->getAmount(),
                $results[$index]->getCurrency()
            );
            $remainder = $this->subtract(
                $this->moneyFactory->createMoney($remainder, $currency),
                $this->moneyFactory->createMoney('1', $currency)
            );
            unset($fractions[$index]);
        }

        return $results;
    }

    /**
     * @inheritDoc
     */
    public function allocateTo(Money $money, int $n): array
    {
        return $this->allocate($money, array_fill(0, $n, 1));
    }

    public function share(Money $money, string $ratio, string $total): Money
    {
        return $this->floor(
            $this->moneyFactory->createMoney(
                bcdiv(bcmul($money->getAmount(), $ratio, CalculatorInterface::PRECISION), $total, CalculatorInterface::PRECISION),
                $money->getCurrency()
            )
        );
    }

    public function floor(Money $money): Money
    {
        $currency = $money->getCurrency();

        if ($this->comparator->isNegative($money)) {
            return $this->add($money, $this->moneyFactory->createMoney('-1', $currency));
        }

        return $this->add($money, $this->moneyFactory->createMoney(0, $currency));
    }

    /**
     * @inheritDoc
     */
    public function absolute(Money $money): Money
    {
        $newAmount = ltrim($money->getAmount(), '-');

        return $this->moneyFactory->createMoney($newAmount, $money->getCurrency());
    }

    /**
     * @inheritDoc
     */
    public function getMin(Money $first, Money ...$collection): Money
    {
        $min = $first;

        foreach ($collection as $money) {
            if (!$this->comparator->lessThan($money, $min)) {
                continue;
            }

            $min = $money;
        }

        return $min;
    }

    /**
     * @inheritDoc
     */
    public function getMax(Money $first, Money ...$collection): Money
    {
        $max = $first;

        foreach ($collection as $money) {
            if (!$this->comparator->greaterThan($money, $max)) {
                continue;
            }

            $max = $money;
        }

        return $max;
    }

    /**
     * @inheritDoc
     */
    public function sum(Money $first, Money ...$collection): Money
    {
        return $this->add($first, ...$collection);
    }

    /**
     * @inheritDoc
     */
    public function average(Money $first, Money ...$collection): Money
    {
        return $this->divide(
            $this->sum($first, ...$collection),
            count($collection) + 1
        );
    }

    /**
     * @inheritDoc
     */
    public function negative(Money $money): Money
    {
        return $this->subtract(
            $this->moneyFactory->createMoney(0, $money->getCurrency()),
            $money
        );
    }

    /**
     * @inheritDoc
     */
    public function mod(Money $money, Money $divisor): Money
    {
        if ($this->comparator->isZero($divisor)) {
            throw new \InvalidArgumentException('Modulo cannot be zero');
        }

        return $this->moneyFactory->createMoney(
            bcmod($money->getAmount(), $divisor->getAmount(), CalculatorInterface::PRECISION) ?? '0',
            $money->getCurrency()
        );
    }

    /**
     * @inheritDoc
     */
    public function ratioOf(Money $money): string
    {
    }
}
