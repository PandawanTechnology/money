<?php

declare(strict_types=1);

namespace PandawanTechnology\Money\Exception;

class CurrencyMismatchException extends \InvalidArgumentException
{
    /**
     * @inheritDoc
     */
    public function __construct(
        string $message = "Currency does not match",
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}