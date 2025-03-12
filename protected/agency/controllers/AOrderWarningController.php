<?php

    class AOrderWarningController extends Controller
    {
        /**
         * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
         * using two-column layout. See 'protected/views/layouts/column2.php'.
         */
        public $layout = '//layouts/column2';

        /**
         * @return array action filters
         */
        public function filters()
        {
            return array(
                'rights', // perform access control for CRUD operations
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
                    'users'   => array('*'),
                ),
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                    'actions' => array('create', 'update'),
                    'users'   => array('@'),
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
            $order         = AOrders::model()->findByAttributes(array('id' => $id));
            $this->render('view', array(
                'model'         => $order,
                'order_state'   => $order_state,
                'order_detail'  => $order_detail,
                'order_shipper' => $order_shipper,
                'logs_sim'      => $logs_sim,
            ));
        }

        /**
         * Creates a new model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         */
        public function actionCreate()
        {
            $model = new AOrderWarning;

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['AOrderWarning'])) {
                $model->attributes = $_POST['AOrderWarning'];
                if ($model->save())
                    $this->redirect(array('view', 'id' => $model->id));
            }

            $this->render('create', array(
                'model' => $model,
            ));
        }

        /**
         * Updates a particular model.
         * If update is successful, the browser will be redirected to the 'view' page.
         *
         * @param integer $id the ID of the model to be updated
         */
        public function actionUpdate($id)
        {
            $model = $this->loadModel($id);

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['AOrderWarning'])) {
                $model->attributes = $_POST['AOrderWarning'];
                if ($model->save())
                    $this->redirect(array('view', 'id' => $model->id));
            }

            $this->render('update', array(
                'model' => $model,
            ));
        }

        /**
         * Deletes a particular model.
         * If deletion is successful, the browser will be redirected to the 'admin' page.
         *
         * @param integer $id the ID of the model to be deleted
         */
        public function actionDelete($id)
        {
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }

        /**
         * Lists all models.
         */
        public function actionIndex()
        {
            $dataProvider = new CActiveDataProvider('AOrderWarning');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model                    = new AOrderWarning('search');
            $model_search             = new AOrderWarning('search');
            $model_search->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
            $model_search->end_date   = date('d/m/Y');
            $model->start_date        = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
            $model->end_date          = date('d/m/Y');
            $post                     = FALSE;
            $model->scenario          = "admin";
            $model->unsetAttributes();  // clear any default values
            if (isset($_GET['AOrderWarning'])) {
                $post              = TRUE;
                $model->attributes = $_GET['AOrderWarning'];
                if (isset($_GET['AOrderWarning']['province_code']) && $_GET['AOrderWarning']['province_code'] != '') {
                    $post = TRUE;

                    $model->province_code = $_GET['AOrderWarning']['province_code'];
                }
                if (isset($_GET['AOrderWarning']['sale_office_code']) && $_GET['AOrderWarning']['sale_office_code'] != '') {
                    $model->sale_office_code = $_GET['AOrderWarning']['sale_office_code'];

                    $post = TRUE;
                }
                if (isset($_GET['AOrderWarning']['start_date']) && $_GET['AOrderWarning']['start_date'] != '') {
                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['AOrderWarning']['start_date'])));
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['AOrderWarning']['end_date'])));
                } else {
                    $model->start_date = '';
                    $model->end_date   = '';
                }
                if (isset($_GET['AOrderWarning']['brand_offices_id']) && $_GET['AOrderWarning']['brand_offices_id'] != '') {
                    $model->brand_offices_id = $_GET['AOrderWarning']['brand_offices_id'];

                    $post = TRUE;
                }

            }
            if (isset($_POST['AOrderWarning'])) {
                $post              = TRUE;
                $model->attributes = $_POST['AOrderWarning'];
                if ($_POST['AOrderWarning']['start_date'] != '' && $_POST['AOrderWarning']['end_date'] != '') {

                    $model_search->attributes = $_POST['AOrderWarning'];
                    $model_search->start_date = $_POST['AOrderWarning']['start_date'];
                    $model_search->end_date   = $_POST['AOrderWarning']['end_date'];

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['AOrderWarning']['start_date'])));
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['AOrderWarning']['end_date'])));

                }

                $model->status_shipper = $model_search->status_shipper = $_POST['AOrderWarning']['status_shipper'];
                if (isset($_POST['AOrderWarning']['province_code']) && $_POST['AOrderWarning']['province_code'] != '') {
                    $model->province_code        = $_POST['AOrderWarning']['province_code'];
                    $model_search->province_code = $_POST['AOrderWarning']['province_code'];
                }
                if (isset($_POST['AOrderWarning']['sale_office_code']) && $_POST['AOrderWarning']['sale_office_code'] != '') {
                    $model->sale_office_code        = $_POST['AOrderWarning']['sale_office_code'];
                    $model_search->sale_office_code = $_POST['AOrderWarning']['sale_office_code'];
                }
                if (isset($_POST['AOrderWarning']['brand_offices_id']) && $_POST['AOrderWarning']['brand_offices_id'] != '') {
                    $model->brand_offices_id        = $_POST['AOrderWarning']['brand_offices_id'];
                    $model_search->brand_offices_id = $_POST['AOrderWarning']['brand_offices_id'];
                }

                if (!$model->validate()) {
                    $model->getErrors();
                }
            }


            $this->render('admin', array(
                'model'        => $model,
                'model_search' => $model_search,
                'post'         => $post,
            ));
        }

        /**
         * Returns the data model based on the primary key given in the GET variable.
         * If the data model is not found, an HTTP exception will be raised.
         *
         * @param integer $id the ID of the model to be loaded
         *
         * @return AOrderWarning the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = AOrderWarning::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param AOrderWarning $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'aorder-warning-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }
    }
