<?php

namespace MercuryCash\SDK;

use MercuryCash\SDK\Exceptions\JSONException;
use MercuryCash\SDK\Exceptions\ResponseException;
use MercuryCash\SDK\Interfaces\AuthInterface;
use MercuryCash\SDK\Interfaces\AdapterInterface;
use GuzzleHttp\Client;
use MercuryCash\SDK\Interfaces\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Adapter implements AdapterInterface
{
    /**
     * @var Client|null
     */
    protected $client = null;

    /**
     * @var AuthInterface|null
     */
    protected $auth = null;

    /**
     * @var string[]
     */
    protected $methods = ['get', 'post', 'put', 'patch', 'delete'];

    /**
     * @inheritDoc
     */
    public function __construct(AuthInterface $auth, string $baseURI = 'https://api-way.mercury.cash')
    {
        $this->auth = $auth;

        $this->client = new Client([
            'base_uri' => $baseURI,
            'Accept' => 'application/json'
        ]);
    }

    /**
     * @inheritDoc
     */
    public function get(string $uri, RequestInterface $request = null, array $headers = []): ResponseInterface
    {
        return $this->request('get', $uri, $request, $headers);
    }

    /**
     * @inheritDoc
     */
    public function post(string $uri, RequestInterface $request = null, array $headers = []): ResponseInterface
    {
        return $this->request('post', $uri, $request, $headers);
    }

    /**
     * @inheritDoc
     */
    public function put(string $uri, RequestInterface $request = null, array $headers = []): ResponseInterface
    {
        return $this->request('put', $uri, $request, $headers);
    }

    /**
     * @inheritDoc
     */
    public function patch(string $uri, RequestInterface $request = null, array $headers = []): ResponseInterface
    {
        return $this->request('patch', $uri, $request, $headers);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $uri, RequestInterface $request = null, array $headers = []): ResponseInterface
    {
        return $this->request('delete', $uri, $request, $headers);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param RequestInterface|null $request
     * @param array $headers
     * @return ResponseInterface
     * @throws JSONException
     * @throws ResponseException
     */
    public function request(string $method, string $uri, RequestInterface $request = null, array $headers = []): ResponseInterface
    {
        if (!in_array($method, $this->methods)) {
            throw new \InvalidArgumentException('Request method must be get, post, put, patch, or delete');
        }

        $data = $request ? $request->toArray() : [];
        $headers = array_merge($this->auth->getHeaders($data), $headers);

        $response = $this->client->$method($uri, [
            'headers' => $headers,
            ($method === 'get' ? 'query' : 'json') => $data,
        ]);

        $this->checkError($response);

        return $response;
    }

    /**
     * @param ResponseInterface $response
     * @throws JSONException
     * @throws ResponseException
     */
    private function checkError(ResponseInterface $response)
    {
        $json = json_decode($response->getBody());

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JSONException();
        }

        if (isset($json->errors) && count($json->errors) >= 1) {
            throw new ResponseException($json->errors[0]->message, $json->errors[0]->code);
        }

        if (isset($json->data) && !$json->data) {
            throw new ResponseException('Request was unsuccessful.');
        }
    }
}
