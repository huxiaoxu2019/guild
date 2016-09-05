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
		$controllerName = $this->_getCSV($type);
		$this->build = new $controllerName();
		$this->build->go();
	}

	/**
	 * Get the csv type.
	 *
	 * @return int
	 */
	private function _getCSV($type)
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
}
