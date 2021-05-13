<?php

namespace MercuryCash\SDK\Interfaces;

interface RequestInterface
{
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
