<?php

    class SimkitController extends Controller
    {
        public $layout = '/layouts/main';

        private $isMobile = FALSE;

        public function init()
        {
            parent::init();
            $detect         = new MyMobileDetect();
            $this->isMobile = $detect->isMobile();
            if ($detect->isMobile()) {
                $this->layout = '/layouts/mobile_main';
            }
        }

        public function actionIndex()
        {
            $this->pageTitle = 'Sản phẩm - Bộ KIT';

            $list_package = WPackage::getListPackageByType(WPackage::PACKAGE_SIMKIT, 0, FALSE, NULL, 0, 0, 0, WPackage::ALL_PACKAGE);

            $this->render('index', array(
                'list_package' => $list_package,
            ));
        }

        public function actionDetail($id)
        {
            $package = WPackage::model()->find('id=:id AND type=:type', array(':id' => $id, ':type' => WPackage::PACKAGE_SIMKIT));
            if ($package) {
                //check price_discount
                if ($package->price_discount > 0) {
                    $package->price = $package->price_discount;
                } elseif ($package->price_discount == -1) {
                    $package->price = 0;
                }
                $this->pageTitle      = 'Bộ KIT - ' . CHtml::encode($package->name);
                $searchForm           = new SearchForm();
                $searchForm->stock_id = $package->stock_id;
                if (!isset($_GET['ajax'])) {//pagination
                    if (WOrders::checkOrdersSessionExists() === FALSE) {//orders_data exists
                        OtpForm::unsetSession();
                        $data_input = array(
                            'prefix'   => '8488',
                            'search'   => $searchForm->suffix_msisdn,
                            'stock_id' => $searchForm->stock_id,
                        );

                        //call api
                        $orders_data = new OrdersData();
                        $data_output = $orders_data->searchMsisdn($data_input, 'web_search_msisdn_ktv');

                        $orders_data->sim_raw_data         = $data_output;
                        $orders_data->package              = $package;//add package to orders_data=>checkout
                        $orders_data->package_sim_kit      = $package;
                        Yii::app()->session['orders_data'] = $orders_data;//set session sim_raw_data
                    } else {
                        $orders_data = Yii::app()->session['orders_data'];
                        $data_output = $orders_data->sim_raw_data;
                    }
                } else {
                    $orders_data = Yii::app()->session['orders_data'];
                    $data_output = $orders_data->sim_raw_data;
                }

                $data = new CArrayDataProvider($data_output, array(
                    'keyField'   => FALSE,
                    'pagination' => array(
                        'pageSize' => 20,
                    ),
                ));

                $this->render('detail', array(
                    'searchForm' => $searchForm,
                    'data'       => $data,
                    'package'    => $package,
                ));
            } else {
                $this->redirect($this->createUrl('simkit/index'));
            }
        }

        public function actionSearchAjax()
        {
            $searchForm  = new SearchForm();
            $data_output = array();
            $msg         = '';
            $this->performAjaxValidation($searchForm);
            if (!isset(Yii::app()->session['search_msisdn_count'])) {
                Yii::app()->session['search_msisdn_count'] = 0;
            }
            if (!isset($_GET['ajax'])) {
                if (isset($_POST['SearchForm'])) {
                    $searchForm->attributes = $_POST['SearchForm'];
                    if ($searchForm->suffix_msisdn == '' && $searchForm->msisdn_type == '') {
                        $msg = Yii::t('web/portal', 'search_msisdn_empty');
                    } else {
                        if (Yii::app()->session['search_msisdn_count'] > 4 || TRUE) { // verify captcha
                            if (!Utils::googleVerify(Yii::app()->params->secret_key)) {
                                $msg = Yii::t('web/portal', 'captcha_error');
                                $searchForm->addError('captcha', $msg);
                            }
                        }
                        if (!$searchForm->hasErrors()) {
                            OtpForm::unsetSession();

                            $data_input = array(
                                'prefix'   => $searchForm->prefix_msisdn,
                                'search'   => $searchForm->suffix_msisdn,
                                'stock_id' => $searchForm->stock_id,
                            );

                            //call api
                            $orders_data = new OrdersData();
                            $data_output = $orders_data->searchMsisdn($data_input, 'web_search_msisdn_ktv');

                            $orders_data->sim_raw_data                 = $data_output;
                            Yii::app()->session['orders_data']         = $orders_data;//set session sim_raw_data
                            Yii::app()->session['search_msisdn_count'] += 1;
                        }
                    }
                }
            } else {
                $orders_data = Yii::app()->session['orders_data'];
                $data_output = $orders_data->sim_raw_data;
            }
            $data = new CArrayDataProvider($data_output, array(
                'keyField'   => FALSE,
                'pagination' => array(
                    'pageSize' => 20,
                ),
            ));

            echo $this->renderPartial('_list_msisdn', array(
                'data' => $data,
                'msg'  => $msg,
            ), TRUE);
            if (Yii::app()->session['search_msisdn_count'] > 4) { // sau 4 lan search thi hien captcha
                echo "<script> $('#captcha_place_holder').css('display','block')</script>";
//                echo "<script> grecaptcha.render('captcha_place_holder',{'sitekey':'6LdnWS4UAAAAAAyy0Odc6bAuWs8wEm6BD9A6h66t'});</script>";
            }

            Yii::app()->end();
        }

        /**
         * Performs the AJAX validation.
         *
         * @param $model
         *
         * @return string
         */
        protected function performAjaxValidation($model)
        {
            $msg = '';
            if (isset($_POST['SearchForm'])) {
                $msg = CActiveForm::validate($model);
            }

            return CJSON::decode($msg);
        }

    }