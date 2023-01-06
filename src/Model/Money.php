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
        $this->amount = $amount;

        return $this;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}
