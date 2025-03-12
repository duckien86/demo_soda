<?php

    class AFTReportController extends AController
    {

        public function init()
        {
            parent::init();
            $this->defaultAction = 'index';
//            $this->pageTitle     = 'Query Builder';
        }

        public function filters()
        {
            return array(
                'rights', // perform access control for CRUD operations
            );
        }


        /**
         * Báo cáo tổng quan.
         */
        public function actionIndex()
        {

            $form          = new AFTReportForm();
            $form_validate = new AFTReportForm();

            $model = new AFTReport();
            $data  = array();

            if (isset($_POST['AFTReportForm']) || isset($_REQUEST['AFTReportForm'])) {
                if (Yii::app()->cache->get('aftreport_start_date')) {
                    Yii::app()->cache->delete('aftreport_start_date');
                }
                if (Yii::app()->cache->get('aftreport_end_date')) {
                    Yii::app()->cache->delete('aftreport_end_date');
                }
                if (!isset($_POST['AFTReportForm'])) {
                    $_POST['AFTReportForm'] = $_REQUEST['AFTReportForm'];
                }
                if (isset($_POST['AFTReportForm']['start_date']) && $_POST['AFTReportForm']['start_date'] != '') {
                    $form->attributes   = $model->attributes = $_POST['AFTReportForm'];
                    $form->order_id     = $model->order_id = isset($_POST['AFTReportForm']['order_id']) ? $_POST['AFTReportForm']['order_id'] : '';
                    $form->status_order = $model->status_order = isset($_POST['AFTReportForm']['status_order']) ? $_POST['AFTReportForm']['status_order'] : '';
                    $form->contract_id  = $model->contract_id = isset($_POST['AFTReportForm']['contract_id']) ? $_POST['AFTReportForm']['contract_id'] : '';
                    $form->user_tourist = $model->user_tourist = isset($_POST['AFTReportForm']['user_tourist']) ? $_POST['AFTReportForm']['user_tourist'] : '';
                    $form->start_date   = $_POST['AFTReportForm']['start_date'];
                    $model->start_date  = $form_validate->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));
                    if (!Yii::app()->cache->get('aftreport_start_date')) {
                        Yii::app()->cache->set('aftreport_start_date', $model->start_date);
                    }
                }
                if (isset($_POST['AFTReportForm']['end_date']) && $_POST['AFTReportForm']['end_date'] != '') {
                    $form->end_date  = $_POST['AFTReportForm']['end_date'];
                    $model->end_date = $form_validate->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
                    if (!Yii::app()->cache->get('aftreport_end_date')) {
                        Yii::app()->cache->set('aftreport_end_date', $model->end_date);
                    }
                }
                if ($form_validate->validate()) {
                    $data = $model->getOrderData();

                } else {
                    $form_validate->getErrors();
                }
            }

            return $this->render('index', array('form' => $form, 'data' => $data, 'form_validate' => $form_validate));
        }


        /**
         * Báo cáo doanh thu sim du lịch
         */
        public function actionRenueve()
        {

            $form          = new AFTReportForm();
            $form_validate = new AFTReportForm();

            $model = new AFTReport();
            $data  = $data_details = array();

            if (isset($_POST['AFTReportForm']) || isset($_REQUEST['AFTReportForm'])) {
                if (Yii::app()->cache->get('aftreport_start_date')) {
                    Yii::app()->cache->delete('aftreport_start_date');
                }
                if (Yii::app()->cache->get('aftreport_end_date')) {
                    Yii::app()->cache->delete('aftreport_end_date');
                }
                if (!isset($_POST['AFTReportForm'])) {
                    $_POST['AFTReportForm'] = $_REQUEST['AFTReportForm'];
                }
                if (isset($_POST['AFTReportForm']['start_date']) && $_POST['AFTReportForm']['start_date'] != '') {
                    $form->attributes    = $model->attributes = $_POST['AFTReportForm'];
                    $form->order_id      = $model->order_id = isset($_POST['AFTReportForm']['order_id']) ? $_POST['AFTReportForm']['order_id'] : '';
                    $form->contract_id   = $model->contract_id = isset($_POST['AFTReportForm']['contract_id']) ? $_POST['AFTReportForm']['contract_id'] : '';
                    $form->user_tourist  = $model->user_tourist = isset($_POST['AFTReportForm']['user_tourist']) ? $_POST['AFTReportForm']['user_tourist'] : '';
                    $form->item_id       = $model->item_id = isset($_POST['AFTReportForm']['item_id']) ? $_POST['AFTReportForm']['item_id'] : '';
                    $form->province_code = $model->province_code = isset($_POST['AFTReportForm']['province_code']) ? $_POST['AFTReportForm']['province_code'] : '';
                    $form->status_order  = $model->status_order = isset($_POST['AFTReportForm']['status_order']) ? $_POST['AFTReportForm']['status_order'] : '';
                    $form->on_detail  = isset($_POST['AFTReportForm']['on_detail']) ? $_POST['AFTReportForm']['on_detail'] : '';
                    $form->start_date    = $_POST['AFTReportForm']['start_date'];
                    $model->start_date   = $form_validate->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));
                    if (!Yii::app()->cache->get('aftreport_start_date')) {
                        Yii::app()->cache->set('aftreport_start_date', $model->start_date);
                    }
                }
                if (isset($_POST['AFTReportForm']['end_date']) && $_POST['AFTReportForm']['end_date'] != '') {
                    $form->end_date  = $_POST['AFTReportForm']['end_date'];
                    $model->end_date = $form_validate->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
                    if (!Yii::app()->cache->get('aftreport_end_date')) {
                        Yii::app()->cache->set('aftreport_end_date', $model->end_date);
                    }
                }
                if ($form_validate->validate()) {
                    $data = $model->getRenueveOverview();
                    $data = new CArrayDataProvider($data, array(
                        'keyField' => FALSE,
                    ));

                    $data_details = $model->getRenueveDetails();
                } else {
                    $form_validate->getErrors();
                }

            }

            return $this->render('renueve', array('form' => $form, 'data' => $data, 'data_details' => $data_details, 'form_validate' => $form_validate));
        }

        /**
         * Lấy danh sách hợp đồng theo user.
         */
        public function actionGetContractByUsers()
        {

            $user_tourist = Yii::app()->getRequest()->getParam("user_tourist", FALSE);
            if ($user_tourist) {
                $contract = AFTContracts::model()->findAll('user_id =:user_id and status =:status',
                    array(
                        ':user_id' => $user_tourist,
                        ':status'  => AFTContracts::CONTRACT_ACTIVE
                    )
                );

                $data = CHtml::listData($contract, 'id', 'code');
                echo "<option value=''>Mã hợp đồng</option>";
                foreach ($data as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }

        }

        /**
         * Lấy danh sách đơn hàng theo hợp đồng.
         */
        public function actionGetOrdersByContract()
        {

            $contract_id = Yii::app()->getRequest()->getParam("contract_id", FALSE);
            if ($contract_id) {
                $orders = AFTOrders::model()->findAll('contract_id =:contract_id',
                    array(
                        ':contract_id' => $contract_id
                    )
                );

                $data = CHtml::listData($orders, 'id', 'code');
                echo "<option value=''>Mã đơn hàng</option>";
                foreach ($data as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }

        }

    }

?>