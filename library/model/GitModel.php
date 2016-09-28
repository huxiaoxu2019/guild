<?php
/**
 * Guid - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Model;

use Library\Util\Config;

class GitModel
{
    /**
     * The git repository path.
     *
     * @var string
     */
    private $repository = null;

    /**
     * Constructor.
     *
     * @param $repository string
     */
    public function __construct($repository) 
    {
        $this->repository = $repository;
    }

    /**
     * Git pull with shell.
     *
     * @TODO change to use the extension method with php-git.
     *
     * @param $name string
     * @param $branch string
     * @return string
     */
    public function pull($name = 'origin', $branch = 'master') 
    {
        $hup = shell_exec("cd " . Config::get('common.product.cmd_path') . "; git pull {$name} {$branch} 2>&1");    
        return $hup;
    }

    /**
     * Git log.
     *
     * @param $number int 
     * @return array
     */
    public function logWithNameStatus($oldCommitHash, $newCommitHash = 'HEAD')
    {
        $hup = shell_exec("cd " . Config::get('common.product.cmd_path') . "; git log {$oldCommitHash}..{$newCommitHash} --name-status 2>&1");    
        $line_arr = explode("\n", $hup);
        $result = array();
        $length = count($line_arr);
        $current_commit = '';
        for ($i = 0; $i < $length; $i++) {
            if (strpos($line_arr[$i], 'commit') === 0) {
                $current_commit = trim(substr($line_arr[$i], 6));
                $result[$current_commit]['commit'] = $current_commit;
            } else {
                $this->setAttr($result[$current_commit], $line_arr[$i]);    
            }
        }
        return $result;
    }

    /**
     * Diff with name status parameter.
     *
     * @param $oldCommitHash string
     * @param $newCommitHash string
     * @return array
     */
    public function diffWithNameStatus($oldCommitHash, $newCommitHash)
    {
        $hup = shell_exec("cd " . Config::get('common.product.cmd_path') . "; git diff {$oldCommitHash} {$newCommitHash} --name-status 2>&1");    
        $line_arr = explode("\n", $hup);
        return $line_arr;
    }

    /**
     * Set attr.
     *
     * @param $source_arr array
     * @param $string string
     */
    private function setAttr(& $source_arr, $string) {
        $string = trim($string);
        if (!$string) {
            return ;
        }
        $pre = trim(substr($string, 0, 2));
        switch ($pre) {
        case 'M':
            $source_arr['diff'][] = $string;
            return;
            break;
        case 'D':
            $source_arr['diff'][] = $string;
            return;
            break;
        case 'A':
            $source_arr['diff'][] = $string;
            return;
            break;
        default:
            break;
        }
        $pre = explode(": ", $string);
        if (empty($pre[0])) {
            $pre[0] = '';
        }
        switch ($pre[0]) {
            case 'Merge':
                $source_arr['merge'] = trim($pre[1]);
                $commit_hash = explode(" ", $pre[1]);
                $source_arr['merge_diff'] = $this->diffWithNameStatus($commit_hash[0], $source_arr['commit']);
                return;
                break;
            case 'Author':
                $source_arr['author'] = trim($pre[1]);
                return;
                break;
            case 'Date':
                $source_arr['date'] = strtotime(trim($pre[1]));
                return;
                break;
            default:
                break;
        }
        if (empty($source_arr['message'])) {
            $source_arr['message'] = '';
        }
        $source_arr['message'][] = $string;
    }

    /**
     * Get the head commit hash.
     *
     * @return string
     */
    public function getHead()
    {
        $hup = shell_exec("cd " . Config::get('common.product.cmd_path') . "; git rev-parse HEAD 2>&1");    
        return trim($hup, "\n");
    }
}
