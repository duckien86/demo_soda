<?php
    ini_set("session.cookie_httponly", "true");
    date_default_timezone_set('Asia/Saigon');

    // All(prject name,db connection...etc) config should be in "config/config.ini"
    $config_path              = dirname(__FILE__) . "/config/config.ini";
    $GLOBALS['config_common'] = parse_ini_file($config_path, TRUE);


    $yii    = dirname(__FILE__) . $GLOBALS['config_common']['project']['framework'];
    $config = dirname(__FILE__) . '/protected/web/config/main.php';

//    error_reporting($GLOBALS['config_common']['debug_mode']['display_errors']);
    error_reporting(0);
    defined('YII_DEBUG') or define('YII_DEBUG', $GLOBALS['config_common']['debug_mode']['state']);

    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

    if (YII_DEBUG) {
        $SERVER_HTTP_HOST = $_SERVER['HTTP_HOST'];
    } else {
        $SERVER_HTTP_HOST = 'freedoo.vnpt.vn';
    }
    defined('SERVER_HTTP_HOST') or define('SERVER_HTTP_HOST', $SERVER_HTTP_HOST);

    include 'protected/vendors/vendor/autoload.php';

    require_once($yii);
    Yii::createWebApplication($config)->run();
?>
