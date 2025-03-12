<?php

    /**
     * Yii-User module
     *
     * @author  Mikhail Mangushev <mishamx@gmail.com>
     * @link    http://yii-user.googlecode.com/
     * @license http://www.opensource.org/licenses/bsd-license.php
     * @version $Id: UserModule.php 105 2011-02-16 13:05:56Z mishamx $
     */
    class UserModule extends CWebModule
    {
        /**
         * @var int
         * @desc items on page
         */
        public $user_page_size = 10;

        /**
         * @var int
         * @desc items on page
         */
        public $fields_page_size = 10;

        /**
         * @var string
         * @desc hash method (md5,sha1 or algo hash function http://www.php.net/manual/en/function.hash.php)
         */
        public $hash = 'md5';

        /**
         * @var boolean
         * @desc use email for activation user account
         */
        public $sendActivationMail = TRUE;

        /**
         * @var boolean
         * @desc allow auth for is not active user
         */
        public $loginNotActiv = FALSE;

        /**
         * @var boolean
         * @desc activate user on registration (only $sendActivationMail = false)
         */
        public $activeAfterRegister = FALSE;

        /**
         * @var boolean
         * @desc login after registration (need loginNotActiv or activeAfterRegister = true)
         */
        public $autoLogin = TRUE;

        public $registrationUrl     = array("/user/registration");
        public $recoveryUrl         = array("/user/recovery/recovery");
        public $loginUrl            = array("/user/login");
        public $loginUrlCskh        = array("/user/loginCskh");
        public $logoutUrl           = array("/user/logout");
        public $logoutCskhUrl       = array("/user/logoutCskh");
        public $profileUrl          = array("/user/profile");
        public $returnUrl           = array("/user/profile");
        public $returnLogoutUrl     = array("/user/login");
        public $returnLogoutCskhUrl = array("/user/loginCskh");
        public $fieldsMessage       = '';

        /**
         * @var array
         * @desc User model relation from other models
         * @see  http://www.yiiframework.com/doc/guide/database.arr
         */
        public $relations = array();

        /**
         * @var array
         * @desc Profile model relation from other models
         */
        public $profileRelations = array();

        /**
         * @var boolean
         */
        public $captcha = array('registration' => TRUE);

        /**
         * @var boolean
         */
        //public $cacheEnable = false;

        public $tableUsers         = '{{users}}';
        public $tableProfiles      = '{{profiles}}';
        public $tableProfileFields = '{{profiles_fields}}';

        static private $_user;
        static private $_admin;
        static private $_admins;

        /**
         * @var array
         * @desc Behaviors for models
         */
        public $componentBehaviors = array();

        public function init()
        {
            // this method is called when the module is being created
            // you may place code here to customize the module or the application

            // import the module-level models and components
            $this->setImport(array(
                'user.models.*',
                'user.components.*',
            ));
        }

        public function getBehaviorsFor($componentName)
        {
            if (isset($this->componentBehaviors[$componentName])) {
                return $this->componentBehaviors[$componentName];
            } else {
                return array();
            }
        }

        public function beforeControllerAction($controller, $action)
        {
            if (parent::beforeControllerAction($controller, $action)) {
                // this method is called before any module controller action is performed
                // you may place customized code here
                return TRUE;
            } else
                return FALSE;
        }

        /**
         * @param $str
         * @param $params
         * @param $dic
         *
         * @return string
         */
        public static function t($str = '', $params = array(), $dic = 'user')
        {
            return Yii::t("UserModule." . $dic, $str, $params);
        }

        /**
         * @return hash string.
         */
        public static function encrypting($string = "")
        {
            $hash = Yii::app()->getModule('user')->hash;
            if ($hash == "md5")
                return md5($string);
            if ($hash == "sha1")
                return sha1($string);
            else
                return hash($hash, $string);
        }

        /**
         * @param $place
         *
         * @return boolean
         */
        public static function doCaptcha($place = '')
        {
            if (!extension_loaded('gd'))
                return FALSE;
            if (in_array($place, Yii::app()->getModule('user')->captcha))
                return Yii::app()->getModule('user')->captcha[$place];

            return FALSE;
        }

        /**
         * Return admin status.
         *
         * @return boolean
         */
        public static function isAdmin()
        {
            if (Yii::app()->user->isGuest)
                return FALSE;
            else {
                if (!isset(self::$_admin)) {
                    if (self::user()->superuser)
                        self::$_admin = TRUE;
                    else
                        self::$_admin = FALSE;
                }

                return self::$_admin;
            }
        }

        /**
         * Return admins.
         *
         * @return array syperusers names
         */
        public static function getAdmins()
        {
            if (!self::$_admins) {
                $admins      = User::model()->active()->superuser()->findAll();
                $return_name = array();
                foreach ($admins as $admin)
                    array_push($return_name, $admin->username);
                self::$_admins = $return_name;
            }

            return self::$_admins;
        }

        /**
         * Send mail method
         */
        public static function sendMail($email, $subject, $message)
        {
            $adminEmail = Yii::app()->params['adminEmail'];
            $headers    = "MIME-Version: 1.0\r\nFrom: $adminEmail\r\nReply-To: $adminEmail\r\nContent-Type: text/html; charset=utf-8";
            $message    = wordwrap($message, 70);
            $message    = str_replace("\n.", "\n..", $message);

            return mail($email, '=?UTF-8?B?' . base64_encode($subject) . '?=', $message, $headers);
        }

        public static function sendEmail($from, $to, $subject, $short_desc, $content = '', $views_layout_path = 'web.views.site', &$error = '')
        {
            $mail = new YiiMailer();
            $mail->setLayoutPath($views_layout_path);
            $mail->setData(array('message' => $content, 'name' => $from, 'description' => $short_desc));

            $mail->setFrom(Yii::app()->params->sendEmail['username'], $from);
            $mail->setTo($to);
            $mail->setSubject($from . ' | ' . $subject);
            $mail->setSmtp(Yii::app()->params->sendEmail['host'], Yii::app()->params->sendEmail['port'], Yii::app()->params->sendEmail['type'], TRUE, Yii::app()->params->sendEmail['username'], Yii::app()->params->sendEmail['password']);

            if ($mail->send()) {// echo 'Email was sent';

                return TRUE;
            } else {
                //$error = $mail->getError();

                return FALSE;
            }
        }

        /**
         * Return safe user data.
         *
         * @param user id not required
         *
         * @return user object or false
         */
        public static function user($id = 0)
        {
            if ($id)
                return User::model()->active()->findbyPk($id);
            else {
                if (Yii::app()->user->isGuest) {
                    return FALSE;
                } else {
                    if (!self::$_user)
                        self::$_user = User::model()->active()->findbyPk(Yii::app()->user->id);

                    return self::$_user;
                }
            }
        }

        /**
         * Return safe user data.
         *
         * @param user id not required
         *
         * @return user object or false
         */
        public function users()
        {
            return User;
        }
    }
