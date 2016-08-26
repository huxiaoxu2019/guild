<?php
/**
 * Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

use Library\Util\Config;
use Library\Util\PHP;
use Library\Util\Helper;

namespace Library\Util;

class Sync
{
	
	/**
	 * Deploy.
	 */
	public function deploy() {
		Helper::logLn(RUNTIME_LOG, "Deploy the project to the server(s)...");
		$cmdPath  = Config::get("common.product.cmd_path");
		$syncModule = Config::get("common.build.sync_module");
		$ryncCmd  = "/usr/bin/rsync -vzrtopg --exclude-from '/root/run/huati_build/config/rsync_exclude_list' --password-file=/etc/rsyncd.passwd50  --delete --progress {$cmdPath}/ tongjian@10.73.15.50::{$syncModule}";
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
