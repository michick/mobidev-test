<?php
require_once 'CurlLite.php';

class GitHubApi{
	const REPO_SEARCH_URL = 'https://api.github.com/legacy/repos/search/';
	const REPO_DETAIL_URL = 'https://api.github.com/repos/';
	const REPO_CONTRIBUTORS = '/contributors';
	const USER_DETAIL_URL = 'https://api.github.com/users/';
	const AUTH_USER_URL = 'https://api.github.com/user';
	const NUMBER_GITHUB_SEARCH_OUTPUTS = 100;
	
	
	public function searchRepos($keywords = NULL, $start_page = NULL){
		if($keywords){
			$request_url = self::REPO_SEARCH_URL . rawurlencode($keywords);
			$ch = new CurlLite($request_url);
			$start_p = intval($start_page);
			if($start_p){
				$ch->prepareRequest("GET", array('start_page' => $start_p));
			}
			$res_js = $ch->execRequest();
			$res_array = json_decode($res_js, true);
			if(isset($res_array['repositories'])) return $res_array['repositories'];
			else return $res_array;
		}else{
			return false;
		}
	}

	public function getRepoDetails($owner = "yiisoft", $repo = "yii"){
		$request_url = self::REPO_DETAIL_URL . rawurlencode($owner) . '/' . rawurlencode($repo);
		$ch = new CurlLite($request_url);
		$res_js = $ch->execRequest();
		$result = json_decode($res_js, true);
		
		$request_url_contr = $request_url . self::REPO_CONTRIBUTORS;
		$ch->setopt(CURLOPT_URL, $request_url_contr);
		$contr_js = $ch->execRequest();
		$contr = json_decode($contr_js, true);
		
		$result['contributors'] = $contr;
		
		return $result;
	}

	public function getUserDetails($username){
		$request_url = self::USER_DETAIL_URL . rawurlencode($username);
		$ch = new CurlLite($request_url);
		$res_js = $ch->execRequest();
		$result = json_decode($res_js, true);
		
		return $result;
	}
	
	public function getCurrentUsername(){
		$request_url = self::AUTH_USER_URL;
		$ch = new CurlLite($request_url);
		$res_js = $ch->execRequest();		
		$result = json_decode($res_js, true);
		
		if(isset($result['login'])) return $result['login'];
		else return false;
	}
}
?>