<?php

    class AController extends RController
    {

        public $layout      = '//layouts/column1';
        public $menu        = array();
        public $breadcrumbs = array();
        public $group_id;
        public $username;
        public $pageHint    = '';


        //public $channel_code = array();

        public function init()
        {
//            if (Yii::app()->user->id) {
//                $user                           = new ASystemUser;
//                $userInfo                       = $user->getSystemUserById(Yii::app()->user->id); //var_dump($userInfo->cp_code);exit();
//                $this->group_id                 = $userInfo->group_id;
//                $this->username                 = $userInfo->username;
//                Yii::app()->session['group_id'] = $this->group_id;
//                Yii::app()->session['username'] = $this->username;
//            }
            $cs = Yii::app()->getClientScript();
            $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery-ui.min.js');
            $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/global.js');

            Yii::$classMap = array_merge(Yii::$classMap, array(
                'CaptchaExtendedAction'    => Yii::getPathOfAlias('ext.captchaExtended') . DIRECTORY_SEPARATOR . 'CaptchaExtendedAction.php',
                'CaptchaExtendedValidator' => Yii::getPathOfAlias('ext.captchaExtended') . DIRECTORY_SEPARATOR . 'CaptchaExtendedValidator.php'
            ));
            if (!isset($_SERVER['HTTP_X_FORWARDED_PROTO'])){
                $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

                $this->redirect($redirect);
            }
        }


    }

?>