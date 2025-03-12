<?php

    class AShipperController extends AController
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
                'rights', // we only allow deletion via POST request
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
            return array();
        }

        /**
         * Displays a particular model.
         *
         * @param integer $id the ID of the model to be displayed
         */
        public function actionView($id, $start_date = '', $end_date = '')
        {
            $pagination         = FALSE;
            $data               = array();
            $total_renueve_date = $total_order = $start_date = $end_date = '';
            if ((isset($_GET['start_date']) && isset($_GET['end_date']))) {
                $start_date = $_GET['start_date'];
                $end_date   = $_GET['end_date'];
                $pagination = TRUE;

                $model_detail     = new AOrders();
                $criteria         = new CDbCriteria();
                $criteria->select = "sum(od.price) as total_renueve_date, count(distinct t.order_id) as total_order";
                if ($start_date && $end_date) {
                    $start_date          = date("Y-m-d 00:00:00", strtotime(str_replace('/', '-', $start_date)));
                    $end_date            = date("Y-m-d 23:59:59", strtotime(str_replace('/', '-', $end_date)));
                    $criteria->condition = "t.shipper_id='$id' and t.assign_date>='$start_date' and assign_date <= '$end_date'";
                } else {
                    $criteria->condition = "t.shipper_id='" . $id . "'";
                }
                $criteria->join = "INNER JOIN {{order_details}} od ON od.order_id =t.order_id";
                //Lấy tổng doanh thu theo thời gian của shipper.
                $total = AShipperOrder::model()->findAll($criteria)[0];
                //Lấy tổng số đơn hàng của shipper theo thời gian

                if ($total) {
                    $total_order        = $total->total_order;
                    $total_renueve_date = $total->total_renueve_date;
                }
                $data = $model_detail->search_detail($start_date, $end_date, $id);

            }
            $model             = $this->loadModel($id);
            $model->start_date = $start_date;
            $model->end_date   = $end_date;

            return $this->render('view', array(
                'model'              => $model,
                'data'               => $data,
                'total_renueve_date' => $total_renueve_date,
                'total_order'        => $total_order,
                'start_date'         => $start_date,
                'end_date'           => $end_date,
            ));
        }

        /**
         * Creates a new model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         */
        public function actionCreate()
        {
            $model = new AShipper();

            $model->scenario = "create";
            // Uncomment the following line if AJAX validation is needed
            $this->performAjaxValidation($model);
            if (isset($_POST['AShipper']) && !$_POST['ajax']) {
                $model->attributes = $_POST['AShipper'];
                $model->id         = md5(date('Y-m-d H:i:s'));
                $password          = $model->password;
                if ($model->validate()) {

                    $model->password = md5($model->password);
                    if ($model->save()) {
                        $model->sendSMS($model->username, $password);
                        $this->redirect(array('admin'));
                    }
                }
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
            $model           = $this->loadModel($id);
            $model->scenario = "update";
            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);
//            self::performAjaxValidation($model);
            if (isset($_POST['AShipper']) && !$_POST['ajax']) {
                $model->attributes = $_POST['AShipper'];
                if ($model->validate()) {
                    if ($model->update()) {
                        $this->redirect(array('admin'));
                    }
                }
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
            $dataProvider = new CActiveDataProvider('AShipper');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model        = new AShipper('search');
            $model_search = new AShipper();

            $data_overview = array();

            $model_search->scenario = "admin";
            $model->unsetAttributes();  // clear any default values
            $show = 0;
            $post = FALSE;


            if (isset($_GET['AShipper'])) {
                $show = 1;

                $model->attributes        = $_GET['AShipper'];
                $model_search->attributes = $_GET['AShipper'];

                if ($_GET['AShipper']['start_date'] != '' && $_GET['AShipper']['end_date'] != '') {
                    $model_search->start_date = $_GET['AShipper']['start_date'];
                    $model_search->end_date   = $_GET['AShipper']['end_date'];

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['AShipper']['start_date']))) . " 00:00:00";
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['AShipper']['end_date']))) . " 23:59:59";
                }
                $model->status_traffic = $model_search->status_traffic = $_GET['AShipper']['status_traffic'];
            }
            if (isset($_POST['AShipper'])) {

                $show                     = 1;
                $post                     = TRUE;
                $model->attributes        = $_POST['AShipper'];
                $model_search->attributes = $_POST['AShipper'];
//                CVarDumper::dump($model,10,true);
//                CVarDumper::dump($model_search,10,true);

            }
            // Lấy tổng số đơn hàng.


            $this->render('admin', array(
                'model'        => $model,
                'model_search' => $model_search,
                'show'         => $show,
                'post'         => $post
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdminRenueve()
        {
            $model        = new AShipper('search');
            $model_search = new AShipper();

            $data_overview = array();

            $model->scenario = $model_search->scenario = "admin_renueve";
            $model->unsetAttributes();  // clear any default values
            $show = 0;

            $model_search->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
            $model_search->end_date   = date('d/m/Y');

            if (isset($_POST['AShipper'])) {

                $model->attributes        = $_POST['AShipper'];
                $model_search->attributes = $_POST['AShipper'];

                $model->id        = $_POST['AShipper']['id'];
                $model_search->id = $_POST['AShipper']['id'];

                if ($_POST['AShipper']['start_date'] != '' && $_POST['AShipper']['end_date'] != '') {
                    $model_search->start_date = $_POST['AShipper']['start_date'];
                    $model_search->end_date   = $_POST['AShipper']['end_date'];
                    $model->start_date        = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['AShipper']['start_date']))) . " 00:00:00";
                    $model->end_date          = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['AShipper']['end_date']))) . " 23:59:59";

                }

                if ($model->validate()) {
                    $show = 1;

                    $model->status_traffic = $model_search->status_traffic = $_POST['AShipper']['status_traffic'];
                    $data_overview         = $model->getTotalOrderInfoByInput();

//                    $total_shipper = $model->getTotalShipperByInput();

//                    $data_overview->total_shipper = $total_shipper;
                } else {
                    $model->getErrors();
                }

            }
            // Lấy tổng số đơn hàng.


            $this->render('renueve_shipper', array(
                'model'         => $model,
                'model_search'  => $model_search,
                'data_overview' => $data_overview,
                'show'          => $show
            ));
        }

        /**
         * Returns the data model based on the primary key given in the GET variable.
         * If the data model is not found, an HTTP exception will be raised.
         *
         * @param integer $id the ID of the model to be loaded
         *
         * @return CskhShipper the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = AShipper::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param CskhShipper $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_GET['ajax']) && $_GET['ajax'] === 'cskh-shipper-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }


        /**
         * Lấy danh sách quận huyện theo tỉnh
         */
        public function actionGetDistrictByProvice()
        {

            $provice_code = Yii::app()->getRequest()->getParam("province_code", FALSE);
            if ($provice_code) {
                $criteria = new CDbCriteria();

                $criteria->condition = "province_code='" . $provice_code . "'";

                $data   = District::model()->findAll($criteria);
                $return = CHtml::listData($data, 'code', 'name');
                echo "<option> </option>";
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }

        }

        /**
         * Lấy danh sách quận huyện theo tỉnh
         */
        public function actionGetWardByDistrict()
        {

            $district_code = Yii::app()->getRequest()->getParam("district_code", FALSE);
            if ($district_code) {
                $criteria = new CDbCriteria();

                $criteria->condition = "district_code='" . $district_code . "'";

                $data   = Ward::model()->findAll($criteria);
                $return = CHtml::listData($data, 'code', 'name');
                echo "<option> </option>";
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
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
                $return = CHtml::listData($data, 'code', 'name');
                echo "<option> </option>";
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }

        }

        /**
         * Báo cáo doanh thu shipper.
         */
        public function actionReportRenueveShipper()
        {
            $model = new CskhShipper('search');
            $model->unsetAttributes();  // clear any default values
            if (isset($_POST['CskhShipper'])) {
                $model->attributes = $_POST['CskhShipper'];
            }
            if (isset($_GET['CskhShipper'])) {
                $model->attributes = $_GET['CskhShipper'];
            }

            return $this->render('report', array('model' => $model));
        }

        public function actionGetShipperBySaleId()
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
    }
