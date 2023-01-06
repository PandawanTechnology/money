<?php

declare(strict_types=1);

namespace PandawanTechnology\Money\Exception;

class DivisionByZeroException extends \DomainException
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        string $message = 'Division by 0',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
