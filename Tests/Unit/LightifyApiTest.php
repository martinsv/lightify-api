<?php
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 30.05.16
 * Time: 23:39
 */

namespace Role\LightifyApi\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Role\LightifyApi\LightifyApi;

class LightifyApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LightifyApi
     */
    private $lightifyApi;

    protected function setUp()
    {
        $client = $this
            ->getMock(Client::class)
        ;

        $request = $this->getMock(Request::class, [], [], Request::class, false);
        $response = $this->getMock(Response::class);

        $client->expects($this->exactly(1))
            ->method('send')
            ->will($this->returnCallback(function () use ($response) {
                return $response;
            }));
        
        $client->expects($this->exactly(1))
            ->method('request')
            ->will($this->returnCallback(function () use ($request) {
                return $request;
            }));

        $this->lightifyApi = new LightifyApi(
            $client,
            LightifyApi::LIGHTIFY_EUROPE,
            'test',
            'test123',
            '123abc'
        );
    }

    public function testAuthentication()
    {
        $this->lightifyApi->doRequest(LightifyApi::RESOURCE_SESSION, [
            'username' => 'test',
            'password' => 'test123',
            'serialNumber' => '123abc'
        ]);
    }
}
