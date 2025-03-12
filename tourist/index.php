<?php
    // All(prject name,db connection...etc) config should be in "config/config.ini"
    $config_path              = dirname(dirname(__FILE__)) . "/config/config.ini";
    $GLOBALS['config_common'] = parse_ini_file($config_path, TRUE);

    $yii    = dirname(dirname(__FILE__)) . $GLOBALS['config_common']['project']['framework'];
    $config = dirname(__FILE__) . '/../protected/tourist/config/main.php';
    ini_set('display_errors', $GLOBALS['config_common']['debug_mode']['display_errors']);

    defined('YII_DEBUG') or define('YII_DEBUG', $GLOBALS['config_common']['debug_mode']['state']);

    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

    if (YII_DEBUG) {
        $SERVER_HTTP_HOST = $_SERVER['HTTP_HOST'];
    } else {
        $SERVER_HTTP_HOST = 'freedoo.vnpt.vn';
    }
    defined('SERVER_HTTP_HOST') or define('SERVER_HTTP_HOST', $SERVER_HTTP_HOST);


    require_once($yii);
    Yii::createWebApplication($config)->run();
?>
