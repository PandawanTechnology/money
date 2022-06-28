<?php

declare(strict_types=1);

namespace PandawanTechnology\Money\Formatter;

use PandawanTechnology\Money\Model\Money;

interface FormatterInterface
{
    /**
     * Display the formatted numeric part of the Money object
     */
    public function formatAmount(Money $money, string $locale = null): string;

    /**
     * Display the formatted numeric part of the Money object
     */
    public function asFloat(Money $money, string $locale = null): float;

    /**
     * Display the real amount the Money object is representing
     */
    public function formatPrice(Money $money, string $locale = null): string;

    /**
     * Converts a currency into its symbol if existing. Will return the same value otherwise.
     *
     * @param string|null $locale Since this method is relaying, by default, on `symfony/intl` component,
     *                            when not providing a value for this parameter, it will fall back, to the
     *                            parameter `intl.default_locale` set in `php.ini` file.
     */
    public function formatCurrencySymbol(string $currencyCode, ?string $locale = null): string;
}