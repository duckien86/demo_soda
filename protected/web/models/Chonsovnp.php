<?php

    /**
     * Created by PhpStorm.
     * User: staff
     * Date: 8/20/2018
     * Time: 4:44 PM
     */
    class Chonsovnp extends AddToCard
    {
        
        public function addToCart($params){
            unset(Yii::app()->session['delivery_type']);
            $file_name = 'addtocart';
            $logMsg    = array();
            $logMsg[]  = array('Start apis gateway: addtocart Log', 'Start process: ' . __LINE__, 'I', time());
            $logMsg[]  = array(Yii::app()->request->requestUri, 'Request URI: ' . __LINE__, 'T', time());
            $logMsg[]  = array(CJSON::encode($params), 'Request params:' . __LINE__, 'T', time());

            $sim_number     = isset($params['sim_number']) ? $params['sim_number'] : false;
            $sim_price      = isset($params['sim_price']) ? $params['sim_price'] : '';
            $sim_type       = isset($params['sim_type']) ? $params['sim_type'] : false;
            $sim_term       = isset($params['sim_term']) ? $params['sim_term'] : '';
            $sim_priceterm  = isset($params['sim_priceterm']) ? $params['sim_priceterm'] : false;
            $sim_store      = isset($params['sim_store']) ? $params['sim_store'] : false;
            $transaction_id = isset($params['transaction_id']) ? $params['transaction_id'] : false;
            $channel        = isset($params['channel']) ? strtolower($params['channel']) : false;
            $secure         = isset($params['secure']) ? $params['secure'] : '';
            $opt            = isset($params['otp']) ? $params['otp'] : '';
            $option         = isset($params['option']) ? $params['option'] : '';
            $delivery_type  = isset($params['delivery_type']) ? $params['delivery_type'] : '';

            $arr_params = array(
                'sim_number'     => $sim_number,
                'sim_price'      => $sim_price,
                'sim_type'       => $sim_type,
                'sim_term'       => $sim_term,
                'sim_priceterm'  => $sim_priceterm,
                'sim_store'      => $sim_store,
                'transaction_id' => $transaction_id,
                'channel'        => $channel,
                'opt'           => $opt,
                'option'        => $option,
                'delivery_type' => $delivery_type
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
            $logMsg[]    = array($secure, 'secure raw_data:' . __LINE__, 'T', time());
            $logMsg[]    = array($secure_hash, 'secure_hash hashAllFields():' . __LINE__, 'T', time());
            $flag        = FALSE;
            if ($secure == $secure_hash) {
                if ($this->validateParam($array_validate)) {
                    
//                    $this->setCookieFromAffiliate($channel, $transaction_id);

                    /*begin add to cart*/
                    $data_input = array(
                        'so_tb' => $sim_number,
                        'store' => $sim_store,
                        'otp'   => $opt,
                        'option'  => $option,
                    );
                    
                    if($sim_priceterm > 0){
                        $sim_price = 60000;
                        $sim_type = 2;
                    }else{
                        $sim_price = 50000;
                        $sim_type = 1;
                    }
                    $sim_number = CFunction_MPS::makePhoneNumberStandard($sim_number);
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
                            'otp'   => $opt,
                            'option'  => $option,
                        )
                    );
                    if ($orders_data->checkSimInRawData($sim_number, $sim_type, $sim_price, $orders_data->sim_raw_data, $sim)) {
                        //call api addtocart
                        Yii::app()->session['channel'] = $channel;
                        $addToCartResult = $orders_data->addToCart($data_input);
                        if (isset($addToCartResult['mtx']) && !empty($addToCartResult['mtx'])) {
                            $orders_data->operation = OrdersData::OPERATION_BUYSIM;
                            $modelOrder             = new WOrders();
                            $modelOrder->otp        = $addToCartResult['mtx'];
                            $modelOrder->id         = $modelOrder->generateOrderId();
                            //sso_id
                            if (!Yii::app()->user->isGuest) {
                                $modelOrder->sso_id = Yii::app()->user->sso_id;
                            }
                            $modelOrder->affiliate_source         = $channel;
                            $modelOrder->affiliate_transaction_id = $transaction_id;
                            if($delivery_type == 1 || $delivery_type == 2){
                                $modelOrder->delivery_type = $delivery_type;
                                Yii::app()->session['delivery_type'] = $delivery_type;
                            }

                            $orders_data->orders = $modelOrder;
                            $orders_data->sim    = $sim;

                            $flag = TRUE;
                            //set session
                            Yii::app()->session['orders_data']  = $orders_data;
                            Yii::app()->session['session_cart'] = time();
                        } else {
                            $logMsg[] = array(CJSON::encode($addToCartResult) .' | '.$channel, 'call api addToCart:' . __LINE__, 'E', time());
                        }
                    } else {
                        $logMsg[] = array('Fail | '.$channel, 'checkSimInRawData():' . __LINE__, 'E', time());
                    }/*end add to cart*/
                } else {
                    $logMsg[] = array('empty | '.$channel, '$sim_number || $sim_type || $sim_store || $transaction_id || $channel:' . __LINE__, 'E', time());
                }
            } else {
                $logMsg[] = array('secure not match | '.$channel, 'checksum secure:' . __LINE__, 'E', time());
            }

            $logMsg[] = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $this->writeLogs($file_name, $logMsg);
            
            return array(
                'flag' => $flag,
                'url'  => 'checkoutapi',
                't'    => 0
            );
        }
    }