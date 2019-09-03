<?php

namespace Sisense\Api;

use Sisense\Client;
use Sisense\Exceptions\SisenseClientException;

class Auth extends Client
{
    protected $apiGroup = 'Auth';

    /**
     * @param $username
     * @param string $password
     *
     * @return string
     * @throws SisenseClientException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getToken($username, $password)
    {
        $data = [
            'username' => $username,
            'password' => $password,
        ];

        $response = $this->post('api/v1/authentication/login', $data);

        if (empty($response['access_token'])) {
            throw new SisenseClientException('Unable to authenticate');
        }

        return $response['access_token'];
    }
}
