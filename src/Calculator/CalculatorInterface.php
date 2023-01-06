<?php

declare(strict_types=1);

namespace PandawanTechnology\Money\Calculator;

use PandawanTechnology\Money\Exception\CurrencyMismatchException;
use PandawanTechnology\Money\Exception\DivisionByZeroException;
use PandawanTechnology\Money\Model\Money;

interface CalculatorInterface
{
    public const PRECISION = 5;

    /**
     * @throws CurrencyMismatchException
     */
    public function add(Money $first, Money ...$addends): Money;

    /**
     * @throws CurrencyMismatchException
     */
    public function subtract(Money $first, Money ...$addends): Money;

    /**
     * TODO handle rounding.
     */
    public function multiply(Money $money, int|string|float $multiplier): Money;

    /**
     * TODO handle rounding.
     *
     * @throws DivisionByZeroException
     */
    public function divide(Money $money, int|string|float $divisor): Money;

    /**
     * @throws CurrencyMismatchException
     */
    public function mod(Money $money, Money $divisor): Money;

    public function share(Money $money, string $ratio, string $total): Money;

    public function floor(Money $money): Money;

    /**
     * @param int[] $ratios
     *
     * @return Money[]
     *
     * @throws CurrencyMismatchException
     */
    public function allocate(Money $money, array $ratios): array;

    /**
     * @return Money[]
     *
     * @throws CurrencyMismatchException
     */
    public function allocateTo(Money $money, int $n): array;

    public function ratioOf(Money $money): string;

    public function absolute(Money $money): Money;

    public function negative(Money $money): Money;

    /**
     * @throws CurrencyMismatchException
     */
    public function getMin(Money $first, Money ...$collection): Money;

    /**
     * @throws CurrencyMismatchException
     */
    public function getMax(Money $first, Money ...$collection): Money;

    /**
     * @throws CurrencyMismatchException
     */
    public function sum(Money $first, Money ...$collection): Money;

    /**
     * @throws CurrencyMismatchException
     */
    public function average(Money $first, Money ...$collection): Money;
}
