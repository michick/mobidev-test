<?
require_once 'lib/DBConn.php';
class AbstractMapper{
	protected $CONNECTION;
	
	protected function __construct(){
		$this->CONNECTION = DBConn::getConnection();
	}
}
?>