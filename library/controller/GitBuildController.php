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
use Library\Util\Build;

class GitBuildController extends AbstractController
{
    /**
     * Build method.
     */
    public function go()
    {
        /* outer deploy type */
        if (DEPLOY_TYPE == 'outer') {
            if(ONLINE_ALL == 'true') {
                $this->buildToOnlineEnviroment();
            } else {
                $this->buildToGrayLevelEnviroment();
            }
            /*
                if (ONLINE_ALL == 'outer_master_to_build') {
                    $this->mergeMasterToBuild();
                }
             */
        }

        /* inner deploy type */
        if (DEPLOY_TYPE == 'inner') {
            $this->buildToInnerEnv();
        }
    }

    /**
     * Build to gray level simulation environment.
     *
     * @TODO check php syntax; the git commit data is right?
     *
     * For test now.
     */
    private function buildToGrayLevelEnviroment()
    {
        Helper::logLn(RUNTIME_LOG, "Building to gray level simulation environment...");

        /* some build info */
        FileDatabase::set('build_' . BUILD_VERSION, 'build_time', time());
        $hours = Config::get("common.build.deploy_hours");
        FileDatabase::set('build_' . BUILD_VERSION, 'deploy_plan_time', strtotime(date('Y-m-d H:00:00', time() + $hours * 60 * 60)));

        /* deploy code */
        $repository = Config::get('common.product.cmd_path');
        $gitModel = new GitModel($repository);
        $gitModel->pull(Config::get('common.build.git_remote'), Config::get('common.build.git_branch'));
        $build = new Build();
        Helper::logLn(RUNTIME_LOG, 'Build to gray level enviroment...');
        $build->buildToGrayLevelEnviroment();

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
    private function buildToOnlineEnviroment()
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
            $this->rollback();
            break;
        case BUILD_STATUS_DEPLOYED:
            /* deployed */
            $this->view->assign('type', MailModel::TYPE_DEPLOY_TO_ALL_ONLINE_SUCCESSFULLY);
            Helper::logLn(RUNTIME_LOG, 'This version has been deployed.');
            break;
        default:
            /* deploy to all online */
            $this->view->assign('type', MailModel::TYPE_DEPLOY_TO_ALL_ONLINE_SUCCESSFULLY);
            $this->deployToAllOnline();
            break;
        }

        /* send mail*/
        Helper::logLn(RUNTIME_LOG, 'Sending email...');
        $mailModel = new MailModel($mailType); 
        $this->view->assign('data', $mailModel->getContent());
        $mailContent = $this->view->fetch('gitbuild/online.tpl');
        $sendMailResult = Mail::send($mailModel->getTo(), $mailModel->getCc(), $mailModel->getSubject(), $mailContent);
        Helper::logLn(RUNTIME_LOG, 'Mail sent.');

