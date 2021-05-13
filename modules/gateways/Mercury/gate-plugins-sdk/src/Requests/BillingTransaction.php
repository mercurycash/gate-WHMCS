<?php

namespace MercuryCash\SDK\Requests;

use MercuryCash\SDK\Traits\FromArrayTrait;
use MercuryCash\SDK\Traits\ToArrayTrait;
use MercuryCash\SDK\Interfaces\RequestInterface;

class BillingTransaction implements RequestInterface
{
    use FromArrayTrait;
    use ToArrayTrait;

    /**
     * @var array
     */
    protected $client = null;

    /**
     * @var bool
     */
    protected $isBuyerAddressAdded = null;

    /**
     * @var string
     */
    protected $currency = null;

    /**
     * @var int
     */
    protected $due_date = null;

    /**
     * @var string
     */
    protected $invoice_number = null;

    /**
     * @var int
     */
    protected $amount = null;

    /**
     * @var bool
     */
    protected $processing_fee = null;

    /**
     * @var bool
     */
    protected $sendEmail = null;

    /**
     * @var array
     */
    protected $products = null;

    /**
     * @var int
     */
    protected $confirmations = null;

    /**
     * @var string
     */
    protected $cron_expression = null;

    /**
     * @var string
     */
    protected $end_date = null;

    /**
     * @return array
     */
    public function getClient(): array
    {
        return $this->client;
    }

    /**
     * @param array $client
     * @return BillingTransaction
     */
    public function setClient(array $client): BillingTransaction
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBuyerAddressAdded(): bool
    {
        return $this->isBuyerAddressAdded;
    }

    /**
     * @param bool $isBuyerAddressAdded
     * @return BillingTransaction
     */
    public function setIsBuyerAddressAdded(bool $isBuyerAddressAdded): BillingTransaction
    {
        $this->isBuyerAddressAdded = $isBuyerAddressAdded;

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
     * @return BillingTransaction
     */
    public function setCurrency(string $currency): BillingTransaction
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return int
     */
    public function getDueDate(): int
    {
        return $this->due_date;
    }

    /**
     * @param int $due_date
     * @return BillingTransaction
     */
    public function setDueDate(int $due_date): BillingTransaction
    {
        $this->due_date = $due_date;

        return $this;
    }

    /**
     * @return string
     */
    public function getInvoiceTransactionNumber(): string
    {
        return $this->invoice_number;
    }

    /**
     * @param string $invoice_number
     * @return BillingTransaction
     */
    public function BillingTransactionNumber(string $invoice_number): BillingTransaction
    {
        $this->invoice_number = $invoice_number;

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
     * @return BillingTransaction
     */
    public function setAmount(int $amount): BillingTransaction
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return bool
     */
    public function isProcessingFee(): bool
    {
        return $this->processing_fee;
    }

    /**
     * @param bool $processing_fee
     * @return BillingTransaction
     */
    public function setProcessingFee(bool $processing_fee): BillingTransaction
    {
        $this->processing_fee = $processing_fee;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSendEmail(): bool
    {
        return $this->sendEmail;
    }

    /**
     * @param bool $sendEmail
     * @return BillingTransaction
     */
    public function setSendEmail(bool $sendEmail): BillingTransaction
    {
        $this->sendEmail = $sendEmail;

        return $this;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param array $products
     * @return BillingTransaction
     */
    public function setProducts(array $products): BillingTransaction
    {
        $this->products = $products;

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
     * @return BillingTransaction
     */
    public function setConfirmations(int $confirmations): BillingTransaction
    {
        $this->confirmations = $confirmations;

        return $this;
    }

    /**
     * @return string
     */
    public function getCronExpression(): string
    {
        return $this->cron_expression;
    }

    /**
     * @param string $cron_expression
     * @return BillingTransaction
     */
    public function setCronExpression(string $cron_expression): BillingTransaction
    {
        $this->cron_expression = $cron_expression;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndDate(): string
    {
        return $this->end_date;
    }

    /**
     * @param string $end_date
     * @return BillingTransaction
     */
    public function setEndDate(string $end_date): BillingTransaction
    {
        $this->end_date = $end_date;

        return $this;
    }
}
