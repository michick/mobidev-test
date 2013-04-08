<?
require_once 'lib/LikeService.php';
require_once 'lib/GitHubApi.php';
DBConn::init();

$action = trim(strip_tags($_POST['action']));
$out = new stdClass;
$out->result = false;

$api = new GitHubApi();
$like_service = new LikeService($api->getCurrentUsername());

switch($action){
	case 'modify_like':
		$obj_type = trim(strip_tags($_POST['obj_type']));
		$owner = trim(strip_tags($_POST['owner']));
		if(isset($_POST['reponame'])) $reponame = trim(strip_tags($_POST['reponame']));
		else $reponame = false;
		$task = trim(strip_tags($_POST['task']));
		
		if($task == 'add_like'){
			$out->result = $like_service->addLike($obj_type, $owner, $reponame);
			if($out->result) $out->message = 'like_added';
		}elseif($task == 'del_like'){
			$out->result = $like_service->deleteLike($obj_type, $owner, $reponame);
			if($out->result) $out->message = 'like_deleted';
		}
		break;
		
	case 'get_more_search_results':
		$query = '';
		if(isset($_POST['query'])) $query = trim(strip_tags($_POST['query']));
		$start_page = 0;
		if(isset($_POST['start_page'])) $start_page = trim(strip_tags($_POST['start_page']));
		$start_page = intval($start_page);
		$new_repos = $api->searchRepos($query, $start_page);
		
		$out->next_page = 'none';
		$out->html = '';
		if($new_repos === false){
			$out->result = false;
		}elseif($new_repos){
			$out->result = true;
			$likes = $like_service->getAllUserLikes(LikeService::TYPE_REPO);
			if(count($new_repos) == GitHubApi::NUMBER_GITHUB_SEARCH_OUTPUTS) $out->next_page = $start_page + 1;
			$i = 0;
			$div = '';
			foreach($new_repos as $repo){
				$i++;
				if($i % 2) $div .= '<div  class="s_out">';
				else $div .= '<div  class="s_out odd">';
					$div .= '<div class="search_out"><h3><a href="/index.php?owner=' . $repo['username'] . '&repo=' . $repo['name'] .'">' . $repo['name'] . '</a></h3></div>';
					$div .= '<div class="search_out">';
						if($repo['homepage']) $div .= '<a href="'. $repo['homepage'] . '" class="dot">' . $repo['homepage'] . '</a>';
						else $div .= "&nbsp;";
					$div .= '</div>';
					$div .= '<div class="search_out">';
						$div .= '<a href="/user.php?user=' . $repo['owner']. '">'. $repo['owner'] . '</a>';
					$div .= '</div>';
					$div .= '<div class="sep_small"></div>';
					$div .= '<div class="search_out">Watchers: ' . $repo['watchers'] . '</div>';
					$div .= '<div class="search_out">Forks: ' . $repo['forks'] . '</div>';
					$div .= '<div class="search_out" style="text-align:right;">';
						$div .= '<button style="width:70px;" onclick="modifyLikes(this, \'' . LikeService::TYPE_REPO . "','" . $repo['owner'] . "', '" . $repo['name'] . '\');">';
						if(LikeService::checkIfLiked($likes, "repo", $repo['owner'], $repo['name'])) $div.= "UnLike";
						else $div .= "Like";
						$div .= '</button>';
					$div .= '</div>';
					$div .= '<div class="sep_small"></div>';
				$div .= '</div>';
			}
			$out->html = $div;
			
		}else{
			$out->result = true;
		}
		break;
}
echo json_encode($out);
?>
