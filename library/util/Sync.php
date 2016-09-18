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
		$cmdPath = '/data1/www/htdocs/huati_build';

		Helper::logLn(RUNTIME_LOG, "Pulling the last source code to server from the local cmd_path {$cmdPath}...");
		$repository = Config::get('common.product.cmd_path');
		$gitModel = new GitModel($repository);
		$gitModel->pull();

		Helper::logLn(RUNTIME_LOG, "Deploying the project to the server(s)...");
		$syncModule = Config::get("common.build.sync_module");
//		$ryncCmd  = "/usr/bin/rsync -vzrtopg --exclude-from '/root/run/huati_build/config/rsync_exclude_list' --password-file=/etc/rsyncd.passwd50  --delete --progress {$cmdPath}/ tongjian@10.73.15.50::{$syncModule}";
		$ryncCmd  = "/usr/bin/rsync -vzrtopg --exclude-from '/root/run/huati_build/config/rsync_exclude_list' --password-file=/etc/rsyncd.passwd  --delete --progress {$cmdPath}/ root@10.210.241.151::{$syncModule}";

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
