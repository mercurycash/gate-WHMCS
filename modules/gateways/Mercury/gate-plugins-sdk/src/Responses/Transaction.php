<?php

namespace MercuryCash\SDK\Responses;

use MercuryCash\SDK\Traits\FromArrayTrait;
use MercuryCash\SDK\Traits\ToArrayTrait;
use MercuryCash\SDK\Interfaces\ResponseInterface;

class Transaction implements ResponseInterface
{
    use FromArrayTrait;
    use ToArrayTrait;

    /**
     * @var string
     */
    protected $uuid = null;

    /**
     * @var string
     */
    protected $address = null;

    /**
     * @var float
     */
    protected $cryptoAmount = null;

    /**
     * @var float
     */
    protected $fiatAmount = null;

    /**
     * @var int
     */
    protected $tip = 1;

    /**
     * @var float
     */
    protected $rate = null;

    /**
     * @var float
     */
    protected $fee = null;

    /**
     * @var string
     */
    protected $fiatIsoCode = null;

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return Transaction
     */
    public function setUuid(string $uuid): Transaction
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return Transaction
     */
    public function setAddress(string $address): Transaction
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return float
     */
    public function getCryptoAmount(): float
    {
        return $this->cryptoAmount;
    }

    /**
     * @param float $cryptoAmount
     * @return Transaction
     */
    public function setCryptoAmount(float $cryptoAmount): Transaction
    {
        $this->cryptoAmount = $cryptoAmount;

        return $this;
    }

    /**
     * @return float
     */
    public function getFiatAmount(): float
    {
        return $this->fiatAmount;
    }

    /**
     * @param float $fiatAmount
     * @return Transaction
     */
    public function setFiatAmount(float $fiatAmount): Transaction
    {
        $this->fiatAmount = $fiatAmount;

        return $this;
    }

    /**
     * @return int
     */
    public function getTip(): int
    {
        return $this->tip;
    }

    /**
     * @param int $tip
     * @return Transaction
     */
    public function setTip(int $tip): Transaction
    {
        $this->tip = $tip;

        return $this;
    }

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     * @return Transaction
     */
    public function setRate(float $rate): Transaction
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return float
     */
    public function getFee(): float
    {
        return $this->fee;
    }

    /**
     * @param float $fee
     * @return Transaction
     */
    public function setFee(float $fee): Transaction
    {
        $this->fee = $fee;

        return $this;
    }

    /**
     * @return string
     */
    public function getFiatIsoCode(): string
    {
        return $this->fiatIsoCode;
    }

    /**
     * @param string $fiatIsoCode
     * @return Transaction
     */
    public function setFiatIsoCode(string $fiatIsoCode): Transaction
    {
        $this->fiatIsoCode = $fiatIsoCode;

        return $this;
    }
}
