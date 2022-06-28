<?php

declare(strict_types=1);

namespace PandawanTechnology\Money\Factory;

use PandawanTechnology\Money\Model\Money;
use PandawanTechnology\Money\Exception\InvalidCurrencyCodeException;
use PandawanTechnology\Money\Manager\CurrencyManager;

class MoneyFactory
{
    public function __construct(
        private CurrencyManager $currencyManager,
        private string $defaultCurrencyCode = ''
    ) {
    }

    public function createMoney(string|int|float $amount, ?string $currencyCode = null): Money
    {
        if (\is_null($currencyCode)) {
            $currencyCode = $this->defaultCurrencyCode;
        }

        if (!$this->currencyManager->isValidCurrencyCode($currencyCode)) {
            throw new InvalidCurrencyCodeException($currencyCode, $this->currencyManager->getAllowedCurrencyCodes());
        }

        return new Money($amount, $currencyCode);
    }
}