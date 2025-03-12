<?php

    /**
     * LoginForm class.
     * LoginForm is the data structure for keeping
     * user login form data. It is used by the 'login' action of 'SiteController'.
     */
    class ACustomerForm extends CFormModel
    {
        public $nation;
        public $full_name;
        public $gender;
        public $number_page;
        public $personal_id_create_date;
        public $personal_id_create_place;
        public $phone_number;
        public $birth_day;
        public $package_code;
        public $customer_type;
        public $personal_id_type;
        public $sim;
        public $register_for; // Đối tượng sử dụng thuê bao.
        public $subscription_permanent_address; // Địa chỉ thường trú tương ứng với address trong bảng sim.

        /**
         * Declares the validation rules.
         * The rules state that username and password are required,
         * and password needs to be authenticated.
         */
        public function rules()
        {

            return array(
                // otp are required
                array('nation,sim, register_for, customer_type, subscription_permanent_address, 
                personal_id_type, personal_id_create_place,gender, full_name,number_page, personal_id_create_date, phone_number, 
                birth_day', 'required'),

                array('nation,sim,register_for,customer_type,subscription_permanent_address,personal_id_type,
                personal_id_create_place,gender,full_name,number_page,personal_id_create_date,phone_number,birth_day', 'safe'),

                array('birth_day', 'checkMaxBirthday'),
                array('personal_id', 'checkNumberPage'),
                array('personal_id_create_date', 'checkCreateDate'),

            );
        }

        /**
         * @param $attribute
         * @param $params
         */
        public function checkMaxBirthday($attribute, $params)
        {
            if ($this->$attribute) {
                $value    = date('Y', strtotime(str_replace('/', '-', $this->$attribute)));
                $max_year = date('Y') - 14;
                if ($value > $max_year) {
                    $this->addError($attribute, Yii::t('web/portal', 'error_max_birthday'));
                }
            }
        }

        /**
         * @param $attribute
         * @param $params
         */
        public function checkNumberPage($attribute, $params)
        {
            if ($this->personal_id_type == 1 && !empty($this->number_page)) { // Nếu là chứng minh thư nhân dân

                $pattern = '/^([0-9]{9}|[0-9]{12})$/';
                if ($this->number_page && !preg_match($pattern, $this->number_page)) {
                    $this->addError('number_page', "Số CMT phải gồm 9 hoặc 12 chữ số");
                }
            } else if ($this->personal_id_type == 45 && !empty($this->number_page)) { // Thẻ căn cước
                $pattern = '/^([0-9]{12})$/';
                if ($this->number_page && !preg_match($pattern, $this->number_page)) {
                    $this->addError('number_page', "Thẻ căn cước phải gồm 12 chữ số");
                }

            } else if ($this->personal_id_type == 3 && !empty($this->number_page)) { // Hộ chiêu
                $pattern = "/^[A_Z]{1}[0-9]{7}$/i";
                if ($this->number_page && !preg_match($pattern, $this->number_page)) {
                    $this->addError('number_page', "Số Hộ chiếu ốm 8 ký tự, ký tự đầu là chữ cái in hóa + 7 chữ số");
                }

            }
        }


        /**
         * @param $attribute
         * @param $params
         */
        public function checkCreateDate($attribute, $params)
        {
            if ($this->$attribute) {
                $value    = date('Y', strtotime(str_replace('/', '-', $this->$attribute)));
                $max_year = date('Y', strtotime(str_replace('/', '-', $this->birth_day))) + 14;
                if ($value < $max_year) {
                    $this->addError($attribute, Yii::t('web/portal', 'error_max_birthday'));
                }
            }
        }

        /**
         * Declares attribute labels.
         */
        public function attributeLabels()
        {
            return array(
                'nation'                         => 'Quốc tịch',
                'full_name'                      => 'Họ và tên',
                'gender'                         => 'Giới tính',
                'number_page'                    => 'Số giấy tờ',
                'personal_id_create_date'        => 'Ngày cấp',
                'phone_number'                   => 'Số thuê bao',
                'birth_day'                      => 'Ngày sinh',
                'package_code'                   => 'Gói cước',
                'customer_type'                  => 'Loại khách hàng',
                'personal_id_type'               => 'Loại giấy tờ',
                'personal_id_create_place'       => 'Cơ quan cấp',
                'subscription_permanent_address' => 'Nơi ĐK hộ khẩu',
                'sim'                            => 'Số sim',
                'register_for'                   => 'Đối tượng sử dụng',
            );
        }

        /**
         * Authenticates the otp
         * This is the 'authenticate' validator as declared in rules().
         */
        public function authenticate($attribute, $params)
        {

            if (!$this->hasErrors()) {
                $user = AOrders::model()->findAllByAttributes(array('id' => $this->order_id, 'otp' => $this->$attribute));
                if (!$user) {
                    $this->addError($attribute,
                        'Bạn nhập sai số xác minh! Vui lòng nhập lại');
                }
            }
        }

        public function getRegisterFor()
        {
            return array(
                1 => 'Bản thân',
                2 => 'Con đẻ',
                3 => 'Con nuôi dưới 14 tuỏi',
                4 => 'Người được giám hộ',
                5 => 'Thiết bị',
            );
        }


    }
