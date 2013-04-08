<?
class DBConn{
	
	const host = 'localhost';
	const db_name = 'githublikes';
	const user = '';
	const pass = '';
	
	static private $INSTANCE = NULL;
	private $PDO;
	
	private function __construct(){
		$this->PDO = new PDO('mysql:host='.self::host.';dbname='.self::db_name, self::user, self::pass);
		$this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$this->PDO->query('SET NAMES utf8');
		
		//$this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	}
	
	static public function init(){
		
		if (self::$INSTANCE == NULL){
			self::$INSTANCE = new self();
		}else{
			throw new Exception("DBConn already init!");
		}
	}
	
	static public function getConnection(){
		if (self::$INSTANCE == NULL){
			throw new Exception('DBConn not init');
		}
		return self::$INSTANCE->PDO;
	}
}
?>