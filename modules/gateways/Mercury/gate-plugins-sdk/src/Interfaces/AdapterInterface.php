<?php

namespace MercuryCash\SDK\Interfaces;

use Psr\Http\Message\ResponseInterface;

interface AdapterInterface
{
    /**
     * AdapterInterface constructor.
     *
     * @param AuthInterface $auth
     * @param string $baseURI
     */
    public function __construct(AuthInterface $auth, string $baseURI);

    /**
     * @param string $uri
     * @param RequestInterface|null $request
     * @param array $headers
     * @return ResponseInterface
     */
    public function get(string $uri, RequestInterface $request = null, array $headers = []): ResponseInterface;

    /**
     * @param string $uri
     * @param RequestInterface|null $request
     * @param array $headers
     * @return ResponseInterface
     */
    public function post(string $uri, RequestInterface $request = null, array $headers = []): ResponseInterface;

    /**
     * @param string $uri
     * @param RequestInterface|null $request
     * @param array $headers
     * @return ResponseInterface
     */
    public function put(string $uri, RequestInterface $request = null, array $headers = []): ResponseInterface;

    /**
     * @param string $uri
     * @param RequestInterface|null $request
     * @param array $headers
     * @return ResponseInterface
     */
    public function patch(string $uri, RequestInterface $request = null, array $headers = []): ResponseInterface;

    /**
     * @param string $uri
     * @param RequestInterface|null $request
     * @param array $headers
     * @return ResponseInterface
     */
    public function delete(string $uri, RequestInterface $request = null, array $headers = []): ResponseInterface;
}
