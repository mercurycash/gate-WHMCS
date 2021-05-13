<?php

namespace MercuryCash\SDK\Auth;

use MercuryCash\SDK\Interfaces\AuthInterface;

class None implements AuthInterface
{
    /**
     * @param array|null $data
     * @return array
     */
    public function getHeaders(array $data = []): array
    {
        return [];
    }
}
