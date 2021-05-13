<?php

namespace MercuryCash\SDK\Auth;

use MercuryCash\SDK\Interfaces\AuthInterface;

class APIKey implements AuthInterface
{
    /**
     * @var string|null
     */
    protected $api_key = null;

    /**
     * @var string|null
     */
    protected $api_secret = null;

    /**
     * XApiKey constructor.
     * @param string $api_key
     * @param string $api_secret
     */
    public function __construct(string $api_key, string $api_secret)
    {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
    }

    /**
     * @param array|null $data
     * @return string[]
     */
    public function getHeaders(array $data = []): array
    {
        $data = $data ? json_encode($data) : '{}';
        $hash = hash('sha512', $this->api_secret, false);
        $signature = hash_hmac('sha256', $data, $hash, false);

        return [
            'x-api-key' => $this->api_key,
            'signature' => $signature,
        ];
    }
}
