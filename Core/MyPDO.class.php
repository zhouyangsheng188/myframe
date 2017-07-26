<?php
namespace Core;
//引入空间元素
use \PDO;
use \PDOException;

class MyPDO{
	private $type;
	private $host;
	private $port;
	private $user;
	private $pass;
	private $dbname;
	private $charset;
	private $pdo;//用来保存pdo对象

	//构造方法：初始化PDO
	public function __construct($pdoinfo=array()){
		//引入配置文件
		global $config;
		//初始化属性
		$this->type=isset($pdoinfo['type'])?$pdoinfo['type']:$config['type'];
		$this->host=isset($pdoinfo['host'])?$pdoinfo['host']:$config[$this->type]['host'];
		$this->port=isset($pdoinfo['port'])?$pdoinfo['port']:$config[$this->type]['port'];
		$this->user=isset($pdoinfo['user'])?$pdoinfo['user']:$config[$this->type]['user'];
		$this->pass=isset($pdoinfo['pass'])?$pdoinfo['pass']:$config[$this->type]['pass'];
		$this->dbname=isset($pdoinfo['dbname'])?$pdoinfo['dbname']:$config[$this->type]['dbname'];
		$this->charset=isset($pdoinfo['charset'])?$pdoinfo['charset']:$config[$this->type]['charset'];

		
		//建立连接，异常处理
		try{
			$this->pdo=new PDO("{$this->type}:host={$this->host};port={$this->port};dbname={$this->dbname};charset={$this->charset}",$this->user,$this->pass);
			//异常处理
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			//连接失败：开发过程中直接报错并终止，如果是生产环境中，应该写入到系统日志当中
			echo 'PDO初始化数据库连接失败！<br/>';
			echo '失败文件：'.$e->getFile().'<br/>';
			echo '失败行：'.$e->getLine().'<br/>';
			echo '失败原因：'.$e->getMessage();
			exit;
		}
	}

	/**
	 *写入数据
	 *@param1 string $sql,要执行的SQL写指令
	 *@teturn int,受影响的行数
	 */
	public function db_exec($sql){
		//调用PDO方法中的exec执行
		try{
			return $this->pdo->exec($sql);
		}catch(PDOException $e){
			echo 'SQL执行失败<br/>';
			echo '失败的SQL指令为：'.$sql.'<br/>';
			echo '失败行：'.$e->getLine().'<br/>';
			echo '失败原因：'.$e->getMessage().'<br/>';
			exit;
		}
	}

	/**
	 *读取数据
	 *@param1 string $sql,要执行的SQL指令
	 *@param2 bool $all=false
	 *@return array,返回一个数组
	 */
	private function db_query($sql,$all=false){
		try{
			$stmt=$this->pdo->query($sql);
			return $all ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetch(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			echo 'SQL执行失败<br/>';
			echo '失败的SQL指令为：'.$sql.'<br/>';
			echo '失败行：'.$e->getLine().'<br/>';
			echo '失败原因：'.$e->getMessage().'<br/>';
			exit;
		}
	}

    /**
     * 获取自增长ID
     * @return int,自增长ID
     */
    public function db_insertID(){
    	//调用PDO中的lastInsertId方法
    	return $this->pdo->lastInsertId();
    }
    
    /**
     * 读取一条数据
     * @param1 string $sql,要执行的SQL指令
     * @return array,返回一个一维数据
     */
    public function db_getOne($sql){
        return $this->db_query($sql);
    }

    /**
     * 读取多条数据
     * @param1 string $sql,要执行的SQL指令
     * @return array,返回一个二维数据
     */
    public function db_getAll($sql){
    	return $this->db_query($sql,true);
    }
}