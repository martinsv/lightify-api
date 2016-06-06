<?php

namespace Role\LightifyApi\Manager;
use Role\LightifyApi\LightifyApi;

/**
 * Created by PhpStorm.
 * User: robin
 * Date: 06.06.16
 * Time: 19:26
 */
interface ManagerInterface
{
    /**
     * ManagerInterface constructor.
     *
     * @param LightifyApi $api
     */
    public function __construct(LightifyApi $api);

    /**
     * @return array
     */
    public function getList();

    /**
     * @param integer $idx
     * @param boolean $on
     * @param integer $time
     * @param integer $level
     *
     * @return mixed
     */
    public function toggle($idx, $on, $time = 0, $level = 1);

    /**
     * @param integer $idx
     * @param string  $color
     * @param integer $time
     *
     * @return mixed
     */
    public function switchColor($idx, $color, $time = 0);

    /**
     * @param integer $idx
     * @param string  $level
     * @param integer $time
     *
     * @return mixed
     */
    public function dim($idx, $level, $time = 0);

    /**
     * @param integer $idx
     * @param integer $temperature
     * @param integer $time
     *
     * @return mixed
     */
    public function switchTemperature($idx, $temperature, $time = 0);
}
