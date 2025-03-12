<?php

    class SearchOrdersController extends Controller
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

            return $this->render('view', array(
                'model'         => $this->loadModel($id),
                'order_state'   => $order_state,
                'order_detail'  => $order_detail,
                'order_shipper' => $order_shipper,
            ));
        }


        /**
         * Creates a new model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         */
        public function actionCreate()
        {
            $model = new AOrders;

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['AOrders'])) {
                $model->attributes = $_POST['AOrders'];
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

            if (isset($_POST['AOrders'])) {
                $model->attributes = $_POST['AOrders'];
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
            $dataProvider = new CActiveDataProvider('AOrders');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model       = new AOrders();
            $data_output = $data_title = $data_output_detail = $order_detail = $data_history = array();
            $post        = 0;
            if (isset($_POST['AOrders']) || $_GET['AOrders']) {
                $post = 1;
                if (!isset($_POST['AOrders'])) {
                    $_POST['AOrders'] = $_GET['AOrders'];
                }
                $confirm = $paid = $delivered = '3';

                $model->order_search = $_POST['AOrders']['order_search'];
                //Nếu tiêu chí là số điện thoại.
                if (isset($_POST['AOrders']['input_type'])) {

                    if (isset($_POST['AOrders']['status_state'])) {
                        $model->status_state = $_POST['AOrders']['status_state'];
                        if ($_POST['AOrders']['status_state'] == 'confirm') {
                            $confirm = '10';
                        }
                        if ($_POST['AOrders']['status_state'] == 'paid') {
                            $paid = '10';
                        }
                        if ($_POST['AOrders']['status_state'] == 'delivered') {
                            $delivered = '10';
                        }
                    }
                    $model->input_type = $_POST['AOrders']['input_type'];
                    if ($_POST['AOrders']['input_type'] == 'phone_contact') {
                        $phone_number = $_POST['AOrders']['order_search'];
                        $data_input   = array(
                            "listArea"    => array(array("province_code" => "", "district_code" => "")),
                            "states"      => array("confirm" => $confirm, "paid" => $paid, "delivered" => $delivered),
                            "owner"       => array("sso_id" => "", "phone_contact" => $phone_number),
                            "create_date" => "",
                            "end_date"    => "",
                            "shipper_id"  => "",
                            "order_id"    => ""
                        );

                        //call api lấy thông tin đơn hàng của khách hàng.
                        $data_output = $model->getListOrder($data_input);
                        if (!empty($data_output)) {
                            $data_output = new CArrayDataProvider($data_output, array(
                                'keyField'   => FALSE,
                                'pagination' => array(
                                    'params'   => array(
                                        "AOrders[input_type]"   => $_POST['AOrders']['input_type'],
                                        "AOrders[status_state]" => $_POST['AOrders']['status_state'],
                                        "AOrders[order_search]" => $_POST['AOrders']['order_search'],
                                    ),
                                    'pageSize' => 10,
                                ),
                            ));
                        }

                        //Dữ liệu đầu vào lấy thông tin đơn hàng
                        return $this->render('customer_history', array(
                            'model' => $model,
                            'data'  => $data_output,
                        ));
                    }

                    //Nếu tiêu chí là mã đơn hàng.
                    if ($_POST['AOrders']['input_type'] == 'order_id') {

                        $order_id = $_POST['AOrders']['order_search'];
                        //Call api lịch sử đơn hàng theo order_id
                        $data_input_detail  = array(
                            'order_id' => $_POST['AOrders']['order_search'],
                        );
                        $data_output_detail = $model->getOrderDetail($data_input_detail);
                        if (isset($data_output_detail['order_detail'])) {
                            $order_detail = $data_output_detail['order_detail'];

                            if (!empty($order_detail)) {
                                $order_detail = new CArrayDataProvider($order_detail, array(
                                    'keyField' => FALSE,
                                ));
                            }
                        }
                        if (isset($data_output_detail['order_states'])) {
                            //Lấy lịch sử đơn hàng (giả lập)
                            $data_history   = AOrderState::getListOrderState($_POST['CskhOrders']['order_search'], TRUE, 30);//order history
                        }

                    }

                    //Nếu tiêu chí là mã đơn hàng.
                    if ($_POST['AOrders']['input_type'] == 'sim') {

                        $sim = $_POST['AOrders']['order_search'];
                        //Call api lịch sử đơn hàng theo order_id
                        $data_output = $model->getListOrderBySim($sim);
                        if (!empty($data_output)) {
                            $data_output = new CArrayDataProvider($data_output, array(
                                'keyField' => FALSE,
                            ));

                            return $this->render('customer_history', array(
                                'model' => $model,
                                'data'  => $data_output,
                            ));

                        }
                    }
                }
            }

            return $this->render('admin', array(
                'model'              => $model,
                'data'               => $data_output,
                'data_title'         => $data_title,
                'data_output_detail' => $data_output_detail,
                'order_detail'       => $order_detail,
                'data_history'       => $data_history,
                'post'               => $post,
            ));
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
                throw new CHttpException(404, 'The requested page does not exist . ');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param AOrders $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'cskh - orders - form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }
    }
