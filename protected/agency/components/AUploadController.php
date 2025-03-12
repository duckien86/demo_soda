<?php

    class AUploadController extends RController
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
            $cs = Yii::app()->getClientScript();
            $cs->registerCssFile(Yii::app()->theme->baseUrl . '/css/mystyle.css');
            Yii::$classMap = array_merge(Yii::$classMap, array(
                'CaptchaExtendedAction'    => Yii::getPathOfAlias('ext.captchaExtended') . DIRECTORY_SEPARATOR . 'CaptchaExtendedAction.php',
                'CaptchaExtendedValidator' => Yii::getPathOfAlias('ext.captchaExtended') . DIRECTORY_SEPARATOR . 'CaptchaExtendedValidator.php'
            ));
        }
    }

?>