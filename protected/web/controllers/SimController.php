<?php

class SimController extends Controller
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
        $this->pageImage       = 'http://' . SERVER_HTTP_HOST . Yii::app()->theme->baseUrl . '/images/slider1.jpg';
        $this->pageDescription = Yii::t('web/portal', 'page_description');
    }

    public function actionIndex()
    {
        $this->pageTitle = 'Sản phẩm - Sim số';
        $searchForm      = new SearchForm();
        $type_sim = $_GET['t'];
        $source = null;

        if(isset(Yii::app()->request->cookies['utm_source'])){
            $code = Yii::app()->request->cookies['utm_source']->value;
            $affiliate = WAffiliateManager::model()->findByAttributes(array('code' => $code, 'status' => WAffiliateManager::AFF_ACTIVE));
            if($affiliate && !empty($affiliate->default_source)){
                $searchForm->source = $affiliate->default_source;
            }
        }

        if(isset($_GET['source'])){
            $searchForm->source = $_GET['source'];
        }

        if (isset($_GET['package'])){
            Yii::app()->session['ss_package_id'] = $_GET['package'];
        }else{
            unset(Yii::app()->session['ss_package_id']);
        }

        if (!isset($_GET['ajax'])) {//pagination
            if (WOrders::checkOrdersSessionExists() === FALSE) {//orders_data exists
                OtpForm::unsetSession();
                $data_input = array(
                    'prefix' => '8488',
                    'search' => '',
                    'sim_type' => $type_sim,
                    'source' => $searchForm->source,
                );

                //call api
                $orders_data = new OrdersData();
                $data_output = $orders_data->searchMsisdn($data_input);
//CVarDumper::dump($data_output,10,true);
                $orders_data->sim_raw_data         = $data_output;

                if(isset($type_sim) && $type_sim == 'esim'){
                    $orders_data->sim_type = WOrders::ESIM;
                }else{
                    $orders_data->sim_type = WOrders::NOTESIM;
                }
                Yii::app()->session['orders_data'] = $orders_data;//set session sim_raw_data
            } else {
                $orders_data = Yii::app()->session['orders_data'];
                $data_output = $orders_data->sim_raw_data;
            }
        } else {
            $orders_data = Yii::app()->session['orders_data'];
            $data_output = $orders_data->sim_raw_data;
        }
        $this->render('index', array(
            'searchForm' => $searchForm,
            'data'       => $this->getSearchingMsisnd($data_output),
            'type_sim'   => $type_sim,
        ));
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
                            'prefix' => $searchForm->prefix_msisdn,
                            'search' => $searchForm->suffix_msisdn,
                            'sim_type' => Yii::app()->session['orders_data']->sim_type,
                            'source' => $searchForm->source,
                        );

                        //call api
                        $orders_data = new OrdersData();
                        $data_output = $orders_data->searchMsisdn($data_input);

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
        $isMobile = $this->isMobile;
        if ($isMobile){
            $view = '_mobile_list_msisdn_tab';
        }else{
            $view = '_list_msisdn';
        }
        echo $this->renderPartial($view, array(
            'data' => $this->getSearchingMsisnd($data_output, $isMobile),
            'msg'  => $msg,
            'isMobile' => $isMobile
        ), TRUE);
        if (Yii::app()->session['search_msisdn_count'] > 4) { // sau 4 lan search thi hien captcha
            echo "<script> $('#captcha_place_holder').css('display','block')</script>";
//                echo "<script> grecaptcha.render('captcha_place_holder',{'sitekey':'6LdnWS4UAAAAAAyy0Odc6bAuWs8wEm6BD9A6h66t'});</script>";
        }

        Yii::app()->end();
    }

    /*
     * return searching result for web and mobile
     * $data_output : (object)
     * */
    public function getSearchingMsisnd($data_output){
        $data_prepaid = [];
        $data_postpaid = [];
        if ($this->isMobile && !empty($data_output)) {
            foreach ($data_output as $key => $value){
                if($value['msisdn_type'] == 1){
                    array_push($data_prepaid, $value);
                }else{
                    array_push($data_postpaid, $value);
                }
            }
            $data['data_prepaid'] = $data_prepaid;
            $data['data_postpaid'] = $data_postpaid;
        } else {
            $pagination = array(
                'pageSize' => 20,
            );
            $data = new CArrayDataProvider($data_output, array(
                'keyField'   => FALSE,
                'pagination' => $pagination,
            ));
        }
        return $data;
    }

    public function actionAddtocart()
    {
        if(!empty(Yii::app()->request->cookies['utm_source']) && Yii::app()->request->cookies['utm_source']->value == 'chonsovnp'){
            unset(Yii::app()->request->cookies['utm_source']);
            unset(Yii::app()->request->cookies['aff_sid']);
        }
        $sim_number    = Yii::app()->request->getParam('sim_number', '');
        $sim_price     = Yii::app()->request->getParam('sim_price', '');
        $sim_type      = Yii::app()->request->getParam('sim_type', '');
        $sim_term      = Yii::app()->request->getParam('sim_term', '');
        $sim_priceterm = Yii::app()->request->getParam('sim_priceterm', '');
        $sim_store     = Yii::app()->request->getParam('sim_store', '');
        $iframe        = Yii::app()->request->getParam('iframe', '');
        $result        = array('error_code' => 1, 'url' => '', 'msg' => '',);

        if ($sim_number && $sim_type && $sim_store) {
            $data_input = array(
                'so_tb' => $sim_number,
                'store' => $sim_store,
            );

            $sim       = new WSim();
            $cache_key = '';
            if ($iframe) {//check iframe get orders_data from cache
                if (isset(Yii::app()->request->cookies['orders_data_cache_key'])
                    && !empty(Yii::app()->request->cookies['orders_data_cache_key']->value)
                ) {
                    $cache_key   = Yii::app()->request->cookies['orders_data_cache_key']->value;
                    $orders_data = Yii::app()->redis_orders_data->get($cache_key);
                }
            } else {
                $orders_data = Yii::app()->session['orders_data'];
            }
            if (isset($orders_data) && isset($orders_data->sim_raw_data) && $orders_data->checkSimInRawData($sim_number, $sim_type, $sim_price, $orders_data->sim_raw_data, $sim)) {

                $addToCartResult = $orders_data->addToCart($data_input);
//                CVarDumper::dump($addToCartResult,10,true);die();
                // neu co ma xac thuc sim tra ve
                if (isset($addToCartResult['mtx']) && !empty($addToCartResult['mtx'])) {
                    $orders_data->operation = OrdersData::OPERATION_BUYSIM;

                    $modelOrder      = new WOrders();
                    $modelOrder->otp = $addToCartResult['mtx'];
                    $modelOrder->id  = $modelOrder->generateOrderId();
                    //sso_id
                    if (!Yii::app()->user->isGuest) {
                        $modelOrder->sso_id = Yii::app()->user->sso_id;
                    }
                    //check cookie campaign
                    if (isset(Yii::app()->request->cookies['campaign_source']) && !empty(Yii::app()->request->cookies['campaign_source'])) {
                        $modelOrder->campaign_source = Yii::app()->request->cookies['campaign_source']->value;
                    }
                    if (isset(Yii::app()->request->cookies['campaign_id']) && !empty(Yii::app()->request->cookies['campaign_id'])) {
                        $modelOrder->campaign_id = Yii::app()->request->cookies['campaign_id']->value;
                    }

                    if ($sim->type == WSim::TYPE_POSTPAID) {
                        $orders_data->package = NULL;
                    }
                    $orders_data->orders = $modelOrder;
                    $orders_data->sim    = $sim;

                    if ($iframe) {
                        //set session: count_down
                        Yii::app()->session['session_cart'] = time();
                        //set redis cache orders data
                        $key                                                  = 'orders_data_iframe_' . $modelOrder->id;//check receiver/comfirmPaymentIframe
                        $orders_data_cache                                    = new CHttpCookie('orders_data_cache_key', $key);
                        $orders_data_cache->expire                            = time() + Yii::app()->params->cache_timeout_config['cart_iframe'];//30'
                        Yii::app()->request->cookies['orders_data_cache_key'] = $orders_data_cache;
                        Yii::app()->redis_orders_data->set($key, $orders_data, Yii::app()->params->cache_timeout_config['cart_iframe']);//30'
                        $url_checkout = $this->createUrl('checkout/checkoutIframe');
                    } else {
                        //set session
                        Yii::app()->session['orders_data']  = $orders_data;
                        Yii::app()->session['session_cart'] = time();
                        $url_checkout                       = $this->createUrl('checkout/checkout');
                    }
                    $result = array(
                        'error_code' => 0,
                        'url'        => $url_checkout,
                        'msg'        => '',
                    );
                } else {
                    if($addToCartResult == "-1 | STK-915"){
                        $result = array(
                            'error_code' => 1,
                            'url'        => '',
                            'msg'        => Yii::t('web/portal', 'add_to_cart_fail_khdn'),
                        );
                    }else{
                        $result = array(
                            'error_code' => 1,
                            'url'        => '',
                            'msg'        => Yii::t('web/portal', 'add_to_cart_fail'),
                        );
                    }
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
                    $orders_data                       = new OrdersData();
                    $orders_data->sim_raw_data         = $sim_raw_data;
                    Yii::app()->session['orders_data'] = $orders_data;//set session sim_raw_data

                    $flag = TRUE;
                    if ($curr_controller == 'checkoutapi' && $curr_action == 'checkout') {
                        $url_redirect = 'http://chonso.vinaphone.com.vn/p/chonso/chon-so-ca-nhan.html';
                    }
                    if ($curr_controller == 'checkout' && $curr_action == 'checkout') {
                        $url_redirect = $this->createUrl('sim/index');
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
        if (isset($_POST['SearchForm'])) {
            $msg = CActiveForm::validate($model);
        }

        return CJSON::decode($msg);
    }

    /**
     * action default iframe
     * sim page default
     */
    public function actionIframe()
    {
        $this->pageTitle = 'Sản phẩm - Sim số';
        $this->layout    = '/layouts/main_iframe';
        $searchForm      = new SearchForm();

        if (isset(Yii::app()->request->cookies['orders_data_cache_key'])
            && !empty(Yii::app()->request->cookies['orders_data_cache_key']->value)
        ) {
            $key         = Yii::app()->request->cookies['orders_data_cache_key']->value;
            $orders_data = Yii::app()->redis_orders_data->get($key);
        }
        $data_output = array();
        if (!isset($_GET['ajax'])) {//pagination
            if (empty($orders_data)) {//orders_data empty
                OtpForm::unsetSession();
                $data_input = array(
                    'prefix' => '8488',
                    'search' => '',
                );

                //call api
                $orders_data = new OrdersData();
                $data_output = $orders_data->searchMsisdn($data_input);

                $orders_data->sim_raw_data = $data_output;

                //set redis cache orders data
                $cache_key                                            = 'orders_data_iframe_' . time() . rand(1000, 9999);
                $orders_data_cache                                    = new CHttpCookie('orders_data_cache_key', $cache_key);
                $orders_data_cache->expire                            = time() + Yii::app()->params->cache_timeout_config['cart_iframe'];//30'
                Yii::app()->request->cookies['orders_data_cache_key'] = $orders_data_cache;

                Yii::app()->redis_orders_data->set($cache_key, $orders_data, Yii::app()->params->cache_timeout_config['cart_iframe']);//30'
            } else {
                if (isset($orders_data) && isset($orders_data->sim_raw_data)) {
                    $data_output = $orders_data->sim_raw_data;
                }
            }
        } else {
            if (isset($orders_data) && isset($orders_data->sim_raw_data)) {
                $data_output = $orders_data->sim_raw_data;
            }
        }

        if ($this->isMobile && !empty($data_output)) {
            $pagination = FALSE;
        } else {
            $pagination = array(
                'pageSize' => 20,
            );
        }
        $data = new CArrayDataProvider($data_output, array(
            'keyField'   => FALSE,
            'pagination' => $pagination,
        ));

        $this->render('sim_iframe', array(
            'searchForm' => $searchForm,
            'data'       => $data,
        ));
    }

    /**
     * action: search ajax msisdn with iframe
     * renderPartial to view sim/_list_msisdn_iframe
     */
    public function actionSearchIframe()
    {
        $this->layout = '/layouts/main_iframe';
        $searchForm   = new SearchForm();
        $data_output  = array();
        $orders_data  = array();
        $msg          = '';
        $key          = '';
        $this->performAjaxValidation($searchForm);
        if (!isset(Yii::app()->session['search_msisdn_count_iframe'])) {
            Yii::app()->session['search_msisdn_count_iframe'] = 0;
        }
        if (isset(Yii::app()->request->cookies['orders_data_cache_key'])
            && !empty(Yii::app()->request->cookies['orders_data_cache_key']->value)
        ) {
            $key         = Yii::app()->request->cookies['orders_data_cache_key']->value;
            $orders_data = Yii::app()->redis_orders_data->get($key);
        }
        if (!isset($_GET['ajax'])) {
            if (isset($_POST['SearchForm'])) {
                $searchForm->attributes = $_POST['SearchForm'];
                if ($searchForm->suffix_msisdn == '' && $searchForm->msisdn_type == '') {
                    $msg = Yii::t('web/portal', 'search_msisdn_empty');
                } else {
                    if (Yii::app()->session['search_msisdn_count_iframe'] > 4 || TRUE) { // verify captcha
                        if (!Utils::googleVerify(Yii::app()->params->secret_key)) {
                            $msg = Yii::t('web/portal', 'captcha_error');
                            $searchForm->addError('captcha', $msg);
                        }
                    }
                    if (!$searchForm->hasErrors()) {
                        OtpForm::unsetSession();
                        Yii::app()->redis_orders_data->delete($key);

                        $data_input = array(
                            'prefix' => $searchForm->prefix_msisdn,
                            'search' => $searchForm->suffix_msisdn,
                        );

                        //call api
                        $orders_data = new OrdersData();
                        $data_output = $orders_data->searchMsisdn($data_input);

                        $orders_data->sim_raw_data                        = $data_output;
                        Yii::app()->session['search_msisdn_count_iframe'] += 1;
                        //set redis cache orders data
                        $cache_key                                            = 'orders_data_iframe_' . time() . rand(1000, 9999);
                        $orders_data_cache                                    = new CHttpCookie('orders_data_cache_key', $cache_key);
                        $orders_data_cache->expire                            = time() + Yii::app()->params->cache_timeout_config['cart_iframe'];//30'
                        Yii::app()->request->cookies['orders_data_cache_key'] = $orders_data_cache;

                        Yii::app()->redis_orders_data->set($cache_key, $orders_data, Yii::app()->params->cache_timeout_config['cart_iframe']);//30'
                    }
                }
            }
        } else {
            if (!empty($orders_data->sim_raw_data)
            ) {
                $data_output = $orders_data->sim_raw_data;
            }
        }
        if ($this->isMobile && !empty($data_output)) {
            $pagination = FALSE;
        } else {
            $pagination = array(
                'pageSize' => 20,
            );
        }
        $data = new CArrayDataProvider($data_output, array(
            'keyField'   => FALSE,
            'pagination' => $pagination,
        ));

        echo $this->renderPartial('_list_msisdn_iframe', array(
            'data' => $data,
            'msg'  => $msg,
        ), TRUE);
        if (Yii::app()->session['search_msisdn_count_iframe'] > 4) { // sau 4 lan search thi hien captcha
            echo "<script> $('#captcha_place_holder').css('display','block')</script>";
//                echo "<script> grecaptcha.render('captcha_place_holder',{'sitekey':'6LdnWS4UAAAAAAyy0Odc6bAuWs8wEm6BD9A6h66t'});</script>";
        }

        Yii::app()->end();
    }
    public function actionActiveQrCode($order_id = ''){
        $model = new WOrders();
        $sim = new WSim();
        $order_detail = [];
        $package_name = [];
        $shipper = [];
        $province_name = '';
        $sale_office_code_name = '';
        $total_revenue = '';
        $success = false;
        if($_POST && $_POST['WOrders']){
            $model->otp = $_POST['WOrders']['otp'];
            $order  = WOrders::model()->findByAttributes(array('id' => $order_id, 'otp' => $_POST['WOrders']['otp']));
            if($order){
                $model = $order;
                $success = true;
                $sim  = WSim::model()->findByAttributes(array('order_id' => $order_id));
                // get total_revenue
                $criteria = new CDbCriteria();
                $criteria->select = "SUM(price) as total_revenue";
                if (isset($sim) && $sim->type == WSim::TYPE_POSTPAID) {
                    $criteria->condition = "order_id ='" . $order_id . "' and type IN('sim','price_term','esim')";
                } else if (isset($sim) && $sim->type == WSim::TYPE_PREPAID) {
                    $criteria->condition = "order_id ='" . $order_id . "' and type IN('sim','package','esim')";
                } else {
                    $criteria->condition = "order_id ='" . $order_id . "' and type IN('sim','package','price_term','esim')";
                }
                // get order_detail
                $order_detail = WOrderDetails::model()->findAll($criteria);
                $total_revenue = !empty($order_detail) ? $order_detail[0]->total_revenue : '';
                // get package
                $package = WOrderDetails::model()->findByAttributes(array('order_id' => $order_id, 'type' => 'package'));
                $package_name = !empty($package) ? $package->item_name : '';

                $shipper = WShipper::getShipperDetail($order_id, $model->shipper_id);

                $province = WProvince::model()->findByAttributes(array('code' => $model->province_code));
                $province_name = !empty($province) ? $province->name : '';

                $sale_office_code = SaleOffices::model()->findByAttributes(array('code' => $model->sale_office_code));
                $sale_office_code_name = !empty($sale_office_code) ? $sale_office_code->name : '';
            }else{
                $model->addError('otp', Yii::t('web/portal', 'warning_required_otp'));
            }
        }

        $this->render('activeQrCode', array(
                'success' => $success,
                'model' => $model,
                'sim' => $sim,
                'total_revenue' => $total_revenue,
                'package' => $package_name ,
                'shipper' => $shipper,
                'province' => $province_name,
                'sale_office_code' => $sale_office_code_name,
            )
        );
    }


    public function actionGetListMsisdnPrefix()
    {
        $return = array(
            'dataHtml' => ''
        );
        $source = $_POST['source'];
        $list_prefix = SearchForm::getListMsisdnPrefixBySource($source);
        foreach ($list_prefix as $key => $value){
            $return['dataHtml'].= "<option value='$key'>".$value."</option>";
        }
        echo CJSON::encode($return);
        Yii::app()->end();
    }
} //end class