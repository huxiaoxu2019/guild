<?php
/**
 * Guild - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Controller;

abstract class AbstractController 
{
	/**
	 * Smarty engine object.
	 *
	 * @var object
	 */
	protected $view = null;

	/**
     * Constructor.
     *
     * @TODO seprate the web and cli mode.
	 */
	public function __construct()
	{
        /* Smarty */
		$this->view = new \Smarty();
		$this->view->caching = false;
		$this->view->template_dir = SMARTY_TEMPLATE_DIR;
		$this->view->compile_dir = SMARTY_COMPILE_DIR;

		/* WEB */
		if (APP_MODE == 'WEB') {
            /* valid action name */
			$actionName = ACTION_NAME;
			if (!method_exists($this, ACTION_NAME)) {
				die('Unknown page.');
			}

            /* valid some constants */
            if (!check_app_version() || !check_build_version()) {
                die('Unvalid param.');
            }

            /* call method */
            $this->$actionName();
        }
	}

    /**
     * Redirect.
     *
     * @param $location string
     * @param $params array
     */
    public function redirect($location, $params) {
        if (APP_MODE != 'WEB') {
            return;
        }
        $location = $location . '?' . http_build_query($params);
        header("location:{$location}");
        exit;
    }
}
