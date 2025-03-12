<?php

    /**
     * Controller is the customized base controller class.
     * All controller classes for this application should extend from this base class.
     */
    class Controller extends CController
    {
        /**
         * @var string the default layout for the controller view. Defaults to '//layouts/column1',
         * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
         */
        public $layout          = '//layouts/column1';
        public $pageTitle       = '';
        public $pageKeyword     = '';
        public $pageDescription = '';
        public $pageImage       = '';

        const VINAPHONE_TELCO = 'VINAPHONE';
        const MOBIFONE_TELCO  = 'MOBIFONE';
        const VIETTEL_TELCO   = 'VIETTEL';
        const UNKNOWN_TELCO   = 'UNKNOWN_TELCO';

        const CTV         = 1;
        const CTV_PARTNER = '002';
        const USER        = 0;

        const IS_ADMIN  = 10;
        const SUB_ADMIN = 1;
        const NOT_ADMIN = 0;

        public $theme_url = '';

        public $algorithm = MCRYPT_RIJNDAEL_128;

        public function init()
        {
            //check verify captcha
            /*if ((!isset(Yii::app()->session['landing_verify']) || Yii::app()->session['landing_verify'] == FALSE)
                && Yii::app()->controller->id != 'landing'
            ) {
                $this->redirect($this->createUrl('landing/index'));
            }*/
            //end check verify captcha
            /*$arr_user = array(
                'launching' => 'freedoo@03112017',
            );

            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];
            if (!YII_DEBUG) {
                if (!in_array($username, array_keys($arr_user)) || ($password != $arr_user[$username])) {
                    header('WWW-Authenticate: Basic realm="http://freedoo.vnpt.vn/ Authentication System"');
                    header('HTTP/1.0 401 Unauthorized');
                    echo "You must enter a valid login ID and password to access this page\n";
                    exit;
                }
            }*/

            $this->theme_url = Yii::app()->theme->baseUrl;
            /*Fix xss*/
            if (isset($_GET) && count($_GET) > 0) {
                $p = new CHtmlPurifier();
                foreach ($_GET as $k => $v) {
                    $_GET[$k] = $p->purify($v);
                }
            }

            if (isset($_POST) && count($_POST) > 0) {
                $p = new CHtmlPurifier();
                foreach ($_POST as $k => $v) {
                    $_POST[$k] = $p->purify($v);
                }
            }
            /*End Fix xss*/

            Yii::app()->language = 'vi';

            // current session's data is exists
            if (!isset(Yii::app()->session['session_data']) || empty(Yii::app()->session['session_data'])) {
                Yii::app()->session['session_data'] = new stdClass();
            }

            //------- User's data -------//
            //detect telco connection
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $clientIP = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $clientIP = $_SERVER['REMOTE_ADDR'];
            }

            $channel_code = IP::detectTelco($clientIP, Yii::app()->params->IP_MAP_3G);

            Yii::app()->session['session_data']->channel_code = strtoupper($channel_code);

//            if(isset($_GET['v']) && $_GET['v']){
//                if($channel_code == "VINAPHONE" && (!isset(Yii::app()->session['session_data']->current_msisdn) || empty(Yii::app()->session['session_data']->current_msisdn))){
//                    //if((!isset(Yii::app()->session['session_data']->current_msisdn) || empty(Yii::app()->session['session_data']->current_msisdn))){
//                    if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) || strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == "https"){
//                        $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//                        $this->redirect($redirect);
//                    }else{
//                        if (isset($_SERVER['HTTP_MSISDN']) && strlen($_SERVER['HTTP_MSISDN']) > 8) {
//                            Yii::app()->session['session_data']->current_msisdn = $_SERVER['HTTP_MSISDN'];
//                            $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//                            $this->redirect($redirect);
//                        } else {
//                            unset(Yii::app()->session['session_data']->current_msisdn);
//                        }
//                    }
//                }else{
//                    if(empty($_SERVER['HTTP_X_FORWARDED_PROTO']) || $_SERVER['HTTP_X_FORWARDED_PROTO'] != "https"){
//                        $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//                        $this->redirect($redirect);
//                    }
//                }
//            }
            if(YII_DEBUG == FALSE && (empty($_SERVER['HTTP_X_FORWARDED_PROTO']) || $_SERVER['HTTP_X_FORWARDED_PROTO'] != "https" )){
                $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                $this->redirect($redirect);
            }

//            if (YII_DEBUG == TRUE) {
//                Yii::app()->session['session_data']->current_msisdn = '84919222400';
//            }
            if (Yii::app()->user->isGuest) { //Check auto login.
                if (isset(Yii::app()->session['session_data']->current_msisdn) && (Yii::app()->session['session_data']->current_msisdn != '')) {
                    $this->checkAutoLogin(Yii::app()->session['session_data']->current_msisdn);
                }
            }

            //Lấy controller,action hiện tại cho
            $current_url = Yii::app()->urlManager->parseUrl(Yii::app()->request);
            $ary_request = explode('/', $current_url);

            Yii::app()->session['session_data']->controller = isset($ary_request[0]) ? $ary_request[0] : '';
            Yii::app()->session['session_data']->action     = isset($ary_request[1]) ? $ary_request[1] : '';

            //check campaign from affiliate
            $this->setCookieFromAffiliate();

            $this->_trafficLog(Yii::app()->request->getParam('utm_source'), Yii::app()->request->getParam('utm_campaign'));

            //check sso_id
            if (!Yii::app()->user->isGuest && empty(Yii::app()->user->sso_id)) {
                Yii::app()->user->logout();
            }
        }

        /**
         * @var array context menu items. This property will be assigned to {@link CMenu::items}.
         */
        public $menu = array();
        /**
         * @var array the breadcrumbs of the current page. The value of this property will
         * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
         * for more details on how to specify this property.
         */

        public $breadcrumbs = array();

        /**
         * @param CAction $action
         *
         * @return bool
         */


        /**
         * auto login with msisdn
         *
         * @param $current_msisdn
         *
         * @return bool
         */
        public function checkAutoLogin($current_msisdn)
        {
            $customer = WCustomers::model()->find('phone=:phone', array(':phone' => CFunction::makePhoneNumberStandard($current_msisdn)));

            if ($customer) {
                $model_login             = new WLoginForm();
                $model_login->username   = $customer['phone'];
                $model_login->rememberMe = 1;
                if ($model_login->loginWithMsisdn()) {
                    return TRUE;
                }
            }

            return FALSE;
        }

        /**
         * @param CAction $action
         * Check action controller diffrent change-password and update-info.
         *
         * @return bool
         */
        public function beforeAction($action)
        {
            $this->check_login();

            if (parent::beforeAction($action)) {
                if (!isset(Yii::app()->user->customer_id)) {
                    if ($action->id == 'change-password' || $action->id == 'update-info') {
                        $this->redirect(Yii::app()->createUrl('site/index'));
                    } else
                        return TRUE;
                } else
                    return TRUE;
            }

        }

        public function afterAction($action)
        {
            if (Yii::app()->controller->id != 'checkout' && Yii::app()->controller->id != 'checkoutapi' ) {
                //check finish order

                if (WOrders::checkOrdersSessionExists()) {
                    $orders_data = Yii::app()->session['orders_data'];
                    $modelSim    = $orders_data->sim;
                    if ($modelSim && isset($modelSim->msisdn) && !empty($modelSim->msisdn) && isset($modelSim->store_id) && !empty($modelSim->store_id)) {
                        echo '<script>displayWarning();</script>';
                    }
                }
            }
            parent::afterAction($action);
        }

        /**
         *  CheckLogin Check session.
         */
        public function check_login()
        {
            $data = Yii::app()->getRequest()->getParam('data', FALSE);

            if ($data) {
                $key_aes = Yii::app()->params['aes_key'] . date('Ymdhi');
//                $key_aes      = Yii::app()->params['aes_key'];
                $data_decrypt = Utils::decrypt($data, $key_aes, $this->algorithm);
                $new          = FALSE;
                parse_str($data_decrypt, $data_parse_str); // Data parse string.

                if (isset($data_parse_str['user_id']) && isset($data_parse_str['username']) && isset($data_parse_str['password'])) {
                    $customer = WCustomers::model()->findByAttributes(array('username' => $data_parse_str['username']));
//                    if (isset($data_parse_str['is_new']) && $data_parse_str['is_new'] == 1) { // Nếu là tài khoản mới hoặc tài khoản được update bên OneID.
                    if (!$customer) {
                        $customer = new WCustomers();
                        $new      = TRUE;
                    }
                    $customer->attributes = $data_parse_str;

                    $customer->create_time = $data_parse_str['created_at'];
                    $customer->last_update = $data_parse_str['updated_at'];

                    $is_admin = self::NOT_ADMIN;

                    // Update customer_type CTV hay User trường hợp tạo mới user.
                    if (isset($data_parse_str['type'])) {
                        $customer->customer_type = $data_parse_str['type'];
                    }
                    //Set level sub_admin, admin, member.
                    if (isset($data_parse_str['is_admin'])) {
                        $customer->level = $data_parse_str['is_admin'];
                        $is_admin        = $data_parse_str['is_admin'];
                    }
                    $customer->sso_id = $data_parse_str['user_id'];
                    if ($customer->validate()) {
                        $customer->save();
                        if ($new == TRUE) {
                            if (date('Y-m-d H:i:s') >= '2018-02-12 00:00:00' && date('Y-m-d H:i:s') <= '2018-03-12 23:59:59') {
                                $customer::setPoint($data_parse_str['user_id'], 100, 'First member', 'First User');
                            }
                        }
                    }
                    // Login.
                    $login           = new WLoginForm();
                    $login->username = $data_parse_str['username'];
                    $login->password = $data_parse_str['password'];
                    if ($login->loginSSO()) {
                        Yii::app()->user->setState('is_admin', $is_admin);
                    }
                }
            }
        }

        public function setCookieFromAffiliate()
        {
            if (isset($_GET['utm_source'])) {
                $utm_source_value = $_GET['utm_source'];
                $aff_sid_value    = '';
                if ($utm_source_value == 'freedoo') {
                    if (isset($_GET['vsb_click_id'])) {
                        $aff_sid_value = $_GET['vsb_click_id'];
                    }
                } else {
                    if (isset($_GET['aff_sid'])) {
                        $aff_sid_value = $_GET['aff_sid'];
                    }
                }
                if ($utm_source_value && $aff_sid_value) {
                    //check affiliate exists
                    if (WAffiliateManager::getAffiliateByCode($utm_source_value)) {
                        if($utm_source_value == 'Zalo'){
                            $utm_source = '';
                            $aff_sid = '';
                        }else{
                            $utm_source         = new CHttpCookie('utm_source', $utm_source_value);
                            $aff_sid            = new CHttpCookie('aff_sid', $aff_sid_value);
                            $utm_source->expire = time() + 60 * 60 * 24 * 30;//30 days
                            $aff_sid->expire    = time() + 60 * 60 * 24 * 30;//30 days

                            Yii::app()->request->cookies['utm_source'] = $utm_source;
                            Yii::app()->request->cookies['aff_sid']    = $aff_sid;
                        }
                    }
                }
            }
        }

        private function _trafficLog($channel_code, $campaign_id)
        {

            if (!isset(Yii::app()->session['isLogged'])) {
                $campaign = WCampaignConfigs::getByCampaign($channel_code, $campaign_id);

                if ($campaign) {
                    //set cookie campaign
                    $utm_source         = new CHttpCookie('campaign_source', $channel_code);
                    $aff_sid            = new CHttpCookie('campaign_id', $campaign_id);
                    $utm_source->expire = time() + 60 * 60 * 24 * 180;//30 days
                    $aff_sid->expire    = time() + 60 * 60 * 24 * 180;//30 days

                    Yii::app()->request->cookies['campaign_source'] = $utm_source;
                    Yii::app()->request->cookies['campaign_id']     = $aff_sid;
                    //set cookie campaign

                    $current_url = Yii::app()->urlManager->parseUrl(Yii::app()->request);
                    $ary_request = explode('/', $current_url);

                    $data = array(
                        'msisdn'      => 'NULL',
                        'device_name' => 'NULL',
                        'brand'       => 'FREEDOO',
                        'ua'          => Yii::app()->request->userAgent,
                        'ip'          => Yii::app()->request->userHostAddress,
                        'controller'  => $ary_request[0],
                        'action'      => $ary_request[1],
                        'channel'     => $campaign->utm_source,
                        'campaign'    => $campaign->utm_campaign,
                        'device_os'   => 'NULL',
                        'is_member'   => 0,
                    );

                    $logObj = new TraceLogTraffic(NULL, 'pageview');
                    $logObj->setLogFile($_SERVER['SERVER_ADDR'] . '_' . date("Ymd") . '.log');
                    $logObj->processWriteLogs($data);

                    Yii::app()->session['isLogged'] = TRUE;


                }
            }
            $campaign_redirect = WCampaignConfigs::getByCampaign($channel_code, $campaign_id);
            if (Yii::app()->request->getParam('utm_source') && Yii::app()->request->getParam('utm_campaign')) {
                if (Yii::app()->request->cookies['campaign_id'] != 'homepage') {
                    $redirect_link = $campaign_redirect->target_link . "/?&utm_source=" .
                        $campaign_redirect->utm_source . "&utm_campaign=" . $campaign_redirect->utm_campaign;

                    $curpage = Yii::app()->getBaseUrl(TRUE) . Yii::app()->request->requestUri;
                    if (Yii::app()->getBaseUrl(TRUE) == 'http://118.70.177.77:8694/vnpt_online/portal/source') {
                        $curpage = 'http://118.70.177.77:8694' . Yii::app()->request->requestUri;
                    }

                    if ($redirect_link != $curpage) {
                        return $this->redirect($redirect_link);
                    }
                }
            }
        }

    }