<?php

namespace MercuryCash\SDK\Interfaces;

interface ApiInterface
{
    /**
     * ApiInterface constructor.
     *
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter);
}
