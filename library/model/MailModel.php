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
    const TYPE_DEPLOY_TO_INNER_SUCCESSFULLY      = 5;
    const TYPE_DEPLOY_TO_INNER_FAILED            = 6;

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

        /* Get build description */
        $content['build_description'] = $this->getBuildDescription();

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
     * @TODO abstract optimization
     * @return string
     */
    public function getSubject() {
        if (!$this->subject)
        {
            switch ($this->deployType)
            {
            case self::TYPE_DEPLOY_TO_ALL_ONLINE_SUCCESSFULLY :
                $title = Config::get("common.app.suc_title");
                $currentBuildVersion = FileDatabase::get('build', 'currentBuildVersion');
                $this->subject = sprintf($title, 'Online ' . $currentBuildVersion['build_version']);
                break;
            case self::TYPE_DEPLOY_TO_ALL_ONLINE_FAILED :
                $title = Config::get("common.app.fai_title");
                $currentBuildVersion = FileDatabase::get('build', 'currentBuildVersion');
                $this->subject = sprintf($title, 'Online ' . $currentBuildVersion['build_version']);
                break;
            case self::TYPE_DEPLOY_TO_GRAY_LEVEL_SUCCESSFULLY :
                $title = Config::get("common.app.suc_title");
                $this->subject = sprintf($title, 'Gray level ' . BUILD_VERSION);
                break;
            case self::TYPE_DEPLOY_TO_GRAY_LEVEL_FAILED :
                $title = Config::get("common.app.fai_title");
                $this->subject = sprintf($title, 'Gray level ' . BUILD_VERSION);
                break;
            case self::TYPE_DEPLOY_TO_INNER_SUCCESSFULLY :
                $title = Config::get("common.app.suc_title");
                $this->subject = sprintf($title, BUILD_VERSION);
                break;
            case self::TYPE_DEPLOY_TO_INNER_FAILED :
                $title = Config::get("common.app.fai_title");
                $this->subject = sprintf($title, BUILD_VERSION);
                break;
            default :
                $title = Config::get("common.app.fai_title");
                $this->subject = sprintf($title, BUILD_VERSION);
                break;
            }
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
        Helper::logLn(RUNTIME_LOG, 'getGitInfo...');
        /* db */
        $fileDatabase = new FileDatabase();
        $lastStatbleBuildVersion = FileDatabase::get('build', 'lastStableBuildVersion');
        $lastCommitHash = $lastStatbleBuildVersion['commit_version'];
        Helper::logLn(RUNTIME_LOG, 'Get the last commit version:' . $lastCommitHash);

        /* git model */
        $repository = Config::get("common.product.cmd_path");
        $gitModel = new GitModel($repository);
        $result = $gitModel->logWithNameStatus($lastCommitHash);        

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
        return $productModel->getDescriptionInfo();
    }

    /**
     * Get build description.
     *
     * @TODO abstract optimization
     *
     * @return string
     */
    private function getBuildDescription() {
        $productModel = new ProductModel();    
        $info = '';
        switch ($this->deployType)
        {
        case self::TYPE_DEPLOY_TO_ALL_ONLINE_SUCCESSFULLY :
            $info = $productModel->getOnlineSucInfo();
            $currentBuildVersion = FileDatabase::get('build', 'currentBuildVersion');
            $info = sprintf($info, $currentBuildVersion['build_version']);
            break;
        case self::TYPE_DEPLOY_TO_ALL_ONLINE_FAILED :
            $info = $productModel->getOnlineFailInfo();
            $currentBuildVersion = FileDatabase::get('build', 'currentBuildVersion');
            $info = sprintf($info, $currentBuildVersion['build_version']);
            break;
        case self::TYPE_DEPLOY_TO_GRAY_LEVEL_SUCCESSFULLY :
            $info = $productModel->getGrayInfo();
            $hours = Config::get("common.build.deploy_hours");
            $plan_time = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:00:00', time())) + $hours * 60 * 60);
            $params = array('version' => APP_VERSION, 'build_version' => BUILD_VERSION);
            $build_domain = Config::get('common.build.build_domain');
            $build_console_url = 'http://' . $build_domain . '/BuildConsole/pushToOnline?' . http_build_query($params);
            $build_console_url = "<a href='{$build_console_url}'>{$build_console_url}</a>";
            $info = sprintf($info, $plan_time, $build_console_url);
            break;
        case self::TYPE_DEPLOY_TO_GRAY_LEVEL_FAILED :
            $info = $productModel->getGrayInfo();
            $hours = Config::get("common.build.deploy_hours");
            $plan_time = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:00:00', time())) + $hours * 60 * 60);
            $params = array('version' => APP_VERSION, 'build_version' => BUILD_VERSION);
            $build_domain = Config::get('common.build.build_domain');
            $build_console_url = 'http://' . $build_domain . '/BuildConsole/pushToOnline?' . http_build_query($params);
            $build_console_url = "<a href='{$build_console_url}'>{$build_console_url}</a>";
            $info = sprintf($info, $plan_time, $build_console_url);
            break;
        case self::TYPE_DEPLOY_TO_INNER_SUCCESSFULLY : 
        case self:: TYPE_DEPLOY_TO_INNER_FAILED : 
            break;
        default:
            break;
        }
        return $info;
    }
}
