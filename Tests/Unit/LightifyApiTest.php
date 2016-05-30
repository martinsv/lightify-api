<?php
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 30.05.16
 * Time: 23:39
 */

namespace Role\LightifyApi\Tests;

use GuzzleHttp\Client;
use Role\LightifyApi\LightifyApi;

class LightifyApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LightifyApi
     */
    private $lightifyApi;

    protected function setUp()
    {
        /** @var Client $client */
        $client = $this
            ->getMock('Guzzle\\PSR7\\Client', [], [], 'Guzzle\\PSR7\\Client')
        ;

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

    }
}
