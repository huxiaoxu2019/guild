<?php
/**
 * Guild - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Util;

class PHP
{

	/**
	 * Check the php file.
	 *
	 * @param $file string
	 * @return string
	 */
	public function check($file) {
		$cmd = "/usr/local/bin/php -l {$file}";
		$output = shell_exec($cmd);
		return  $output;
	}
}
