<?php
    $web  = dirname(dirname(__FILE__));
    $base = dirname($web);
    Yii::setPathOfAlias('web', $web);
    $baseArray = require($base . '/config/main.php');
    $webArray  = array(
        'basePath'          => $base,
        'preload'           => array(
            'log',
            'yiibooster',
            'YiiMailer',
        ),
        'controllerPath'    => $web . '/controllers',
        'viewPath'          => $web . '/views',
        'runtimePath'       => $web . '/runtime',
        'defaultController' => 'site',
        'import'            => array(
            'web.models.*',
            'web.components.*',
            'application.models.*',
            'application.components.*',
            'application.extensions.*',
            'application.extensions.*',
            'application.extensions.loadConfigXML.*',
            'web.components.MSISDN_DETECT.*',
            'ext.YiiMailer.YiiMailer',
        ),
        'language'          => 'vi',
        'params'            => require(dirname(__FILE__) . '/params.php'),
        'components'        => array(
            'errorHandler' => array(
                'errorAction' => 'site/error',
            ),
            'request'      => array(
                'enableCsrfValidation'   => TRUE,
                'enableCookieValidation' => TRUE,
                'class'                  => 'HttpRequest',
                'noCsrfValidationRoutes' => array( // config mang nhung action ko can check csrf
                    'apisgw/createUser',
                ),
            ), //en

            'yiibooster' => array(
                'class' => 'ext.yiibooster.components.Booster', // assuming you extracted bootstrap under extensions
            ),
            'user'       => array(
                'class'          => 'WebUser',
                // enable cookie-based authentication
                'allowAutoLogin' => TRUE,
                'identityCookie' => array(
                    'httpOnly' => TRUE,
                )
            ),
            'bootstrap'  => array(
                'class' => 'ext.bootstrap.components.Bootstrap', // assuming you extracted bootstrap under extensions
            ),
            // uncomment the following to enable URLs in path-format
            'urlManager' => array(
                'urlFormat'      => 'path', // 'path' or 'get'
                'showScriptName' => FALSE, // show index.php
                'caseSensitive'  => FALSE, // case sensitive
//                'urlSuffix'      => '.html',
                'rules'          => array(

                    'login/<pid:.*?>'                                                 => 'site/login',
                    'register/<pid:.*?>'                                              => 'site/register',
                    'test'                                                            => 'site/test',
                    'forgetpass/<pid:.*?>'                                            => 'site/forgetPassword',
                    'changepass/<pid:.*?>'                                            => 'site/changePassword',
                    'changeforgetpass/<pid:.*?>/<data:.*?>'                           => 'site/changeForgetPass',
                    'changestatus'                                                    => 'site/changeStatusUser',
                    'otp/<pid:.*?>'                                                   => 'site/otp',
                    'testApiRegister'                                                 => 'site/testApiRegister',
                    'apiRegister/<pid:.*?>'                                           => 'site/apiRegister',
                    'updateinfo/<pid:.*?>'                                            => 'site/updateInfo',
                    'apisgw/create-user'                                              => 'apisgw/createUser',
                    'genKeyCtvNull'                                                   => 'site/genKeyCtvNull',
                    'updateType/<pid:.*?>'                                            => 'site/updateType',
                    'testupdateType'                                                  => 'site/testupdateType',
                    'fixInviterCode'                                                  => 'site/fixInviterCode',
                    'checkAttExist/<attributes:.*?>/<values:.*?>'                     => 'site/checkAttExist',
                    'checkAttExistUpdate/<attributes:.*?>/<values:.*?>/<user_id:.*?>' => 'site/checkAttExistUpdate',
                    'createUserSSO'                                                   => 'site/createUserSSO',
                    'createUserSSOINS'                                                => 'site/createUserSSOINS',
                    'login-api/<data:.*?>'                                            => 'site/loginApi',
                    'test-login-api'                                                  => 'site/testLoginApi',
                    '<controller:\w+>/<action:\w+>'                                   => '<controller>/<action>',

                ),
            ),
        ),

        'theme' => 'oneId',
    );
    if (!function_exists('w3_array_union_recursive')) {
        /**
         * This function does similar work to $array1+$array2,
         * except that this union is applied recursively.
         *
         * @param array $array1 - more important array
         * @param array $array2 - values of this array get overwritten
         *
         * @return array
         */
        function w3_array_union_recursive($array1, $array2)
        {
            $retval = $array1 + $array2;
            foreach ($array1 as $key => $value) {
                if (isset($array2[$key]) && isset($array2[$key]) && is_array($array1[$key]) && is_array($array2[$key])) {
                    $retval[$key] = w3_array_union_recursive($array1[$key], $array2[$key]);
                }
            }

            return $retval;
        }

    }

    return w3_array_union_recursive($webArray, $baseArray);
?>        
    
