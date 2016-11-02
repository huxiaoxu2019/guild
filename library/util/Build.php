<?php
/**
 * Guild - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Util;

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
    /*
     * Build to gray level enviroment.
     */
    public function buildToGrayLevelEnviroment() {}

    /**
     * Build to online enviroment.
     */
    public function buildToOnlineEnviroment() {}

    /**
     * Get rollback list.
     */
    public function getRollbackList() {}
}
