<?php
date_default_timezone_set("PRC");

$yii=dirname(__FILE__).'/../YiiFramework/yiilite.php';
$config=dirname(__FILE__).'/config.php';

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

require_once($yii);
Yii::createWebApplication($config)->run();
