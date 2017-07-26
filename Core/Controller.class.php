<?php
namespace Core;
use \Core\View;

class Controller{
    //实例化视图
    protected $view;
    public function __construct(){
        $this->view=new View();
    }
	//公共方法
    //操作成功时
    protected function success($msg,$url,$time=1){
        header("Refresh:{$time};url='index.php?{$url}'");
        echo $msg;
        exit();
    }
    //操作失败时
    protected function error($msg,$url,$time=3){
    	header("Refresh:{$time};url='index.php?{$url}'");
    	echo $msg;
    	exit();
    }
}