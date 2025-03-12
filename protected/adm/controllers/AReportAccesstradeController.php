<?php

    class AReportAccesstradeController extends AController
    {

        public function init()
        {
            parent::init();
            $this->defaultAction = 'index';
        }

        public function filters()
        {
            return array(
                'rights', // perform access control for CRUD operations
            );
        }


        /**
         * Doanh thu sim
         */
        public function actionSim()
        {
            $form          = new AReportATForm();
            $form_validate = new AReportATForm();
            $model         = new AReportAT();
            $post          = 0;
            $data          = $data_detail = array();
            if (isset($_POST['AReportATForm']) || isset($_REQUEST['AReportATForm'])) {

                if (!isset($_POST['AReportATForm'])) {
                    $_POST['AReportATForm'] = $_REQUEST['AReportATForm'];
                }
                $post = 1;

                $form->attributes    = $model->attributes = $_POST['AReportATForm'];
                $form->sim_type      = $form_validate->sim_type = $model->sim_type = $_POST['AReportATForm']['sim_type'];
                $form->status        = $form_validate->status = $model->status = $_POST['AReportATForm']['status'];
                $form->province_code = $model->province_code = $_POST['AReportATForm']['province_code'];
                $form->channel_code  = $form_validate->channel_code = $model->channel_code = $_POST['AReportATForm']['channel_code'];
                if (isset($_POST['AReportATForm']['start_date']) && $_POST['AReportATForm']['start_date'] != '') {
                    $form->start_date          = $_POST['AReportATForm']['start_date'];
                    $form_validate->start_date = $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));
                }
                if (isset($_POST['AReportATForm']['end_date']) && $_POST['AReportATForm']['end_date'] != '') {
                    $form->end_date          = $_POST['AReportATForm']['end_date'];
                    $form_validate->end_date = $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
                }
                if ($form_validate->validate()) {
                    //Lấy doanh thu sim tổng quan.
                    $data        = $model->getSimOverView();
                    $data_detail = $model->getSimDetails();

                } else {
                    $form_validate->getErrors();
                }
            }


            return $this->render('sim',
                array('form'          => $form,
                      'post'          => $post,
                      'data'          => $data,
                      'data_detail'   => $data_detail,
                      'form_validate' => $form_validate,
                      'model'         => $model)
            );
        }

        /**
         * @return mixed
         *  Báo cáo affiliate
         */
        public function actionAffiliate()
        {
            $form          = new AReportATForm();
            $form_validate = new AReportATForm();
            $model         = new AReportAT();
            $post          = 0;
            $data          = $data_detail = array();
            $page          = 0;
            if (isset($_POST['AReportATForm']) || isset($_REQUEST['AReportATForm'])) {

                if (!isset($_POST['AReportATForm'])) {
                    $_POST['AReportATForm'] = $_REQUEST['AReportATForm'];
                }
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                }
                $post             = 1;
                $form->attributes = $model->attributes = $_POST['AReportATForm'];
                if (!ADMIN && !SUPER_ADMIN) {
                    if (isset(Yii::app()->user->province_code)) {
                        if (!empty(Yii::app()->user->province_code)) {
                            $form->province_code = $model->province_code = Yii::app()->user->province_code;
                        }
                    }
                } else {
                    $form->province_code = $model->province_code = $_POST['AReportATForm']['province_code'];
                }
                $form->status = $model->status = $form_validate->status = $_POST['AReportATForm']['status'];

                if (isset($_POST['AReportATForm']['start_date']) && $_POST['AReportATForm']['start_date'] != '') {
                    $form->start_date          = $_POST['AReportATForm']['start_date'];
                    $form_validate->start_date = $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));
                }
                if (isset($_POST['AReportATForm']['end_date']) && $_POST['AReportATForm']['end_date'] != '') {
                    $form->end_date          = $_POST['AReportATForm']['end_date'];
                    $form_validate->end_date = $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
                }
                if ($form_validate->validate()) {
                    //Lấy doanh thu affiliate tổng quan.
//                    $data_sim     = $model->getAffiliateSimOverView();
//                    $data_package = $model->getAffiliatePackageOverView();
//                    $data         = array_merge($data_sim, $data_package);

                    $data = $model->getAffiliateOverView();

                    //Lấy doanh thu affiliate tổng quan.
                    $data_detail_sim = $model->getSimAffiliateDetails();
                    $data_detail_package = $model->getPackageAffiliateDetails();
                    $data_detail         = self::controllDataDetailAffiliate($data_detail_sim, $data_detail_package);

                    $data_detail = new CArrayDataProvider($data_detail, array(
                        'keyField'   => FALSE,
                        'pagination' => array(
                            'currentPage' => $page - 1,
                            'pageSize'    => 30,
                            'params'      => array(
                                'AReportATForm[start_date]'    => $model->start_date,
                                'AReportATForm[end_date]'      => $model->end_date,
                                'AReportATForm[province_code]' => $model->province_code,
                                'AReportATForm[status]'        => $model->status,
                            ),
                        ),
                    ));

                } else {
                    $form_validate->getErrors();
                }
            }


            return $this->render('affiliate',
                array('form'          => $form,
                      'post'          => $post,
                      'data'          => $data,
                      'data_detail'   => $data_detail,
                      'form_validate' => $form_validate,
                      'model'         => $model)
            );
        }

        public function actionPaidAffiliate()
        {
            $form          = new AReportATForm();
            $form_validate = new AReportATForm();
            $model         = new AReportAT();
            $post          = 0;
            $data          = $data_detail = array();
            $page          = 0;

            $form_validate->scenario = "paid_affiliate";
            $form->ctv_type          = $model->ctv_type = 1;
            if (isset($_POST['AReportATForm']) || isset($_REQUEST['AReportATForm'])) {

                if (Yii::app()->cache->get('query_month')) {
                    Yii::app()->cache->delete('query_month');
                }
                if (!isset($_POST['AReportATForm'])) {
                    $_POST['AReportATForm'] = $_REQUEST['AReportATForm'];
                }
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                }
                $post             = 1;
                $form->attributes = $model->attributes = $_POST['AReportATForm'];
                if (!ADMIN && !SUPER_ADMIN) {
                    if (isset(Yii::app()->user->province_code)) {
                        if (!empty(Yii::app()->user->province_code)) {
                            $form->province_code = $model->province_code = Yii::app()->user->province_code;
                        }
                    }
                } else {
                    $form->province_code = $model->province_code = $_POST['AReportATForm']['province_code'];
                }
                $form->ctv_id   = $model->ctv_id = $_POST['AReportATForm']['ctv_id'];
                $form->ctv_type = $model->ctv_type = $_POST['AReportATForm']['ctv_type'];
                $form->month    = $model->month = $form_validate->month = $_POST['AReportATForm']['month'];
                $form->year     = $model->year = $form_validate->year = $_POST['AReportATForm']['year'];

//                if (isset($_POST['AReportATForm']['start_date']) && $_POST['AReportATForm']['start_date'] != '') {
//                    $form->start_date          = $_POST['AReportATForm']['start_date'];
//                    $form_validate->start_date = $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));
//                }
//                if (isset($_POST['AReportATForm']['end_date']) && $_POST['AReportATForm']['end_date'] != '') {
//                    $form->end_date          = $_POST['AReportATForm']['end_date'];
//                    $form_validate->end_date = $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
//                }
                if ($form_validate->validate()) {
                    if (!Yii::app()->cache->get('query_month') && !empty($form->month)) {
                        Yii::app()->cache->set('query_month', $form->month);
                    }

                    //Lấy doanh thu affiliate tổng quan.
                    $data = $model->getPaidAffiliateDetails();


                    $data = new CArrayDataProvider($data, array(
                        'keyField'   => FALSE,
                        'pagination' => array(
                            'currentPage' => $page - 1,
                            'pageSize'    => 30,
                            'params'      => array(
                                'AReportATForm[month]'         => $model->month,
                                'AReportATForm[year]'          => $model->year,
                                'AReportATForm[province_code]' => $model->province_code,
                                'AReportATForm[ctv_id]'        => $model->ctv_id,
                                'AReportATForm[ctv_type]'      => $model->ctv_type,
                            ),
                        ),
                    ));
                }
            }


            return $this->render('paid',
                array('form'          => $form,
                      'post'          => $post,
                      'data'          => $data,
                      'data_detail'   => $data_detail,
                      'form_validate' => $form_validate,
                      'model'         => $model
                )
            );
        }

        public function controllDataAffiliate($data, $data_detail)
        {
            $provinces = array();
            $result    = array();


            if (is_array($data) && !empty($data)) {
                foreach ($data as $key => $value) {
                    if (isset($value->order_province_code)) {
                        if (!in_array($value->order_province_code, $provinces)) {
                            array_push($provinces, $value->order_province_code);
                            $result_key                        = array(
                                'order_province_code' => '',
                                'total_order'         => '',
                                'total_renueve'       => 0,
                            );
                            $result_key['order_province_code'] = $value->order_province_code;
                            $orders                            = array();
                            if (is_array($data_detail) && !empty($data_detail)) {
                                foreach ($data_detail as $key_detail => $value_detail) {
                                    if (isset($value_detail->order_id)
                                        && $value_detail->order_province_code == $value->order_province_code
                                    ) {
                                        if (!in_array($value_detail->order_id, $orders)) {
                                            array_push($orders, $value_detail->order_id);
                                        }
                                        $result_key['total_renueve'] += $value_detail->item_price;

                                    }
                                }
                            }

                            $result_key['total_order'] = count($orders);
                            $result[]                  = $result_key;
                        }
                    }
                }
            }

//            CVarDumper::dump($result,10,true);die();
            return $result;
        }

        public function controllDataDetailAffiliate($data_sim, $data_package)
        {
            $orders = array();
            $result = array();
            if (is_array($data_sim) && !empty($data_sim)) {
                foreach ($data_sim as $key => $value) {
                    if (isset($value->order_code)) {
                        if (!in_array($value->order_code, $orders)) {
                            array_push($orders, $value->order_code);
                        }
                    }
                }
            }
            if (is_array($data_package) && !empty($data_package)) {
                foreach ($data_package as $key => $value) {
                    if (isset($value->order_code)) {
                        if (!in_array($value->order_code, $orders)) {
                            array_push($orders, $value->order_code);
                        }
                    }
                }
            }

            foreach ($orders as $order) {

                $result_key = array(
                    'order_code'      => $order,
                    'vnp_province_id' => '',
                    'msisdn'          => '',
                    'package_name'    => '',
                    'action_status'   => '',
                    'price_sim'       => 0,
                    'price_package'   => 0,
                    'transaction_id'  => 0,
                    'renueve_sim'     => 0,
                    'sub_type'        => '',
                    'renueve_package' => 0,
                    'publisher_id'    => '',
                    'amount_sim'      => 0,
                    'amount_package'  => 0,
                );
                foreach ($data_sim as $key => $value) {
                    if ($value->order_code == $order) {
                        $result_key['order_code']      = $value->order_code;
                        $result_key['vnp_province_id'] = $value->vnp_province_id;
                        $result_key['msisdn']          = $value->msisdn;
                        $result_key['action_status']   = $value->action_status;
                        $result_key['publisher_id']    = $value->publisher_id;
                        $result_key['transaction_id']  = $value->transaction_id;
                        $result_key['price_sim']       = $value->price_sim;
                        $result_key['amount_sim']      = ($value->action_status == 3) ? $value->amount : 0;
                        $result_key['sub_type']        = $value->type;
                        $result_key['renueve_sim']     = $value->total_money;
                    }
                }
                foreach ($data_package as $key => $value) {
                    if ($value->order_code == $order) {
                        $result_key['order_code']      = $value->order_code;
                        $result_key['vnp_province_id'] = $value->vnp_province_id;
                        $result_key['msisdn']          = $value->msisdn;
                        $result_key['action_status']   = $value->action_status;
                        $result_key['publisher_id']    = $value->publisher_id;
                        $result_key['package_name']    = $value->product_name;
                        $result_key['price_package']   = $value->price_package;
                        $result_key['sub_type']        = $value->type;
                        $result_key['amount_package']  = ($value->action_status == 3) ? $value->amount : 0;
                        $result_key['renueve_package'] = $value->total_money;
                    }
                }
                $result[] = $result_key;
            }

            return $result;
        }

        /**
         * Doanh thu gói cước/ dịch vụ tổng quan
         */
        public function actionPackage()
        {
            $form          = new AReportATForm();
            $form_validate = new AReportATForm();
            $model         = new AReportAT();
            $post          = 0;
            $data          = $data_detail = array();
            if (isset($_POST['AReportATForm']) || isset($_REQUEST['AReportATForm'])) {

                if (!isset($_POST['AReportATForm'])) {
                    $_POST['AReportATForm'] = $_REQUEST['AReportATForm'];
                }
                $post = 1;

                $form->attributes    = $model->attributes = $_POST['AReportATForm'];
                $form->package_group = $form_validate->package_group = $model->package_group = $_POST['AReportATForm']['package_group'];
                $form->package_id    = $form_validate->package_id = $model->package_id = $_POST['AReportATForm']['package_id'];
                $form->province_code = $model->province_code = $_POST['AReportATForm']['province_code'];
                $form->status        = $form_validate->status = $model->status = $_POST['AReportATForm']['status'];
                $form->channel_code  = $form_validate->channel_code = $model->channel_code = $_POST['AReportATForm']['channel_code'];
                if (isset($_POST['AReportATForm']['start_date']) && $_POST['AReportATForm']['start_date'] != '') {
                    $form->start_date          = $_POST['AReportATForm']['start_date'];
                    $form_validate->start_date = $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));
                }
                if (isset($_POST['AReportATForm']['end_date']) && $_POST['AReportATForm']['end_date'] != '') {
                    $form->end_date          = $_POST['AReportATForm']['end_date'];
                    $form_validate->end_date = $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
                }
                if ($form_validate->validate()) {
                    //Lấy doanh thu sim tổng quan.
                    $data        = $model->getPackageOverView();
                    $data_detail = $model->getPackageDetails();
                } else {
                    $form_validate->getErrors();
                }
            }


            return $this->render('package',
                array('form'          => $form,
                      'post'          => $post,
                      'data'          => $data,
                      'data_detail'   => $data_detail,
                      'form_validate' => $form_validate,
                      'model'         => $model)
            );
        }

        public function actionGetPackageByGroup()
        {
            $package_group = Yii::app()->getRequest()->getParam("package_group", FALSE);
            if ($package_group) {
                $criteria            = new CDbCriteria();
                $criteria->condition = "type='" . $package_group . "'";

                $data   = Package::model()->findAll($criteria);
                $return = CHtml::listData($data, 'id', 'name');
                echo "<option value=''>Chọn tất cả</option>";
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }
        }
    }

?>