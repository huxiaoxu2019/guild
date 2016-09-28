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
use Library\Util\FileDatabase;

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
     * @TODO check php syntax; the git commit data is right?
     *
     * For test now.
     */
    private function buildToGrayLevelSimulationEnv()
    {
        Helper::logLn(RUNTIME_LOG, "Building to gray level simulation environment...");

        /* some build info */
        FileDatabase::set('build_' . BUILD_VERSION, 'build_time', time());
        FileDatabase::set('build_' . BUILD_VERSION, 'deploy_plan_time', strtotime(date('Y-m-d H:00:00', time() + 8 * 60 * 60)));

        /* deploy code */
        $repository = Config::get('common.product.cmd_path');
        $gitModel = new GitModel($repository);
        $gitModel->pull();
        Sync::deploy();

        /* get mail content */
        Helper::logLn(RUNTIME_LOG, 'Get mail content, includes commit, product_description, product, test info, subject, vcs and so on...');
        $mailModel = new MailModel(MailModel::TYPE_DEPLOY_TO_GRAY_LEVEL_SUCCESSFULLY); 
        $this->view->assign('data', $mailModel->getContent());

        /* send mail */
        Helper::logLn(RUNTIME_LOG, 'Sending email...');
        $mailContent = $this->view->fetch('gitbuild/gray.tpl');
        $sendMailResult = Mail::send($mailModel->getTo(), $mailModel->getCc(), $mailModel->getSubject(), $mailContent, ATTACHMENT);
        Helper::logLn(RUNTIME_LOG, 'Mail sent.');

        /* save build infomartion */
        Helper::logLn(RUNTIME_LOG, 'Saving build info...');
        FileDatabase::set('build_' . BUILD_VERSION, 'mail_content', $mailContent);
        FileDatabase::set('build_' . BUILD_VERSION, 'mail_attachment_path', ATTACHMENT);
        FileDatabase::set('build_' . BUILD_VERSION, 'runtime_log_path', RUNTIME_LOG);

        /* modify the build version */
        Helper::logLn(RUNTIME_LOG, 'Modify build version...');
        FileDatabase::set('build', 'currentBuildVersion', array('build_version' => BUILD_VERSION, 'commit_version' => $gitModel->getHead()));
    }

    /**
     * Build to all online environment.
     */
    private function buildToOnlineEnv()
    {
        /* Build to all online environment */
        Helper::logLn(RUNTIME_LOG, 'build to all online environment...');
        $lastStatbleBuildVersion = FileDatabase::get('build', 'lastStableBuildVersion');
        $currentBuildVersion = FileDatabase::get('build', 'currentBuildVersion');
        $status = FileDatabase::get('build_' . $currentBuildVersion['build_version'], 'status');
        $mailType = MailModel::TYPE_DEPLOY_TO_ALL_ONLINE_SUCCESSFULLY;
        switch ($status) {
        case BUILD_STATUS_PASSED:
            /* deploy to all online */
            $this->view->assign('type', MailModel::TYPE_DEPLOY_TO_ALL_ONLINE_SUCCESSFULLY);
            $this->deployToAllOnline();
            break;
        case BUILD_STATUS_NOT_PASSED:
            /* rollback */
            $this->view->assign('type', MailModel::TYPE_DEPLOY_TO_ALL_ONLINE_FAILED);
            $mailType = MailModel::TYPE_DEPLOY_TO_ALL_ONLINE_FAILED;
            $this->rollback($lastStatbleBuildVersion['commit_version']);
            break;
        case BUILD_STATUS_DEPLOYED:
            /* deployed */
            $this->view->assign('type', MailModel::TYPE_DEPLOY_TO_ALL_ONLINE_SUCCESSFULLY);
            Helper::logLn(RUNTIME_LOG, 'This version has been deployed.');
            break;
        default:
            /* rollback */
            $this->view->assign('type', MailModel::TYPE_DEPLOY_TO_ALL_ONLINE_FAILED);
            $mailType = MailModel::TYPE_DEPLOY_TO_ALL_ONLINE_FAILED;
            $this->rollBack();
            break;
        }

        /* send mail*/
        Helper::logLn(RUNTIME_LOG, 'Sending email...');
        $mailModel = new MailModel($mailType); 
        $this->view->assign('data', $mailModel->getContent());
        $mailContent = $this->view->fetch('gitbuild/online.tpl');
        $sendMailResult = Mail::send($mailModel->getTo(), $mailModel->getCc(), $mailModel->getSubject(), $mailContent);
        Helper::logLn(RUNTIME_LOG, 'Mail sent.');
    }

    /**
     * Rollback.
     *
     * @TODO 
     * @param $lastCommitVersion string
     */
    private function rollBack($lastCommitVersion) 
    {
        Helper::logLn(RUNTIME_LOG, 'Rollbacking...');
    }

    /**
     * Deploy to all online.
     *
     * @TODO
     */
    private function deployToAllOnline() 
    {
        Helper::logLn(RUNTIME_LOG, 'deployToAllOnline...');

        /* Modify the build status */
        $currentBuildVersion = FileDatabase::get('build', 'currentBuildVersion');
        FileDatabase::set('build', 'lastStableBuildVersion', array('build_version' => $currentBuildVersion['build_version'], 'commit_version' => $currentBuildVersion['commit_version']));
    }
}
