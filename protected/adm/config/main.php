<?php
    $adm  = dirname(dirname(__FILE__));
    $base = dirname($adm);
    Yii::setPathOfAlias('adm', $adm);

    $baseArray = require($base . '/config/main.php');

    // This is the main Web application backend configuration. Any writable
    // CWebApplication properties can be configured here.
    $admArray = array(
        'basePath'          => $base,
        'preload'           => array('log',
            'yiibooster',
        ),
        'controllerPath'    => $adm . '/controllers',
        'viewPath'          => $adm . '/views',
        'runtimePath'       => $adm . '/runtime',
        'defaultController' => 'ASite',
        // autoloading model and component classes
        'import'            => array(
            'adm.models.*',
            'adm.components.*',
            'application.models.*',
            'application.components.*',
            'application.modules.user.UserModule',
            'application.modules.user.models.*',
            'application.modules.user.components.*',
            'application.modules.rights.*',
            'application.modules.rights.components.*',
            'application.extensions.*',
            'ext.YiiMailer.YiiMailer'
        ),
        'language'          => 'vi',
        'modules'           => array(
            'user' => array(
                'tableUsers'          => 'tbl_users',
                'tableProfiles'       => 'tbl_profiles',
                'tableProfileFields'  => 'tbl_profiles_fields',

                # encrypting method (php hash function)
                'hash'                => 'md5',

                # send activation email
                'sendActivationMail'  => FALSE,

                # allow access for non-activated users
                'loginNotActiv'       => FALSE,

                # activate user on registration (only sendActivationMail = false)
                'activeAfterRegister' => FALSE,

                # automatically login from registration
                'autoLogin'           => TRUE,

                # registration path
                'registrationUrl'     => array('/user/registration'),

                # recovery password path
                'recoveryUrl'         => array('/user/recovery'),

                # login form path
                'loginUrl'            => array('/user/login'),

                # page after login
                'returnUrl'           => array('/user/profile'),

                # page after logout
                'returnLogoutUrl'     => array('/user/login'),
            ),

            'rights' => array(
                'install'           => FALSE,
//                        'superuserName'=>'Admin', // Name of the role with super user privileges.
//                        'authenticatedName'=>'Authenticated',  // Name of the authenticated user role.
//                        'userIdColumn'=>'id', // Name of the user id column in the database.
//                        'userNameColumn'=>'username',  // Name of the user name column in the database.
                'enableBizRule'     => FALSE,  // Whether to enable authorization item business rules.
                'enableBizRuleData' => FALSE,   // Whether to enable data for business rules.
//                        'displayDescription'=>true,  // Whether to use item description instead of name.
//                        'flashSuccessKey'=>'RightsSuccess', // Key to use for setting success flash messages.
//                        'flashErrorKey'=>'RightsError', // Key to use for setting error flash messages.
//
//                        'baseUrl'=>'/rights', // Base URL for Rights. Change if module is nested.
                'layout'            => 'webroot.themes.gentelella.views.rights.layouts.main',  // Layout to use for displaying Rights.
                'appLayout'         => 'webroot.themes.gentelella.views.layouts.main', // Application layout.
                'cssFile'           => '/themes/gentelella/css/rights.css', // Style sheet file to use for Rights.
                'debug'             => FALSE,
            ),

            // uncomment the following to enable the Gii tool
            'gii'    => array(
                'class'          => 'system.gii.GiiModule',
                'password'       => '123456',
                'ipFilters'      => array('127.0.0.1', '::1', '10.2.0.*', '192.168.6.*'),
                'generatorPaths' => array('bootstrap.gii'),
            ),
        ),
        // application-level parameters that can be accessed
        'params'            => require(dirname(__FILE__) . '/params.php'),

        // application components
        'components'        => array(
            'user'        => array(
                'class'          => 'RWebUser',
                // enable cookie-based authentication
                'allowAutoLogin' => TRUE,
                'loginUrl'       => array('/user/login'),
            ),
            'authManager' => array(
                'class'           => 'RDbAuthManager',
                'connectionID'    => 'db',
                'defaultRoles'    => array('Authenticated'),
                'itemTable'       => 'tbl_authitem',
                'itemChildTable'  => 'tbl_authitemchild',
                'assignmentTable' => 'tbl_authassignment',
                'rightsTable'     => 'tbl_rights',
            ),

            'errorHandler' => array(
                // use 'site/error' action to display errors
                'errorAction' => 'aSite/error',
            ),
            'yiibooster'   => array(
                'class' => 'ext.yiibooster.components.Booster', // assuming you extracted bootstrap under extensions
            ),
            'file'         => array(
                'class' => 'application.extensions.file.CFile',
            ),
            'request'      => array(
                'class'                  => 'adm.components.HttpRequest',
                'enableCsrfValidation'   => TRUE,
                'enableCookieValidation' => TRUE,
            ), //en
            'ftp'          => array(
                'class'       => 'application.extensions.ftp.EFtpComponent',
                'host'        => '125.212.192.191',//'host'=>'10.2.0.107',
                'port'        => 21,
                'username'    => 'ftpvod',//'username'=>'ftpcentech',
                'password'    => 'Vod2k15@Centech185@Ihub',
                //'password'=>'123456',
                'ssl'         => FALSE,
                'timeout'     => 1200,
                'autoConnect' => TRUE,
            ),
            // uncomment the following to enable URLs in path-format
            'urlManager'   => array(
                'urlFormat' => 'get', // 'path' or 'get'
                'rules'     => array(),
            ),
        ),

        'theme' => 'gentelella',
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
                if (isset($array1[$key]) && isset($array2[$key]) && is_array($array1[$key]) && is_array($array2[$key]))
                    $retval[$key] = w3_array_union_recursive($array1[$key], $array2[$key]);
            }

            return $retval;
        }
    }
    return w3_array_union_recursive($admArray, $baseArray);
?>