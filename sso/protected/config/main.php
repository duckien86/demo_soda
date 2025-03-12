<?php
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
    return array(
        'basePath'   => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
        'name'       => $GLOBALS['config_common']['project']['name'],
        'language'   => 'en',
        // autoloading model and component classes
        'import'     => array(
            'application.models.*',
            'application.components.*',
            'application.extensions.*',
        ),
        'modules'    => array(),
        // appl ication components
        'components' => array(
            'request'    => array(
                'enableCsrfValidation'   => TRUE,
                'enableCookieValidation' => TRUE,
            ), //en
            // user config
            'user'       => array(
                // enable cookie-based authentication
                'allowAutoLogin' => TRUE,
                'identityCookie' => array(
                    'httpOnly' => TRUE,
                )
            ),
            // uncomment the following to enable URLs in path-format
            'urlManager' => array(
                'urlFormat' => 'get', // 'path' or 'get'
                'rules'     => array(),
            ),
            // uncomment the following to use a MySQL database
            'db'         => array(
                'connectionString'      => $GLOBALS['config_common']['db']['connectionString'],
                'username'              => $GLOBALS['config_common']['db']['username'],
                'password'              => $GLOBALS['config_common']['db']['password'],
                'tablePrefix'           => $GLOBALS['config_common']['db']['tablePrefix'],
                'emulatePrepare'        => TRUE,
                'enableProfiling'       => TRUE,
                'enableParamLogging'    => TRUE,
                'charset'               => 'utf8',
                'schemaCachingDuration' => '3600',
            ),

            'errorHandler' => array(
                // use 'site/error' action to display errors
                'errorAction' => 'aSite/error',
            ),
//            'cache'        => array(
//                'class'   => 'CMemCache',
//                'servers' => array(
//                    array(
//                        'host'   => 'localhost',
//                        'port'   => 11211,
//                        'weight' => 60,
//                    )
////                    array(
////                        'host'=>'server2',
////                        'port'=>11211,
////                        'weight'=>40,
////                    ),
//                ),
//            ),
//            'cache'=>array(
//                'class'=>'ext.redis.CRedisCache',
//                'hostname'=>'localhost',
//                'port'=>6379,
//                'database'=>0,
//                'options'=>STREAM_CLIENT_CONNECT,
//            ),
//            'cache'        => array(
//                'class'    => 'CRedisCache',
//                'hostname' => 'localhost',
//                'port'     => 6379,
//                'database' => 0,
//            ),
            'cache'=>array(
                'class'=>'system.caching.CDbCache'
            ),
            'log'          => array(
                'class' => 'CLogRouter',
                'routes' => array(
                    array(
                        'class'  => 'CFileLogRoute',
                        'levels' => 'error, warning',
                    ),
                    // uncomment the following to show log messages on web pages
                    /*
                    array(
                        'class'=>'CWebLogRoute',
                    ),
                    */
                ),
            ),
        ),
        // application-level parameters that can be accessed
        // using Yii::app()->params['paramName']
        'params'     => require(dirname(__FILE__) . '/params.php'),
    );