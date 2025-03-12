<?php

    class WAffiliateManager extends AffiliateManager
    {
        const  AFF_ACTIVE   = 1;
        const  AFF_INACTIVE = 0;

        const  AFF_VNE = 'vne_shop';

        /**
         * Loai so 1
         */
        const AFF_FLOW_1 = 1; // affiliate
        const AFF_FLOW_2 = 2; // mhtn, ...
        const AFF_FLOW_3 = 3; // mua sim vnpt

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return WAffiliateManager the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * @param $code
         *
         * @return static
         */
        public static function getAffiliateByCode($code)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 'code = :code and status = :status';
            $criteria->params    = array(':code' => $code , ':status' => self::AFF_ACTIVE);

            return self::model()->find($criteria);
        }
        /**
         * @param $code
         * check return api-checkout
         * @return boolean
         */
        public static function checkApiCheckout($code)
        {
            $code = '';
            // check exist channel in session
            if(Yii::app()->session['channel']){
                $code = Yii::app()->session['channel'];
                unset(Yii::app()->session['channel']);
            }
            // check exist affiliate_source in session
            $orders_data = Yii::app()->session['orders_data'];
            if($orders_data && $orders_data->orders && $orders_data->orders->affiliate_source){
                $code = $orders_data->orders->affiliate_source;
            }
            // điều kiện có code
            if(!empty($code)){
                $criteria            = new CDbCriteria();
                $criteria->condition = 'code = :code and status = :status';
                $criteria->params    = array(':code' => $code , ':status' => self::AFF_ACTIVE);

                $model = self::model()->find($criteria);
                if($model && $model->type == self::AFF_FLOW_3){
                    unset(Yii::app()->request->cookies['utm_source']);
                    unset(Yii::app()->request->cookies['aff_sid']);
                    return true;
                }
            }
            return false;
        }
        /**
         * lấy urlRedireact theo affiliate_code
         * @param $code
         * @return $url_redirect
         */
        public static function getRedirectUrl($code)
        {
            $url_redirect = '';
            $affiliate = self::model()->findByAttributes(array('code' => $code, 'type' => self::AFF_FLOW_2));
            
            if($affiliate){
                $url_redirect = $affiliate->url_redirect;
            }
            return $url_redirect;
        }
        
        /*
         * Điều hiếu đến trang yêu cầu  
         * @param $affiliate_source
         * @param $status_order : trạng thái -> 1 : thành công, TH còn lại: thất bại
         * @param $affiliate_transaction_id : transaction_id
         * @param $order_id : mã đơn hàng
         * @param $msisdn : số điện thoại được mua
         * @return redirect url
         * */
        public static function redirectUrl($status_order, $affiliate_transaction_id, $order_id, $msisdn, $url_redirect){

            $type = 'redirect_url';
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'url_redirect' => $url_redirect,
                'status_order' => $status_order,
                'affiliate_transaction_id'   => $affiliate_transaction_id,
                'order_id' => $order_id,
                'msisdn' => $msisdn,
            );
            $logMsg[]   = array(CJSON::encode($arr_param), 'Input: ' . __LINE__, 'T', time());
            $logFolder  = "web/Log_call_api/" . date("Y/m/d");
            $logObj     = TraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($server_add . $type . '.log');
            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);
            $url_redirect = $url_redirect .'?status='.$status_order  .'&order_id='.$order_id  .'&phone='.$msisdn  .'&transaction_id='.$affiliate_transaction_id;
            header('Location: '. $url_redirect);
        }
        
        
    }
