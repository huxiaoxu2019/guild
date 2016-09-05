<?php
/**
 * Guild - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Controller;

class SVNBuildController extends AbstractController
{
	/**
	 * Build.
	 */
	public function go()
	{
		Helper::logLn(RUNTIME_LOG, "Building from svn controller...");
	}
}
