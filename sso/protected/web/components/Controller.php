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

        const CONTROLLER_CUSTOMER = 'customer';
        //action do not check redirect to changPassword in CustomerController
        const ACTION_CHANGEPASS = 'changePassword';
        public $theme_url = '';

        public function init()
        {
            parent::init();
            $this->theme_url = Yii::app()->theme->baseUrl;
            /*Fix xss*/

            Yii::$classMap = array_merge(Yii::$classMap, array(
                'CaptchaExtendedAction'    => Yii::getPathOfAlias('ext.captchaExtended') . DIRECTORY_SEPARATOR . 'CaptchaExtendedAction.php',
                'CaptchaExtendedValidator' => Yii::getPathOfAlias('ext.captchaExtended') . DIRECTORY_SEPARATOR . 'CaptchaExtendedValidator.php'
            ));
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
            if (!isset($_SERVER['HTTP_X_FORWARDED_PROTO'])){
                $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

                $this->redirect($redirect);
            }
            /*End Fix xss*/
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
        protected function beforeAction($action)
        {
            return parent::beforeAction($action);
        }

        /**
         * auto login with msisdn
         *
         * @param $current_msisdn
         *
         * @return bool
         */

        private function _trafficLog($channel_code, $campaign_id)
        {
            if (!isset(Yii::app()->session['isLogged'])) {
                $campaign = WCampaignConfigs::getByCampaign($channel_code, $campaign_id);

                if ($campaign) {
                    $current_url = Yii::app()->urlManager->parseUrl(Yii::app()->request);
                    $ary_request = explode('/', $current_url);

                    $data    = array(
                        'msisdn'      => isset(Yii::app()->session['session_data']->current_msisdn) ? Yii::app()->session['session_data']->current_msisdn : 'NULL',
                        'device_name' => 'NULL',
                        'brand'       => 'Freedoo',
                        'ua'          => Yii::app()->request->userAgent,
                        'ip'          => Yii::app()->request->userHostAddress,
                        'controller'  => $ary_request[0],
                        'action'      => $ary_request[1],
                        'channel'     => $campaign->utm_source,
                        'campaing'    => $campaign->utm_campaign,
                        'device_os'   => 'NULL',
                        'is_member'   => 0,
                    );

                    $mLogger = new TrafficLog($channel_code, TrafficLog::LOG_TYPE_PAGE_VIEW);
                    $mLogger->write($data);
                    Yii::app()->session['isLogged'] = TRUE;
                }
            }
        }


    }