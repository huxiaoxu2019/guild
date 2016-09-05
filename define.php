<?php
/**
 * Guild - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

if (!defined("APP_PATH")) {
	define("APP_PATH", dirname(__FILE__)); 
}

if (isset($argv[1])) {
	define("APP_VERSION", $argv[1]);
} else {
	die("error param");
}

if (isset($argv[2])) {
	$params = explode(",", $argv[2]);
	if(is_array($params) && count($params) > 0) {
		foreach($params as $v) {
			$param = explode(":", $v);
  			define(strtoupper($param[0]), $param[1]);
		}
 	}
}

if (!defined("VCS")) {
	define("VCS", "git");
}

if (!defined("VCS_GIT")) {
	define("VCS_GIT", "git");
}

if (!defined("VCS_SVN")) {
	define("VCS_SVN", "svn");
}

if (!defined("SHOW_COMMIT")) {
	define("SHOW_COMMIT", "true");
}

if (!defined("ONLINE_ALL")) {
	define("ONLINE_ALL", "false");
}

if (!defined('ATTACHMENT')) {
	define('ATTACHMENT', APP_PATH . '/data/runtime/' . date('Y/m/d/', time()) . 'email-attachment-log-' . time() . '.txt');
}

if (!defined('RUNTIME_LOG')) {
	define('RUNTIME_LOG', APP_PATH . '/data/runtime/' . date('Y/m/d/', time()) . time() . '.log');
}

if (!defined("DEBUG")) {
	define("DEBUG", "false");
}

if (!defined('SMARTY_TEMPLATE_DIR')) {
	define('SMARTY_TEMPLATE_DIR', APP_PATH . '/view/');
}

if (!defined('SMARTY_COMPILE_DIR')) {
	define('SMARTY_COMPILE_DIR', APP_PATH . '/data/runtime/' . date('Y/m/d/', time()) . 'smarty/compile/');
}

