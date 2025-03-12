<?php

    /**
     * Created by PhpStorm.
     * User: staff
     * Date: 24/12/2018
     * Time: 9:49 AM
     */
    class Zalo
    {
        public function addToCart($params){

            $results = [];
            $file_name = 'addtocart';
            $logMsg    = array();
            $logMsg[]  = array('Start apis gateway: addtocart Log', 'Start process: ' . __LINE__, 'I', time());
            $logMsg[]  = array(Yii::app()->request->requestUri, 'Request URI: ' . __LINE__, 'T', time());
            $logMsg[]  = array(CJSON::encode($params), 'Request params:' . __LINE__, 'T', time());

            $sim_number     = isset($params['sim_number']) ? $params['sim_number'] : false;
            $sim_price      = isset($params['sim_price']) ? $params['sim_price'] : '';
            $sim_type       = isset($params['sim_type']) ? $params['sim_type'] : false;
            $sim_term       = isset($params['sim_term']) ? $params['sim_term'] : '';
            $sim_priceterm  = isset($params['sim_priceterm']) ? $params['sim_priceterm'] : '';
            $sim_store      = isset($params['sim_store']) ? $params['sim_store'] : false;
            $transaction_id = isset($params['transaction_id']) ? $params['transaction_id'] : false;
            $channel        = isset($params['channel']) ? strtolower($params['channel']) : false;
            $secure         = isset($params['secure']) ? $params['secure'] : '';

            $arr_params = array(
                'sim_number'     => $sim_number,
                'sim_price'      => $sim_price,
                'sim_type'       => $sim_type,
                'sim_term'       => $sim_term,
                'sim_priceterm'  => $sim_priceterm,
                'sim_store'      => $sim_store,
                'transaction_id' => $transaction_id,
                'channel'        => $channel,
            );
            $array_validate = array(
                'sim_number'     => $sim_number,
                'sim_price'      => $sim_price,
                'sim_type'       => $sim_type,
                'sim_term'       => $sim_term,
                'sim_priceterm'  => $sim_priceterm,
                'sim_store'      => $sim_store,
                'transaction_id' => $transaction_id,
                'channel'        => $channel,
            );
            $secure_hash = $this->hashAllFields($arr_params);
//            CVarDumper::dump($secure_hash, 10, true); die;
            $logMsg[]    = array($secure, 'secure raw_data:' . __LINE__, 'T', time());
            $logMsg[]    = array($secure_hash, 'secure_hash hashAllFields():' . __LINE__, 'T', time());
            $flag        = FALSE;
            if ($secure == $secure_hash) {
                if ($this->validateParam($array_validate)) {

                    $this->setCookieFromAffiliate($channel, $transaction_id);

                    /*begin add to cart*/
                    $data_input = array(
                        'so_tb' => $sim_number,
                        'store' => $sim_store,
                    );
                   
                    $sim         = new WSim();
                    $orders_data = new OrdersData();
                    //set sim_number to array sim_raw_data
                    $orders_data->sim_raw_data = array(
                        array(
                            'msisdn'      => $sim_number,
                            'msisdn_type' => $sim_type,
                            'price'       => $sim_price,
                            'term'        => $sim_term,
                            'price_term'  => $sim_priceterm,
                            'store'       => $sim_store,
                        )
                    );
                    if ($orders_data->checkSimInRawData($sim_number, $sim_type, $sim_price, $orders_data->sim_raw_data, $sim)) {
                        //call api addtocart
                        $addToCartResult = $orders_data->addToCart($data_input);
                        if (isset($addToCartResult['mtx']) && !empty($addToCartResult['mtx'])) {
                            $orders_data->operation = OrdersData::OPERATION_BUYSIM;
                            $modelOrder             = new WOrders();
                            $modelOrder->otp        = $addToCartResult['mtx'];
                            //sso_id
                            if (!Yii::app()->user->isGuest) {
                                $modelOrder->sso_id = Yii::app()->user->sso_id;
                            }
                            $modelOrder->affiliate_source         = $channel;
                            $modelOrder->affiliate_transaction_id = $transaction_id;

                            $orders_data->orders = $modelOrder;
                            $orders_data->sim    = $sim;

                            $flag = TRUE;
                            $results['data'] = array('transaction_id' => $transaction_id);

                            //set cache redis
                            $key        = 'orders_data_' .$channel .'_'. $transaction_id;
                            Yii::app()->cache->set($key, $orders_data, Yii::app()->params->cache_timeout_config['order_data']);//30'
                        } else {
                            $logMsg[] = array(CJSON::encode($addToCartResult) .' | '.$channel, 'call api addToCart:' . __LINE__, 'E', time());
                            $results['error'] = 'Fail';
                        }
                    } else {
                        $logMsg[] = array('Fail | '.$channel, 'checkSimInRawData():' . __LINE__, 'E', time());
                        $results['error'] = 'Fail';
                    }/*end add to cart*/
                } else {
                    $logMsg[] = array('empty | '.$channel, '$sim_number || $sim_type || $sim_store || $transaction_id || $channel:' . __LINE__, 'E', time());
                    $results['error'] = 'Invalid params';
                }
            } else {
                $logMsg[] = array('secure not match | '.$channel, 'checksum secure:' . __LINE__, 'E', time());
                $results['error'] = 'secure not match';
            }

            $logMsg[] = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $this->writeLogs($file_name, $logMsg);

            $results['ok'] = $flag;
            return $results;
        }
        public function setCookieFromAffiliate($utm_source_value, $aff_sid_value)
        {

            if ($utm_source_value && $aff_sid_value) {

                //check affiliate exists
                if (WAffiliateManager::getAffiliateByCode($utm_source_value)) {

                    $utm_source         = new CHttpCookie('utm_source', $utm_source_value);
                    $aff_sid            = new CHttpCookie('aff_sid', $aff_sid_value);
                    $utm_source->expire = time() + 60 * 60 * 24 * 30;//30 days
                    $aff_sid->expire    = time() + 60 * 60 * 24 * 30;//30 days

                    Yii::app()->request->cookies['utm_source'] = $utm_source;
                    Yii::app()->request->cookies['aff_sid']    = $aff_sid;
                }
            }
        }
        /**
         * @param $array_param
         *
         * @return string
         */
        protected function hashAllFields($array_param)
        {
            $dataCover = implode('', array_values($array_param));
            $user = WAffiliateManager::getAffiliateByCode($array_param['channel']);
            $secret_key = md5('hash secure failed');
            if($user){
                $secret_key =  md5($user->code.$user->create_date.$user->status);;
            }
            return md5($dataCover . $secret_key);
        }

        /**
         * @param $file_name
         * @param $logMsg
         */
        protected function writeLogs($file_name, $logMsg)
        {
            $logFolder  = "web/Log_apis_gateway/" . date("Y/m/d");
            $logObj     = SystemLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($server_add . $file_name . '.log');
            $logObj->processWriteLogs($logMsg);
        }

        public function validateParam($params){
            foreach($params as $key => $val){
                if($val == '' || $val == 'null') {
                    return false;
                }
            }
            return true;
        }
    }