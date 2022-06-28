<?php

declare(strict_types=1);

namespace PandawanTechnology\Money\ConfigurationLoader;

interface CurrencyConfigurationsLoaderInterface
{
    /**
     * Load the currencies' configurations in the following structure:
     * [
     *      $CURRENCY_CODE => [
     *          'alphabeticCode' => (string),
     *          'currency' => (string),
     *          'minorUnit' => (int),
     *          'numericCode' => (int),
     *      ]
     * ]
     *
     * @see ../../currency.php
     */
    public function getCurrenciesConfigurations(): array;
}
