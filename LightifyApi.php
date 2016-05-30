<?php
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 30.05.16
 * Time: 00:19
 */

namespace Role\LightifyApi;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Role\LightifyApi\Exceptions\ErrorException;

class LightifyApi
{
    const LIGHTIFY_EUROPE = 'https://eu.lightify-api.org/lightify/services';
    const LIGHTIFY_USA = 'https://us.lightify-api.org/lightify/services';

    const RESOURCE_SESSION = 'session';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $url;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $serialNumber;

    /**
     * @var string
     */
    private $securityToken;

    /**
     * @var boolean
     */
    private $authenticated;
    
    /**
     * LightifyApi constructor.
     *
     * @param Client $client
     * @param string $url
     * @param string $userName
     * @param string $password
     * @param string $serialNumber
     */
    public function __construct(Client $client, $url, $userName, $password, $serialNumber)
    {
        $this->client = $client;
        $this->userName = $userName;
        $this->password = $password;
        $this->serialNumber = $serialNumber;
        $this->url = $url;
    }

    /**
     * @param string $resource
     * @param array $data
     * @param string $requestMethod
     *
     * @throws ErrorException
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function doRequest($resource, $data = [], $requestMethod = 'GET')
    {
        if (!$this->authenticated && $resource !== self::RESOURCE_SESSION) {
            $this->authenticate();
        }

        $response = $this->client->send(
            new Request(
                $requestMethod,
                $this->url . '/' . $resource,
                [
                    'Content-Type' => 'application/json',
                    'authorization' => $this->securityToken
                ],
                $data
            )
        );

        if ((int) substr($response->getStatusCode(), 0, 1) === 4) {
            throw new ErrorException($response['errorMessage'], $response['errorCode']);
        }
    }

    private function authenticate()
    {
        $response = $this->doRequest(self::RESOURCE_SESSION, [
            'username' => $this->userName,
            'password' => $this->password,
            'serialNumber' => $this->serialNumber
        ]);

        $this->securityToken = $response['securityToken'];
        $this->userId = $response['userId'];

        $this->authenticated = true;
    }
}
