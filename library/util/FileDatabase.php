<?php
/**
 * Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Util;

use Library\Util\FileDatabase;

class FileDatabase
{	

	/**
	 * The max db file size(bytes).
	 */
	const MAX_DB_BYTES = 1024;

	/**
	 * The filename constant.
	 */
	const FILENAME_GIT = 'git';
	const FILENAME_SVN = 'svn';
	
	/**
	 * Get.
	 *
	 * @param $filename string
	 * @param $key string
	 * @return mixed
	 */
	public static function get($filename, $key = '') 
	{
		$filepath = self::getFilePath($filename);
		$result = array();
		if ($filepath) {
			$fileHandle = fopen($filepath, 'r');
			$fileContent = fread($fileHandle, filesize($filepath));
			$result = json_decode($fileContent, true) ? json_decode($fileContent, true) : $fileContent;
			fclose($fileHandle);
		}
		if ($key) {
			return isset($result[$key]) ? $result[$key] : array();
		} else {
			return $result;
		}
	}

	/**
	 * Set.
	 *
	 * @param $filename string
	 * @param $key string
	 * @param $value mixed
	 * @return bool
	 */
	public static function set($filename, $key, $value) 
	{
		$filepath = self::getFilePath($filename);
		if ($filepath) {
			$originalValue = self::get($filename);
			$originalValue[$key] = $value;
			$originalValue = json_encode($originalValue);
			if (strlen($originalValue) > self::MAX_DB_BYTES) {
				throw new \Exception("The value is more than 1024 bytes.");
			}
			$fileHandle = fopen($filepath, 'w');
			fwrite($fileHandle, $originalValue);
			fclose($fileHandle);
			return true;
		}
		return false;
	}

	/**
	 * Get the db filepath.
	 *
	 * @param $filename string 
	 * @return string
	 */
	private static function getFilePath($filename) 
	{
		$filename = APP_PATH . '/db/' . APP_VERSION . '/' . $filename;
		if (file_exists($filename)) {
			return $filename;
		} else {
			return "";
		}
	}
}
