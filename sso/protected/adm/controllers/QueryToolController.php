<?php

    class QueryToolController extends Controller
    {
        private $msisdn_pre = '84';

        public function init()
        {

            parent::init();
            $this->defaultAction = 'index';
            $this->pageTitle     = 'Query Builder';
        }

        public function accessRules()
        {
            return array(
                array('allow',  // allow all users to perform 'index' and 'view' actions
                    'actions' => array('index', 'view'),
                    'users'   => array('@'),
                ),
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                    'actions' => array('create', 'update', 'admin'),
                    'users'   => array('@'),
                ),
                array('allow', // allow admin user to perform 'admin' and 'delete' actions
                    'actions' => array('delete'),
                    'users'   => array('admin'),
                ),
                array('deny',  // deny all users
                    'users' => array('*'),
                ),
            );
        }

        public function filters()
        {
            return array(
                'accessControl', // perform access control for CRUD operations
            );
        }

        public function actionIndex()
        {
            $dataProvider = array();
            $option = Yii::app()->session['option'] = Yii::app()->request->getParam('option', 1);
            $db_type                                = Yii::app()->request->getParam('db_type', 'oracle');
            $op_limit                               = Yii::app()->request->getParam('oplimit', '40');
            if ($option)

                if (isset($_REQUEST['post']) || Yii::app()->request->isAjaxRequest) {
                    if (!Yii::app()->request->isAjaxRequest) {
                        Yii::app()->session['query_string'] = Yii::app()->request->getParam('query_string', '');

                    }
                    $result       = array();
                    $result       = Yii::app()->db->createCommand(Yii::app()->session['query_string'])->queryAll();
                    $dataProvider = new CArrayDataProvider($result, array(
                        'id'         => 'user',
                        'sort'       => array(
                            'attributes' => array(
                                'id', 'username', 'email',
                            ),
                        ),
                        'keyField'   => FALSE,
                        'pagination' => ($op_limit == 'unlimit') ? FALSE : array(
                            'pageSize' => 40,
                        ),
                    ));
                }
            $this->render('index', array(
                'dataProvider' => $dataProvider,
                'option'       => $option,
                'db_type'      => $db_type,
                'op_limit'     => $op_limit,
            ));

        }

    }

?>