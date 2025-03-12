<?php

    class ASimAgencyController extends AController
    {
        public $layout = '/layouts/main';

        public function init()
        {
            if (!ADMIN && !SUPER_ADMIN) {
                if (isset(Yii::app()->user->province_code)) {
                    if (Yii::app()->user->province_code != Yii::app()->params['CellPhoneS']) {
                        throw new CHttpException(403, 'Not authorization Exception!');
                    }
                }
            }
            parent::init();
        }

        /**
         * @return array action filters
         */
        public function filters()
        {
            return array(
//			'accessControl', // perform access control for CRUD operations
                //'postOnly + delete', // we only allow deletion via POST request
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

        public function actionIndex()
        {
            $searchForm = new ASearchForm();
            $data       = array();
            $data_output = array();

            $data = new CArrayDataProvider($data_output, array(
                'keyField'   => FALSE,
                'pagination' => array(
                    'pageSize' => 20
                )
            ));

            $this->render('index', array(
                'searchForm' => $searchForm,
                'data'       => $data,
                'data_output' => $data_output
            ));
        }

        public function actionSearchAjax()
        {
            $searchForm  = new ASearchForm();
            $data_output = array();
            $msg         = '';
            $this->performAjaxValidation($searchForm);

            if (!isset($_GET['ajax'])) {
                if (isset($_POST['ASearchForm'])) {
                    $searchForm->attributes = $_POST['ASearchForm'];
                    if ($searchForm->suffix_msisdn == '' && $searchForm->msisdn_type == '') {
                        $msg = Yii::t('adm/label', 'search_msisdn_empty');
                    } else {
                        if (!$searchForm->hasErrors()) {
                            OtpForm::unsetSession();

                            $data_input = array(
                                'prefix' => $searchForm->prefix_msisdn,
                                'search' => $searchForm->suffix_msisdn,
                            );
                            //call api.
                            $orders_data = new AOrdersData();
                            $data_output = $orders_data->searchMsisdnAgency($data_input);
                            if (!empty($data_output)) {
                                if (is_array($data_output)) {
                                    foreach ($data_output as $key => $value) {
                                        if ($value['msisdn_type'] == 2) {
                                            unset($data_output[$key]);
                                        }
                                    }
                                }
                            }

                            $orders_data->sim_raw_data = $data_output;

                            Yii::app()->session['orders_data'] = $orders_data;//set session sim_raw_data
                        }
                    }
                }
            } else {
                $orders_data = Yii::app()->session['orders_data'];
                $data_output = $orders_data->sim_raw_data;
            }

            if ($data_output) {
                $data = new CArrayDataProvider($data_output, array(
                    'keyField'   => FALSE,
                    'pagination' => array(
                        'pageSize' => 10,
                    ),
                ));
            } else {
                $data = array();
                $msg  = Yii::t('adm/label', 'search_result_empty');
            }

            echo $this->renderPartial('_list_msisdn', array(
                'data' => $data,
                'msg'  => $msg,
            ), TRUE);

            Yii::app()->end();
        }

        public function actionAddtocart()
        {
            $sim_number    = Yii::app()->request->getParam('sim_number', '');
            $sim_price     = Yii::app()->request->getParam('sim_price', '');
            $sim_type      = Yii::app()->request->getParam('sim_type', '');
            $sim_term      = Yii::app()->request->getParam('sim_term', '');
            $sim_priceterm = Yii::app()->request->getParam('sim_priceterm', '');
            $sim_store     = Yii::app()->request->getParam('sim_store', '');
            $result        = array('error_code' => 1, 'url' => '', 'msg' => '',);

            if ($sim_number && $sim_type && $sim_store) {
                $data_input = array(
                    'so_tb' => $sim_number,
                    'store' => $sim_store,
                );

                $sim = new ASim();

                $orders_data = Yii::app()->session['orders_data'];

                if (isset($orders_data->sim_raw_data) && $orders_data->checkSimInRawData($sim_number, $sim_type, $sim_price, $orders_data->sim_raw_data, $sim)) {

                    $addToCartResult = $orders_data->addToCart($data_input);

                    // neu co ma xac thuc sim tra ve
                    if (isset($addToCartResult['mtx']) && !empty($addToCartResult['mtx'])) {
                        $orders_data->operation = AOrdersData::OPERATION_BUYSIM;

                        $modelOrder      = new AOrders();
                        $modelOrder->otp = $addToCartResult['mtx'];
                        $modelOrder->id  = $modelOrder->generateOrderId();
                        //sso_id
//                        if (!Yii::app()->user->isGuest) {
//                            $modelOrder->sso_id = Yii::app()->user->sso_id;
//                        }
                        if ($sim->type == ASim::TYPE_POSTPAID) {
                            $orders_data->package = NULL;
                        }
                        $orders_data->orders     = $modelOrder;
                        $orders_data->sim        = $sim;
                        $orders_data->sim_agency = AOrdersData::BUY_SIM_AGENCY;
                        //set session
                        Yii::app()->session['orders_data']  = $orders_data;
                        Yii::app()->session['session_cart'] = time();

                        $result = array(
                            'error_code' => 0,
                            'url'        => $this->createUrl('aCheckout/checkout'),
                            'msg'        => '',
                        );
                    } else {
                        $result = array(
                            'error_code' => 1,
                            'url'        => '',
                            'msg'        => Yii::t('web/portal', 'add_to_cart_fail'),
                        );
                    }
                } else {

                    $result = array(
                        'error_code' => 1,
                        'url'        => '',
                        'msg'        => Yii::t('web/portal', 'add_to_cart_fail'),
                    );
                }
            }
            echo CJSON::encode($result);
            Yii::app()->end();
        }

        public function actionRemoveKeepMsisdn()
        {
            $curr_controller = Yii::app()->request->getParam('curr_controller', '');
            $curr_action     = Yii::app()->request->getParam('curr_action', '');
            $flag            = FALSE;
            $url_redirect    = '';
            if (isset(Yii::app()->session['orders_data'])) {
                $orders_data = Yii::app()->session['orders_data'];
                $modelSim    = $orders_data->sim;
                if ($modelSim && isset($modelSim->msisdn) && !empty($modelSim->msisdn) && isset($modelSim->store_id) && !empty($modelSim->store_id)) {
                    //call api
                    $data_input = array(
                        'so_tb' => $modelSim->msisdn,
                        'store' => $modelSim->store_id,
                    );

                    if ($orders_data->removeKeepMsisdn($data_input, $curr_controller . $curr_action)) {
                        $sim_raw_data = Yii::app()->session['orders_data']->sim_raw_data;
                        OtpForm::unsetSession();
                        $orders_data                       = new AOrdersData();
                        $orders_data->sim_raw_data         = $sim_raw_data;
                        Yii::app()->session['orders_data'] = $orders_data;//set session sim_raw_data

                        $flag = TRUE;
                        if ($curr_controller == 'aCheckout' && $curr_action == 'checkout') {
                            $url_redirect = $this->createUrl('aSimAgency/index');
                        }
                    }
                }
            }
            echo CJSON::encode(array(
                'status'       => $flag,
                'url_redirect' => $url_redirect
            ));
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
            if (isset($_POST['ASearchForm'])) {
                $msg = CActiveForm::validate($model);
            }

            return CJSON::decode($msg);
        }

        public function actionCreateLink()
        {
            if (AOrders::checkOrdersSessionExists() === FALSE) {
                AOrders::unsetSession();
                $this->redirect($this->createUrl('aSim/message', array('t' => 2)));
            } else {
                $modelTokenLink = new ATokenLinks();
                $orders_data    = Yii::app()->session['orders_data'];
                $modelOrder     = $orders_data->orders;
                $modelSim       = $orders_data->sim;
                //validate ajax
                if (isset($_POST['ajax']) && $_POST['ajax'] === 'ktv_create_link') {
                    $errors = CJSON::decode(CActiveForm::validate($modelTokenLink));
                    $errors += CJSON::decode(CActiveForm::validate($modelOrder));
                    echo CJSON::encode($errors);
                    Yii::app()->end();
                }

                if (isset($_POST['AOrders']) && isset($_POST['ATokenLinks'])) {
                    $modelOrder->attributes           = $_POST['AOrders'];
                    $modelOrder->active_cod           = isset($_POST['AOrders']['active_cod']) ? $_POST['AOrders']['active_cod'] : Orders::INACTIVE_COD;
                    $modelTokenLink->attributes       = $_POST['ATokenLinks'];
                    $modelTokenLink->order_id         = $modelOrder->id;
                    $modelTokenLink->pre_order_msisdn = $modelSim->msisdn;
                    $token                            = md5($modelSim->msisdn . time() . uniqid());
                    $modelTokenLink->id               = $token;
                    if (YII_DEBUG == TRUE) {
                        $modelTokenLink->link = 'http://' . $_SERVER['HTTP_HOST'] . '/vnpt_online/portal/source/link/' . $token;
                    } else {
                        $modelTokenLink->link = 'https://' . $_SERVER['HTTP_HOST'] . '/link/' . $token;
                    }
                    if ($modelTokenLink->validate() && $modelTokenLink->save()) {
                        $modelTokenLink->send_link_method = ATokenLinks::SEND_SMS;
                        $orders_data->cskh_token_link     = $modelTokenLink;
                        //set session
                        Yii::app()->session['orders_data'] = $orders_data;

                        $this->redirect($this->createUrl('aSim/updateLink'));
                    }
                }

                $this->render('create_link', array(
                    'modelOrder' => $modelOrder,
                    'model'      => $modelTokenLink,
                ));
            }
        }

        public function actionUpdateLink()
        {
            if (AOrders::checkOrdersSessionExists() === FALSE) {
                AOrders::unsetSession();
                $this->redirect($this->createUrl('aSim/message', array('t' => 2)));
            } else {
                $orders_data    = Yii::app()->session['orders_data'];
                $modelOrder     = $orders_data->orders;
                $modelSim       = $orders_data->sim;
                $modelTokenLink = $orders_data->cskh_token_link;
                if ($modelOrder && $modelSim && $modelTokenLink) {
                    if (isset($_POST['ATokenLinks'])) {
                        $modelTokenLink->send_link_method = $_POST['ATokenLinks']['send_link_method'];
                        if ($modelTokenLink->validate() && $modelTokenLink->save()) {
                            //set cache redis_ktv: $orders_data
                            $cache_timeout             = Yii::app()->params['sessionTimeout'] - (time() - Yii::app()->session['session_cart']);//time left
                            $orders_data->session_cart = $cache_timeout;
                            $key                       = 'ktv_add_cart_' . $modelTokenLink->id;
                            $flag                      = 0;

                            $content                       = Yii::t('adm/mt_content', 'token_link', array(
                                '{token_link}' => $modelTokenLink->link,
                            ));
                            $orders_data_new               = new CommonOrdersData();
                            $orders_data_new->sim_raw_data = $orders_data->sim_raw_data;
                            $orders_data_new->orders       = $modelOrder;
                            $orders_data_new->sim          = $modelSim;
                            $orders_data_new->session_cart = Yii::app()->session['session_cart'];
                            $orders_data_new->setCommonModel();
                            //check send_link_method
                            switch ($modelTokenLink->send_link_method) {
                                case ATokenLinks::SEND_SMS:
                                    if (YII_DEBUG == TRUE) {
                                        //set cache order_data
                                        Yii::app()->redis_ktv->set($key, $orders_data_new, $cache_timeout);//time left: $cache_timeout=20*60(20')
                                        $flag = 1;
                                    } else {
                                        //send MT token link
                                        if (OtpForm::sentMtVNP($modelTokenLink->customer_msisdn, $content, 'ktv_add_cart')) {
                                            //set cache order_data
                                            Yii::app()->redis_ktv->set($key, $orders_data_new, $cache_timeout);//time left: $cache_timeout=20*60(20')
                                            $flag = 1;
                                        } else {
                                            $flag = 3;
                                        }
                                    }
                                    break;
                                case ATokenLinks::SEND_EMAIL:
                                    //send mail token link
                                    $from = Yii::t('adm/label', 'lbl_from_mail', array('{msisdn}' => $modelTokenLink->customer_msisdn));
                                    if (OtpForm::sentMail($from, $modelTokenLink->customer_email, $content, 'ktv_add_cart')) {
                                        //set cache order_data
                                        Yii::app()->redis_ktv->set($key, $orders_data_new, $cache_timeout);//time left: $cache_timeout=20*60(20')
                                        $flag = 1;
                                    } else {
                                        $flag = 4;
                                    }
                                    break;
                                default:
                                    //set cache order_data
                                    Yii::app()->redis_ktv->set($key, $orders_data_new, $cache_timeout);//time left: $cache_timeout=20*60(20')
                                    $flag = 1;
                                    break;
                            }
                            if ($flag == 1) {//send success->update status
                                $modelTokenLink->status = ATokenLinks::STATUS_SUCCESS;
                                $modelTokenLink->save();
                            }

                            $this->redirect($this->createUrl('aSim/message', array('t' => $flag)));
                        }
                    }
                    $this->render('update_link', array(
                        'modelOrder' => $modelOrder,
                        'model'      => $modelTokenLink,
                    ));
                } else {
                    throw new CHttpException(404, 'The requested page does not exist.');
                }
            }
        }

        public function actionMessage($t = '')
        {
            /* 0: fail
             * 1: success
             * 2: session_timeout
             */

            AOrders::unsetSession();
            switch ($t) {
                case 1:
                    $msg = Yii::t('adm/label', 'create_token_link_success');
                    break;
                case 2:
                    $msg = Yii::t('adm/label', 'session_timeout');
                    break;
                case 3:
                    $msg = Yii::t('adm/label', 'send_mt_fail');
                    break;
                case 4:
                    $msg = Yii::t('adm/label', 'send_mail_fail');
                    break;
                default:
                    $msg = Yii::t('adm/label', 'create_token_link_fail');
            }
            $this->render('message', array(
                'msg' => $msg
            ));
        }

        public function actionTest($token)
        {
            $modelTokenLink = ATokenLinks::model()->find('id=:id AND status=:status', array(':id' => $token, ':status' => ATokenLinks::STATUS_SUCCESS));
            //get cache
            $key         = 'ktv_add_cart_' . $token;
            $orders_data = Yii::app()->redis_ktv->get($key);
            CVarDumper::dump($orders_data, 10, TRUE);
            die;
            if ($modelTokenLink) {
                //get cache
                $key          = 'ktv_add_cart_' . $token;
                $orders_data  = Yii::app()->redis_ktv->get($key);
                $modelOrder   = $orders_data->orders;
                $modelSim     = $orders_data->sim;
                $session_cart = $orders_data->session_cart;
                if ($modelOrder && $modelSim) {
                    Yii::app()->session['orders_data']  = $orders_data;
                    Yii::app()->session['session_cart'] = $session_cart;
                    //delete redis
                    CVarDumper::dump($session_cart / 60, 10, TRUE);
                    CVarDumper::dump($orders_data, 10, TRUE);
                    die;
                } else {
                    echo 'order||sim not found';
                }
            } else {
                echo 'token link not found';
            }
        }
    } //end class