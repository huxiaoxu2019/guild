<?php
/**
 * Guild - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Controller;

use Library\Util\FileDatabase;
use Library\Model\ProductModel;

class BuildConsoleController extends AbstractController
{
    /**
     * Push to online enviroment.
     */
    public function pushToOnline() {
        /* get html data */
        $data = array();
        $data['mail_content'] = FileDatabase::get('build_' . BUILD_VERSION, 'mail_content');
        $data['mail_attachment'] = FileDatabase::getFileContent(FileDatabase::get('build_' . BUILD_VERSION, 'mail_attachment_path'));
        if ($data['mail_attachment']) {
            $data['mail_attachment'] = explode("\n", $data['mail_attachment']);
        }
        $data['runtime_log'] = FileDatabase::getFileContent(FileDatabase::get('build_' . BUILD_VERSION, 'runtime_log_path'));
        if ($data['runtime_log']) {
            $data['runtime_log'] = explode("\n", $data['runtime_log']);
        }
        $data['build_version'] = BUILD_VERSION;
        $productModel = new ProductModel();
        $data['product'] = $productModel->getInfo(); 
        $data['build_time'] = FileDatabase::get('build_' . BUILD_VERSION, 'build_time');
        $data['deploy_plan_time'] = FileDatabase::get('build_' . BUILD_VERSION, 'deploy_plan_time');
        $data['build_status'] = FileDatabase::get('build_' . BUILD_VERSION, 'status');

        /* assign and display */
        $this->view->assign('data', $data);
        $this->view->display('buildconsole/push_to_online.tpl');
    }

    /*
     * Set the status of build.
     */
    public function setBuildStatus() {
        $status = filter_input(INPUT_GET, 'status', FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => 3)));
        FileDatabase::set('build_' . BUILD_VERSION, 'status', $status);
        $this->redirect('/BuildConsole/pushToOnline', array('app_name' => APP_NAME, 'build_version' => BUILD_VERSION));
    }
}
