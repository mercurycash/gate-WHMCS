<?php

namespace MercuryCash\SDK\Responses;

use MercuryCash\SDK\Interfaces\ResponseInterface;
use MercuryCash\SDK\Traits\FromArrayTrait;
use MercuryCash\SDK\Traits\ToArrayTrait;

class Status implements ResponseInterface
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
    protected $fromAddress = null;

    /**
     * @var int
     */
    protected $amount = null;

    /**
     * @var string
     */
    protected $currency = null;

    /**
     * @var string
     */
    protected $transactionFee = null;

    /**
     * @var string
     */
    protected $networkFee = null;

    /**
     * @var string
     */
    protected $user = null;

    /**
     * @var string
     */
    protected $status = null;

    /**
     * @var int
     */
    protected $confirmations = 0;


    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return Status
     */
    public function setUuid(string $uuid): Status
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return string
     */
    public function getFromAddress(): string
    {
        return $this->fromAddress;
    }

    /**
     * @param string $fromAddress
     * @return Status
     */
    public function setFromAddress(string $fromAddress): Status
    {
        $this->fromAddress = $fromAddress;

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
     * @return Status
     */
    public function setAmount(int $amount): Status
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return Status
     */
    public function setCurrency(string $currency): Status
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionFee(): string
    {
        return $this->transactionFee;
    }

    /**
     * @param string $transactionFee
     * @return Status
     */
    public function setTransactionFee(string $transactionFee): Status
    {
        $this->transactionFee = $transactionFee;

        return $this;
    }

    /**
     * @return string
     */
    public function getNetworkFee(): string
    {
        return $this->networkFee;
    }

    /**
     * @param string $networkFee
     * @return Status
     */
    public function setNetworkFee(string $networkFee): Status
    {
        $this->networkFee = $networkFee;

        return $this;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $user
     * 
     * @return Status
     */
    public function setUser(string $user): Status
    {
        $this->user = $user;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Status
     */
    public function setStatus(string $status): Status
    {
        $this->status = $status;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getConfirmations(): int
    {
        return ($this->сonfirmations) ? $this->сonfirmations : 0;
    }

    /**
     * @param int $сonfirmations
     * @return Status
     */
    public function setConfirmations($сonfirmations): Status
    {
        $this->сonfirmations = ($сonfirmations) ? $сonfirmations : 0;

        return $this;
    }

}
