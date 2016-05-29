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

class LightifyApi
{
    const LIGHTIFY_EUROPE = 'https://eu.lightify-api.org/lightify/services';
    const LIGHTIFY_USA = 'https://us.lightify-api.org/lightify/services';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $url;

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
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function doRequest($resource, $data, $requestMethod = 'GET')
    {
        return $this->client->send(
            new Request(
                $requestMethod,
                $this->url . '/' . $resource,
                ['Content-Type' => 'application/json'],
                $data
            )
        );
    }

    private function authenticate()
    {
        $this->authenticated = true;
    }
}
