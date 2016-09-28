<?php
/**
 * Guild - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Util;

use Library\Model\GitModel;
use Library\Util\Config;
use Library\Util\PHP;
use Library\Util\Helper;

class Sync
{
    /**
     * Deploy.
     */
    public static function deploy() {
        $cmdPath  = Config::get('common.product.cmd_path');
        $passwd  = Config::get('common.build.sync_passwd');
        $syncModule = Config::get("common.build.sync_module");

        Helper::logLn(RUNTIME_LOG, "Pulling the last source code to server from the local cmd_path {$cmdPath}...");
        Helper::logLn(RUNTIME_LOG, "Deploying the project to the server(s)...");
        $ryncCmd  = "/usr/bin/rsync -vzrtopg --exclude-from '" . APP_PATH . "/config/rsync_exclude_list' --password-file={$passwd}  --delete --progress {$cmdPath}/ root@10.210.237.31::{$syncModule}";

        Helper::logLn(RUNTIME_LOG, "rsync:{$ryncCmd}");
        $output = <<<DEPLOY
===================================================================
                             DEPLOY
===================================================================\n
DEPLOY;
        $output .= shell_exec($ryncCmd);
        $filepath = ATTACHMENT;
        $file = fopen($filepath, "a+");    
        fwrite($file, $output);
        fclose($file);
    }
}
