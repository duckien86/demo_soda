<?php

    class ReportTrafficController extends AController
    {

        public function init()
        {
            parent::init();
            $this->defaultAction = 'index';
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
        public function actionIndex()
        {
            $form          = new ReportForm();
            $form_validate = new ReportForm();
            $model         = new ReportOci();
            $data          = $data_total = $data_order = array();
            if (isset($_POST['ReportForm']) || isset($_REQUEST['ReportForm'])) {
                if (!isset($_POST['ReportForm'])) {
                    $_POST['ReportForm'] = $_REQUEST['ReportForm'];
                }
                $model->channel_code = $form->channel_code = $_POST['ReportForm']['channel_code'];
                $model->utm_campaign = $form->utm_campaign = $_POST['ReportForm']['utm_campaign'];
                if (isset($_POST['ReportForm']['start_date']) && $_POST['ReportForm']['start_date'] != '') {
                    $form->attributes  = $model->attributes = $_POST['ReportForm'];
                    $form->start_date  = $_POST['ReportForm']['start_date'];
                    $model->start_date = $form_validate->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));
                }
                if (isset($_POST['ReportForm']['end_date']) && $_POST['ReportForm']['end_date'] != '') {
                    $form->end_date  = $_POST['ReportForm']['end_date'];
                    $model->end_date = $form_validate->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
                }

                if ($form_validate->validate()) {

                    $data               = $model->getUserTraffixByHour();
                    $data_total         = $model->getTotalUserTraffixByHour();
                    $data_order_success = $model->getOrderCampaign();
                    $data_order         = $model->getOrderCampaign(1);
                    foreach ($data_order_success as $orders) { // Lấy tổng số lương đơn hàng theo campaign
                        foreach ($data_total as $key => $value) {
                            if ($orders->campaign_id == $value['CAMPAIGN']) {
                                if (!isset($data_total[$key]['ORDER_SUCCESS'])) {
                                    $data_total[$key]['ORDER_SUCCESS'] = $orders->total;
                                } else {
                                    $data_total[$key]['ORDER_SUCCESS'] += $orders->total;
                                }
                            }
                        }
                    }
                    foreach ($data_order as $orders) { // Lấy tổng số lương đơn hàng theo campaign
                        foreach ($data_total as $key => $value) {
                            if ($orders->campaign_id == $value['CAMPAIGN']) {
                                if (!isset($data_total[$key]['ORDER'])) {
                                    $data_total[$key]['ORDER'] = $orders->total;
                                } else {
                                    $data_total[$key]['ORDER'] += $orders->total;
                                }
                            }
                        }
                    }
                    $data       = new CArrayDataProvider($data, array(
                        'keyField'   => FALSE,
                        'pagination' => array(
                            'params'   => array(
                                'ReportForm[end_date]'   => $model->end_date,
                                'ReportForm[start_date]' => $model->start_date,
                            ),
                            'pageSize' => 30,
                        ),

                    ));
                    $data_total = new CArrayDataProvider($data_total, array(
                        'keyField'   => FALSE,
                        'pagination' => array(
                            'params'   => array(
                                'ReportForm[end_date]'   => $model->end_date,
                                'ReportForm[start_date]' => $model->start_date,
                            ),
                            'pageSize' => 30,
                        ),

                    ));

                } else {
                    $form_validate->getErrors();
                }
            }

            return $this->render('index',
                array('form'          => $form,
                      'data'          => $data,
                      'form_validate' => $form_validate,
                      'data_total'    => $data_total,
                      'data_order'    => $data_order
                )
            );
        }

    }

?>