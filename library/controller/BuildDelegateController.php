<?php
/**
 * Guild - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Controller;

use Library\Controller\GitBuildController;
use Library\Controller\SVNBuildController;
use Library\Util\Config;

/**
 * Build delegate class.
 */
class BuildDelegateController
{
    /**
     * Which type is the controler verison system selected.
     */
    const SVN = "Library\Controller\SVNBuildController";
    const GIT = "Library\Controller\GitBuildController";

    /**
     * The build class object.
     *
     * @var object
     */
    private $build = null;

    /**
     * Build method.
     *
     * @param int $type the type of the vcs
     */
    public function go($type = self::SVN)
    {
        $controllerName = $this->getCSV($type);
        $appVersions = $this->getAppVersions();
        foreach ($appVersions as $appVersion) {
            $this->build = new $controllerName($appVersion);
            $this->build->go();
        }
    }

    /**
     * Get the csv type.
     *
     * @return int
     */
    private function getCSV($type)
    {
        switch ($type) {
        case VCS_GIT:
            $vcs = self::GIT;    
            break;
        case VCS_SVN:
            $vcs = self::SVN;    
            break;
        default :
            $vcs = self::SVN;
            break;
        }
        return $vcs;
    }

    /**
     * Get the app versions.
     */
    private function getAppVersions() {
        return Config::get('common.app_versions');
    }
}
