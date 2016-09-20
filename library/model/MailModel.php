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

	/**
	 * Deploty type.
	 *
	 * @var int
	 */
	private $deployType = 0;

	/*
	 * Mail subject.
	 *
	 * @var string
	 */
	private $subject = '';

	/**
	 * Constructor.
	 *
	 * @param int $deployType
	 */
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
			'commit' => '',
			'product_description' => '', 
			'product' => array(),
			'test' => '',
			'subject' => '',
			'vcs' => array());
		/* Get Product Description */
		$content['product_description'] = $this->getProductDescriptionInfo();	

		/* Get VCS info */
		if (VCS == VCS_GIT) {
			$content['vcs'] = $this->getGitInfo();
		} else {
			$content['vcs'] = $this->getSVNInfo();
		}

		/* product */
		$content['product'] = $this->getProductInfo();

		/* test */
		$content['test'] = $this->getTestInfo();

		/* subject */
		$content['subject'] = $this->getSubject();

		/* return */
		return $content;
	}

	/**
	 * Get email address(to).
	 *
	 * @return string
	 */
	public function getTo() 
	{
		return Config::get('mail.receiver.to');
	}

	/**
	 * Get email address(cc).
	 *
	 * @return string
	 */
	public function getCc() 
	{
		return Config::get('mail.receiver.cc');
	}

	/*
	 * Get mail subject.
	 *
	 * @return string
	 */
	public function getSubject() {
		if (!$this->subject)
		{
			switch ($this->deployType)
			{
			case self::TYPE_DEPLOY_TO_ALL_ONLINE_SUCCESSFULLY :
				$title = Config::get("common.app.suc_title");
				break;
			case self::TYPE_DEPLOY_TO_ALL_ONLINE_FAILED :
				$title = Config::get("common.app.fai_title");
				break;
			case self::TYPE_DEPLOY_TO_GRAY_LEVEL_SUCCESSFULLY :
				$title = Config::get("common.app.suc_title");
				break;
			case self::TYPE_DEPLOY_TO_GRAY_LEVEL_FAILED :
				$title = Config::get("common.app.fai_title");
				break;
			default :
				$title = Config::get("common.app.fai_title");
				break;
			}
			$build = '.' . date("ymd", time()) . '01';
			$this->subject = sprintf($title, $build, '');
		}
		return $this->subject;
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
		/* db */
		$fileDatabase = new FileDatabase();
		$lastCommitHash = $fileDatabase->get(FileDatabase::FILENAME_GIT, 'lastVersion');

		/* git model */
		$repository = Config::get("common.product.cmd_path");
		$gitModel = new GitModel($repository);
	    $result = $gitModel->log();		
		
		/* return */
		return $result;
	}

	/**
	 * Get SVN info.
	 *
	 * @return array
	 */
	private function getSVNInfo() {}

	/**
	 * Get test info.
	 *
	 * @return string
	 */
	private function getTestInfo() 
	{
		return Config::get('common.test.desc');
	}

	/**
	 * Get product description info.
	 *
	 * @param return string
	 */
	private function getProductDescriptionInfo()
   	{
		$productModel = new ProductModel();	
		$info = $productModel->getDescriptionInfo();
		switch ($this->deployType)
		{
		case self::TYPE_DEPLOY_TO_ALL_ONLINE_SUCCESSFULLY:
			break;
		case self::TYPE_DEPLOY_TO_ALL_ONLINE_FAILED:
			break;
		case self::TYPE_DEPLOY_TO_GRAY_LEVEL_SUCCESSFULLY:
			$info .= '<br />' . $productModel->getGrayInfo();
			break;
		case self::TYPE_DEPLOY_TO_GRAY_LEVEL_FAILED:
			$info .= '<br />' . $productModel->getGrayInfo();
			break;
		default:
			break;
		}
		$plan_time = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:00:00', time())) + 4 * 60 * 60);
		$info = sprintf($info, $plan_time);
		return $info;
	}
}
