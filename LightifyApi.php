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

    const RESOURCE_DEVICES = 'devices';
    const RESOURCE_DEVICE_SET = 'device/set';
    const RESOURCE_ALL_DEVICE_SET = 'devices/all/set';

    const RESOURCE_GROUPS = 'groups';
    const RESOURCE_GROUP_SET = 'group/set';

    const RESOURCE_SCENE = 'scene/recall';

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
    private $securityToken;
    
    /**
     * LightifyApi constructor.
     *
     * @param Client $client
     * @param string $url
     */
    public function __construct(Client $client, $url)
    {
        $this->client = $client;

        if (strtoupper($url) === 'EU') {
            $this->url = self::LIGHTIFY_EUROPE;
        } elseif ($url === 'US') {
            $this->url = self::LIGHTIFY_USA;
        }
    }

    /**
     * @param string $resource
     * @param array $data
     * @param string $requestMethod
     *
     * @throws ErrorException
     *
     * @return mixed
     */
    public function doRequest($resource, $data = [], $requestMethod = 'GET')
    {
        $headers = ['Content-Type' => 'application/json'];

        if ($this->securityToken) {
            $headers['authorization'] = (string) $this->securityToken;
        }

        $response = $this->client->send(
            new Request(
                $requestMethod,
                $this->url . '/' . $resource,
                $headers,
                json_encode($data)
            )
        );

        if ((int) substr($response->getStatusCode(), 0, 1) === 4) {
            throw new ErrorException($response['errorMessage'], $response['errorCode']);
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string  $resource
     * @param integer $idx
     * @param integer $temperature
     * @param integer $time
     *
     * @return mixed
     *
     * @throws ErrorException
     */
    public function switchTemperature($resource, $idx, $temperature, $time = 0)
    {
        $response = $this->doRequest(
            $this->generateSingleOperation(
                $resource,
                $idx,
                [
                    'ctemp' => $temperature,
                    'time'  => $time
                ]
            )
        );

        return $response;
    }

    /**
     * @param string $resource
     * @param integer $idx
     * @param integer $level
     * @param integer $time
     *
     * @return mixed
     *
     * @throws ErrorException
     */
    public function dim($resource, $idx, $level, $time = 0)
    {
        $response = $this->doRequest(
            $this->generateSingleOperation(
                $resource,
                $idx,
                [
                    'level' => $level,
                    'time'  => $time
                ]
            )
        );

        return $response;
    }

    /**
     * @param string  $resource
     * @param integer $idx
     * @param string  $color
     * @param integer $time
     *
     * @return mixed
     *
     * @throws ErrorException
     */
    public function switchColor($resource, $idx, $color, $time = 0)
    {
        $response = $this->doRequest(
            $this->generateSingleOperation(
                $resource,
                $idx,
                [
                    'color' => $color,
                    'time'  => $time
                ]
            )
        );

        return $response;
    }

    /**
     * @param string  $resource
     * @param integer $idx
     * @param boolean $on
     * @param integer $time
     * @param integer $level
     *
     * @return mixed
     *
     * @throws ErrorException
     */
    public function toggleOnOff($resource, $idx, $on, $time = 0, $level = null)
    {
        $params = [
            'onoff' => (int) $on,
            'time'  => $time,
        ];

        if (!is_null($level)) {
            $params['level'] = $level;
        }

        $response = $this->doRequest(
            $this->generateSingleOperation(
                $resource,
                $idx,
                $params
            )
        );

        return $response;
    }

    /**
     * @param string  $resource
     * @param integer $idx
     * @param array   $parameters
     *
     * @return string
     */
    private function generateSingleOperation($resource, $idx, array $parameters = [])
    {
        $parametersUri = '?idx=' . (int) $idx;

        foreach ($parameters as $key => $value) {
            $parametersUri .= '&' . $key . '=' . $value;
        }

        return $resource . $parametersUri;
    }
}
