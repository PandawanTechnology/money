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
        $referenceCurrencyCode = $first->getCurrency();
        $currencyPrecision = $this->currencyManager->getCurrencyPrecision($referenceCurrencyCode);

        while ($addend = array_shift($addends)) {
            if (!$this->comparator->isSameCurrency($first, $addend)) {
                throw new CurrencyMismatchException();
            }

            $result = $this->moneyFactory->createMoney(
                bcadd($result->getAmount(), $addend->getAmount(), $currencyPrecision),
                $referenceCurrencyCode
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
        $referenceCurrencyCode = $first->getCurrency();
        $currencyPrecision = $this->currencyManager->getCurrencyPrecision($referenceCurrencyCode);

        while ($addend = array_shift($addends)) {
            if (!$this->comparator->isSameCurrency($first, $addend)) {
                throw new CurrencyMismatchException();
            }

            $result = $this->moneyFactory->createMoney(
                bcsub($result->getAmount(), $addend->getAmount(), $currencyPrecision),
                $referenceCurrencyCode
            );
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function multiply(Money $money, int|string|float $multiplier): Money
    {
        $currencyCode = $money->getCurrency();
        $currencyPrecision = $this->currencyManager->getCurrencyPrecision($currencyCode);

        return $this->moneyFactory->createMoney(
            bcmul($money->getAmount(), (string) $multiplier, $currencyPrecision),
            $currencyCode
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

        $referenceCurrencyCode = $money->getCurrency();
        $currencyPrecision = $this->currencyManager->getCurrencyPrecision($referenceCurrencyCode);

        return $this->moneyFactory->createMoney(
            bcdiv($money->getAmount(), $divisor, $currencyPrecision),
            $referenceCurrencyCode
        );
    }

    /**
     * @inheritDoc
     */
    public function mod(Money $money, Money $divisor): Money
    {
        // TODO: Implement mod() method.
    }

    /**
     * @inheritDoc
     */
    public function allocate(Money $money, array $ratios): array
    {
        // TODO: Implement allocate() method.
    }

    /**
     * @inheritDoc
     */
    public function allocateTo(Money $money, int $n): array
    {
        // TODO: Implement allocateTo() method.
    }

    /**
     * @inheritDoc
     */
    public function ratioOf(Money $money): string
    {
        // TODO: Implement ratioOf() method.
    }

    /**
     * @inheritDoc
     */
    public function absolute(Money $money): Money
    {
        // TODO: Implement absolute() method.
    }

    /**
     * @inheritDoc
     */
    public function negative(Money $money): Money
    {
        // TODO: Implement negative() method.
    }

    /**
     * @inheritDoc
     */
    public function getMin(Money $first, Money ...$collection): Money
    {
        // TODO: Implement getMin() method.
    }

    /**
     * @inheritDoc
     */
    public function getMax(Money $first, Money ...$collection): Money
    {
        // TODO: Implement getMax() method.
    }

    /**
     * @inheritDoc
     */
    public function sum(Money $first, Money ...$collection): Money
    {
        // TODO: Implement sum() method.
    }

    /**
     * @inheritDoc
     */
    public function average(Money $first, Money ...$collection): Money
    {
        // TODO: Implement average() method.
    }
}