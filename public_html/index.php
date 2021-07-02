<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
ini_set('error_reporting', E_ALL);
ini_set('date.timezone', 'Europe/Moscow');

// change the following paths if necessary
$yii = dirname(__FILE__) . '/framework/yii.php';
$config = dirname(__FILE__) . '/protected/config/main.php';
$YII_BOOTSTRAP_CLIENT = false;
//defined('YII_BOOTSTRAP_CLIENT') or define('YII_BOOTSTRAP_CLIENT', false);
// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

require_once($yii);
Yii::createWebApplication($config);
Yii::app()->onBeginRequest = array('LilyModule', 'initModule');
Yii::app()->run();
//Yii::createWebApplication($config)->run();

