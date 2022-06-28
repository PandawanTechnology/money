<?php

declare(strict_types=1);

namespace PandawanTechnology\Money\Formatter;

use PandawanTechnology\Money\Model\Money;
use PandawanTechnology\MoneyBundle\Formatter\CurrencyFormatter;

class Formatter implements FormatterInterface
{
    /**
     * @var \NumberFormatter[]
     */
    private static $decimalFormatters = [];

    /**
     * @var \NumberFormatter[]
     */
    private static $currencyFormatters = [];

    public function __construct(private string $defaultLocale, private CurrencyFormatter $currencyFormatter)
    {
    }

    /**
     * @inheritDoc
     */
    public function formatAmount(Money $money, string $locale = null): string
    {
        return $this->getDecimalFormatterForLocale($locale)
            ->format((float) $money->getAmount());
    }

    /**
     * @inheritDoc
     */
    public function asFloat(Money $money, string $locale = null): float
    {
        return (float) $money->getAmount();
    }

    /**
     * @inheritDoc
     */
    public function formatPrice(Money $money, string $locale = null): string
    {
        return $this->getCurrencyFormatterForLocale($locale)
            ->formatCurrency((float) $money->getAmount(), $money->getCurrency());
    }

    /**
     * @inheritDoc
     */
    public function formatCurrencySymbol(string $currencyCode, ?string $locale = null): string
    {
        return $this->currencyFormatter->getSymbol($currencyCode, $locale);
    }

    private function getDecimalFormatterForLocale(?string $locale = null): \NumberFormatter
    {
        $computedLocale = $locale ?: $this->defaultLocale;

        if (!isset(static::$decimalFormatters[$computedLocale])) {
            static::$decimalFormatters[$computedLocale] = $this->buildFormatterForLocale($computedLocale, \NumberFormatter::DECIMAL);
        }

        return static::$decimalFormatters[$computedLocale];
    }

    private function getCurrencyFormatterForLocale(?string $locale = null): \NumberFormatter
    {
        $computedLocale = $locale ?: $this->defaultLocale;

        if (!isset(static::$currencyFormatters[$computedLocale])) {
            static::$currencyFormatters[$computedLocale] = $this->buildFormatterForLocale($computedLocale, \NumberFormatter::CURRENCY);
        }

        return static::$currencyFormatters[$computedLocale];
    }

    private function buildFormatterForLocale(string $locale, int $style): \NumberFormatter
    {
        return new \NumberFormatter($locale, $style);
    }
}