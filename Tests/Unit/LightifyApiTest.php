<?php
/**
 *   This file is part of Smart Lights.
 *
 *   Smart Lights is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   Smart Lights is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 *
 *   Diese Datei ist Teil von Smart Lights.
 *
 *   Smart Lights ist Freie Software: Sie können es unter den Bedingungen
 *   der GNU General Public License, wie von der Free Software Foundation,
 *   Version 3 der Lizenz oder (nach Ihrer Wahl) jeder späteren
 *   veröffentlichten Version, weiterverbreiten und/oder modifizieren.
 *
 *   Smart Lights wird in der Hoffnung, dass es nützlich sein wird, aber
 *   OHNE JEDE GEWÄHRLEISTUNG, bereitgestellt; sogar ohne die implizite
 *   Gewährleistung der MARKTFÄHIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK.
 *   Siehe die GNU General Public License für weitere Details.
 *
 *   Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
 *   Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>
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
