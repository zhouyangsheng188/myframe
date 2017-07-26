<?php
namespace Core;
use \Core\MyPDO;

class Model{
	//属性：保存DAO对象
	protected $dao;
	//构造方法：数据库的连接认证
	public function __construct(){
         //连接认证
		$this->dao=new MyPDO();
	}
	//通过ID获取一条记录
	protected function getDataById($id){
		 $sql="select * from {$this->table} where id ={$id}";
		 return $this->dao->db_getOne($sql);
	}
}