<?php

namespace MercuryCash\SDK\Requests;

use MercuryCash\SDK\Traits\FromArrayTrait;
use MercuryCash\SDK\Traits\ToArrayTrait;
use MercuryCash\SDK\Interfaces\RequestInterface;

class CreateTransaction implements RequestInterface
{
    use FromArrayTrait;
    use ToArrayTrait;

    /**
     * @var string
     */
    protected $order_number = null;

    /**
     * @var string
     */
    protected $email = null;

    /**
     * @var string
     */
    protected $phone = null;

    /**
     * @var string
     */
    protected $crypto = null;

    /**
     * @var string
     */
    protected $fiat = null;

    /**
     * @var int
     */
    protected $amount = null;

    /**
     * @var int
     */
    protected $tip = null;

    /**
     * @var int
     */
    protected $confirmations = null;

    /**
     * @return string
     */
    public function getOrderNumber(): string
    {
        return $this->order_number;
    }

    /**
     * @param string $order_number
     * @return CreateTransaction
     */
    public function setOrderNumber(string $order_number): CreateTransaction
    {
        $this->order_number = $order_number;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return CreateTransaction
     */
    public function setEmail(string $email): CreateTransaction
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return CreateTransaction
     */
    public function setPhone(string $phone): CreateTransaction
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getCrypto(): string
    {
        return $this->crypto;
    }

    /**
     * @param string $crypto
     * @return CreateTransaction
     */
    public function setCrypto(string $crypto): CreateTransaction
    {
        $this->crypto = $crypto;

        return $this;
    }

    /**
     * @return string
     */
    public function getFiat(): string
    {
        return $this->fiat;
    }

    /**
     * @param string $fiat
     * @return CreateTransaction
     */
    public function setFiat(string $fiat): CreateTransaction
    {
        $this->fiat = $fiat;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return CreateTransaction
     */
    public function setAmount(int $amount): CreateTransaction
    {
        $this->amount = $amount;

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
     * @return CreateTransaction
     */
    public function setTip(int $tip): CreateTransaction
    {
        $this->tip = $tip;

        return $this;
    }

    /**
     * @return int
     */
    public function getConfirmations(): int
    {
        return $this->confirmations;
    }

    /**
     * @param int $confirmations
     * @return CreateTransaction
     */
    public function setConfirmations(int $confirmations): CreateTransaction
    {
        $this->confirmations = $confirmations;

        return $this;
    }
}
