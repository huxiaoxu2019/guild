<?php
/**
 * Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Model;

class GitModel
{
	/**
	 * The git repository object.
	 *
	 * @var object
	 */
	private $repository = null;

	/**
	 * Constructor.
	 *
	 * @param $repository string
	 */
	public function __construct($repository) 
	{
		$this->repository = git_repository_open($repository);
	}

	/**
	 * Walk.
	 */
	public function walk() 
	{
	}

	/**
	 * Diff.
	 *
	 * @param $oldTreeHash string
	 * @param $newTreeHash string
	 */
	public function diff($oldTreeHash = 'd66c937ff6f7b108a9fe5f4a371cf93957172fe6', $newTreeHash = 'b2240b6a1c7c228f117cdff32449ec8f72409cfe') 
	{
		$newTree = git_tree_lookup($this->repository, $newTreeHash);
		$oldTree = git_tree_lookup($this->repository, $oldTreeHash);
		$opts = git_diff_options_init();
		/*
		$opts['flags'] = 0;
		$opts['version'] = 1;
		$opts['context_lines'] = 2;
		$opts['interhunk_lines'] = 10;
		$opts['old_prefix'] = 'a';
		$opts['new_prefix'] = 'b';
		$opts['pathspec'] = array();
		$opts['max_size'] = -1;
		$opts['notify_cb'] = function() {
			echo "notiry_cb...\n";
		};
		$opts['notify_payload'] = null;
		 */
		$diff = git_diff_tree_to_tree($this->repository, $oldTree,  $newTree, $opts);
//		$diff = git_diff_tree_to_workdir($this->repository,  $newTree,  $opts);
		$p = array();
		git_diff_print($diff, 2, function($diff_delta, $diff_hunk, $diff_line, $payload){
			if ($diff_line['origin'] == "-" || $diff_line['origin'] == "+") {
				echo $diff_line['origin'];
			}   
			echo $diff_line['content'];
		}, $p);
	}

	/**
	 * Get commit info.
	 *
	 * @param $commitHash string
	 * @return array
	 */
	public function getCommitInfo($commitHash = '118731') 
	{
		$result= array();
//		$commit = git_commit_lookup($this->repository, $commitHash);	
		$commit = git_commit_lookup_prefix($this->repository, $commitHash, strlen($commitHash));	
		$result['author']  = git_commit_author($commit);
		$result['message'] = git_commit_message($commit);
		$result['tree_id'] = git_commit_tree_id($commit);
		$result['parent_count'] = git_commit_parentcount($commit);
		for ($i = 0; $i < $result['parent_count']; $i++) {
			$result['parent_id'][$i] = git_commit_parent_id($commit, $i);
		}
		$result['committer'] = git_commit_committer($commit);
		$result['time'] = git_commit_time($commit);
		$result['id'] = git_commit_id($commit);
		return $result;
	}
}
