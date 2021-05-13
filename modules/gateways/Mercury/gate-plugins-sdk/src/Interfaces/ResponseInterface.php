<?php

namespace MercuryCash\SDK\Interfaces;

interface ResponseInterface
{
    /**
     * ResponseInterface constructor.
     * @param array $data
     */
    public function __construct(array $data);

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data);

    /**
     * @return array
     */
    public function toArray(): array;
}
