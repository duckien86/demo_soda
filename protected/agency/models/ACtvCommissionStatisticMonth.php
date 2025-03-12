<?php

    /**
     * @property integer $id
     * @property string  $publisher_id
     * @property string  $amount
     * @property integer $month
     * @property integer $year
     * @property string  $create_date
     * @property integer $status
     * @property string  $transaction_id
     * @property string  $update_by
     */
    class ACtvCommissionStatisticMonth extends CActiveRecord
    {
        const PAID   = 10;
        const UNPAID = 0;
        public $start_date;
        public $end_date;
        public $total_amount;
        public $input_type;
        public $info_search;
        public $update_time;
        public $vnp_province_id;
        public $total_receive;

        public function getDbConnection()
        {
            return Yii::app()->db_affiliates;
        }

        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'tbl_commission_statistic_month';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('info_search, month, year', 'required', 'on' => 'admin'),
                array('id, month, year, status', 'numerical', 'integerOnly' => TRUE),
                array('publisher_id, transaction_id, update_by', 'length', 'max' => 255),
                array('amount, vnp_province_id', 'length', 'max' => 10),
                array('create_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, publisher_id, amount, month, year, create_date, status, transaction_id, update_by', 'safe', 'on' => 'search'),
            );
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
                'id'             => 'ID',
                'publisher_id'   => 'Tên đăng nhập',
                'amount'         => 'Tổng hoa hồng',
                'month'          => 'Tháng',
                'year'           => 'Năm',
                'create_date'    => 'Ngày tổng kết',
                'status'         => 'Trạng thái',
                'transaction_id' => 'Transaction',
                'update_by'      => 'Người cập nhật',
                'total_amount'   => 'Tổng hoa hồng',
                'input_type'     => 'Chọn tiêu chí tra cứu',
                'info_search'    => 'Thông tin tra cứu',
                'update_time'    => 'Thời gian thanh toán',
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
        public function search($post = TRUE)
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria = new CDbCriteria;

            if ($post) {
                if ($this->publisher_id != '') {
                    $criteria->addCondition("publisher_id ='" . $this->publisher_id . "'");
                }
                if ($this->month != '') {
                    $criteria->addCondition("month ='" . $this->month . "'");
                }
                if ($this->year != '') {
                    $criteria->addCondition("year ='" . $this->year . "'");
                }
            }

            return new CActiveDataProvider($this, array(
                'criteria'   => $criteria,
                'pagination' => array(
                    'pageSize' => 30,
                ),
            ));
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return ACtvCommissionStatisticMonth the static model classWWWWWWWWW
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * @return array
         * Lấy tất cả trạng thái.
         */
        public function getAllStatus()
        {
            return array(
                self::PAID   => 'Đã thanh toán',
                self::UNPAID => 'Chưa thanh toán',
            );
        }

        public static function getStatus($status)
        {
            $data = array(
                self::PAID   => 'Đã thanh toán',
                self::UNPAID => 'Chưa thanh toán',
            );

            return isset($data[$status]) ? $data[$status] : 0;
        }

        public function getMonthArray()
        {
            return array(
                1  => '01',
                2  => '02',
                3  => '03',
                4  => '04',
                5  => '05',
                6  => '06',
                7  => '07',
                8  => '08',
                9  => '09',
                10 => '10',
                11 => '11',
                12 => '12',
            );
        }

        public function getYearArray()
        {
            return array(

                2017 => '2017',
                2018 => '2018',
                2019 => '2019',
                2020 => '2020',
                2021 => '2021',
                2022 => '2022',
                2023 => '2023',
            );
        }

        /**
         * Tạo mới transaction.
         */
        public static function actionCreateTransaction($lengthChars = 32, $month)
        {
            $transaction = '';
            if ($lengthChars <= 0) {
                return FALSE;
            } else {
                $user_id         = Yii::app()->user->id;
                $time            = time();
                $transaction_key = $user_id . $time . $month;
                $transaction     = substr(str_shuffle($transaction_key), 0, $lengthChars);
                if ($transaction) {
                    $check = ACtvCommissionStatisticMonth::model()->findByAttributes(array('transaction_id' => $transaction));
                    if ($check) {
                        self::actionCreateTransaction(32, $month);
                    }
                }


            }

            return $transaction;
        }

        public static function getBankAccount($user_id)
        {
            $user = ACtvUserBankAccount::model()->findByAttributes(array('user_id' => $user_id));
            if ($user) {
                return $user->account_number;
            }

            return "";
        }

        public static function getAccountName($user_id)
        {
            $user = ACtvUserBankAccount::model()->findByAttributes(array('user_id' => $user_id));
            if ($user) {
                return $user->account_name;
            }

            return "";
        }

        public static function getBanks($user_id)
        {
            $user = ACtvUserBankAccount::model()->findByAttributes(array('user_id' => $user_id));
            if (isset($user->bank_id)) {
                $banks = ACtvBanks::model()->findByAttributes(array('id' => $user->bank_id));
                if (isset($banks->bank_name)) {
                    return $banks->bank_name;
                }
            }

            return "";
        }

        /**
         * @param $user_id
         * @param $month
         * Lấy tổng thù lao còn tồn đọng
         */
        public static function getCommisionReceive($user_id, $month, $transaction_id = '')
        {
            $users_check = ACtvUsers::model()->findByAttributes(array('user_id' => $user_id));
            if ($users_check) {
                $criteria         = new CDbCriteria();
                $criteria->select = "SUM(t.amount) as total_receive";
                if ($users_check->is_business == 1) {
                    $criteria->condition = "u.agency_id ='" . $user_id . "' and t.month <'" . $month . "' and (t.status =0 or t.transaction_id ='" . $transaction_id . "')";
                    $criteria->join      = "INNER JOIN vsb_affiliate.tbl_users u ON u.user_id = t.publisher_id";
                    $criteria->group     = "u.agency_id";
                } else {
                    $criteria->condition = "t.publisher_id ='" . $user_id . "' and t.month <'" . $month . "' and (t.status =0 or t.transaction_id ='" . $transaction_id . "')";
                }
//                CVarDumper::dump($criteria,10,true);die();
                $users = ACtvCommissionStatisticMonth::model()->findAll($criteria);
                if (isset($users[0]->total_receive)) {
                    return $users[0]->total_receive;
                }
            }
            return 0;
        }
    }
