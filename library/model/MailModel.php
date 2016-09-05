<?php
/**
 * Guild - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Model;

use Library\Model\GitModel;
use Library\Model\ProductModel;
use Library\Model\TestModel;
use Library\Model\App\Model;
use Library\Util\FileDatabase;
use Library\Util\Helper;
use Library\Util\Config;

class MailModel
{

	/**
	 * Some constants.
	 */
	const TYPE_DEPLOY_TO_ALL_ONLINE_SUCCESSFULLY = 1;
	const TYPE_DEPLOY_TO_ALL_ONLINE_FAILED       = 2;
	const TYPE_DEPLOY_TO_GRAY_LEVEL_SUCCESSFULLY = 3;
	const TYPE_DEPLOY_TO_GRAY_LEVEL_FAILED       = 4;

	private $deployType = 0;

	public function __construct($deployType = self::TYPE_DEPLOY_TO_ALL_ONLINE_SUCCESSFULLY) 
	{
		Helper::logLn(RUNTIME_LOG, "MailModel...");

		$this->deployType = $deployType;
	}

	/**
	 * Get mail content field.
	 *
	 * @param return string
	 */
	public function getContent() 
	{
		/* define */
		$content = array(
			'produt_description' => '', 
			'vcs' => array());
		/* Get Product Description */
		$content['produt_description'] = $this->getProductDescriptionInfo();	

		/* Get VCS info */
		if (VCS == VCS_GIT) {
			$content['vcs'] = $this->getGitInfo();
		} else {
			$content['vcs'] = $this->getSVNInfo();
		}

		/* return */
		return $content;
	}

	public function getTo() 
	{
		return Config::get('mail.receiver.to');
	}

	public function getCc() 
	{
		return Config::get('mail.receiver.cc');
	}

	public function getSubject() 
	{
		
	}

	/**
	 * Get product info.
	 *
	 * @param return mixed
	 */
	private function getProductInfo() 
	{
		$productModel = new ProductModel();	
		return $productModel->getInfo();
	}

	/**
	 * Get git commit and diff information.
	 *
	 * @param return array
	 */
	private function getGitInfo() 
	{
		/* define */
		$result = array('commitsMap' => array(), 'diffsMap' => array());

		/* db */
		$fileDatabase = new FileDatabase();
		$lastCommitHash = $fileDatabase->get(FileDatabase::FILENAME_GIT, 'lastVersion');

		/* git model */
		$repository = Config::get("common.product.cmd_path");
		$gitModel = new GitModel($repository);
		$gitModel->pull();
		$walks = $gitModel->revwalk($lastCommitHash);
		array_push($walks, $lastCommitHash);
		$length = count($walks);
		$commits = array();

		foreach ($walks as $walk) {
			$commits[] = $gitModel->commitInfo($walk);
		}
		for ($i = 0; $i < $length - 1; $i++) {
			$result['commitsMap'][$commits[$i]['id']] = $commits[$i];
			$result['diffsMap'][$commits[$i]['id']] = $gitModel->diffTreeToTree($commits[$i]['tree_id'], 
				$commits[$i + 1]['tree_id'], 
				GIT_DIFF_FORMAT_NAME_STATUS);	
		}
		if ($length) {
			$lastCommitHash = $commits[0]['id'];
		}
		
		/* return */
		return $result;
	}

	private function getSVNInfo() 
	{

	}

	private function getTestInfo() 
	{

	}

	/**
	 * Get product description info.
	 *
	 * @param return string
	 */
	private function getProductDescriptionInfo()
   	{
		$productModel = new ProductModel();	
		return $productModel->getDescriptionInfo();
	}
}
