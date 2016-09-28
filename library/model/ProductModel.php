<?php
/**
 * Guild - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Model;

use Library\Util\Config;

class ProductModel
{
    /**
     * Get info.
     *
     * @return mixed
     */
    public function getInfo()
    {
        $result = Config::get("common.product");
        switch (VCS) 
        {
        case VCS_GIT :
            $result['vcs_url'] = $result['git_url'];
            break;
        case VCS_SVN :
            $result['vcs_url'] = $result['svn_url'];
            break;
        default :
            $result['vcs_url'] = $result['svn_url'];
            break;
        }
        return $result;
    }

    /**
     * Get info.
     *
     * @return string
     */
    public function getDescriptionInfo()
    {
        return Config::get("common.app.desc");
    }

    /**
     * Get gray info.
     *
     * @return string
     */
    public function getGrayInfo()
    {
        return Config::get('common.app.gray_desc');
    }

    /**
     * Get online successful info.
     *
     * @return string
     */
    public function getOnlineSucInfo()
    {
        return Config::get('common.app.online_suc_desc');
    }

    /**
     * Get online fail info.
     *
     * @return string
     */
    public function getOnlineFailInfo()
    {
        return Config::get('common.app.online_fail_desc');
    }
}
