<?php

declare(strict_types=1);

namespace PandawanTechnology\Money\Model;

class Money
{
    private string $amount;

    public function __construct($amount, protected string $currency = '')
    {
        $this->amount = (string) $amount;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setAmount(string $amount): self
    {
        return new self($amount, $this->currency);
    }

    public function setCurrency(string $currency): self
    {
        return new self($this->amount, $currency);
    }
}
