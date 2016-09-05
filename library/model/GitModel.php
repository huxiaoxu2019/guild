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
	 * The git repository object.
	 *
	 * @var object
	 */
	private $repository = null;

	/**
	 * The last diff result.
	 *
	 * @var array
	 */
	private $lastDiff = array();

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
	 * Diff.
	 *
	 * @param $oldTreeHash string
	 * @param $newTreeHash string
	 * @format $format int such as: 
     *								GIT_DIFF_FORMAT_PATCH        = 1u < full git diff 
	 *								GIT_DIFF_FORMAT_PATCH_HEADER = 2u < just the file headers of patch 
	 *								GIT_DIFF_FORMAT_RAW          = 3u < like git diff --raw 
	 *								GIT_DIFF_FORMAT_NAME_ONLY    = 4u < like git diff --name-only
	 *								GIT_DIFF_FORMAT_NAME_STATUS  = 5u < like git diff --name-status 
	 * @return array
	 */
	public function diffTreeToTree($oldTreeHash, $newTreeHash, $format = GIT_DIFF_FORMAT_PATCH) 
	{
		$newTree = git_tree_lookup($this->repository, $newTreeHash);
		$oldTree = git_tree_lookup($this->repository, $oldTreeHash);
		$opts = git_diff_options_init();
		$result = array();
		$fcc = array();
		$this->lastDiff = array();
		$fci = function($diff_delta, $diff_hunk, $diff_line, $payload) {
			$result = '';
			if ($diff_line['origin'] == "-" || $diff_line['origin'] == "+") {
				$result = $diff_line['origin'];
			}   
			$result .= trim($diff_line['content'], "\n");
			$this->lastDiff[] = $result;
		};

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
		git_diff_print($diff, $format, $fci, $fcc);
		return $this->lastDiff;
	}

	/**
	 * Get commit info.
	 *
	 * @param $commitHash string
	 * @return array
	 */
	public function commitInfo($commitHash) 
	{
		$result= array();
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

	/**
	 * Get commit hash array through revwalk.
	 *
	 * @param $commitHash string
	 * @return array
	 */
	public function revwalk($commitHash)
	{
		$walker = git_revwalk_new($this->repository);
		$result = array();
		git_revwalk_push_range($walker, "{$commitHash}..HEAD");
		while ($id = git_revwalk_next($walker)) {
			$result[] = $id;
		}
		return $result;
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

	/*
	public function merge() 
	{
		cl_git_pass(git_reference_lookup(&their_ref, repo, "heads/master"));
		cl_git_pass(git_merge_head_from_ref(&their_head, repo, their_ref));
		cl_git_fail((error = git_merge(&result, repo, (const git_merge_head **)&their_head, 1, &opts)));
		$theirRef = git_reference_lookup($this->repository, 'refs/heads/master');
		$theirHead = git_merge_head_from_ref($this->repository, $theirRef);
		$rs = git_merge($this->repository, $theirHead, array());
		var_dump($rs);
	}
	*/
}
