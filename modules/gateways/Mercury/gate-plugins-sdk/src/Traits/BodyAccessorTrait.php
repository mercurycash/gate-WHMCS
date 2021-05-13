<?php

namespace MercuryCash\SDK\Traits;

trait BodyAccessorTrait
{
    /**
     * @var mixed
     */
    protected $body = null;

    /**
     * @return mixed|null
     */
    public function getBody()
    {
        return $this->body;
    }
}
