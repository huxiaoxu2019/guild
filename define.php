<?php
/**
 * Guild - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

if (isset($_SERVER['APP_MODE'])) {
	define('APP_MODE', $_SERVER['APP_MODE']);
} else {
	define('APP_MODE', 'CLI');
}

/* COMMON DEFINE */
if (!defined("APP_PATH")) {
	define("APP_PATH", dirname(__FILE__)); 
}

if (!defined('SMARTY_TEMPLATE_DIR')) {
	define('SMARTY_TEMPLATE_DIR', APP_PATH . '/view/');
}

if (!defined('SMARTY_COMPILE_DIR')) {
	define('SMARTY_COMPILE_DIR', APP_PATH . '/data/runtime/' . date('Y/m/d/', time()) . 'smarty/compile/');
}

if (!defined('RUNTIME_LOG')) {
    define('RUNTIME_LOG', APP_PATH . '/data/runtime/' . date('Y/m/d/', time()) . time() . '.log');
}

if (!defined("DEBUG")) {
    define("DEBUG", "false");
}

if (!defined("VCS_GIT")) {
    define("VCS_GIT", "git");
}

if (!defined("VCS_SVN")) {
    define("VCS_SVN", "svn");
}

if (!defined("VCS")) {
    define("VCS", "git");
}

if (!defined('BUILD_STATUS_DEPLOYED')) {
    define('BUILD_STATUS_DEPLOYED', 3);
}

if (!defined('BUILD_STATUS_PASSED')) {
    define('BUILD_STATUS_PASSED', 1);
}

if (!defined('BUILD_STATUS_NOT_PASSED')) {
    define('BUILD_STATUS_NOT_PASSED', 2);
}

/* CLI DEFINE */
if (APP_MODE == 'CLI') {
	if (isset($argv[1])) {
		define("APP_VERSION", $argv[1]);
	} else {
		die("error param");
	}

	if (isset($argv[2])) {
		$params = explode(",", $argv[2]);
		if (is_array($params) && count($params) > 0) {
			foreach ($params as $v) {
				$param = explode(":", $v);
                if (!defined(strtoupper($param[0]))) {
                    define(strtoupper($param[0]), $param[1]);
                }
			}
		}
	}

    if (!defined('BUILD_VERSION')) {
        $app_version = explode('_', APP_VERSION);
        define('BUILD_VERSION', $app_version[1] . '_' . date('Ymd', time()) . '01');
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

	if (!defined('DEPLOY_TYPE_TO_ALL_ONLINE_SUCCESSFULLY_NAME')) {
		define('DEPLOY_TYPE_TO_ALL_ONLINE_SUCCESSFULLY_NAME', 'Daily Build Online Environment');
	}

	if (!defined('DEPLOY_TYPE_TO_ALL_ONLINE_FAILED_NAME')) {
		define('DEPLOY_TYPE_TO_ALL_ONLINE_FAILED_NAME', 'Daily Build Online Environment');
	}

	if (!defined('DEPLOY_TYPE_TO_GRAY_LEVEL_SUCCESSFULLY_NAME')) {
		define('DEPLOY_TYPE_TO_GRAY_LEVEL_SUCCESSFULLY_NAME', 'Daily Build Gray Level Online Environment');
	}

	if (!defined('DEPLOY_TYPE_TO_GRAY_LEVEL_FAILED_NAME')) {
		define('DEPLOY_TYPE_TO_GRAY_LEVEL_FAILED_NAME', 'Daily Build Gray Level Online Environment');
	}
}

/* WEB DEFINE */
if (APP_MODE == 'WEB') {
	$path_info = trim($_SERVER['PATH_INFO'], '/');
	$path_info = explode('/', $path_info);
	if (!defined('CONTROLLER_NAME')) {
		define('CONTROLLER_NAME', isset($path_info[0]) ? $path_info[0] : '');
	}
	if (!defined('ACTION_NAME')) {
		define('ACTION_NAME', isset($path_info[1]) ? $path_info[1] : '');
	}

    $app_version = filter_input(INPUT_GET, 'version', FILTER_SANITIZE_STRING);
    if ($app_version && !defined('APP_VERSION')) {
        define('APP_VERSION', $app_version);
    }

    $build_version = filter_input(INPUT_GET, 'build_version', FILTER_SANITIZE_STRING);
    if ($build_version && !defined('BUILD_VERSION')) {
        define('BUILD_VERSION', $build_version);
    }
}
