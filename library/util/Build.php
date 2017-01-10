<?php
/**
 * Guild - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Util;

use Library\Util\Build\BuildInterface;

/**
 * Build class.
 *
 * Build to the servers, such as the gray level enviroment or the online enviroment.
 * This class is based on the third-api configured in config file.
 *
 * If you want to use the class, you have to write the functions declared in the BuildInterface.
 */
class Build implements BuildInterface
{
    /**
     * The constructor.
     */
    public function __construct($params)
    {
        return false;
    }

    /*
     * Build to gray level enviroment.
     *
     * @param array $params
     *
     * @return bool
     */
    public function buildToGrayLevelEnviroment($params = array())
    {
        return false;
    }

    /**
     * Build to online enviroment.
     *
     * @param array $params
     *
     * @return bool
     */
    public function buildToOnlineEnviroment($params = array())
    {
        return false;
    }

    /*
     * Get the list of rollback packages.
     *
     * @param array $params
     *
     * @return array such as array('v1.0.1', 'v1.0.0', 'v0.9.2', 'v.0.9.1')
     */
    public function getRollbackList($params = array())
    {
        return array();
    }

    /**
     * Rollback.
     *
     * @param array @params
     * 
     * @return bool
     */
    public function rollback($params = array())
    {
        return false;
    }
}
