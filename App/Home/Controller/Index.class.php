<?php
namespace Home\Controller;
//引入空间文件
use \Core\Controller;

class Index extends Controller{
    //入口方法
    public function index(){
        echo '欢迎使用myFrame自定义框架！';
    }
}