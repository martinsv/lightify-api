<?php
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 06.06.16
 * Time: 19:36
 */

namespace Role\LightifyApi\Manager;


use Role\LightifyApi\LightifyApi;

class GroupManager implements ManagerInterface
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
        return $this->api->doRequest(LightifyApi::RESOURCE_GROUPS);
    }

    /**
     * @inheritDoc
     */
    public function toggle($idx, $on, $time = 0, $level = 1)
    {
        return $this->api->toggleOnOff(LightifyApi::RESOURCE_GROUP_SET, $idx, $on, $time, $level);
    }

    /**
     * @inheritDoc
     */
    public function switchColor($idx, $color, $time = 0)
    {
        return $this->api->switchColor(LightifyApi::RESOURCE_GROUP_SET, $idx, $color, $time);
    }

    /**
     * @inheritDoc
     */
    public function dim($idx, $level, $time = 0)
    {
        return $this->api->dim(LightifyApi::RESOURCE_GROUP_SET, $idx, $level, $time);
    }

    /**
     * @inheritDoc
     */
    public function switchTemperature($idx, $temperature, $time = 0)
    {
        return $this->api->switchTemperature(LightifyApi::RESOURCE_GROUP_SET, $idx, $temperature, $time);
    }
}
