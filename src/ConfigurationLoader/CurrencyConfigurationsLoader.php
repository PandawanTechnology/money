<?php

declare(strict_types=1);

namespace PandawanTechnology\Money\ConfigurationLoader;

class CurrencyConfigurationsLoader implements CurrencyConfigurationsLoaderInterface
{
    public function __construct(
        private string $currenciesConfigurationsFilePath = __DIR__.'/../../currency.php'
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getCurrenciesConfigurations(): array
    {
        if (!\is_readable($this->currenciesConfigurationsFilePath)) {
            throw new \RuntimeException(
                sprintf(
                    'Currencies configurations\' file path "%s" is not readable',
                    $this->currenciesConfigurationsFilePath
                )
            );
        }

        return require $this->currenciesConfigurationsFilePath;
    }
}