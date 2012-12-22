<?php
header('Content-type: text/html; charset=utf-8');
session_start();
if(is_file('dump/site.stop') && ((!isset($_SESSION['X3.User.role']) && strpos($_SERVER['REQUEST_URI'],'/admin')===false && strpos($_SERVER['REQUEST_URI'],'/login')===false) || (isset($_SESSION['X3.User.role']) && $_SESSION['X3.User.role']!='admin' && $_SESSION['X3.User.role']!='root'))){
//    $time = date('i') + ;
    echo "<h1>Сайт временно недоступен. Ведутся технические работы.</h1>";
    echo "<h2>Просим прощения за временные неудобства.</h2>";
//    echo "<h3>Сайт должен заработать через:$time минут</h3>";
    echo "<span style='color:#fff'>{$_SERVER['REMOTE_ADDR']}</span>";
    exit;
}
// change the following paths if necessary
$x3=dirname(__FILE__).'/application/x3framework/X3.php';
$config=dirname(__FILE__).'/application/config/main.php';
// remove the following lines when in production mode
//define('X3_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
#defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
ini_set('display_errors', true);
error_reporting(E_ALL & ~E_NOTICE);
require_once($x3);
$app = X3::init($config);
$app->run();
?>