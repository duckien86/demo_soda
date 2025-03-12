<?php

    /**
     * Controller is the customized base controller class.
     * All controller classes for this application should extend from this base class.
     */
    class Controller extends RController
    {
        /**
         * @var string the default layout for the controller view. Defaults to '//layouts/column1',
         * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
         */
        public $layout      = '//layouts/column1';
        public $menu        = array();
        public $breadcrumbs = array();
        public $group_id;
        public $username;
        public $pageHint    = '';

        public function init()
        {
            Yii::$classMap = array_merge(Yii::$classMap, array(
                'CaptchaExtendedAction'    => Yii::getPathOfAlias('ext.captchaExtended') . DIRECTORY_SEPARATOR . 'CaptchaExtendedAction.php',
                'CaptchaExtendedValidator' => Yii::getPathOfAlias('ext.captchaExtended') . DIRECTORY_SEPARATOR . 'CaptchaExtendedValidator.php'
            ));
        }
    }