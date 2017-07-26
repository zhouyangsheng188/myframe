<?php
namespace Core;
//use 
class View{
	//属性：保存控制器需要模板解析的数据
	private $smarty;
    //构造方法：实例化Smarty
	public function __construct(){
		//引入Smarty
		include_once(VENDOR_DIR.'Smarty/Smarty.class.php');
        $this->smarty=new \Smarty();
        //smarty的设置
        $this->smarty->setTemplateDir(APP_DIR.PLAT.'/View/');
        $this->smarty->setCompileDir(APP_DIR.PLAT.'/View_c/');
	}
	//公开方法赋值
	public function assign($name,$value){
		$this->smarty->assign($name,$value);
	}
	//显示数据
	public function display($tpl){
		$this->smarty->display($tpl);
	}
}