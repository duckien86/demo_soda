<?php

    class ReportCtvController extends AController
    {

        public function init()
        {
            parent::init();
            $this->defaultAction = 'simRenueve';
//            $this->pageTitle     = 'Query Builder';
        }

        public function accessRules()
        {
            return array(
                array('allow',  // allow all users to perform 'index' and 'view' actions
                    'actions' => array('simRenueve', 'getUserByProvince', 'packageRenueve', 'getPackageByPeriod', 'packageMainTainRenueve', 'introduceRenueve', 'supportRenueve'),
                    'users'   => array('@'),
                ),
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                    'actions' => array('simRenueve', 'getUserByProvince', 'packageRenueve', 'getPackageByPeriod', 'packageMainTainRenueve', 'introduceRenueve', 'supportRenueve'),
                    'users'   => array('@'),
                ),
                array('allow', // allow admin user to perform 'admin' and 'delete' actions
                    'actions' => array('simRenueve', 'getUserByProvince', 'packageRenueve', 'packageMainTainRenueve', 'introduceRenueve', 'supportRenueve'),
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
                'rights', // perform access control for CRUD operations
            );
        }

        /**
         * Thống kê hoa hồng sim số.
         *
         * @return string
         */
        public function actionSimRenueve()
        {
            $form          = new ReportForm();
            $form_validate = new ReportForm();
            $model         = $model_detai = new ReportCTV();
            $on_detail     = FALSE;
            $data_renueve  = $data_renueve_detail = $data_detail_title = array();
            if (isset($_POST['ReportForm'])) {
                $form->attributes    = $model->attributes = $model_detai->attributes = $_POST['ReportForm'];
                $form->province_code = $model->province_code = $_POST['ReportForm']['province_code'];
                $form->ctv_id        = $model->ctv_id = $_POST['ReportForm']['ctv_id'];

                if (isset($_POST['ReportForm']['start_date']) && $_POST['ReportForm']['start_date']) {
                    $form->start_date          = $_POST['ReportForm']['start_date'];
                    $form_validate->start_date = $model_detai->start_date = $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));
                }
                if (isset($_POST['ReportForm']['end_date']) && $_POST['ReportForm']['end_date'] != '') {
                    $form->end_date          = $_POST['ReportForm']['end_date'];
                    $form_validate->end_date = $model_detai->end_date = $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
                }
                if ($form_validate->validate()) {
                    $data_renueve = $model->controlRenueveSim($model->getSim());
                    if (isset($_POST['ReportForm']['on_detail'])) {
                        $on_detail           = TRUE;
                        $data_renueve_detail = $model_detai->getDetailSim();
                        $data_detail_title   = $model->getTitleDetail();
                    }
                } else {
                    $form_validate->getErrors();
                }

            }
            $form->on_detail = $on_detail;

            return $this->render('sim_renueve',
                array('form'                => $form,
                      'data_renueve'        => $data_renueve,
                      'model'               => $model,
                      'on_detail'           => $on_detail,
                      'data_detail_title'   => $data_detail_title,
                      'data_renueve_detail' => $data_renueve_detail));
        }

        /**
         * Thống kê hoa hồng phát triển gói cước.
         */
        public function actionPackageRenueve()
        {
            $form          = new ReportForm();
            $form_validate = new ReportForm();
            $model         = $model_detai = new ReportCTV();
            $on_detail     = FALSE;
            $data_renueve  = $data_renueve_detail = $data_detail_title = array();
            if (isset($_POST['ReportForm'])) {
                $form->attributes    = $model->attributes = $_POST['ReportForm'];
                $form->province_code = $model->province_code = $_POST['ReportForm']['province_code'];
                $form->package_id    = $model->package_id = $_POST['ReportForm']['package_id'];
                $form->ctv_id        = $model->ctv_id = $_POST['ReportForm']['ctv_id'];
                if (isset($_POST['ReportForm']['start_date']) && $_POST['ReportForm']['start_date']) {
                    $form->start_date          = $_POST['ReportForm']['start_date'];
                    $form_validate->start_date = $model_detai->start_date = $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));
                }
                if (isset($_POST['ReportForm']['end_date']) && $_POST['ReportForm']['end_date'] != '') {
                    $form->end_date          = $_POST['ReportForm']['end_date'];
                    $form_validate->end_date = $model_detai->end_date = $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
                }
                if ($form_validate->validate()) {
                    $data_renueve = $model->controlRenuevePackage($model->getPackageRenueve($model::PACKAGE_TYPE_RENUEVE));

                    $data_renueve_detail = $model->getPackageDetail($model::PACKAGE_TYPE_RENUEVE);

                    if (isset($_POST['ReportForm']['on_detail'])) {
                        $on_detail         = TRUE;
                        $data_detail_title = $model->getTitleDetail();
                    }
                } else {
                    $form_validate->getErrors();
                }

            }
            $form->on_detail = $on_detail;

            return $this->render('package_renueve',
                array('form'                => $form,
                      'data_renueve'        => $data_renueve,
                      'model'               => $model,
                      'on_detail'           => $on_detail,
                      'data_detail_title'   => $data_detail_title,
                      'data_renueve_detail' => $data_renueve_detail));
        }

        /**
         * Lấy danh sách Ktv theo trung tâm bằng aJax.
         */
        public function actionGetUserByProvince()
        {
            $provice_code = Yii::app()->getRequest()->getParam("province_code", FALSE);
            $data         = Yii::app()->db_affiliates->createCommand("select * from tbl_users where province=:provice")
                ->bindParam(':provice', $provice_code)
                ->queryAll();

            $return = CHtml::listData($data, 'user_name', 'user_name');
            echo "<option>Chọn tất cả</option>";
            foreach ($return as $k => $v) {
                echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
            }
        }

        /**
         * Lấy danh sách gói cước theo chu kỳ
         */
        public function actionGetPackageByPeriod()
        {
            $period   = Yii::app()->getRequest()->getParam("period", FALSE);
            $criteria = new CDbCriteria();

            $criteria->condition = "period =" . $period;

            $data   = Package::model()->findAll($criteria);
            $return = CHtml::listData($data, 'id', 'name');
            echo "<option>Chọn tất cả</option>";
            foreach ($return as $k => $v) {
                echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
            }
        }

        /**
         * Hoa hồng duy trì gói cước.
         */
        public function actionPackageMainTainRenueve()
        {
            $form          = new ReportForm();
            $form_validate = new ReportForm();
            $model         = $model_detai = new ReportCTV();
            $on_detail     = FALSE;
            $data_renueve  = $data_renueve_detail = $data_detail_title = array();
            if (isset($_POST['ReportForm'])) {
                $form->attributes    = $model->attributes = $_POST['ReportForm'];
                $form->province_code = $model->province_code = $_POST['ReportForm']['province_code'];
                $form->package_id    = $model->package_id = $_POST['ReportForm']['package_id'];
                $form->ctv_id        = $model->ctv_id = $_POST['ReportForm']['ctv_id'];
                if (isset($_POST['ReportForm']['start_date']) && $_POST['ReportForm']['start_date']) {
                    $form->start_date          = $_POST['ReportForm']['start_date'];
                    $form_validate->start_date = $model_detai->start_date = $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));
                }
                if (isset($_POST['ReportForm']['end_date']) && $_POST['ReportForm']['end_date'] != '') {
                    $form->end_date          = $_POST['ReportForm']['end_date'];
                    $form_validate->end_date = $model_detai->end_date = $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
                }
                if ($form_validate->validate()) {
                    $data_renueve = $model->controlRenuevePackage($model->getPackageRenueve($model::PACKAGE_KEEPING_RENUEVE));

                    $data_renueve_detail = $model->getPackageDetail($model::PACKAGE_KEEPING_RENUEVE);

                    if (isset($_POST['ReportForm']['on_detail'])) {
                        $on_detail         = TRUE;
                        $data_detail_title = $model->getTitleDetail();
                    }
                } else {
                    $form_validate->getErrors();
                }

            }
            $form->on_detail = $on_detail;

            return $this->render('package_maintain',
                array('form'                => $form,
                      'data_renueve'        => $data_renueve,
                      'model'               => $model,
                      'on_detail'           => $on_detail,
                      'data_detail_title'   => $data_detail_title,
                      'data_renueve_detail' => $data_renueve_detail));
        }

        /**
         * Hoa hồng giới thiệu CTV.
         */
        public function actionIntroduceRenueve()
        {
            $form          = new ReportForm();
            $form_validate = new ReportForm();
            $model         = new ReportCTV();
            $data          = array();
            if (isset($_POST['ReportForm'])) {

                $form->attributes = $model->attributes = $_POST['ReportForm'];
                $form->ctv_id     = $model->ctv_id = $_POST['ReportForm']['ctv_id'];
                if (isset($_POST['ReportForm']['start_date']) && $_POST['ReportForm']['start_date'] != '') {
                    $form->start_date  = $_POST['ReportForm']['start_date'];
                    $model->start_date = $form_validate->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));
                }
                if (isset($_POST['ReportForm']['end_date']) && $_POST['ReportForm']['end_date'] != '') {
                    $form->end_date  = $_POST['ReportForm']['end_date'];
                    $model->end_date = $form_validate->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
                }
                if ($form_validate->validate()) {
                    $data = $model->getBrokerSupportCTV($model::BROKER_RENUEVE);
                } else {
                    $form_validate->getErrors();
                }
            }

            return $this->render('introduce', array('form' => $form, 'data' => $data));
        }

        /**
         * Hoa hồng hỗ trợ CTV.
         */
        public function actionSupportRenueve()
        {
            $form          = new ReportForm();
            $form_validate = new ReportForm();
            $model         = new ReportCTV();
            $data          = array();
            if (isset($_POST['ReportForm'])) {

                $form->attributes = $model->attributes = $_POST['ReportForm'];
                $form->ctv_id     = $model->ctv_id = $_POST['ReportForm']['ctv_id'];
                if (isset($_POST['ReportForm']['start_date']) && $_POST['ReportForm']['start_date'] != '') {
                    $form->start_date  = $_POST['ReportForm']['start_date'];
                    $model->start_date = $form_validate->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));
                }
                if (isset($_POST['ReportForm']['end_date']) && $_POST['ReportForm']['end_date'] != '') {
                    $form->end_date          = $_POST['ReportForm']['end_date'];
                    $form_validate->end_date = $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
                }
                if ($form_validate->validate()) {
                    $data = $model->getBrokerSupportCTV($model::SUPPORT_RENUEVE);
                } else {
                    $form_validate->getErrors();
                }
            }

            return $this->render('support', array('form' => $form, 'data' => $data));

        }

        /**
         * Lấy gói cước theo nhóm.
         */
        public function actionGetPackageByGroup()
        {
            $package_group = Yii::app()->getRequest()->getParam("package_group", FALSE);
            if ($package_group) {
                $criteria            = new CDbCriteria();
                $criteria->condition = "type = " . $package_group;

                $data   = Package::model()->findAll($criteria);
                $return = CHtml::listData($data, 'id', 'name');
                echo "<option>Chọn tất cả</option>";
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }
        }
    }

?>