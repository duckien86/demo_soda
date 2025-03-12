<?php

    class AChangesController extends AController
    {
        /**
         * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
         * using two-column layout. See 'protected/views/layouts/column2.php'.
         */
        public $layout        = '//layouts/column2';
        public $defaultAction = 'admin';

        /**
         * @return array action filters
         */
        public function filters()
        {
            return array(
//			'accessControl', // perform access control for CRUD operations
//			'postOnly + delete', // we only allow deletion via POST request
                'rights',
            );
        }

        /**
         * Specifies the access control rules.
         * This method is used by the 'accessControl' filter.
         *
         * @return array access control rules
         */
        public function accessRules()
        {
            return array(
                array('allow',  // allow all users to perform 'index' and 'view' actions
                    'actions' => array('index', 'view'),
                    'users'   => array('@'),
                ),
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                    'actions' => array('create', 'update'),
                    'users'   => array('admin'),
                ),
                array('allow', // allow admin user to perform 'admin' and 'delete' actions
                    'actions' => array('admin', 'delete'),
                    'users'   => array('admin'),
                ),
                array('deny',  // deny all users
                    'users' => array('*'),
                ),
            );
        }


        /**
         * Displays a particular model.
         *
         * @param integer $id the ID of the model to be displayed
         */
        public function actionView($id)
        {
            $order_state   = AOrderState::getListOrderState($id, TRUE, 30);//order history
            $order_detail  = AOrderState::getDetailOrder($id);
            $order_shipper = AOrders::getShipperDetail($id);
            $logs_sim      = ALogsSim::getLogs($id);
            $this->render('view', array(
                'model'         => $this->loadModel($id),
                'order_state'   => $order_state,
                'order_detail'  => $order_detail,
                'order_shipper' => $order_shipper,
                'logs_sim'      => $logs_sim,
            ));
        }


        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model        = new AOrders();
            $model_search = new AOrders();
            $model->unsetAttributes();  // clear any default values
            $model->scenario = $model_search->scenario = 'admin';

            $model_search->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
            $model_search->end_date   = date('d/m/Y');

            $show = FALSE;
            self::performAjaxValidation($model);
            if (isset($_POST['AOrders'])) {
                $model->attributes        = $_POST['AOrders'];
                $model_search->attributes = $_POST['AOrders'];
                if ($_POST['AOrders']['sale_office_code'] != '') {
                    $model->sale_office_code        = $_POST['AOrders']['sale_office_code'];
                    $model->brand_offices_id        = $_POST['AOrders']['brand_offices_id'];
                    $model_search->sale_office_code = $_POST['AOrders']['sale_office_code'];
                    $model_search->brand_offices_id = $_POST['AOrders']['brand_offices_id'];
                }
                if ($_POST['AOrders']['start_date'] != '' && $_POST['AOrders']['end_date'] != '') {
                    $show                     = TRUE;
                    $model_search->start_date = $_POST['AOrders']['start_date'];
                    $model_search->end_date   = $_POST['AOrders']['end_date'];
                    $model->start_date        = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['AOrders']['start_date'])));
                    $model->end_date          = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['AOrders']['end_date'])));
                }

                if (!$model->validate()) {
                    $model->getErrors();
                    $show = FALSE;
                }
            }
            if (isset($_GET['AOrders'])) {
                $show              = TRUE;
                $model->attributes = $_GET['AOrders'];
                if ($_GET['AOrders']['start_date'] != '' && $_GET['AOrders']['end_date'] != '') {
                    $model_search->start_date = $_GET['AOrders']['start_date'];
                    $model_search->end_date   = $_GET['AOrders']['end_date'];
                    $model->start_date        = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['AOrders']['start_date'])));
                    $model->end_date          = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['AOrders']['end_date'])));
                }
            }

            $this->render('admin', array(
                'model'        => $model,
                'model_search' => $model_search,
                'show'         => $show,
            ));
        }

        /**
         * Manages all models.
         */
        /**
         * Hiện popup tra mã xác thực.
         */
        public function actionShowForm()
        {

            $data     = array();
            $order_id = Yii::app()->request->getParam('order_id', FALSE);
            if ($order_id) {
                $model  = new AOrders();
                $orders = AOrders::model()->findByAttributes(array('id' => $order_id));
                if ($orders) {
                    $model->id            = $order_id;
                    $model->province_code = $orders->province_code;
                    $data                 = $this->renderPartial('_popup_change_form',
                        array(
                            'model' => $model
                        )
                    );
                }
            }
            echo $data;
            exit();
        }

        /**
         * Manages all models.
         */
        /**
         * Hiện popup tra mã xác thực.
         */
        public function actionChangeOrders()
        {

            $data             = array();
            $order_id         = Yii::app()->request->getParam('order_id', FALSE);
            $sale_office_code = Yii::app()->request->getParam('sale_office_code', FALSE);
            if ($order_id && $sale_office_code) {
                $orders = AOrders::model()->findByAttributes(array('id' => $order_id));
                if ($orders) {
                    $orders->sale_office_code = $sale_office_code;
                    if ($orders->update()) {
                        AOrders::sendSMS($order_id);
                        echo TRUE;
                    }
                }
            }
            echo TRUE;
            exit();
        }

        /**
         * Returns the data model based on the primary key given in the GET variable.
         * If the data model is not found, an HTTP exception will be raised.
         *
         * @param integer $id the ID of the model to be loaded
         *
         * @return AOrders the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = AOrders::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param AOrders $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'aorders-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }
    }
