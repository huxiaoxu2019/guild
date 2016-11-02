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
     */
    public function buildToGrayLevelEnviroment();

    /**
     * Build to online enviroment.
     */
    public function buildToOnlineEnviroment();

    /*
     * Get the list of rollback packages.
     */
    public function getRollbackList();
}
