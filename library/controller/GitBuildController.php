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
use Library\Util\Config;
use Library\Util\Mail;
use Library\Util\Sync;
use Library\Model\GitModel;
use Library\Model\MailModel;

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

		/* deploy code */
		Sync::deploy();

		/* get mail content */
		Helper::logLn(RUNTIME_LOG, 'Get mail content, includes commit, product_description, product, test info, subject, vcs and so on...');
		$mailModel = new MailModel(MailModel::TYPE_DEPLOY_TO_GRAY_LEVEL_SUCCESSFULLY); 
		$this->view->assign('data', $mailModel->getContent());

		/* send mail */
		Helper::logLn(RUNTIME_LOG, 'Sending email...');
		$sendMailResult = Mail::send($mailModel->getTo(), $mailModel->getCc(), $mailModel->getSubject(), $this->view->fetch('gray.tpl'));
		Helper::logLn(RUNTIME_LOG, 'Mail sent.');
	}

	/**
	 * Build to all online environment.
	 */
	private function buildToOnlineEnv()
	{
	}

}
