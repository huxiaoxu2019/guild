<?php
/**
 * Guild - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

global $allfiles;
$allfiles = array();

/**
 * Read dir.
 *
 * @return array
 */
function read_dir_all($dir)
{
	global $allfiles;
	$ret = array(
		'dirs' => array(),
		'files' => array()
	);
	$handle = null;
	if ($handle = opendir($dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != '.' && $file !== '..') {
				$cur_path = $dir . DIRECTORY_SEPARATOR . $file; 
				if (is_dir($cur_path)) {
					$ret['dirs'][$cur_path] = read_dir_all($cur_path);
				} else {
					$ret['files'][] = $cur_path;
					$allfiles[] = $cur_path;
				}
			}
		}
		closedir($handle);
	}
	return $ret;
}

/**
 * Check app version constant.
 *
 * @param bool
 */
function check_app_version() 
{
    if (!defined('APP_VERSION')) {
        return false;
    }
    if (!file_exists(APP_PATH . '/config/' . APP_VERSION)) {
        return false;
    }
    if (!file_exists(APP_PATH . '/db/' . APP_VERSION)) {
        return false;
    }
    return true;
}

/**
 * Check build version constant.
 *
 * @return bool
 */
function check_build_version() 
{
    if (!defined('BUILD_VERSION')) {
        return false;
    }
    if (!file_exists(APP_PATH . '/db/' . APP_VERSION . '/build_' . BUILD_VERSION)) {
        return false;
    }
    return true;
}
