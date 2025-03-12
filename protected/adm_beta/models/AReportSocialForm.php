<?php

    /**
     * LoginForm class.
     * LoginForm is the data structure for keeping
     * user login form data. It is used by the 'login' action of 'SiteController'.
     */
    class AReportSocialForm extends CFormModel
    {
        public $start_date;
        public $end_date;
        public $customer_id;
        public $status;

        const ACTIVE   = 10;
        const INACTIVE = 0;

        /**
         * Declares the validation rules.
         * The rules state that username and password are required,
         * and password needs to be authenticated.
         */
        public function rules()
        {
            return array(
                // username and password are required
                array('start_date, end_date', 'required', 'on' => 'index'),
                array('start_date, end_date', 'required', 'on' => 'report_user'),
                array('customer_id', 'required', 'on' => 'detail_user'),
                array(
                    'end_date',
                    'compare',
                    'compareAttribute' => 'start_date',
                    'operator'         => '>=',
                    'allowEmpty'       => FALSE,
                    'message'          => "Ngày kết thúc phải lớn hơn ngày bắt đầu"
                ),
                // rememberMe needs to be a boolean
                // password needs to be authenticated
            );
        }

        /**
         * Declares attribute labels.
         */
        public function attributeLabels()
        {
            return array(
                'start_date'  => 'Bắt đầu',
                'end_date'    => 'Kết thúc',
                'customer_id' => 'Tài khoản',
                'status'      => 'Trạng thái',
            );
        }

        /**
         * Authenticates the password.
         * This is the 'authenticate' validator as declared in rules().
         */
        public function authenticate($attribute, $params)
        {
            if (!$this->hasErrors()) {
                $this->_identity = new UserIdentity($this->username, $this->password);
                if (!$this->_identity->authenticate())
                    $this->addError('password', 'Incorrect username or password.');
            }
        }

        public function getCustomerName($sso_id)
        {

            $customer = ACustomers::model()->findByAttributes(array('sso_id' => $sso_id));

            return ($customer->username) ? $customer->username : '';
        }

        /**
         * @param $point
         * Đổi hạng thành viên.
         *
         * @return string
         */
        public static function getLevel($point)
        {
            $result = "Kết nối";
            if (isset($point)) {
                if ($point < 5) {
                    $result = "Kết nối";
                } else if ($point >= 10 && $point <= 15) {
                    $result = "Trẻ chung";
                } else if ($point >= 15 && $point <= 20) {
                    $result = "Trẻ chung";
                } else if ($point >= 50) {
                    $result = "Phá cách";
                }
            }

            return $result;
        }

        public function getAllStatus()
        {
            return array(
                self::ACTIVE   => 'Kích hoạt',
                self::INACTIVE => 'Ẩn',
            );
        }

        public function getStatus($status)
        {
            $data   = array(
                self::ACTIVE   => 'Kích hoạt',
                self::INACTIVE => 'Ẩn',
            );
            $result = array();
            if (isset($data[$status])) {
                $result = array(
                    $status => $data[$status],
                );
            }

            return $result;
        }
    }
