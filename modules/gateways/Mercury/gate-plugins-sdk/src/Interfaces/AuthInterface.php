<?php

namespace MercuryCash\SDK\Interfaces;

interface AuthInterface
{
    public function getHeaders(array $data = []): array;
}