        /* modify the version */
        Helper::logLn(RUNTIME_LOG, 'Modifying the build version...');
        $this->modifyTheBuildVersion($status);
    }

    /**
     * Rollback.
     *
     * @TODO 
     */
    private function rollBack() 
    {
        Helper::logLn(RUNTIME_LOG, 'Rollbacking...');
        $build = new Build();
        $params = array();

        $rollbackList = $build->getRollbackList(array('piplinedefid' => Build::BUILD_V5_ROLLBACK));
        $rollbackVersion = '';
        if (!empty($rollbackList[1])) {
           $rollbackVersion = $rollbackList[1];
        } else {
            Helper::logLn(RUNTIME_LOG, 'Rollback failed');
            return false;
        }
        $params[0]['piplinedefid'] = Build::BUILD_V5_ROLLBACK;
        $params[0]['rollback_version'] = $rollbackVersion;

        $rollbackList = $build->getRollbackList(array('piplinedefid' => Build::BUILD_V6_ROLLBACK));
        if (!empty($rollbackList[1])) {
           $rollbackVersion = $rollbackList[1];
        } else {
            Helper::logLn(RUNTIME_LOG, 'Rollback failed');
            return false;
        }
        $params[1]['piplinedefid'] = Build::BUILD_V6_ROLLBACK;
        $params[1]['rollback_version'] = $rollbackVersion;
        Helper::logLn(RUNTIME_LOG, 'Get the rollback params:' . var_export($params, true));

        return $build->rollback($params);
    }

    /**
     * Deploy to all online.
     *
     * @TODO
     */
    private function deployToAllOnline() 
    {
        Helper::logLn(RUNTIME_LOG, 'deployToAllOnline...');
    }

    /**
     * Deploy to inner enviroment.
     */
    private function buildToInnerEnv()
    {
        Helper::logLn(RUNTIME_LOG, "Building to inner environment...");

        /* some build info */
        FileDatabase::set('build_' . BUILD_VERSION, 'build_time', time());

        /* deploy code */
        $repository = Config::get('common.product.cmd_path');
        $gitModel = new GitModel($repository);
        $gitModel->pull(Config::get('common.build.git_remote'), Config::get('common.build.git_branch'));
        Sync::deploy();

        /* get mail content */
        Helper::logLn(RUNTIME_LOG, 'Get mail content, includes commit, product_description, product, test info, subject, vcs and so on...');
        $mailModel = new MailModel(MailModel::TYPE_DEPLOY_TO_INNER_SUCCESSFULLY); 
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
        FileDatabase::set('build', 'lastStableBuildVersion', array('build_version' => BUILD_VERSION, 'commit_version' => $gitModel->getHead()));
    }

    /**
     * Modify the build version.
     *
     * @param int $status
     */
    private function modifyTheBuildVersion($status)
    {
        switch ($status) {
        case BUILD_STATUS_PASSED:
            /* deploy to all online */
            Helper::logLn(RUNTIME_LOG, 'deploy to all online type');
            $currentBuildVersion = FileDatabase::get('build', 'currentBuildVersion');
            FileDatabase::set('build', 'lastStableBuildVersion', 
                array('build_version' => $currentBuildVersion['build_version'], 'commit_version' => $currentBuildVersion['commit_version']));
            break;
        case BUILD_STATUS_NOT_PASSED:
            Helper::logLn(RUNTIME_LOG, 'rollback type');
            /* rollback */
            $lastStableBuildVersion = FileDatabase::get('build', 'lastStableBuildVersion');
            FileDatabase::set('build', 'currentBuildVersion', 
                array('build_version' => $lastStableBuildVersion['build_version'], 'commit_version' => $lastStableBuildVersion['commit_version']));
            break;
        case BUILD_STATUS_DEPLOYED:
            /* deployed */
            Helper::logLn(RUNTIME_LOG, 'deployed type');
            break;
        default:
            /* deploy to all online */
            Helper::logLn(RUNTIME_LOG, 'deploy to all online type');
            $currentBuildVersion = FileDatabase::get('build', 'currentBuildVersion');
            FileDatabase::set('build', 'lastStableBuildVersion', 
                array('build_version' => $currentBuildVersion['build_version'], 'commit_version' => $currentBuildVersion['commit_version']));
            break;
        }
    }

    /**
     * Merge the master to build.
     */
    private function mergeMasterToBuild()
    {
        $repository = Config::get('common.product.cmd_path');
        $gitModel = new GitModel($repository);
        $info = $gitModel->checkout('master');
        Helper::logLn(RUNTIME_LOG, "checkout master\n" . $info);
        $info = $gitModel->pull(Config::get('common.build.git_remote'), 'master');
        Helper::logLn(RUNTIME_LOG, "pull master\n" . $info);
        $info = $gitModel->checkout(Config::get('common.build.git_branch'));
        Helper::logLn(RUNTIME_LOG, "checkout build\n" . $info);
        $info = $gitModel->pull(Config::get('common.build.git_remote'), Config::get('common.build.git_branch'));
        Helper::logLn(RUNTIME_LOG, "pull build\n" . $info);
        $info =$gitModel->merge(Config::get('common.build.git_remote'), 'master');
        Helper::logLn(RUNTIME_LOG, "merge master to build\n" . $info);
        $info =$gitModel->push(Config::get('common.build.git_remote'), 'build');
        Helper::logLn(RUNTIME_LOG, "git push origin build\n" . $info);
        $info = $gitModel->mergetool();
        if (strpos('No files need merging', $info) !== false) {
            // merge conflict
        }
    }
}
