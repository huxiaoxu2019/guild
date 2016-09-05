<?php
/**
 * Guild - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Controller;

use Library\Util\Helper;
use Library\Util\FileDatabase;
use Library\Util\Config;
use Library\Util\Mail;
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
	 *
	 * For test now.
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
		$gitModel->pull();
		$lastCommitHash = '942382fb26ca94f381f5c84597b4974b1acbf027';
		$walks = $gitModel->revwalk($lastCommitHash);
		array_push($walks, $lastCommitHash);
		$length = count($walks);
		$commits = array();
		$commitsMap = array();
		$diffsMap = array();

		foreach ($walks as $walk) {
			$commits[] = $gitModel->commitInfo($walk);
		}
		for ($i = 0; $i < $length - 1; $i++) {
			$commitsMap[$commits[$i]['id']] = $commits[$i];
			$diffsMap[$commits[$i]['id']] = $gitModel->diffTreeToTree($commits[$i]['tree_id'], 
				$commits[$i + 1]['tree_id'], 
				GIT_DIFF_FORMAT_NAME_STATUS);	
		}
		if ($length) {
			$lastCommitHash = $commits[0]['id'];
		}

		/* mail */
		Mail::send('huxu@staff.weibo.com', '', 'test s', 'test b');

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
