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
use Role\LightifyBundle\Model\Device;

class LightifyApi
{
    const LIGHTIFY_EUROPE = 'https://eu.lightify-api.org/lightify/services';
    const LIGHTIFY_USA = 'https://us.lightify-api.org/lightify/services';

    const RESOURCE_SESSION = 'session';
    const RESOURCE_DEVICES = 'devices';
    const RESOURCE_DEVICE_SET = 'device/set';

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
     * @return mixed
     */
    public function doRequest($resource, $data = [], $requestMethod = 'GET')
    {
        if (!$this->authenticated && $resource !== self::RESOURCE_SESSION) {
            $this->authenticate();
        }

        $headers = ['Content-Type' => 'application/json'];

        if ($resource !== self::RESOURCE_SESSION) {
            $headers['authorization'] = $this->securityToken;
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
     * @return mixed|\Psr\Http\Message\ResponseInterface
     *
     * @throws ErrorException
     */
    public function listDevices()
    {
        $response = $this->doRequest(self::RESOURCE_DEVICES);

        return $response;
    }

    /**
     * @param integer $deviceId
     * @param boolean $on
     *
     * @return mixed
     * @throws ErrorException
     */
    public function toggleOnOffDevice($deviceId, $on)
    {
        $response = $this->doRequest(self::RESOURCE_DEVICE_SET . '?idx=' . $deviceId . '&onoff=' . (int) $on);

        return $response;
    }

    /**
     * @param $deviceId
     * @return mixed
     *
     * @throws ErrorException
     */
    public function switchOnDevice($deviceId)
    {
        $response = $this->doRequest(
            $this->generateSingleOperation(
                self::RESOURCE_DEVICE_SET,
                $deviceId,
                [
                    'onoff' => true
                ]
            )
        );

        return $response;
    }

    /**
     * @param $deviceId
     * @return mixed
     *
     * @throws ErrorException
     */
    public function switchOffDevice($deviceId)
    {
        $response = $this->doRequest(
            $this->generateSingleOperation(
                self::RESOURCE_DEVICE_SET,
                $deviceId,
                [
                    'onoff' => '0'
                ]
            )
        );

        return $response;
    }

    /**
     * @param integer $deviceId
     * @param float   $level
     * @param integer $time
     *
     * @return mixed
     *
     * @throws ErrorException
     */
    public function fadeInDevice($deviceId, $level, $time)
    {
        $response = $this->doRequest(
            $this->generateSingleOperation(
                self::RESOURCE_DEVICE_SET,
                $deviceId,
                [
                    'onoff' => 1,
                    'level' => $level,
                    'time' => $time
                ]
            )
        );

        return $response;
    }

    /**
     * @param integer $deviceId
     * @param float   $level
     * @param integer $time
     *
     * @return mixed
     *
     * @throws ErrorException
     */
    public function fadeOutDevice($deviceId, $level, $time)
    {
        $response = $this->doRequest(
            $this->generateSingleOperation(
                self::RESOURCE_DEVICE_SET,
                $deviceId,
                [
                    'onoff' => 0,
                    'level' => $level,
                    'time' => $time,
                ]
            )
        );

        return $response;
    }

    /**
     * @param integer $deviceId
     * @param string  $color
     * @param integer $time
     *
     * @return mixed
     *
     * @throws ErrorException
     */
    public function switchColor($deviceId, $color, $time = 0)
    {
        $response = $this->doRequest(
            $this->generateSingleOperation(
                self::RESOURCE_DEVICE_SET,
                $deviceId,
                [
                    'color' => $color,
                    'time' => $time
                ]
            )
        );

        return $response;
    }

    /**
     * @param integer $deviceId
     * @param float   $level
     * @param integer $time
     *
     * @return mixed
     *
     * @throws ErrorException
     */
    public function dimDevice($deviceId, $level, $time = 0)
    {
        $response = $this->doRequest(
            $this->generateSingleOperation(
                self::RESOURCE_DEVICE_SET,
                $deviceId,
                [
                    'time' => $time,
                    'onoff' => 1,
                    'level' => $level
                ]
            )
        );

        return $response;
    }

    /**
     * @param integer $deviceId
     * @param integer $cTemp
     * @param integer $time
     *
     * @return mixed
     *
     * @throws ErrorException
     */
    public function changeTemperature($deviceId, $cTemp, $time = 0)
    {
        $response = $this->doRequest(
            $this->generateSingleOperation(
                self::RESOURCE_DEVICE_SET,
                $deviceId,
                [
                    'time'  => $time,
                    'ctemp' => $cTemp
                ]
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

    /**
     * @throws ErrorException
     */
    private function authenticate()
    {
        $response = $this->doRequest(self::RESOURCE_SESSION, [
            'username' => $this->userName,
            'password' => $this->password,
            'serialNumber' => $this->serialNumber
        ], 'POST');

        $this->securityToken = $response['securityToken'];
        $this->userId = $response['userId'];

        $this->authenticated = true;
    }
}
