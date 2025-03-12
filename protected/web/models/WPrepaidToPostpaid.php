<?php

    /**
     * This is the model class for table "{{prepaid_to_postpaid}}".
     *
     * The followings are the available columns in table '{{prepaid_to_postpaid}}':
     *
     * @property int    $id
     * @property string $msisdn
     * @property string $order_id
     * @property string $package_code
     * @property string $full_name
     * @property string $personal_id
     * @property string $province_code
     * @property string $district_code
     * @property string $ward_code
     * @property string $address_detail
     * @property string $promo_code
     * @property string $otp
     * @property string $receive_date
     * @property string $finish_date
     * @property string $request_id
     * @property string $create_date
     * @property int    $status
     * @property string $user_id
     * @property string $note
     * @property string $sale_office_code
     */
    class WPrepaidToPostpaid extends PrepaidToPostpaid
    {
        CONST PTP_FAIL        = 0;    //Thất bại
        CONST PTP_APPROVE     = 1;    //Chờ duyệt
        CONST PTP_PROCESSING  = 2;    //Đang xử lí
        CONST PTP_OUT_OF_DATE = 8;    //Quá hạn
        CONST PTP_COMPLETE    = 10;    //Hoàn thành

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('id', 'length', 'max' => 100),
                array('msisdn, full_name, personal_id, province_code, district_code, ward_code', 'required', 'on' => 'create'),
                array('address_detail', 'required', 'message' => Yii::t('web/portal', 'ptp_address_detail_required'), 'on' => 'create'),
                array('status', 'numerical', 'integerOnly' => TRUE),
                array('msisdn, personal_id, province_code, promo_code, otp, order_id, request_id, user_id, sale_office_code', 'length', 'max' => 255),
                array('personal_id', 'checkFormat'),
                array('full_name', 'checkName'),
                array('msisdn', 'msisdn_validation'),
                array('msisdn', 'checkInfoPhone'),
                array('msisdn', 'checkMsisdnValid'),
               // array('package_code', 'required', 'on' => 'choose_package', 'message' => Yii::t('web/portal', 'ptp_package_required')),
                array('package_code', 'checkPackage', 'on' => 'choose_package'),
                array('promo_code', 'checkCouponCode'),
                array('receive_date, finish_date, create_date, note', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, msisdn, personal_id, province_code, order_id, receive_date, finish_date, request_id, create_date, status, user_id, full_name, district_code, ward_code, address_detail, promo_code, otp, package_code, note, sale_office_code', 'safe', 'on' => 'search'),
            );
        }

        public function checkName()
        {
            if(!empty($this->full_name)){
                $name = Utils::unsign_string($this->full_name, ' ', TRUE);
                if(preg_match('/^[a-zA-z\s]+$/',$name) == TRUE){
                    return TRUE;
                }else{
                    $this->addError('full_name', 'Họ tên không hợp lệ!');
                }
            }
            return FALSE;
        }
        public function checkInfoPhone()
        {
            $msisdn = $this->msisdn;
            $data_input = array(
                'so_tb' => $msisdn
            );
            $data_output = Utils::getInfoPhone($data_input);
            if($data_output['code']== -1){
                $this->addError('msisdn', Yii::t('web/portal', 'error_msisdn_vinaphone'));
            }
            return TRUE;
        }

        public function checkPackage()
        {
            if(!empty($this->package_code) && !empty($this->msisdn)){
                $package = WPackage::model()->findByAttributes(array('code' => $this->package_code));
                if(WPackage::checkSimFreedoo($this->msisdn) == FALSE && $package->freedoo == WPackage::FREEDOO_PACKAGE){
                    $this->addError('package_code', Yii::t('web/portal', 'err_cannot_register_package_freedoo'));
                    return FALSE;
                }
            }
            return TRUE;
        }


        public static function generatePtpId()
        {
            return Utils::generateRandomString(11, TRUE);
        }

        protected function beforeSave()
        {
            if (empty($this->create_date)) {
                $this->create_date = date('Y-m-d H:i:s');
            }

            return parent::beforeSave();
        }


        /**
         * @param $haystack
         * @param $needle
         *
         * @return bool
         */
        function startsWith($haystack, $needle)
        {
            $length = strlen($needle);

            return (substr($haystack, 0, $length) === $needle);
        }

        /**
         * @param $attribute
         * @param $params
         */
        public function checkCouponCode($attribute, $params)
        {
            if ($this->$attribute) {
                $str  = $this->$attribute;
                $flag = TRUE;
                if ($this->startsWith($str, 'P') || $this->startsWith($str, 'AP') || $this->startsWith($str, 'APP')
                    || $this->startsWith($str, 'p') || $this->startsWith($str, 'ap') || $this->startsWith($str, 'app')
                ) {
                    $pattern = '/^(P|AP|APP|p|ap|app)([0-9]{7})$/';//user code
//				if (isset(Yii::app()->request->cookies['utm_source']) && !empty(Yii::app()->request->cookies['utm_source'])
//					&& isset(Yii::app()->request->cookies['aff_sid']) && !empty(Yii::app()->request->cookies['aff_sid'])
////                        && Yii::app()->request->cookies['utm_source']->value == 'freedoo'
//				) {//check cookie from affiliate
//					$flag = FALSE;
//					$this->addError($attribute, Yii::t('web/portal', 'invalid_promo_code'));
//				} else {
                    //check valid
                    if (!preg_match($pattern, $str)) {
                        $flag = FALSE;
                        $this->addError($attribute, Yii::t('web/portal', 'invalid_promo_code'));
                    }
//				}
                } else {
                    $flag = FALSE;
                    $this->addError($attribute, Yii::t('web/portal', 'invalid_promo_code'));
                }

                if ($flag) {
                    $orders_data = new OrdersData();
                    $data_input  = array(
                        'promo_code' => $str
                    );
                    $valid       = $orders_data->checkCouponCode($data_input);
                    if (!$valid) {
                        $this->addError($attribute, Yii::t('web/portal', 'cannot_use_promo_code'));
                    }
                }
            }
        }

        public function checkMsisdnValid()
        {
            if ($this->msisdn) {
                $date = date('d/m/Y', strtotime('-6 months'));

                $criteria            = new CDbCriteria();
                $criteria->condition = 't.msisdn = :msisdn AND t.create_date > :date AND t.status = :status_success';
                $criteria->params    = array(
                    ':msisdn'         => $this->msisdn,
                    ':date'           => $date,
                    ':status_success' => WPrepaidToPostpaid::PTP_COMPLETE,
                );
                if (WPrepaidToPostpaid::model()->find($criteria)) {
                    $this->addError('msisdn', Yii::t('web/portal', 'ptp_error_msisdn_done'));
                } else {
                    return TRUE;
                }
            }

            return FALSE;
        }

        /**
         * @return bool
         */
        public function detectByTelco()
        {
            if ($this->msisdn) {
                $telco = Utils::detectTelcoByMsisdn($this->msisdn);
                if ($telco == WOrders::VINAPHONE_TELCO) {
                    return TRUE;
                } else {
                    $this->addError('msisdn', Yii::t('web/portal', 'error_msisdn_vinaphone'));
                }
            }

            return FALSE;
        }

        /**
         * check if phone is valid
         *
         * @return bool
         */
        public function msisdn_validation($attribute)
        {
            if ($this->$attribute) {
                $input = $this->$attribute;
                if (preg_match("/^0[0-9]{9,10}$/i", $input) == TRUE || preg_match("/^84[0-9]{9,11}$/i", $input) == TRUE) {
                    return TRUE;
                } else {
                    $this->addError($attribute, Yii::t('web/portal', 'msisdn_validation'));
                }
            }

            return FALSE;
        }

        /**
         * @param $attribute
         * @param $params
         */
        public function checkFormat($attribute, $params)
        {
            $pattern = '/^([0-9]{9}|[0-9]{12})$/';
            if ($this->$attribute && !preg_match($pattern, $this->$attribute)) {
                $this->addError($attribute, Yii::t('web/portal', 'format_personal_id'));
            }
        }


        /**
         * @return array relational rules.
         */
        public function relations()
        {
            // NOTE: you may need to adjust the relation name and the related
            // class name for the relations automatically generated below.
            return array();
        }

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'               => Yii::t('web/portal', 'id'),
                'msisdn'           => Yii::t('web/portal', 'msisdn_ptp'),
                'order_id'         => Yii::t('web/portal', 'order_id'),
                'package_code'     => Yii::t('web/portal', 'package'),
                'full_name'        => Yii::t('web/portal', 'full_name'),
                'personal_id'      => Yii::t('web/portal', 'personal_id'),
                'province_code'    => Yii::t('web/portal', 'province'),
                'district_code'    => Yii::t('web/portal', 'district'),
                'ward_code'        => Yii::t('web/portal', 'ward_code'),
                'address_detail'   => Yii::t('web/portal', 'address_detail_ptp'),
                'promo_code'       => Yii::t('web/portal', 'promo_code_ptp'),
                'otp'              => Yii::t('web/portal', 'otp'),
                'receive_date'     => Yii::t('web/portal', 'receive_date'),
                'finish_date'      => Yii::t('web/portal', 'finish_date_ptp'),
                'request_id'       => Yii::t('web/portal', 'request_id_ptp'),
                'create_date'      => Yii::t('web/portal', 'create_date'),
                'status'           => Yii::t('web/portal', 'status'),
                'user_id'          => Yii::t('web/portal', 'user_id_ptp'),
                'note'             => Yii::t('web/portal', 'note'),
                'sale_office_code' => Yii::t('web/portal', 'sale_office_code'),
            );
        }

        /**
         * Retrieves a list of models based on the current search/filter conditions.
         *
         * Typical usecase:
         * - Initialize the model fields with values from filter form.
         * - Execute this method to get CActiveDataProvider instance which will filter
         * models according to data in model fields.
         * - Pass data provider to CGridView, CListView or any similar widget.
         *
         * @return CActiveDataProvider the data provider that can return the models
         * based on the search/filter conditions.
         */
        public function search()
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria = new CDbCriteria;

            $criteria->compare('id', $this->id);
            $criteria->compare('msisdn', $this->msisdn, TRUE);
            $criteria->compare('order_id', $this->order_id, TRUE);
            $criteria->compare('package_code', $this->package_code, TRUE);
            $criteria->compare('full_name', $this->full_name, TRUE);
            $criteria->compare('personal_id', $this->personal_id, TRUE);
            $criteria->compare('province_code', $this->province_code, TRUE);
            $criteria->compare('district_code', $this->district_code, TRUE);
            $criteria->compare('ward_code', $this->ward_code, TRUE);
            $criteria->compare('address_detail', $this->address_detail, TRUE);
            $criteria->compare('promo_code', $this->promo_code, TRUE);
            $criteria->compare('receive_date', $this->receive_date, TRUE);
            $criteria->compare('finish_date', $this->finish_date, TRUE);
            $criteria->compare('request_id', $this->request_id, TRUE);
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('user_id', $this->user_id, TRUE);
            $criteria->compare('note', $this->note, TRUE);
            $criteria->compare('sale_office_code', $this->sale_office_code, TRUE);

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
            ));
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return PrepaidToPostpaid the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }


        public static function unsetSession()
        {
            if(isset(Yii::app()->session['ptp'])){
                unset(Yii::app()->session['ptp']);
            }
            if(isset(Yii::app()->session['token_key_ptp'])){
                unset(Yii::app()->session['token_key_ptp']);
            }
            if(isset(Yii::app()->session['send_token_number_ptp'])){
                unset(Yii::app()->session['send_token_number_ptp']);
            }
            if(isset(Yii::app()->session['verify_number_ptp'])){
                unset(Yii::app()->session['verify_number_ptp']);
            }
            if(isset(Yii::app()->session['send_token_time_ptp'])){
                unset(Yii::app()->session['send_token_time_ptp']);
            }
        }

    }
