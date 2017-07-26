<?php
//命名空间
namespace Core;

//判断权限
if(!defined("ACCESS")) header('location:../index.php');

//定义类
class App{
    //1，创建一个公共的静态方法以供入口文件调用
    public static function run(){
        self::initcharset();
        self::initDirConst();
        self::initSystem();
        self::initConfig();
        self::initURL();
        self::initAutoload();
        self::initDispatch();
    }

    //2,初始化字符集
    private static function initcharset(){
        header("Content-type:text/html;charset=utf-8");
    }

    //3,增加目录常量
    private static function initDirConst(){
        //echo __DIR__;
        define("ROOT_DIR",str_replace('Core','',str_replace('\\','/',__DIR__)));
        define("CORE_DIR",ROOT_DIR.'Core/');
        define("APP_DIR",ROOT_DIR.'App/');
        define("CONFIG_DIR",ROOT_DIR.'Config/');
        define("PUBLIC_DIR",ROOT_DIR.'Public/');
        define("UPLOAD_DIR",ROOT_DIR.'Upload/');
        define("VENDOR_DIR",ROOT_DIR.'Vendor/');
        //echo APP_DIR;
    }

    //4,设定系统控制
    private static function initSystem(){
        @ini_set('error_reporting',E_ALL);//错误级别控制，将显示所有错误
        @ini_set('display_errors',1);//是否显示错误，开发时打开，之后关闭
    }

    //5,设定配置文件
    private static function initConfig(){
        //全局化配置文件
        global $config;
        //加载配置文件：在Config目录下
        $config=include_once(CONFIG_DIR.'config.php') ;
    }

    //6,在APP类中初始化URL
    private static function initURL(){
        //获取三个数据
        $plat=isset($_REQUEST['p'])? $_REQUEST['p']:'Home';
        $module=isset($_REQUEST['m'])?$_REQUEST['m']:'Index';
        $action=isset($_REQUEST['a'])?$_REQUEST['a']:'Index';
        //这几个使用的比较多，定义常量方便使用
        define('PLAT',$plat);
        define('MODULE',$module);
        define('ACTION',$action);
    }

    //7,在APP类中初始化自动加载
    private static function initAutoload(){
        spl_autoload_register(array(__CLASS__,'loadCore'));//__CLASS__::loadCore()
        spl_autoload_register(array(__CLASS__,'loadController'));//注册只能传一个参数，所以传一个数组，系统自动转化为__CLASS__::loadCore()
        spl_autoload_register(array(__CLASS__,'loadModel'));
        spl_autoload_register(array(__CLASS__,'loadVendor'));
    }
    //1,增加多个方法，加载不同文件夹的类
    private static function loadCore($classname){
        //组合文件
        $file=CORE_DIR.basename($classname).'.class.php';
        if(is_file($file)){
            include_once $file;
        }
    }
    private static function loadVendor($classname){
        $file=VENDOR_DIR.basename($classname).'.class.php';
        if(is_file($file)){
            include_once $file;
        }
    }
    private static function loadController($classname){
        $file=APP_DIR.PLAT.'/Controller/'.basename($classname).'.class.php';
        if(is_file($file)){
            include_once $file;
        }
    }
    private static function loadModel($classname){
        $file=APP_DIR.'Model/'.basename($classname).'.class.php';
        if(is_file($file)){
            include_once $file;
        }
    }

    //8,实现分发控制器
    private static function initDispatch(){
        //找到控制器类，实例化调用方法
        //$module=MODULE;
        $action=ACTION;
        //补充空间
        //Home:Home\Controller;
        //Back:Back\Controller;
        $module="\\".PLAT."\\Controller\\".MODULE;

        //实例化
        $m=new $module();
        $m->$action();
    }
}