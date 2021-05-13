<?php

namespace MercuryCash\SDK\Responses;

use MercuryCash\SDK\Traits\FromArrayTrait;
use MercuryCash\SDK\Traits\ToArrayTrait;
use MercuryCash\SDK\Interfaces\ResponseInterface;

class Checkout implements ResponseInterface
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
     * @var int
     */
    protected $networkFee = null;

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return Checkout
     */
    public function setUuid(string $uuid): Checkout
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
     * @return Checkout
     */
    public function setAddress(string $address): Checkout
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return int
     */
    public function getNetworkFee(): int
    {
        return $this->networkFee;
    }

    /**
     * @param int $networkFee
     * @return Checkout
     */
    public function setNetworkFee(int $networkFee): Checkout
    {
        $this->networkFee = $networkFee;

        return $this;
    }
}