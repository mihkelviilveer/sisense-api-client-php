<?php

namespace Sisense;

use InvalidArgumentException;

class Client implements ClientInterface
{
    /**
     * @var array
     */
    private static $defaultPorts = array(
        'http' => 80,
        'https' => 443,
    );

    /**
     * Error strings if json is invalid.
     */
    private static $jsonErrors = array(
        JSON_ERROR_NONE => 'No error has occurred',
        JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
        JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
        JSON_ERROR_SYNTAX => 'Syntax error',
    );


    private $classes = [
        'auth' => 'Auth',
    ];

    /**
     * @var \GuzzleHttp\ClientInterface $http
     */
    private $http;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $apiToken = null;

    /**
     * @var array APIs
     */
    private $apis = [];

    /**
     * Client constructor.
     * @param string $url
     * @param \GuzzleHttp\ClientInterface|null $http
     */
    public function __construct($url, \GuzzleHttp\ClientInterface $http = null)
    {
        if (is_null($http)) {
            $http = new \GuzzleHttp\Client();
        }

        $this->url = $url;
        $this->http = $http;
    }

    /**
     * @param $path
     * @param $method
     * @param array $data
     * @param array $headers
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function runRequest($path, $method, $data = [], $headers = [])
    {
        $response = $this->http->request($method, $this->url . $path);

        return $this->decode(
            $response->getBody()->getContents()
        );
    }

    /**
     * @param $path
     * @param array $params
     * @param bool $decode
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($path, array $params = [])
    {
        $response = $this->runRequest($path, 'GET');

        return $response;
    }

    /**
     * HTTP POSTs $params to $path.
     *
     * @param string $path
     * @param mixed $data
     * @param array $headers
     *
     * @return bool|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post($path, $data = null, $headers = [])
    {
        if (empty($headers['Content-Type']) || $headers['Content-Type'] == 'application/json' ) {
            $data = $this->encodeData($data);
        }

        return $this->runRequest($path, 'POST', $data, $headers);
    }

    /**
     * @param mixed $data
     *
     * @return array
     */
    private function encodeData($data = null)
    {
        if (is_array($data)) {
            return json_encode($data);
        }

        return [];
    }

    /**
     * Decodes json response.
     *
     * Returns $json if no error occured during decoding but decoded value is
     * null.
     *
     * @param string $json
     *
     * @return array|string
     */
    public function decode($json)
    {
        if ('' === $json) {
            return '';
        }
        $decoded = json_decode($json, true);
        if (null !== $decoded) {
            return $decoded;
        }
        if (JSON_ERROR_NONE === json_last_error()) {
            return $json;
        }
        return self::$jsonErrors[json_last_error()];
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the apiToken object globally from json encoded token object.
     *
     * @param string $apiToken json token object
     *
     * @return Client
     */
    public function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return Api\AbstractApi
     */
    public function api($name)
    {
        if (!isset($this->classes[$name])) {
            throw new \InvalidArgumentException('Available api : '.implode(', ', array_keys($this->classes)));
        }
        if (isset($this->apis[$name])) {
            return $this->apis[$name];
        }
        $c = 'Sisense\Api\\' . $this->classes[$name];
        $this->apis[$name] = new $c($this);
        return $this->apis[$name];
    }

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     */
    public function __get($name)
    {
        return $this->api($name);
    }
}
