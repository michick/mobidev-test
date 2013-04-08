<?
require_once 'AbstractMapper.php';

class LikeMapper extends AbstractMapper{
	
	protected function __construct(){
		parent::__construct();
	}
	
	public static function getInstance(){
		return new self();
	}
	
	public function addLike($_type, $_owner, $_reponame, $_username){
		$stmt = $this->CONNECTION->prepare("INSERT INTO likes (username, obj_type, owner, reponame) VALUES(:username, :obj_type, :owner, :reponame)");
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->bindParam(':obj_type', $obj_type, PDO::PARAM_STR);
		$stmt->bindParam(':owner', $owner, PDO::PARAM_STR);
		$stmt->bindParam(':reponame', $reponame, PDO::PARAM_STR);
		
		$username = $_username;
		$obj_type = $_type;
		$owner = $_owner;
		$reponame = $_reponame;
		
		$stmt->execute();
		
		return true;
	}
	
	public function deleteLikeById($_like_id){
		$stmt = $this->CONNECTION->prepare("DELETE FROM likes WHERE id=:like_id");
		$stmt->bindParam(':like_id', $like_id, PDO::PARAM_STR);
		$like_id = $_like_id;
		$stmt->execute();
		return true;
	}
	
	public function getAllUserLikes($_type, $_username){
		$stmt = $this->CONNECTION->prepare("SELECT * FROM likes WHERE username=:username AND obj_type=:type");
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->bindParam(':type', $type, PDO::PARAM_STR);
		$username = $_username;
		$type = $_type;
		$stmt->execute();
		
		$rows = $stmt->fetchall();
		if($rows) return $rows;
		else return false;
	}
	
	public function deleteLike($_type, $_owner, $_reponame, $_username){
		if($_reponame){
			$stmt = $this->CONNECTION->prepare("DELETE FROM likes WHERE username=:username AND obj_type=:obj_type AND owner=:owner AND reponame=:reponame");
			$stmt->bindParam(':reponame', $reponame, PDO::PARAM_STR);
			$reponame = $_reponame;
		}else{
			$stmt = $this->CONNECTION->prepare("DELETE FROM likes WHERE username=:username AND obj_type=:obj_type AND owner=:owner");
		}
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->bindParam(':obj_type', $obj_type, PDO::PARAM_STR);
		$stmt->bindParam(':owner', $owner, PDO::PARAM_STR);
		
		$username = $_username;
		$obj_type = $_type;
		$owner = $_owner;
		
		$stmt->execute();
		
		return true;
	}
}
?>