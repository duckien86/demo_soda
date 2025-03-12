<?php

    class AClearCacheController extends AController
    {
        public function __construct($id, $module = NULL)
        {
            parent::__construct($id, $module);
            $this->menu = array();

        }

        public function init()
        {
            parent::init();
            if (Yii::app()->user->isGuest) {
                $this->redirect($this->createUrl('/user/login'));
            }
        }

        /**
         * @return array action filters
         */
        public function filters()
        {
            return CMap::mergeArray(parent::filters(),array(
//			'accessControl', // perform access control for CRUD operations
                'rights', // perform access control for CRUD operations
            ));
        }

        /**
         * Specifies the access control rules.
         * This method is used by the 'accessControl' filter.
         * @return array access control rules
         */
        public function accessRules() {
            return array(
                array('allow', // allow all users to perform 'index' and 'view' actions
                    'actions' => array('index', 'view'),
                    'users' => array('*'),
                ),
                array('deny', // deny all users
                    'users' => array('*'),
                ),
            );
        }

        /**
         * The function that do clear Cache
         *
         */
        public function actionIndex()
        {
            $this->render('index');
        }


    }