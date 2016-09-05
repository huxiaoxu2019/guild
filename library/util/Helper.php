<?php
/**
 * Guild - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Util;

class Helper
{
	/**
	 * Constant.
	 */
	const BUILD_SUC = 'successfully';
	const BUILD_FAI = 'fail';
	
	/**
	 * Throw messages to the terminal.
	 *
	 * @deprecated
	 * @param $msg mixed
	 */
	public static function console($msg)
	{
		if (is_array($msg)) {
			var_dump($msg);
		} else {
			echo $msg;
		}	
	}

	/**
	 * Throw messages to the terminal in line.
	 *
	 * @deprecated
	 * @param $msg mixed
	 */
	public static function consoleLn($msg) {
		if (!is_array($msg)) {
			$msg .= "\n";
		}
		self::console($msg);
	}
	
	/**
	 * Log.
	 *
	 * @param $filename string
	 * @param $msg string
	 */
	public static function logLn($filename, $msg) {
		$path = dirname($filename);
		if (!is_dir($path)) {
			mkdir(iconv("UTF-8", "GBK", $path), 0777, true); 
		}
		error_log($msg, 3, $filename);
	}

	/**
	 * Set build result to the disk.
	 *
	 * @param $msg string
	 */
	public static function setBuildResult($msg = self::BUILD_SUC) {
		$buildResult =  Config::get("common.build.build_result");
		$fp = fopen($buildResult, "w");
		fwrite($fp, $msg);
		fclose($fp);
	}

	/**
	 * Weather or not the current environment is debug mode.
	 *
	 * @return bool
	 */
	public static function isDebug() 
	{
		if (DEBUG !== 'true') {
			return false;
		}
		return true;
	}
}
