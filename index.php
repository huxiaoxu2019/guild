<?php
/**
 * Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Controller;

include 'define.php';
include 'autoload.php';

$controller_name = "Library\\Controller\\" . CONTROLLER_NAME . 'Controller';
$controller = new $controller_name();
