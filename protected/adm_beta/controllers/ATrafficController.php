<?php

    class ATrafficController extends AController
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
         * Creates a new model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         */
        public function actionCreate()
        {
            $model = new ATraffic();
            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['ATraffic'])) {
                $model->attributes = $_POST['ATraffic'];
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

            if (isset($_POST['ATraffic'])) {
                $model->attributes = $_POST['ATraffic'];
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
            if (!isset($_POST['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }

        /**
         * Lists all models.
         */
        public function actionIndex()
        {
            $dataProvider = new CActiveDataProvider('ATraffic');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model        = new ATraffic();
            $model_search = new ATraffic();
            $model->unsetAttributes();  // clear any default values
            $model->scenario = $model_search->scenario = 'admin';

            $model_search->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
            $model_search->end_date   = date('d/m/Y');

            $show = FALSE;
            self::performAjaxValidation($model);
            if (isset($_POST['ATraffic'])) {
                $model->attributes        = $_POST['ATraffic'];
                $model_search->attributes = $_POST['ATraffic'];
                if ($_POST['ATraffic']['sale_office_code'] != '') {
                    $model->sale_office_code        = $_POST['ATraffic']['sale_office_code'];
                    $model->brand_offices_id        = $_POST['ATraffic']['brand_offices_id'];
                    $model_search->sale_office_code = $_POST['ATraffic']['sale_office_code'];
                    $model_search->brand_offices_id = $_POST['ATraffic']['brand_offices_id'];
                }
                if ($_POST['ATraffic']['start_date'] != '' && $_POST['ATraffic']['end_date'] != '') {
                    $show                     = TRUE;
                    $model_search->start_date = $_POST['ATraffic']['start_date'];
                    $model_search->end_date   = $_POST['ATraffic']['end_date'];
                    $model->status_assign     = $_POST['ATraffic']['status_assign'];
                    $model->start_date        = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['ATraffic']['start_date'])));
                    $model->end_date          = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['ATraffic']['end_date'])));
                }

                if (!$model->validate()) {
                    $model->getErrors();
                    $show = FALSE;
                }
            }
            if (isset($_GET['ATraffic'])) {
                $show              = TRUE;
                $model->attributes = $_GET['ATraffic'];
                if ($_GET['ATraffic']['start_date'] != '' && $_GET['ATraffic']['end_date'] != '') {
                    $model_search->start_date = $_GET['ATraffic']['start_date'];
                    $model_search->end_date   = $_GET['ATraffic']['end_date'];
                    $model->status_assign     = $_GET['ATraffic']['status_assign'];
                    $model->start_date        = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['ATraffic']['start_date'])));
                    $model->end_date          = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['ATraffic']['end_date'])));
                }
            }

            $this->render('admin', array(
                'model'        => $model,
                'model_search' => $model_search,
                'show'         => $show,
            ));
        }

        public function actionRenueveTraffic()
        {
            $model             = new ATraffic('search');
            $model_search      = new ATraffic('search');
            $data_overview     = array();
            $data_renueve_ship = array();
            $model->unsetAttributes();  // clear any default values
            $model->scenario = $model_search->scenario = 'renueve_traffic';
            $show            = FALSE;
            $post            = FALSE;

            $model_search->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
            $model_search->end_date   = date('d/m/Y');

            self::performAjaxValidation($model);
            if (isset($_POST['ATraffic'])) {
                $show = TRUE;
                $post = TRUE;

                $model->attributes        = $_POST['ATraffic'];
                $model_search->attributes = $_POST['ATraffic'];

                if ($_POST['ATraffic']['start_date'] != '' && $_POST['ATraffic']['end_date'] != '') {
                    $model_search->start_date = $_POST['ATraffic']['start_date'];
                    $model_search->end_date   = $_POST['ATraffic']['end_date'];
                    $model->status_assign     = $_POST['ATraffic']['status_assign'];
                    $model->start_date        = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['ATraffic']['start_date'])));
                    $model->end_date          = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['ATraffic']['end_date'])));

                    if ($model->status_shipper == '') {

                        for ($i = 1; $i <= 5; $i++) {
                            $data_key         = array(
                                'title'         => $model->getTitleRenueve($i),
                                'total'         => $model->getTotalRenueveByStatus($i)->total,
                                'total_renueve' => $model->getTotalRenueveByStatus($i)->total_renueve,
                                'package'       => $model->getTotalRenueveByStatus($i, TRUE)->total_renueve,
                            );
                            $data_overview [] = $data_key;
                        }
                        $data_ship = $model->getTotalRenueveByStatus('', FALSE, TRUE);
                    } else {
                        $data_overview = array();

                        $data_key = array(
                            'title'         => $model->getTitleRenueve($model->status_shipper),
                            'total'         => $model->getTotalRenueveByStatus($model->status_shipper)->total,
                            'total_renueve' => $model->getTotalRenueveByStatus($model->status_shipper)->total_renueve,
                            'package'       => $model->getTotalRenueveByStatus($model->status_shipper, TRUE)->total_renueve,
                        );

                        $data_overview[$model->status_shipper] = $data_key;
                        $data_ship                             = $model->getTotalRenueveByStatus($model->status_shipper, FALSE, TRUE);
                    }

                    if ($data_ship) {
                        $data_renueve_ship = array(
                            'title'        => 'Tổng phí vận chuyển',
                            'renueve_ship' => isset($data_ship->total_renueve) ? $data_ship->total_renueve : 0,
                        );
//                        if (!empty($data_renueve_ship)) {
//                            $data_renueve_ship = new CArrayDataProvider($data_renueve_ship, array(
//                                'keyField' => FALSE,
//                            ));
//                        }
                    }

                    if (!empty($data_overview)) {
                        $data_overview = new CArrayDataProvider($data_overview, array(
                            'keyField' => FALSE,
                        ));
                    }
                }
                if (!$model->validate()) {
                    $model->getErrors();

                    $show = FALSE;
                    $post = FALSE;
                }
            }

            if (isset($_GET['ATraffic'])) {

                $show              = TRUE;
                $post              = TRUE;
                $model->attributes = $_GET['ATraffic'];
                if ($model->status_shipper == '') {

                    for ($i = 1; $i <= 5; $i++) {
                        $data_key         = array(
                            'title'         => $model->getTitleRenueve($i),
                            'total'         => $model->getTotalRenueveByStatus($i)->total,
                            'total_renueve' => $model->getTotalRenueveByStatus($i)->total_renueve,
                            'package'       => $model->getTotalRenueveByStatus(0, TRUE)->total_renueve,
                        );
                        $data_overview [] = $data_key;
                    }
                    $data_ship = $model->getTotalRenueveByStatus('', FALSE, TRUE);

                } else {

                    $data_key = array(
                        'title'         => $model->getTitleRenueve($model->status_shipper),
                        'total'         => $model->getTotalRenueveByStatus($model->status_shipper)->total,
                        'total_renueve' => $model->getTotalRenueveByStatus($model->status_shipper)->total_renueve,
                        'package'       => $model->getTotalRenueveByStatus($model->status_shipper, TRUE)->total_renueve,
                    );

                    $data_overview[$model->status_shipper] = $data_key;
                    $data_ship                             = $model->getTotalRenueveByStatus($model->status_shipper, FALSE, TRUE);

                }

                if ($data_ship) {
                    $data_renueve_ship = array(
                        'title'        => 'Tổng phí vận chuyển',
                        'renueve_ship' => isset($data_ship->total_renueve) ? $data_ship->total_renueve : 0,
                    );
//                    if (!empty($data_renueve_ship)) {
//                        $data_renueve_ship = new CArrayDataProvider($data_renueve_ship, array(
//                            'keyField' => FALSE,
//                        ));
//                    }
                }
                if (!empty($data_overview)) {
                    $data_overview = new CArrayDataProvider($data_overview, array(
                        'keyField' => FALSE,
                    ));
                }
                if ($_GET['ATraffic']['start_date'] != '' && $_GET['ATraffic']['end_date'] != '') {
                    $model_search->start_date = $_GET['ATraffic']['start_date'];
                    $model_search->end_date   = $_GET['ATraffic']['end_date'];
                    $model->status_assign     = $_GET['ATraffic']['status_assign'];
                    $model->start_date        = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['ATraffic']['start_date'])));
                    $model->end_date          = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['ATraffic']['end_date'])));
                }

            }
            $this->render('renueve_traffic', array(
                'model'             => $model,
                'model_search'      => $model_search,
                'show'              => $show,
                'post'              => $post,
                'data_overview'     => $data_overview,
                'data_renueve_ship' => $data_renueve_ship,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdminAssign()
        {
            $model        = new ATraffic();
            $model_search = new ATraffic();
            $model->unsetAttributes();  // clear any default values
            $model->scenario          = $model_search->scenario = 'admin_assign';
            $show                     = TRUE;
            $model_search->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
            $model_search->end_date   = date('d/m/Y');

            self::performAjaxValidation($model);
            if (isset($_POST['ATraffic'])) {
                $model->attributes        = $_POST['ATraffic'];
                $model_search->attributes = $_POST['ATraffic'];

                if ($_POST['ATraffic']['start_date'] != '' && $_POST['ATraffic']['end_date'] != '') {
                    if ($_POST['ATraffic']['sale_office_code'] != '') {
                        $model->sale_office_code        = $_POST['ATraffic']['sale_office_code'];
                        $model_search->sale_office_code = $_POST['ATraffic']['sale_office_code'];
                    }
                    $model_search->start_date = $_POST['ATraffic']['start_date'];
                    $model_search->end_date   = $_POST['ATraffic']['end_date'];
                    $model->status_assign     = $_POST['ATraffic']['status_assign'];
                    $model->start_date        = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['ATraffic']['start_date']))) . " 00:00:00";
                    $model->end_date          = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['ATraffic']['end_date']))) . " 23:59:59";
                }
                if (!$model->validate()) {
                    $model->getErrors();
                    $show = FALSE;
                }

            }
            if (isset($_REQUEST['ATraffic'])) {
                $model->attributes = $_REQUEST['ATraffic'];
                if ($_GET['ATraffic']['start_date'] != '' && $_REQUEST['ATraffic']['end_date'] != '') {
                    $model_search->start_date = $_REQUEST['ATraffic']['start_date'];
                    $model_search->end_date   = $_REQUEST['ATraffic']['end_date'];
                    $model->status_assign     = $_REQUEST['ATraffic']['status_assign'];
                    $model->start_date        = date("Y-m-d", strtotime(str_replace('/', '-', $_REQUEST['ATraffic']['start_date']))) . " 00:00:00";
                    $model->end_date          = date("Y-m-d", strtotime(str_replace('/', '-', $_REQUEST['ATraffic']['end_date']))) . " 23:59:59";
                }
            }

            $this->render('admin_assign', array(
                'model'        => $model,
                'model_search' => $model_search,
                'show'         => $show,
            ));
        }


        /**
         * Manages all models.
         */
        public function actionAdminAssignChange()
        {
            $model        = new ATraffic();
            $model_search = new ATraffic();
            $model->unsetAttributes();  // clear any default values
            $model->scenario = $model_search->scenario = 'admin_assign';

            $model_search->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
            $model_search->end_date   = date('d/m/Y');

            $show = FALSE;
            self::performAjaxValidation($model);
            if (isset($_POST['ATraffic'])) {
                $model->attributes        = $_POST['ATraffic'];
                $model_search->attributes = $_POST['ATraffic'];

                if ($_POST['ATraffic']['start_date'] != '' && $_POST['ATraffic']['end_date'] != '') {
                    if ($_POST['ATraffic']['sale_office_code'] != '') {
                        $model->sale_office_code        = $_POST['ATraffic']['sale_office_code'];
                        $model_search->sale_office_code = $_POST['ATraffic']['sale_office_code'];
                    }
                    $show                     = TRUE;
                    $model_search->start_date = $_POST['ATraffic']['start_date'];
                    $model_search->end_date   = $_POST['ATraffic']['end_date'];
                    $model->status_assign     = $_POST['ATraffic']['status_assign'];
                    $model->start_date        = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['ATraffic']['start_date']))) . " 00:00:00";
                    $model->end_date          = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['ATraffic']['end_date']))) . " 23:59:59";
                }

                if (!$model->validate()) {
                    $model->getErrors();
                    $show = FALSE;
                }

            }
            if (isset($_GET['ATraffic'])) {
                $model->attributes = $_GET['ATraffic'];
                $show              = TRUE;
                if ($_GET['ATraffic']['start_date'] != '' && $_GET['ATraffic']['end_date'] != '') {
                    $model_search->start_date = $_GET['ATraffic']['start_date'];
                    $model_search->end_date   = $_GET['ATraffic']['end_date'];
                    $model->status_assign     = $_GET['ATraffic']['status_assign'];
                    $model->start_date        = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['ATraffic']['start_date'])));
                    $model->end_date          = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['ATraffic']['end_date'])));
                }
            }
            $this->render('admin_assign_change', array(
                'model'        => $model,
                'model_search' => $model_search,
                'show'         => $show,
            ));
        }

        public function actionAdminReturn()
        {
            $model        = new ATraffic();
            $model_search = new ATraffic();
            $model->unsetAttributes();  // clear any default values
            $model->scenario = $model_search->scenario = 'admin_assign';
            $show            = TRUE;

            $model_search->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
            $model_search->end_date   = date('d/m/Y');

            self::performAjaxValidation($model);
            if (isset($_POST['ATraffic'])) {
                $model->attributes        = $_POST['ATraffic'];
                $model_search->attributes = $_POST['ATraffic'];
                if ($_POST['ATraffic']['sale_office_code'] != '') {
                    $model->sale_office_code        = $_POST['ATraffic']['sale_office_code'];
                    $model_search->sale_office_code = $_POST['ATraffic']['sale_office_code'];
                }
                if ($_POST['ATraffic']['start_date'] != '' && $_POST['ATraffic']['end_date'] != '') {
                    $model_search->start_date = $_POST['ATraffic']['start_date'];
                    $model_search->end_date   = $_POST['ATraffic']['end_date'];
                    $model->status_assign     = $_POST['ATraffic']['status_assign'];
                    $model->start_date        = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['ATraffic']['start_date'])));
                    $model->end_date          = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['ATraffic']['end_date'])));
                }

                if (!$model->validate()) {
                    $model->getErrors();
                    $show = FALSE;
                }

            }
            if (isset($_GET['ATraffic'])) {
                $model->attributes = $_GET['ATraffic'];
                if ($_GET['ATraffic']['start_date'] != '' && $_GET['ATraffic']['end_date'] != '') {
                    $model_search->start_date = $_GET['ATraffic']['start_date'];
                    $model_search->end_date   = $_GET['ATraffic']['end_date'];
                    $model->status_assign     = $_GET['ATraffic']['status_assign'];
                    $model->start_date        = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['ATraffic']['start_date'])));
                    $model->end_date          = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['ATraffic']['end_date'])));
                }
            }
            $this->render('admin_return', array(
                'model'        => $model,
                'model_search' => $model_search,
                'show'         => $show,
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
            $model = ATraffic::model()->findByPk($id);
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

        public function actionGetShipperByAddress()
        {
            $model        = new ATraffic('search');
            $model_search = new ATraffic('search');
            $show         = TRUE;
            if ($_GET['ATraffic']) {
                $id            = isset($_GET['ATraffic']['id']) ? $_GET['ATraffic']['id'] : '';
                $district_code = isset($_GET['ATraffic']['district_code']) ? $_GET['ATraffic']['district_code'] : '';
                $province_code = isset($_GET['ATraffic']['province_code']) ? $_GET['ATraffic']['province_code'] : '';
                $ward_code     = isset($_GET['ATraffic']['ward_code']) ? $_GET['ATraffic']['ward_code'] : '';
            } else {
                $ward_code     = Yii::app()->getRequest()->getParam("ward_code", FALSE);
                $id            = Yii::app()->getRequest()->getParam("id", FALSE);
                $district_code = Yii::app()->getRequest()->getParam("district_code", FALSE);
                $province_code = Yii::app()->getRequest()->getParam("province_code", FALSE);
                $type          = Yii::app()->getRequest()->getParam("type", '');
            }
            $criteria = new CDbCriteria();
            // Mức 1 theo phường xã
            $result = array();
            if ($id) {
                $sale_offices_id = ASaleOffices::model()->findByAttributes(array('ward_code' => $ward_code));

                $check = AOrders::model()->findByAttributes(array('id' => $id));
                if ($check) {
                    if ($check->sale_office_code != '') {
                        if ($check->shipper_id != '') {
                            $criteria->condition = "sale_offices_code ='" . $check->sale_office_code . "' and id !='" . $check->shipper_id . "'";
                        } else {
                            $criteria->condition = "sale_offices_code='" . $check->sale_office_code . "'";
                        }
                    }
                }
                $data = new CActiveDataProvider('Shipper', array(
                    'criteria'   => $criteria,
                    'pagination' => array(
                        'params'   => array(
                            "ATraffic[province_code]" => $province_code,
                            "ATraffic[district_code]" => $district_code,
                            "ATraffic[id]"            => $id,
                        ),
                        'pageSize' => 30,
                    ),
                ));
                if (isset($type)) {
                    $result = $this->renderPartial('_popup_assign_return_info', array('data' => $data, 'id' => $id));
                } else {
                    $result = $this->renderPartial('_popup_assign_info', array('data' => $data, 'id' => $id));
                }
            } // Mức 2 theo quận huyện
            if (!$_GET['ATraffic']) {
                return $result;
            } else {
                return $this->render('admin_assign', array(
                    'model'        => $model,
                    'model_search' => $model_search,
                    'show'         => $show,
                    'result'       => $result,
                    'id'           => $id,
                ));
            }

        }

        // Phân công shipper ở điều chuyển và phân công.
        public function actionAssignmentShipper()
        {
            $shipper_id = Yii::app()->getRequest()->getParam("shipper_id", FALSE);
            $order_id   = Yii::app()->getRequest()->getParam("order_id", FALSE);
            $email      = Yii::app()->getRequest()->getParam("email", FALSE);
            $result     = 0;

            if ($shipper_id && $order_id && $email) {

                $model        = AShipperOrder::model()->findByAttributes(array('order_id' => $order_id));
                $model_new    = AOrders::model()->findByAttributes(array('id' => $order_id));
                $model_detail = AOrderDetails::model()->findByAttributes(array('order_id' => $order_id, 'type' => 'price_ship'));
                if (!$model) {
                    $model              = new AShipperOrder();
                    $model->type_assign = AShipperOrder::ASSIGN;
                } else {
                    if ($model->shipper_id != $shipper_id) {
                        $model->type_assign = AShipperOrder::CANCEL;
                    }
                    $model->shipper_old = $model->shipper_id;
                }
                $model_new->shipper_id = $shipper_id;

                $model->shipper_id    = $shipper_id;
                $model->order_id      = $order_id;
                $model->assign_date   = date('Y-m-d H:i:s');
                $model->assign_by     = Yii::app()->user->id;
                $model->delivery_date = $model_new->delivery_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . '+2days'));
                $model->ship_cost     = isset($model_detail->price) ? $model_detail->price : 0;
                $model->last_update   = date('Y-m-d H:i:s');
                $model->email         = $email;
                $model->status        = ATraffic::NOT_SHIP;

                if ($model->validate()) {
                    if ($model->save() && $model_new->update()) {
                        $result = 1;
                    }
                }
            }
            echo $result;
        }

        public function actionAssignmentShipperReturn()
        {
            $shipper_id = Yii::app()->getRequest()->getParam("shipper_id", FALSE);
            $order_id   = Yii::app()->getRequest()->getParam("order_id", FALSE);
            $email      = Yii::app()->getRequest()->getParam("email", FALSE);
            $result     = 0;

            if ($shipper_id && $order_id && $email) {

                $model     = AShipperOrder::model()->findByAttributes(array('order_id' => $order_id));
                $model_new = AOrders::model()->findByAttributes(array('id' => $order_id));

                $model_detail = AOrderDetails::model()->findByAttributes(array('order_id' => $order_id, 'type' => 'price_ship'));
                if (!$model) {
                    $model              = new AShipperOrder();
                    $model->type_assign = AShipperOrder::ASSIGN;
                } else {
                    if ($model->shipper_id != $shipper_id) {
                        $model->type_assign = AShipperOrder::CANCEL;
                    }
                    $model->shipper_old = $model->shipper_id;
                }
                $model_new->shipper_id = $shipper_id;

                $model->shipper_id    = $shipper_id;
                $model->order_id      = $order_id;
                $model->assign_date   = date('Y-m-d H:i:s');
                $model->assign_by     = Yii::app()->user->id;
                $model->delivery_date = $model_new->delivery_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . '+2days'));
                $model->ship_cost     = isset($model_detail->price) ? $model_detail->price : 0;
                $model->last_update   = date('Y-m-d H:i:s');
                $model->email         = $email;
                $model->status        = ATraffic::NOT_SHIP;
                $model->order_status  = ATraffic::NOT_SHIP;


                if ($model->validate()) {
                    if ($model->save() && $model_new->update()) {
                        $model_state_old = AOrderState::model()->findByAttributes(array('order_id' => $order_id, 'confirm' => 3));
                        if ($model_state_old) {
                            $model_state = new AOrderState();

                            $model_state->order_id    = $order_id;
                            $model_state->confirm     = 10;
                            $model_state->paid        = 0;
                            $model_state->delivered   = 0;
                            $model_state->note        = "Phân công sau khi gửi trả";
                            $model_state->create_date = date('Y-m-d H:i:s');
                            $model_state->save();
                            $result = 1;
                        }
                        $result = 1;
                    }

                }
            }
            echo $result;
        }

        /**
         * Lấy danh sách quận huyện theo tỉnh
         */
        public function actionGetDistrictByProvice()
        {

            if (!SUPER_ADMIN && !ADMIN) {


                if (Yii::app()->user->id) {
                    $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                    if ($user) {
                        if ($user->district_code != "") {
                            $district = District::model()->findByAttributes(array('code' => $user->district_code));
                            if ($district) {
                                echo CHtml::tag('option', array('value' => $user->district_code), CHtml::encode($district->name), TRUE);
                            }
                        }
                    }
                }

            } else {
                $provice_code = Yii::app()->getRequest()->getParam("province_code", FALSE);
                if ($provice_code) {
                    $criteria = new CDbCriteria();

                    $criteria->condition = "province_code='" . $provice_code . "'";

                    $data   = District::model()->findAll($criteria);
                    $return = CHtml::listData($data, 'code', 'name');
                    echo "<option value=''>Chọn tất cả</option>";
                    foreach ($return as $k => $v) {
                        echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                    }
                }
            }
        }

        /**
         * Lấy danh sách quận huyện theo tỉnh
         */
        public function actionGetBrandOfficesByDistrict()
        {
            if (!SUPER_ADMIN && !ADMIN) {
                if (Yii::app()->user->id) {
                    $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                    if ($user) {
                        if ($user->brand_offices_id != "") {
                            $brand = BrandOffices::model()->findByAttributes(array('id' => $user->ward_code));
                            if ($brand) {
                                echo CHtml::tag('option', array('value' => $user->brand_offices_id), CHtml::encode($brand->name), TRUE);
                            }
                        }
                    }
                }
            } else {
                $district_code = Yii::app()->getRequest()->getParam("district_code", FALSE);
                if ($district_code) {
                    $criteria = new CDbCriteria();

                    $criteria->condition = "district_code='" . $district_code . "'";

                    $data   = BrandOffices::model()->findAll($criteria);
                    $return = CHtml::listData($data, 'id', 'name');
                    echo "<option value=''>Chọn tất cả</option>";
                    foreach ($return as $k => $v) {
                        echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                    }
                }
            }
        }

        public function actionGetBrandOfficeByWard()
        {
            $ward_code = Yii::app()->getRequest()->getParam("ward_code", FALSE);
            if ($ward_code) {
                $criteria = new CDbCriteria();

                $criteria->condition = "ward_code='" . $ward_code . "'";

                $data   = BrandOffices::model()->findAll($criteria);
                $return = CHtml::listData($data, 'id', 'name');
                echo "<option value=''>Chọn tất cả</option>";
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }
        }

        /**
         * Cập nhật trạng thái giao hàng.
         */
        public function actionShowPopupReceive()
        {
            $id     = $_POST['pk'];
            $status = $_POST['value'];

            $data = $this->renderPartial('_popup_confirm_received', array('id' => $id, 'status' => $status));
            echo $data;
            exit();
        }

        /**
         * Cập nhật trạng thái giao hàng.
         */
        public function actionChangeStatusTraffic()
        {

            $order_id = Yii::app()->getRequest()->getParam("order_id", FALSE);
            $status   = Yii::app()->getRequest()->getParam("status", FALSE);

            $model = AShipperOrder::model()->findByAttributes(array('order_id' => $order_id));
            $order = AOrders::model()->findByAttributes(array('id' => $order_id));

            if ($order) { // Nếu là đơn hàng tại nhà.
                if ($model) {
                    $model->last_update = date('Y-m-d H:i:s');
                    $model->status      = $status;
                    $model->update();
                }
                $order->receive_cash_by = Yii::app()->user->id;
//                $order->last_update   = Yii::app()->user->id;
                $order->receive_cash_date = date('Y-m-d H:i:s');
                if ($order->update()) {
                    echo TRUE;
                }
            }
        }

        public
        function actionGetShipperBySaleId()
        {
            $sale_offices_id     = Yii::app()->getRequest()->getParam("sale_offices_code", FALSE);
            $criteria            = new CDbCriteria();
            $criteria->condition = "sale_offices_code='" . $sale_offices_id . "'";

            $data = AShipper::model()->findAll($criteria);

            $return = CHtml::listData($data, 'id', 'username');
            echo "<option value=''>Chọn người giao hàng</option>";
            foreach ($return as $k => $v) {
                echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
            }
        }

        /**
         * Láy danh sách người đại diện
         */
        public
        function actionProxy()
        {
            $value    = Yii::app()->getRequest()->getParam("value", FALSE);
            $type     = Yii::app()->getRequest()->getParam("type", FALSE);
            $order_id = Yii::app()->getRequest()->getParam("order_id", FALSE);

            if ($value && $type && $order_id) {

                $model = new User();
                if ($type == AOrders::SALEOFFICE_PERSION) {
                    $model->sale_offices_id = $value;
                } else {
                    $model->brand_offices_id = $value;
                }
                $data = $this->renderPartial('_show_proxy',
                    array('model' => $model, 'value' => $value, 'type' => $type, 'order_id' => $order_id)
                );

                echo $data;
                exit();
            }
        }

    }
