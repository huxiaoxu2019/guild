<?php
/**
 * Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Util;

class Config
{	
	/**
	 * The cache for config variable.
	 *
	 * @var array
	 */
	private static $_config = array();

	/**
	 * Get config value.
	 *
	 * @param $path string
	 * @return mixed
	 */
	public static function get($path)
	{
		if (!isset(self::$_config[$path])) {
			$arr    = explode('.', $path);
			$conf   = parse_ini_file(APP_PATH . '/config/' . APP_VERSION . '/' . $arr[0] . '.ini', true);
			$length = count($arr);
			for ($i = 1; $i < $length; $i++) {
				if (isset($conf[$arr[$i]])) {
					$conf = $conf[$arr[$i]];
				} else {
					$conf = null;
					break;
				}
			}
			if (!isset($conf) || is_null($conf)) {
				throw new \Exception("The config key to read is invalid. path:" . var_export($path, true));
			}
			self::$_config[$path] =  $conf;
		}
		return self::$_config[$path];
	}
}
