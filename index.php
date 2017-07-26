<?php
//1,定义入口安全口令
define('ACCESS',true);

//2,引入初始化文件：类
include_once('Core/App.class.php');

//3,触发类工作，通常初始化类是静态资源
\Core\App::run();