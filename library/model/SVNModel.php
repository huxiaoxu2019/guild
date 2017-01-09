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

class SVNModel
{
    /**
     * Svn info.
     *
     * @var array
     */
    private static $_svnInfo = array();

    /**
     * Current SVN version.
     *
     * @var string
     */
    private static $_curVer = '';

    /**
     * Get info.
     *
     * @return array
     */
    public function getInfo()
    {
        return $this->getLog($this->getCurVer(), $this->getLastVer());
    }

    /**
     * Get current version.
     *
     * @return string
     */
    public function getCurVer()
    {
        if (!isset(self::$_curVer) || empty(self::$_curVer)) {
            $svnInfo = $this->getSVNInfo();
            foreach ($svnInfo as $value) {
                if (strpos($value, "Last Changed Rev") === 0) {
                    $item = explode(":", $value);
                    self::$_curVer = trim($item[1]); 
                    break;
                }
            }
            if(!isset(self::$_curVer)) {
                die("Get cur version failed. At library\model\SVNmodel.");
            }
        }
        return self::$_curVer;
    }

    /**
     * Get last version.
     *
     * @return string
     */
    public function getLastVer()
    {
        $file = APP_PATH . '/db/' . APP_NAME . '/' . 'svn';
        $handler = fopen($file, "r");
        $version = null;
        while (!feof($handler)) {
            $line = fgets($handler);
            $item = explode(":", $line);
            if ($item[0] == "last_version") {
                $version = trim($item[1]);
            }    
        }    
        fclose($handler);
        if (isset($version)) {
            return $version;
        } else {
            throw new \Exception("Not found the last_version in db/svn file.");
        }
    }

    /**
     * Set last version.
     *
     * @param $version string
     * @return bool
     */
    public function setLastVer($version = null)
    {
        if (!isset($version)) {
            throw new \Exception("Unset version var.");
        }
        $file = APP_PATH . '/db/' . APP_NAME . '/' . 'svn';
        $handler = fopen($file, "r+");
        $content = "";
        while (!feof($handler)) {
            $line = fgets($handler);
            $item = explode(":", $line);
            if ($item[0] == "last_version") {
                $line = "last_version:{$version}";
            }
            $content .= $line . "\n";
        }
        $content = trim($content);
        fseek($handler, 0);
        fwrite($handler, $content);
        fclose($handler);
        return true;
    }

    /**
     * Get SVN info.
     *
     * @return array
     */
    public function getSVNInfo()
    {
        if (!isset(self::$_svnInfo) || empty(self::$_svnInfo)) {
            $cmd = "cd " . Config::get("common.product.cmd_path")  . ";/usr/bin/svn info";
            $output = shell_exec($cmd);
            $output = explode("\n", $output);
            if (!empty($output)) {
                self::$_svnInfo = $output;
            } else {
                die("Get svn info failed. At library\model\SVNmodel.");
            }
        }
        return self::$_svnInfo;
    }

    /**
     * Get diff with param.
     *
     * @param $nv string
     * @param $ov string
     * @param $filename string
     * @param $withSummarize bool
     * @param $withXML bool
     * @return string
     */
    public function getDiffWithParam($nv, $ov, $filename = null, $withSummarize = false, $withXML = false)
    {
        if (!isset($filename)) {
            $filename = "";
        }
        $paramStr = "";
        if ($withSummarize === true) {
            $paramStr .= " --summarize ";
        }
        if ($withXML === true) {
            $paramStr .= " --xml ";
        }
        $cmd = "cd " . Config::get("common.product.cmd_path") . ";/usr/bin/svn diff -r{$nv}:{$ov} {$filename} {$paramStr}";
        return shell_exec($cmd);
    }

    /**
     * Get diff xml
     * 
     * @param $ov string
     * @param $nv string
     * @return object
     */
    public function getDiffXml($ov, $nv)
    {
        if (!isset($ov) || !isset($nv)) { 
            die("Getdiff parameters is invalid. At library\model\SVNmodel.");
        }
        $cmd = "cd " . Config::get("common.product.cmd_path") . ";/usr/bin/svn diff -r{$ov}:{$nv} --summarize --xml";
        return simplexml_load_string(shell_exec($cmd));
    }

    /**
     * Get diff.
     *
     * @param $ov string
     * @param $nv string
     * @return array
     */
    public function getDiff($ov, $nv)
    {
        if (!isset($ov) || !isset($nv)) {
            die("Getdiff parameters is invalid. At library\model\SVNmodel.");
        }
        $cmd = "cd " . Config::get("common.product.cmd_path") . " ;/usr/bin/svn diff -r{$ov}:{$nv} --summarize --xml";
        $output = shell_exec($cmd);
        $output = simplexml_load_string($output);
        $output = json_decode(json_encode($output), true);
        return $output;    
    }

    /**
     * Get log.
     *
     * @param $ov string 
     * @param $nv string 
     * @return array
     */
    public function getLog($ov, $nv)
    {
        if (!isset($ov) || !isset($nv)) {
            die("Getlog parameters is invalid. At library\model\SVNmodel."); 
        }
        $cmd = "cd " . Config::get("common.product.cmd_path") . ";/usr/bin/svn log -r{$ov}:{$nv} --xml";
        $output = shell_exec($cmd);
        $output = simplexml_load_string($output);
        $output = json_decode(json_encode($output), true);
        return $output;
    }

    /**
     * SVN up.
     *
     * @return string
     */
    public function svnUp()
    {
        $cmd = "cd " . Config::get("common.product.cmd_path") . ";/usr/bin/svn up --ignore-externals";
        return shell_exec($cmd);    
    }

    /**
     * SVN up build.
     *
     * @return string
     */
    public function svnUpBuild()
    {
        $cmd = "cd " . Config::get("common.product.cmd_path") . ";/usr/bin/svn up --ignore-externals";
        return shell_exec($cmd);
    }

    /**
     * SVN up environment.
     *
     * @return string
     */
    public function svnUpEnv()
    {
        $cmd = "cd " . Config::get("common.product.drop_path") . ";/usr/bin/svn up --ignore-externals";
        return shell_exec($cmd);
    }
}
