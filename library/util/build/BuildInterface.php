<?php
/**
 * Guild - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Util\Build;

/**
 * The build interface implemented by the Library\Util\Build.php class.
 *
 * The Build.php class should be implemented by yourself.
 */
interface BuildInterface
{
    /**
     * Build to gray level enviroment.
     *
     * @param array $params
     *
     * @return bool
     */
    public function buildToGrayLevelEnviroment($params = array());

    /**
     * Build to online enviroment.
     *
     * @param array $params
     *
     * @return bool
     */
    public function buildToOnlineEnviroment($params = array());

    /*
     * Get the list of rollback packages.
     *
     * @param array $params
     *
     * @return array such as array('v1.0.1', 'v1.0.0', 'v0.9.2', 'v.0.9.1')
     */
    public function getRollbackList($params = array());

    /**
     * Rollback.
     *
     * @param array @params
     * 
     * @return bool
     */
    public function rollback($params = array());
}
