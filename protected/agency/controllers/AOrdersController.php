<?php

    class AOrdersController extends AController
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
            $model = $this->loadModel($id);
            $order_state   = AOrderState::getListOrderState($id, TRUE, 30);//order history
            $order_detail  = AOrderState::getDetailOrder($id);
            $order_shipper = AOrders::getShipperDetail($id);
            $logs_sim      = ALogsSim::getLogs($id);
            $this->render('view', array(
                'model'         => $model,
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
            $model = new AOrders('search');
            if(!ADMIN && !SUPER_ADMIN){
                $model->is_pre_order = 1;
            }
            $model->unsetAttributes();  // clear any default values
            $model->start_date        = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
            $model->end_date          = date('d/m/Y');

            if(isset($_REQUEST['AOrders'])){
                $model->attributes = $_REQUEST['AOrders'];

                if(empty($model->id) && empty($model->phone_contact) && empty($model->sim)){
                    $model->validate();
                }
            }

            $this->render('admin', array(
                'model'        => $model,
            ));
        }
        /*
         * Tra cứu đơn hàng gói đơn lẻ
         */

        public function actionPackageSingle()
        {
            $model = new AOrders('search');

            $model->unsetAttributes();  // clear any default values
            $model->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
            $model->end_date = date('d/m/Y');

            if (isset($_REQUEST['AOrders'])) {
                $model->attributes = $_REQUEST['AOrders'];

                if (empty($model->id) && empty($model->phone_contact)) {
                    $model->validate();
                }
            }

            $this->render('package_single', array(
                'model' => $model,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdminRecycle()
        {
            $model = new AOrders('search');
            $model->unsetAttributes();  // clear any default values
            $model_search             = new AOrders('search');
            $model_search->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
            $model_search->end_date   = date('d/m/Y');
            $model->start_date        = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
            $model->end_date          = date('d/m/Y');
            $post                     = FALSE;
            $model->scenario          = "admin";
            if (isset($_GET['AOrders'])) {

                $model->attributes = $_GET['AOrders'];
                if (isset($_GET['AOrders']['province_code']) && $_GET['AOrders']['province_code'] != '') {
                    $post = TRUE;

                    $model->province_code = $_GET['AOrders']['province_code'];
                }
                if (isset($_GET['AOrders']['sale_office_code']) && $_GET['AOrders']['sale_office_code'] != '') {
                    $model->sale_office_code = $_GET['AOrders']['sale_office_code'];

                    $post = TRUE;
                }
                if (isset($_GET['AOrders']['start_date']) && $_GET['AOrders']['start_date'] != '') {
                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['AOrders']['start_date'])));
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['AOrders']['end_date'])));
                } else {
                    $model->start_date = '';
                    $model->end_date   = '';
                }
                if (isset($_GET['AOrders']['brand_offices_id']) && $_GET['AOrders']['brand_offices_id'] != '') {
                    $model->brand_offices_id = $_GET['AOrders']['brand_offices_id'];

                    $post = TRUE;
                }
                if (isset($_GET['AOrders']['sim']) && $_GET['AOrders']['sim'] != '') {
                    $model->sim = $_GET['AOrders']['sim'];

                    $post = TRUE;
                }
                if (isset($_GET['AOrders']['phone_contact']) && $_GET['AOrders']['phone_contact'] != '') {
                    $model->phone_contact = $_GET['AOrders']['phone_contact'];

                    $post = TRUE;
                }
            }

            if (isset($_POST['AOrders'])) {
                $post = TRUE;
                if ($_POST['AOrders']['start_date'] != '' && $_POST['AOrders']['end_date'] != '') {

                    $model->attributes        = $_POST['AOrders'];
                    $model_search->attributes = $_POST['AOrders'];
                    $model_search->start_date = $_POST['AOrders']['start_date'];
                    $model_search->end_date   = $_POST['AOrders']['end_date'];

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['AOrders']['start_date'])));
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['AOrders']['end_date'])));


                }
                if (isset($_POST['AOrders']['sale_office_code']) && $_POST['AOrders']['sale_office_code'] != '') {
                    $model->sale_office_code        = $_POST['AOrders']['sale_office_code'];
                    $model_search->sale_office_code = $_POST['AOrders']['sale_office_code'];
                }
                if (isset($_POST['AOrders']['brand_offices_id']) && $_POST['AOrders']['brand_offices_id'] != '') {
                    $model->brand_offices_id        = $_POST['AOrders']['brand_offices_id'];
                    $model_search->brand_offices_id = $_POST['AOrders']['brand_offices_id'];
                }


                if (!$model->validate()) {
                    $model->getErrors();
                }
            }

            $this->render('admin_recycle', array(
                'model'        => $model,
                'post'         => $post,
                'model_search' => $model_search,
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
            if ($model === NULL || !AOrders::checkAgencyContract($model->agency_contract_id))
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
         * Láy danh sách người đại diện
         */
        public function actionProxy()
        {
            $value    = Yii::app()->getRequest()->getParam("value", FALSE);
            $type     = Yii::app()->getRequest()->getParam("type", FALSE);
            $order_id = Yii::app()->getRequest()->getParam("order_id", FALSE);

            if ($value && $type && $order_id) {

                $model = new User();
                if ($type == AOrders::SALE_OFFICE_PERSON) {
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
