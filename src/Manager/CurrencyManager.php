<?php

declare(strict_types=1);

namespace PandawanTechnology\Money\Manager;

use PandawanTechnology\Money\ConfigurationLoader\CurrencyConfigurationsLoaderInterface;
use PandawanTechnology\Money\Exception\InvalidCurrencyCodeException;

class CurrencyManager
{
    /**
     * @var string[]
     */
    private $currenciesConfigurations;

    public function __construct(
        private CurrencyConfigurationsLoaderInterface $currencyConfigurationsLoader
    ) {
    }

    /**
     * @return string[] The allowed currency codes
     */
    public function getAllowedCurrencyCodes(): array
    {
        $this->initCurrenciesConfigurations();

        return \array_keys($this->currenciesConfigurations);
    }

    public function isValidCurrencyCode(string $currencyCode): bool
    {
        $this->initCurrenciesConfigurations();

        return isset($this->currenciesConfigurations[$currencyCode]);
    }

    public function getCurrencyPrecision(string $currencyCode): int
    {
        $this->initCurrenciesConfigurations();

        if (!$this->isValidCurrencyCode($currencyCode)) {
            throw new InvalidCurrencyCodeException($currencyCode, $this->getAllowedCurrencyCodes());
        }

        return (int) $this->currenciesConfigurations[$currencyCode]['minorUnit'];
    }

    private function initCurrenciesConfigurations(): void
    {
        if (!\is_null($this->currenciesConfigurations)) {
            return;
        }

        $this->currenciesConfigurations = $this->currencyConfigurationsLoader->getCurrenciesConfigurations();
    }
}
