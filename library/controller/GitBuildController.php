<?php
/**
 * Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Controller;

use Library\Util\Helper;
use Library\Util\FileDatabase;
use Library\Util\Config;
use Library\Model\GitModel;

class GitBuildController extends AbstractController
{
	/**
	 * Build method.
	 */
	public function go()
	{
		if(ONLINE_ALL === "true") {
			$this->buildToOnlineEnv();
		} else {
			$this->buildToGrayLevelSimulationEnv();
		}
	}

	/**
	 * Build to gray level simulation environment.
	 */
	private function buildToGrayLevelSimulationEnv()
	{
		Helper::logLn(RUNTIME_LOG, "Building to gray level simulation environment...");

		/* db */
		$fileDatabase = new FileDatabase();
		$lastVersion = $fileDatabase->get(FileDatabase::FILENAME_GIT, 'lastVersion');

		/* git model */
		$repository = Config::get("common.product.cmd_path");
		$gitModel = new GitModel($repository);
		//$log = $gitModel->getCommitInfo();
		//$log = $gitModel->walk();
		$log = $gitModel->diff();
		var_dump($log);

		/* view */
		$html = $this->view->fetch("gray.tpl");

	}

	/**
	 * Build to all online environment.
	 */
	private function buildToOnlineEnv()
	{
		$gitModel = new GitModel();
	}
}
