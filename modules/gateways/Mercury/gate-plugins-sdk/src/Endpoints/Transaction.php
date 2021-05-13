<?php

namespace MercuryCash\SDK\Endpoints;

use MercuryCash\SDK\Interfaces\AdapterInterface;
use MercuryCash\SDK\Interfaces\ApiInterface;
use MercuryCash\SDK\Requests\BillingTransaction;
use MercuryCash\SDK\Requests\CreateTransaction;
use MercuryCash\SDK\Requests\ProcessTransaction;
use MercuryCash\SDK\Responses\Transaction as TransactionResponse;
use MercuryCash\SDK\Responses\Checkout as CheckoutResponse;
use MercuryCash\SDK\Responses\Status as StatusResponse;

class Transaction implements ApiInterface
{
    /**
     * @var AdapterInterface|null
     */
    protected $adapter = null;

    /**
     * Transaction constructor.
     *
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param array $data
     * @return TransactionResponse
     */
    public function create(array $data): TransactionResponse
    {
        $data = $this->adapter->post('checkout', CreateTransaction::fromArray($data));

        $this->body = json_decode($data->getBody());

        if (isset($this->body->data)) {
            return TransactionResponse::fromArray((array)$this->body->data);
        }

        throw new \InvalidArgumentException($this->body->message);
    }

    /**
     * @param string $uuid
     */
    public function process(string $uuid)
    {
        $data = $this->adapter->put('checkout', ProcessTransaction::fromArray(['uuid' => $uuid]));

        $this->body = json_decode($data->getBody());

        if (isset($this->body->data)) {
            return CheckoutResponse::fromArray((array)$this->body->data);
        }

        throw new \InvalidArgumentException($this->body->message);
    }

    /**
     * @param array $data
     */
    public function billing(array $data)
    {
        $data = $this->adapter->post('invoice', BillingTransaction::fromArray($data));

        $this->body = json_decode($data->getBody());

        if (isset($this->body->data)) {
            return (array)$this->body->data;
        }

        throw new \InvalidArgumentException($this->body->message);
    }

    /**
     * @param string $uuid
     */
    public function status(string $uuid)
    {
        $data = $this->adapter->get('transaction/' . $uuid);

        $this->body = json_decode($data->getBody());

        if (isset($this->body)) {
            return StatusResponse::fromArray((array)$this->body);
        }

        throw new \InvalidArgumentException($this->body->message);
    }
}