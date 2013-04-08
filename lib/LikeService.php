<?
require_once 'LikeMapper.php';
class LikeService{
	private $USER_NAME;
	const TYPE_REPO = 'repo';
	const TYPE_USER = 'user';
	
	
	public function __construct($_user_name){
		$this->USER_NAME = $_user_name;
	}
	
	public function addLike($_type, $_owner, $_reponame = NULL){
		$type = $_type;
		$reponame = $_reponame;
		if($type == self::TYPE_USER) $reponame = NULL;
		else $type = self::TYPE_REPO;
		if(LikeMapper::getInstance()->addLike($type, $_owner, $reponame, $this->USER_NAME))
			return true;
		return false;
	}
	
	public function deleteLikeById($_like_id){
		return LikeMapper::getInstance()->deleteLikeById($_like_id);
	}
	
	public function getAllUserLikes($_type = self::TYPE_USER){
		$type = ($_type == self::TYPE_USER)? self::TYPE_USER : self::TYPE_REPO;
		//if($_type == self::TYPE_USER) $type = self::TYPE_USER;
		//else $type = self::TYPE_REPO;
		return LikeMapper::getInstance()->getAllUserLikes($type, $this->USER_NAME);
	}
	
	public static function checkIfLiked($_likes, $_type, $_owner, $_reponame = NULL){
		if(!$_likes) return false;
		$likes = $_likes;
		$type = $_type;
		$owner = $_owner;
		$reponame = $_reponame;
		if($type == self::TYPE_USER) $reponame = NULL;
		else $type = self::TYPE_REPO;
		$result = false;
		
		if($type == self::TYPE_USER){
			foreach($likes as $like){
				if($like['obj_type'] == $type && $like['owner'] == $owner){
					$result = true;
					break;
				}
			}
		}else{
			foreach($likes as $like){
				if($like['obj_type'] == $type && $like['owner'] == $owner && $like['reponame'] == $reponame){
					$result = true;
					break;
				}
			}
		}
		return $result;
	}
	
	public function deleteLike($_type, $_owner, $_reponame = NULL){
		$type = $_type;
		$reponame = $_reponame;
		if($type == self::TYPE_USER) $reponame = NULL;
		else $type = self::TYPE_REPO;
		if(LikeMapper::getInstance()->deleteLike($type, $_owner, $reponame, $this->USER_NAME))
			return true;
		return false;
	}
}
?>