<?php

declare(strict_types=1);

namespace PandawanTechnology\Money\Exception;

class InvalidCurrencyCodeException extends \OutOfRangeException
{
    /**
     * @inheritDoc
     */
    public function __construct(
        string $submittedCurrencyCode,
        array $allowedCurrencyCodes,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = sprintf(
            'The currency code "%s" is not valid. Possible values are: "%s"',
            $submittedCurrencyCode,
            implode('", "', $allowedCurrencyCodes)
        );

        parent::__construct($message, $code, $previous);
    }
}