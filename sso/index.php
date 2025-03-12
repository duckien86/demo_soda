<?php
    die('website đang bảo trì');
    ini_set("session.cookie_httponly", "true");
    date_default_timezone_set('Asia/Saigon');
    session_start();

    // All(prject name,db connection...etc) config should be in "config/config.ini"
    error_reporting(E_ALL);
    $config_path              = dirname(__FILE__) . "/config/config.ini";
    $GLOBALS['config_common'] = parse_ini_file($config_path, true);


    $yii    = dirname(__FILE__) . $GLOBALS['config_common']['project']['framework'];
    $config = dirname(__FILE__) . '/protected/web/config/main.php';

    //error_reporting($GLOBALS['config_common']['debug_mode']['display_errors']);

    defined('YII_DEBUG') or define('YII_DEBUG', $GLOBALS['config_common']['debug_mode']['state']);

    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
    require_once($yii);
    Yii::createWebApplication($config)->run();
