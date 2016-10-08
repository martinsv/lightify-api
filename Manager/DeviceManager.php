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

namespace Role\LightifyApi\Manager;

use Role\LightifyApi\LightifyApi;

class DeviceManager implements ManagerInterface
{
    /**
     * @var LightifyApi
     */
    private $api;

    /**
     * @inheritDoc
     */
    public function __construct(LightifyApi $api)
    {
        $this->api = $api;
    }

    /**
     * @inheritDoc
     */
    public function getList()
    {
        return $this->api->doRequest(LightifyApi::RESOURCE_DEVICES);
    }

    /**
     * @inheritDoc
     */
    public function toggle($idx, $on, $time = 0, $level = null)
    {
        return $this->api->toggleOnOff(LightifyApi::RESOURCE_DEVICE_SET, $idx, $on, $time, $level);
    }

    /**
     * @inheritDoc
     */
    public function switchColor($idx, $color, $time = 0)
    {
        return $this->api->switchColor(LightifyApi::RESOURCE_DEVICE_SET, $idx, $color, $time);
    }

    /**
     * @inheritDoc
     */
    public function dim($idx, $level, $time = 0)
    {
        return $this->api->dim(LightifyApi::RESOURCE_DEVICE_SET, $idx, $level, $time);
    }

    /**
     * @inheritDoc
     */
    public function switchTemperature($idx, $temperature, $time = 0)
    {
        return $this->api->switchTemperature(LightifyApi::RESOURCE_DEVICE_SET, $idx, $temperature, $time);
    }
}
