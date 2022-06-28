<?php

declare(strict_types=1);

namespace PandawanTechnology\Money\Tests\Calculator;

use PandawanTechnology\Money\Calculator\BcMathCalculator;
use PandawanTechnology\Money\Comparator\BcMathComparator;
use PandawanTechnology\Money\Model\Money;
use PandawanTechnology\Money\Exception\CurrencyMismatchException;
use PandawanTechnology\Money\Exception\DivisionByZeroException;
use PandawanTechnology\Money\Factory\MoneyFactory;
use PandawanTechnology\Money\Manager\CurrencyManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BcMathCalculatorTest extends TestCase
{
    /**
     * @var BcMathCalculator
     */
    private $calculator;

    /**
     * @var BcMathComparator|MockObject
     */
    private $comparator;

    /**
     * @var CurrencyManager|MockObject
     */
    private $currencyManager;

    /**
     * @var MoneyFactory|MockObject
     */
    private $moneyFactory;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->comparator = $this->createMock(BcMathComparator::class);
        $this->currencyManager = $this->createMock(CurrencyManager::class);
        $this->moneyFactory = $this->createMock(MoneyFactory::class);

        $this->calculator = new BcMathCalculator(
            $this->comparator,
            $this->currencyManager,
            $this->moneyFactory
        );
    }

    public function testAddDifferentCurrencies(): void
    {
        $amount1 = new Money(14, 'EUR');
        $amount2 = new Money(14, 'CHF');

        $this->comparator->expects($this->once())
            ->method('isSameCurrency')
            ->with(
                $this->equalTo($amount1),
                $this->equalTo($amount2)
            )
            ->willReturn(false);

        $this->expectException(CurrencyMismatchException::class);
        $this->calculator->add($amount1, $amount2);
    }

    public function testAdd(): void
    {
        $amount1 = new Money(14, 'EUR');
        $amount2 = new Money(14, 'EUR');
        $amount3 = new Money(14, 'EUR');

        $expected = new Money(42, 'EUR');

        $this->comparator->expects($this->exactly(2))
            ->method('isSameCurrency')
            ->willReturn(true);

        $this->currencyManager->expects($this->once())
            ->method('getCurrencyPrecision')
            ->with($this->equalTo('EUR'))
            ->willReturn(2);

        $this->moneyFactory->expects($this->exactly(2))
            ->method('createMoney')
            ->willReturnMap([
                ['28.00', 'EUR', new Money('28.00', 'EUR')],
                ['42.00', 'EUR', $expected],
            ]);

        $this->assertSame($expected, $this->calculator->add($amount1, $amount2, $amount3));
    }

    public function testSubstractDifferentCurrencies(): void
    {
        $amount1 = new Money(14, 'EUR');
        $amount2 = new Money(14, 'CHF');

        $this->currencyManager->expects($this->once())
            ->method('getCurrencyPrecision')
            ->with($this->equalTo('EUR'))
            ->willReturn(2);

        $this->comparator->expects($this->once())
            ->method('isSameCurrency')
            ->with(
                $this->equalTo($amount1),
                $this->equalTo($amount2)
            )
            ->willReturn(false);

        $this->expectException(CurrencyMismatchException::class);
        $this->calculator->subtract($amount1, $amount2);
    }

    public function testSubstract(): void
    {
        $amount1 = new Money(14, 'EUR');
        $amount2 = new Money(14, 'EUR');
        $amount3 = new Money(14, 'EUR');

        $expected = new Money('-14', 'EUR');

        $this->currencyManager->expects($this->once())
            ->method('getCurrencyPrecision')
            ->with($this->equalTo('EUR'))
            ->willReturn(2);

        $this->comparator->expects($this->exactly(2))
            ->method('isSameCurrency')
            ->willReturn(true);

        $this->moneyFactory->expects($this->exactly(2))
            ->method('createMoney')
            ->willReturnMap([
                ['0.00', 'EUR', new Money(0, 'EUR')],
                ['-14.00', 'EUR', $expected],
            ]);

        $this->assertSame($expected, $this->calculator->subtract($amount1, $amount2, $amount3));
    }

    /**
     * @dataProvider dataProviderTestMultiply
     */
    public function testMultiply($multiplier): void
    {
        $amount = new Money(14, 'EUR');

        $this->currencyManager->expects($this->once())
            ->method('getCurrencyPrecision')
            ->with($this->equalTo('EUR'))
            ->willReturn(2);

        $expected = new Money('14.00', 'EUR');

        $this->moneyFactory->expects($this->once())
            ->method('createMoney')
            ->with(
                $this->equalTo('14.00'),
                $this->equalTo('EUR'),
            )
            ->willReturn($expected);

        $this->assertSame($expected, $this->calculator->multiply($amount, $multiplier));
    }

    public function testDivideByZero(): void
    {
        $amount = new Money(14, 'EUR');

        $this->comparator->expects($this->once())
            ->method('isZero')
            ->with($this->equalTo(0))
            ->willReturn(true);

        $this->expectException(DivisionByZeroException::class);
        $this->calculator->divide($amount, 0);
    }

    /**
     * @dataProvider dataProviderTestMultiply
     */
    public function testDivide($divisor): void
    {
        $amount = new Money(14, 'EUR');

        $this->comparator->expects($this->once())
            ->method('isZero')
            ->with($this->equalTo($divisor))
            ->willReturn(false);

        $this->currencyManager->expects($this->once())
            ->method('getCurrencyPrecision')
            ->with($this->equalTo('EUR'))
            ->willReturn(2);

        $expected = new Money('14.00', 'EUR');

        $this->moneyFactory->expects($this->once())
            ->method('createMoney')
            ->with(
                $this->equalTo('14.00'),
                $this->equalTo('EUR'),
            )
            ->willReturn($expected);

        $this->assertSame($expected, $this->calculator->divide($amount, $divisor));
    }

    public function dataProviderTestMultiply(): array
    {
        return [
            ['1'],
            ['1.0'],
            ['1.'],
            [1],
            [1.0],
            [1.],
        ];
    }
}
