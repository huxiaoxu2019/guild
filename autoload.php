<?php
/**
 * Guild - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

header("content-type:text/html;charset=utf-8");

date_default_timezone_set("Asia/Shanghai");

ini_set("magic_quotes_runtime",0); 

require_once APP_PATH . '/library/thirdparty/PHPMailer.php';
require_once APP_PATH . '/library/thirdparty/SMTP.php';
require_once APP_PATH . '/library/thirdparty/smarty/libs/Smarty.class.php';

if (!defined("APP_PATH")) {
    die("no right to access the script.");
}

/* 自动加载类库 */
if (PHP_VERSION >= '5.3.0') {
    spl_autoload_register(function ($class) {
        $pathArr = explode("\\", $class);
        $path     = APP_PATH;
        $length  = count($pathArr);
        for ($i = 0; $i < $length - 1; $i++) {
            $path .= DIRECTORY_SEPARATOR . lcfirst($pathArr[$i]); 
        }
        $file    = $path . DIRECTORY_SEPARATOR . $pathArr[$length - 1] . ".php"; 
        if (file_exists($file)) {
            require_once $file;
        } else {
            if (APP_MODE == 'WEB') {
                die('Unknown page.');
            }
        }
    });
} else {
    die("PHP版本小于5.3.0");
}

/* 自动加载函数库 */
$funcFile = APP_PATH . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'function' . DIRECTORY_SEPARATOR . 'common.php';
if (file_exists($funcFile)) {
    require_once $funcFile;
}
