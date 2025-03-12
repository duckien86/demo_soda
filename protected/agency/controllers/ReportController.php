<?php

    class ReportController extends AController
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
                    'actions' => array('index', 'sim', 'package', 'card', 'getDistrictByProvice', 'getWardByDistrict', 'getBrandOfficeByDistrict'),
                    'users'   => array('@'),
                ),
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                    'actions' => array('index', 'sim', 'package', 'card', 'getDistrictByProvice', 'getWardByDistrict', 'getBrandOfficeByDistrict'),
                    'users'   => array('@'),
                ),
                array('allow', // allow admin user to perform 'admin' and 'delete' actions
                    'actions' => array('index', 'sim', 'package', 'card', 'getDistrictByProvice', 'getWardByDistrict', 'getBrandOfficeByDistrict'),
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
         * Doanh thu tổng quan
         */
        public function actionIndex()
        {
            $form           = new ReportForm();
            $form_validate  = new ReportForm();
            $model          = new Report();
            $data           = array();
            $data_detail    = array();
            if (isset($_POST['ReportForm']) || isset($_REQUEST['ReportForm'])) {
                if (!isset($_POST['ReportForm'])) {
                    $_POST['ReportForm'] = $_REQUEST['ReportForm'];
                }
                $form->province_code = $model->province_code = $_POST['ReportForm']['province_code'];
//                $form->district_code    = $model->district_code = $_POST['ReportForm']['district_code'];
                $form->brand_offices_id = $model->brand_offices_id = $_POST['ReportForm']['brand_offices_id'];
                $form->sale_office_code = $model->sale_office_code = $_POST['ReportForm']['sale_office_code'];
                $form->input_type       = $model->input_type = $_POST['ReportForm']['input_type'];
                $form->payment_method   = $model->payment_method = $_POST['ReportForm']['payment_method'];
                $form->sim_type         = $model->sim_type = $_POST['ReportForm']['sim_type'];
                $form->receive_status   = $model->receive_status = $_POST['ReportForm']['receive_status'];
                $form->on_detail        = $model->on_detail = $_POST['ReportForm']['on_detail'];

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
                    $data = $model->searchRevenueSynthetic(TRUE);
                    if ($form->on_detail == 'on') {
                        $data_detail = $model->searchDetailRevenueSynthetic(TRUE);
                    }
                }
            }

            return $this->render('index', array(
                'form'          => $form,
                'form_validate' => $form_validate,
                'model'         => $model,
                'data'          => $data,
                'data_detail'   => $data_detail,
            ));
        }

        /**
         * Doanh thu sim
         */
        public function actionSim()
        {
            $form          = new ReportForm();
            $form_validate = new ReportForm();
            $model         = new Report(FALSE);
            $get           = 0;
            $data          = $data_detail = $data_term = array();
            if (isset($_POST['ReportForm']) || isset($_REQUEST['ReportForm'])) {

                if (!isset($_POST['ReportForm'])) {
                    $_POST['ReportForm'] = $_REQUEST['ReportForm'];
                }
                $get                 = 1;
                $form->attributes    = $model->attributes = $_POST['ReportForm'];
                $form->sim_type      = $form_validate->sim_type = $model->sim_type = $_POST['ReportForm']['sim_type'];
                $form->province_code = $model->province_code = $_POST['ReportForm']['province_code'];
//                $form->district_code    = $model->district_code = $_POST['ReportForm']['district_code'];
                $form->brand_offices_id = $model->brand_offices_id = $_POST['ReportForm']['brand_offices_id'];
//                $form->ward_code        = $model->ward_code = $_POST['ReportForm']['ward_code'];
                $form->sale_office_code = $model->sale_office_code = $_POST['ReportForm']['sale_office_code'];
                $form->payment_method   = $model->payment_method = $_POST['ReportForm']['payment_method'];
                $form->input_type       = $model->input_type = $_POST['ReportForm']['input_type'];
                $form->on_detail        = $model->on_detail = isset($_POST['ReportForm']['on_detail']) ? $_POST['ReportForm']['on_detail'] : 0;

                if (isset($_POST['ReportForm']['start_date']) && $_POST['ReportForm']['start_date'] != '') {
                    $form->start_date          = $_POST['ReportForm']['start_date'];
                    $form_validate->start_date = $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));
                }
                if (isset($_POST['ReportForm']['end_date']) && $_POST['ReportForm']['end_date'] != '') {
                    $form->end_date          = $_POST['ReportForm']['end_date'];
                    $form_validate->end_date = $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
                }
                if ($form_validate->validate()) {
                    $data = $model->searchRenueveSim(TRUE);
                    if ($form->on_detail == 'on') {
                        $data_detail = $model->searchDetailRenueveSim(TRUE);
                    }
                }
            }


            return $this->render('sim',
                array('form'          => $form,
                      'get'           => $get,
                      'data'          => $data,
                      'data_detail'   => $data_detail,
                      'form_validate' => $form_validate,
                      'model'         => $model
                ));
        }

        /**
         * Doanh thu gói cước/ dịch vụ tổng quan
         */
        public function actionPackage()
        {
            $form          = new ReportForm();
            $form_validate = new ReportForm();
            $model         = new Report(FALSE);
            $data          = $data_detail = array();
            if (isset($_POST['ReportForm']) || isset($_REQUEST['ReportForm'])) {
                if (!isset($_POST['ReportForm'])) {
                    $_POST['ReportForm'] = $_REQUEST['ReportForm'];
                }
                $form->attributes       = $model->attributes        = $_POST['ReportForm'];
                $form->package_group    = $model->package_group     = isset($_POST['ReportForm']['package_group']) ? $_POST['ReportForm']['package_group'] : '';
                $form->package_id       = $model->package_id        = isset($_POST['ReportForm']['package_id']) ? $_POST['ReportForm']['package_id'] : '';
                $form->sim_freedoo      = $model->sim_freedoo       = isset($_POST['ReportForm']['sim_freedoo']) ? $_POST['ReportForm']['sim_freedoo'] : '';
                $form->input_type       = $model->input_type        = isset($_POST['ReportForm']['input_type']) ? $_POST['ReportForm']['input_type'] : '';
                $form->brand_offices_id = $model->brand_offices_id  = isset($_POST['ReportForm']['brand_offices_id'])? $_POST['ReportForm']['brand_offices_id'] : '';

                $form->on_detail        = $model->on_detail         = isset($_POST['ReportForm']['on_detail']) ? $_POST['ReportForm']['on_detail'] : '';

                if (isset($_POST['ReportForm']['start_date']) && $_POST['ReportForm']['start_date'] != '') {
                    $form->start_date          = $_POST['ReportForm']['start_date'];
                    $form_validate->start_date = $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));
                }
                if (isset($_POST['ReportForm']['end_date']) && $_POST['ReportForm']['end_date'] != '') {
                    $form->end_date          = $_POST['ReportForm']['end_date'];
                    $form_validate->end_date = $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
                }
                if ($form_validate->validate()) {
                    $data        = $model->searchRenuevePackageSingle(TRUE);
                    if ($form->on_detail == 'on') {
                        $data_detail = $model->searchDetailRenuevePackageSingle(TRUE);
                    }
                }
            }

            return $this->render('package', array('form' => $form, 'data' => $data, 'data_detail' => $data_detail, 'form_validate' => $form_validate));

        }

        /**
         * Báo cáo thuê bao
         */
        public function actionSubscribers()
        {
            $form                    = new ReportForm();
            $form_validate           = new ReportForm();
            $model                   = new ReportOci();
            $form_validate->scenario = "subscribers";
            $data_post               = $data_pre = array();
            if (isset($_POST['ReportForm']) || isset($_REQUEST['ReportForm'])) {
                if (!isset($_POST['ReportForm'])) {
                    $_POST['ReportForm'] = $_REQUEST['ReportForm'];
                }
                $form->date = $form_validate->date = $model->date = $_POST['ReportForm']['date'];

                if (isset($_POST['ReportForm']['date']) && $_POST['ReportForm']['date'] != '') {
                    $form->start_date          = $_POST['ReportForm']['start_date'];
                    $form_validate->start_date = $model->start_date
                        = date("Y-m-d", strtotime(str_replace('/', '-', $form->date))) . " 00:00:00";
                }
                if (isset($_POST['ReportForm']['date']) && $_POST['ReportForm']['date'] != '') {
                    $form->end_date          = $_POST['ReportForm']['end_date'];
                    $form_validate->end_date = $model->end_date
                        = date("Y-m-d", strtotime(str_replace('/', '-', $form->date))) . " 23:59:59";
                }
                if ($form_validate->validate()) {
                    //Trả sau.
//                    $new_roaming = $model->getSubscribers(ReportOci::NEW_ROAMING, ReportOci::SPS_PRODUCT_POST); // thuê bao mới
                    $active_post  = $model->getSubscribers(ReportOci::ACTIVE, ReportOci::SPS_PRODUCT_POST); // đang hoạt động
                    $cancel_post  = $model->getSubscribers(ReportOci::CANCEL, ReportOci::SPS_PRODUCT_POST); // Thuê bao hủy
                    $lock_ic_post = $model->getSubscribers(ReportOci::LOCK_IC, ReportOci::SPS_PRODUCT_POST); // Thuê bao hủy
                    $lock_oc_post = $model->getSubscribers(ReportOci::LOCK_OC, ReportOci::SPS_PRODUCT_POST); // Thuê bao hủy
                    $lock_2c_post = $model->getSubscribers(ReportOci::LOCK_2C, ReportOci::SPS_PRODUCT_POST); // Thuê bao hủy


                    $data_post = array_merge($data_post, $active_post);
                    $data_post = array_merge($data_post, $cancel_post);
                    $data_post = array_merge($data_post, $lock_ic_post);
                    $data_post = array_merge($data_post, $lock_oc_post);
                    $data_post = array_merge($data_post, $lock_2c_post);
                    $data_post = self::controlDataSubscribers($data_post);

                    //Trả trước.
                    $active_pre  = $model->getSubscribers(ReportOci::ACTIVE, ReportOci::SPS_PRODUCT_VINA690); // đang hoạt động
                    $cancel_pre  = $model->getSubscribers(ReportOci::CANCEL, ReportOci::SPS_PRODUCT_VINA690); // Thuê bao hủy
                    $lock_ic_pre = $model->getSubscribers(ReportOci::LOCK_IC, ReportOci::SPS_PRODUCT_VINA690); // Thuê bao hủy
                    $lock_oc_pre = $model->getSubscribers(ReportOci::LOCK_OC, ReportOci::SPS_PRODUCT_VINA690); // Thuê bao hủy
                    $lock_2c_pre = $model->getSubscribers(ReportOci::LOCK_2C, ReportOci::SPS_PRODUCT_VINA690); // Thuê bao hủy


                    $data_pre = array_merge($data_pre, $active_pre);
                    $data_pre = array_merge($data_pre, $cancel_pre);
                    $data_pre = array_merge($data_pre, $lock_ic_pre);
                    $data_pre = array_merge($data_pre, $lock_oc_pre);
                    $data_pre = array_merge($data_pre, $lock_2c_pre);
                    $data_pre = self::controlDataSubscribers($data_pre);
                } else {
                    $form_validate->getErrors();
                }
            }

            return $this->render('subscribers', array('form' => $form, 'form_validate' => $form_validate, 'data_post' => $data_post, 'data_pre' => $data_pre));

        }

        /**
         * Báo cáo thuê bao
         */
        public function actionSubscribersByMsisdn()
        {
            $form                    = new ReportForm();
            $form_validate           = new ReportForm();
            $model                   = new ReportOci();
            $form_validate->scenario = "subscribers_by_msisdn";
            $data                    = $data_pre = array();
            if (isset($_POST['ReportForm']) || isset($_REQUEST['ReportForm'])) {
                if (!isset($_POST['ReportForm'])) {
                    $_POST['ReportForm'] = $_REQUEST['ReportForm'];
                }
                $form->msisdn              = $form_validate->msisdn = $model->msisdn = $_POST['ReportForm']['msisdn'];
                $form_validate->start_date = date('d/m/Y');
                $form_validate->end_date   = date('d/m/Y');
                $form_validate->date       = date('d/m/Y');
                if ($form_validate->validate()) {
                    //Trả sau.
                    $data = $model->getSubscribersByMsisdn($form->msisdn); // thuê bao mới
                    $data = new CArrayDataProvider($data, array(
                        'keyField' => FALSE,
                    ));
                } else {

                    $form_validate->getErrors();
                }
            }

            return $this->render('msisdn_tool', array('form' => $form, 'form_validate' => $form_validate, 'data' => $data));

        }

        /**
         * Xử lý dữ liệu đầu ra báo cáo thuê bao
         */
        public function controlDataSubscribers($data)
        {
            $matinh = array();
            $result = array();
            foreach ($data as $value) {

                if (!in_array($value['MATINH'], $matinh)) {
                    array_push($matinh, $value['MATINH']);
                }
            }
            foreach ($matinh as $value_ma) {
                $result_key = array(
                    'MATINH'  => '',
                    'ACTIVE'  => 0,
                    'CANCEL'  => 0,
                    'LOCK_IC' => 0,
                    'LOCK_OC' => 0,
                    'LOCK_2C' => 0,
                    'RESTORE' => 0,
                );
                foreach ($data as $value) {
                    if ($value['MATINH'] == $value_ma) {
                        $result_key['MATINH'] = $value_ma;
                        if ($value['STATUS'] == ReportOci::ACTIVE) {
                            $result_key['ACTIVE'] += $value['TOTAL'];
                        }
                        if ($value['STATUS'] == ReportOci::CANCEL) {
                            $result_key['CANCEL'] += $value['TOTAL'];
                        }
                        if ($value['STATUS'] == ReportOci::LOCK_IC) {
                            $result_key['LOCK_IC'] += $value['TOTAL'];
                        }
                        if ($value['STATUS'] == ReportOci::LOCK_OC) {
                            $result_key['LOCK_OC'] += $value['TOTAL'];
                        }
                        if ($value['STATUS'] == ReportOci::LOCK_2C) {
                            $result_key['LOCK_2C'] += $value['TOTAL'];
                        }
                    }
                }
                $result[] = $result_key;
            }

            return $result;
        }

        /**
         * Doanh thu nạp thẻ của thuê bao net trả về
         */
        public function actionCard()
        {
            $form          = new ReportForm();
            $form_validate = new ReportForm();
            $model         = new ReportOci();
            $data          = $data_detail = array();
            if (isset($_POST['ReportForm']) || isset($_REQUEST['ReportForm'])) {
                if (!isset($_POST['ReportForm'])) {
                    $_POST['ReportForm'] = $_REQUEST['ReportForm'];
                }
                $form->attributes      = $model->attributes = $_POST['ReportForm'];
                $form->price_card      = isset($_POST['ReportForm']['price_card']) ? $_POST['ReportForm']['price_card'] : '';
                $form->vnp_province_id = isset($_POST['ReportForm']['vnp_province_id']) ? $_POST['ReportForm']['vnp_province_id'] : '';
                if (isset($_POST['ReportForm']['start_date']) && $_POST['ReportForm']['start_date'] != '') {
                    $form->start_date          = $_POST['ReportForm']['start_date'];
                    $form_validate->start_date = $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));
                }
                if (isset($_POST['ReportForm']['end_date']) && $_POST['ReportForm']['end_date'] != '') {
                    $form->end_date          = $_POST['ReportForm']['end_date'];
                    $form_validate->end_date = $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
                }
                if ($form_validate->validate()) {
                    $data        = $model->getCardFreedooOverView();
                    $data        = new CArrayDataProvider($data, array(
                        'keyField'   => FALSE,
                        'pagination' => array(
                            'params'   => array(
                                "ReportForm[start_date]"      => $model->start_date,
                                "ReportForm[end_date]"        => $model->end_date,
                                "ReportForm[price_card]"      => $model->price_card,
                                "ReportForm[vnp_province_id]" => $model->vnp_province_id,
                            ),
                            'pageSize' => 200,
                        ),
                    ));
                    $data_detail = $model->getCardFreedooDetail();
                    $data_detail = new CArrayDataProvider($data_detail, array(
                        'keyField'   => FALSE,
                        'pagination' => array(
                            'params'   => array(
                                "ReportForm[start_date]"      => $model->start_date,
                                "ReportForm[end_date]"        => $model->end_date,
                                "ReportForm[price_card]"      => $model->price_card,
                                "ReportForm[vnp_province_id]" => $model->vnp_province_id,
                            ),
                            'pageSize' => 30,
                        ),
                    ));
                } else {
                    $form_validate->getErrors();
                }
            }

            return $this->render('card', array('form' => $form, 'data' => $data, 'data_detail' => $data_detail, 'model' => $model));

        }

        /**
         * Doanh thu nạp thẻ của thuê bao freedoo
         */
        public function actionCardFreedoo()
        {
            $form          = new ReportForm();
            $form_validate = new ReportForm();
            $model         = new Report();
            $data_overview = $data_detail = $card_overview = array();
            if (isset($_POST['ReportForm']) || isset($_REQUEST['ReportForm'])) {
                if (!isset($_POST['ReportForm'])) {
                    $_POST['ReportForm'] = $_REQUEST['ReportForm'];
                }
                $form->attributes     = $model->attributes = $_POST['ReportForm'];
                $form->price_card     = $model->price_card = isset($_POST['ReportForm']['price_card']) ? $_POST['ReportForm']['price_card'] : '';
                $form->card_type      = $model->card_type = isset($_POST['ReportForm']['card_type']) ? $_POST['ReportForm']['card_type'] : '';
                $form->payment_method = $model->payment_method = isset($_POST['ReportForm']['payment_method']) ? $_POST['ReportForm']['payment_method'] : '';
                $form->sim_freedoo    = $model->sim_freedoo = isset($_POST['ReportForm']['sim_freedoo']) ? $_POST['ReportForm']['sim_freedoo'] : '';

                if (isset($_POST['ReportForm']['start_date']) && $_POST['ReportForm']['start_date'] != '') {
                    $form->start_date          = $_POST['ReportForm']['start_date'];
                    $form_validate->start_date = $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));
                }
                if (isset($_POST['ReportForm']['end_date']) && $_POST['ReportForm']['end_date'] != '') {
                    $form->end_date          = $_POST['ReportForm']['end_date'];
                    $form_validate->end_date = $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
                }
                if ($form_validate->validate()) {

                    $data_overview = $model->getCardTopupFreeDooOverView();


                    $card_overview = $model->getCardFreeDooType();

                    $data_detail = $model->getCardFreeDooDetails();
                    $data_detail = new CArrayDataProvider($data_detail, array(
                        'keyField'   => FALSE,
                        'pagination' => array(
                            'params'   => array(
                                "ReportForm[start_date]"     => $model->start_date,
                                "ReportForm[end_date]"       => $model->end_date,
                                "ReportForm[price_card]"     => $model->price_card,
                                "ReportForm[payment_method]" => $model->payment_method,
                                "ReportForm[card_type]"      => $model->card_type,
//
                            ),
                            'pageSize' => 30,
                        ),

                    ));
//                    CVarDumper::dump($data_overview,10,true);die();
                } else {
                    $form_validate->getErrors();
                }
            }

            return $this->render('card_freedoo', array('form' => $form, 'data_overview' => $data_overview, 'data_detail' => $data_detail, 'card_overview' => $card_overview, 'model' => $model));

        }

        /**
         * Doanh thu gói cước linh hoạt.
         */
        public function actionPackageFlexible()
        {
            $form          = new ReportForm();
            $form_validate = new ReportForm();
            $model         = new Report();
            $data          = array();
            if (isset($_POST['ReportForm']) || isset($_REQUEST['ReportForm'])) {
                if (!isset($_POST['ReportForm'])) {
                    $_POST['ReportForm'] = $_REQUEST['ReportForm'];
                }
                if (isset($_POST['ReportForm']['start_date']) && $_POST['ReportForm']['start_date'] != '') {
                    $form->attributes  = $model->attributes = $_POST['ReportForm'];
                    $form->start_date  = $_POST['ReportForm']['start_date'];
                    $model->start_date = $form_validate->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));

                }
                if (isset($_POST['ReportForm']['end_date']) && $_POST['ReportForm']['end_date'] != '') {
                    $form->end_date  = $_POST['ReportForm']['end_date'];
                    $model->end_date = $form_validate->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
                }
                $model->period        = $_POST['ReportForm']['period'];
                $model->package_group = $_POST['ReportForm']['package_group'];
                $model->package_id    = $_POST['ReportForm']['package_id'];
                if ($form_validate->validate()) {
                    $data = $model->getInfoPackageFlexible();
                    $data = new CArrayDataProvider($data, array(
                        'keyField'   => FALSE,
                        'pagination' => array(
                            'params'   => array(
                                'get'                       => 1,
                                'ReportForm[start_date]'    => $model->start_date,
                                'ReportForm[end_date]'      => $model->end_date,
                                "ReportForm[period]"        => $model->period,
                                "ReportForm[package_group]" => $model->package_group,
                                "ReportForm[package_id]"    => $model->package_id,
                            ),
                            'pageSize' => 10,
                        )));
                } else {
                    $form_validate->getErrors();
                }
            }

            return $this->render('package_flexible', array('form' => $form, 'data' => $data, 'form_validate' => $form_validate));
        }

        /**
         * Doanh thu gói cước linh hoạt.
         */
        public function actionPackageSimKit()
        {
            $form          = new ReportForm();
            $form_validate = new ReportForm();
            $model         = new Report(FALSE);
            $data          = $data_detail = array();
            if (isset($_POST['ReportForm']) || isset($_REQUEST['ReportForm'])) {
                $form->attributes = $_POST['ReportForm'];
                if (!isset($_POST['ReportForm'])) {
                    $_POST['ReportForm'] = $_REQUEST['ReportForm'];
                }
                if ($_POST['ReportForm']['package_id'] != '') {
                    $model->package_id = $form->package_id = $_POST['ReportForm']['package_id'];
                }
                $form->attributes    = $model->attributes = $_POST['ReportForm'];
                $form->province_code = $model->province_code = $_POST['ReportForm']['province_code'];
                $form->brand_offices_id = $model->brand_offices_id = $_POST['ReportForm']['brand_offices_id'];
                $form->sale_office_code = $model->sale_office_code = $_POST['ReportForm']['sale_office_code'];
                $form->input_type       = $model->input_type = $_POST['ReportForm']['input_type'];
                $form->on_detail        = $model->on_detail = $_POST['ReportForm']['on_detail'];
                $form->sim_type         = $model->sim_type = $_POST['ReportForm']['sim_type'];
                $form->package_id       = $model->package_id = $_POST['ReportForm']['package_id'];

                if (isset($_POST['ReportForm']['start_date']) && $_POST['ReportForm']['start_date'] != '') {
                    $form->start_date          = $_POST['ReportForm']['start_date'];
                    $form_validate->start_date = $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->start_date)));
                }
                if (isset($_POST['ReportForm']['end_date']) && $_POST['ReportForm']['end_date'] != '') {
                    $form->end_date          = $_POST['ReportForm']['end_date'];
                    $form_validate->end_date = $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $form->end_date)));
                }
                if ($form_validate->validate()) {
                    $data = $model->searchRenuevePackageSimKit(TRUE);
                    if ($form->on_detail == 'on') {
                        $data_detail = $model->searchDetailRenuevePackageSimKit(TRUE);
                    }
                }
            }

            return $this->render('package_simkit', array(
                'form' => $form,
                'data' => $data,
                'data_detail' => $data_detail,
                'form_validate' => $form_validate
            ));
        }

        public function actionOnlinePaid()
        {
            $form              = new ReportForm();
            $form_validate     = new ReportForm();
            $model             = new Report();
            $data              = array();
            $form->status_type = 1;
            if (isset($_POST['ReportForm']) || isset($_REQUEST['ReportForm'])) {
                if (!isset($_POST['ReportForm'])) {
                    $_POST['ReportForm'] = $_REQUEST['ReportForm'];
                }
                $form->province_code    = $model->province_code = $_POST['ReportForm']['province_code'];
                $form->brand_offices_id = $model->brand_offices_id = $_POST['ReportForm']['brand_offices_id'];
                $form->sale_office_code = $model->sale_office_code = $_POST['ReportForm']['sale_office_code'];
                $form->input_type       = $model->input_type = $_POST['ReportForm']['input_type'];
                $form->payment_method   = $model->payment_method = $_POST['ReportForm']['payment_method'];
                $form->sim_type         = $model->sim_type = $_POST['ReportForm']['sim_type'];
                $form->online_status    = $model->online_status = $_POST['ReportForm']['online_status'];
                $form->paid_status      = $model->paid_status = $_POST['ReportForm']['paid_status'];
                $form->status_type      = $model->status_type = $_POST['ReportForm']['status_type'];

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
                    $data = $model->getOnlinePaidData();


                    $data = new CArrayDataProvider($data, array(
                        'keyField'   => FALSE,
                        'pagination' => array(
                            'params'   => array(
                                'get'                          => 1,
                                'ReportForm[start_date]'       => $model->start_date,
                                'ReportForm[end_date]'         => $model->end_date,
                                "ReportForm[brand_offices_id]" => $model->brand_offices_id,
                                "ReportForm[sale_office_code]" => $model->sale_office_code,
                                "ReportForm[province_code]"    => $model->province_code,
                                "ReportForm[sim_type]"         => $model->sim_type,
                                "ReportForm[paid_status]"      => $model->paid_status,
                                "ReportForm[status_type]"      => $model->status_type,
                                "ReportForm[input_type]"       => isset($model->input_type) ? $model->input_type : '',
                                "ReportForm[payment_method]"   => isset($model->payment_method) ? $model->payment_method : '',
                                "ReportForm[online_status]"    => isset($model->online_status) ? $model->online_status : '',
                            ),
                            'pageSize' => 30,
                        ),

                    ));
                } else {
                    $form_validate->getErrors();
                }
            }

            return $this->render('online_paid', array('form' => $form, 'data' => $data, 'form_validate' => $form_validate));
        }

        /**
         * Lấy gói cước theo nhóm.
         */
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

        /**
         * Lấy gói cước linh hoạt theo nhóm.
         */
        public function actionGetPackageByGroupFlexible()
        {
            $package_group       = Yii::app()->getRequest()->getParam("package_group", FALSE);
            $period              = Yii::app()->getRequest()->getParam("period", '');
            $criteria            = new CDbCriteria();
            $criteria->condition = "1=1";
            if ($period != '') {
                $criteria->addCondition("period='" . $period . "' AND type='" . $package_group . "'");
            } else {
                $criteria->addCondition("type='" . $package_group . "'");
            }

            $data   = RPackage::model()->findAll($criteria);
            $result = CHtml::listData($data, 'code', 'name');
            echo "<option value=''>Chọn tất cả</option>";
            foreach ($result as $k => $v) {
                echo CHtml::tag('option', array('value' => $k), CHtml::encode($k), TRUE);
            }

        }

        /**
         * Lấy gói cước linh hoạt theo chu kì.
         */
        public function actionGetPackageByPeriodFlexible()
        {
            $period              = Yii::app()->getRequest()->getParam("period", FALSE);
            $package_group       = Yii::app()->getRequest()->getParam("package_group", '');
            $criteria            = new CDbCriteria();
            $criteria->condition = "1=1";
            if ($period != '') {
                if ($package_group != '') {
                    $criteria->addCondition("period='" . $period . "' AND type ='" . $package_group . "'");
                } else {
                    $criteria->addCondition("period='" . $period . "' AND type IN('" . ReportForm::FLEXIBLE_CALL_INT . "',
                                    '" . ReportForm::FLEXIBLE_CALL_EXT . "',
                                    '" . ReportForm::FLEXIBLE_SMS_INT . "',
                                    '" . ReportForm::FLEXIBLE_SMS_EXT . "',
                                    '" . ReportForm::FLEXIBLE_DATA . "')");
                }
            }
            $data   = RPackage::model()->findAll($criteria);
            $result = CHtml::listData($data, 'code', 'name');
            echo "<option value=''>Chọn tất cả</option>";
            foreach ($result as $k => $v) {
                echo CHtml::tag('option', array('value' => $k), CHtml::encode($k), TRUE);
            }
        }
    }

?>