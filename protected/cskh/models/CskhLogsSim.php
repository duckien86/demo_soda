<?php

    /**
     * This is the model class for table "{{logs_sim}}".
     *
     * The followings are the available columns in table '{{logs_sim}}':
     *
     * @property string  $id
     * @property string  $create_date
     * @property string  $msisdn
     * @property string  $order_id
     * @property integer $status
     * @property integer $user_id
     * @property integer $type_user
     * @property string  $note
     * @property string  $extra_params
     */
    class CskhLogsSim extends LogsSim
    {

        const BACKEND = 1;
        const APP     = 2;

        const ACTIVE   = 10;
        const INACTIVE = 0;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('msisdn, status, user_id, type_user', 'required'),
                array('status, user_id, type_user', 'numerical', 'integerOnly' => TRUE),
                array('msisdn, order_id, note, extra_params', 'length', 'max' => 255),
                array('create_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, create_date, msisdn, order_id, status, user_id, type_user, note, extra_params', 'safe', 'on' => 'search'),
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
                'id'           => 'ID',
                'create_date'  => 'Ngày tạo',
                'msisdn'       => 'Số sim',
                'order_id'     => 'Mã đơn hàng',
                'status'       => 'Trạng thái',
                'user_id'      => 'Người khai báo',
                'type_user'    => 'Nhóm tài khoản',
                'note'         => 'Ghi chú',
                'extra_params' => 'Extra Params',
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

            $criteria->compare('id', $this->id, TRUE);
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('msisdn', $this->msisdn, TRUE);
            $criteria->compare('order_id', $this->order_id, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('user_id', $this->user_id);
            $criteria->compare('type_user', $this->type_user);
            $criteria->compare('note', $this->note, TRUE);
            $criteria->compare('extra_params', $this->extra_params, TRUE);

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
         * @return LogsSim the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public static function getLogs($order_id)
        {
            $data = array();
            if ($order_id) {
                $criteria            = new CDbCriteria();
                $criteria->condition = "order_id ='" . $order_id . "'";

                return new CActiveDataProvider('CskhLogsSim', array(
                    'criteria' => $criteria,
                ));
            }


            return $data;
        }

        public function getStatus($status)
        {
            $data = array(
                self::INACTIVE => 'Chưa kích hoạt',
                self::ACTIVE   => 'Đã kích hoạt',
            );

            return $data[$status];
        }

        public function getUserType($status)
        {
            $data = array(
                self::BACKEND => 'Người dùng backend',
                self::APP     => 'Người dùng app',
            );

            return $data[$status];
        }

        public function getUserName($user_id, $type)
        {
            $users = '';
            if ($type == self::BACKEND) {
                $users = User::model()->findByAttributes(array('id' => $user_id));
            } else if ($type == self::APP) {
                $users = CskhShipper::model()->findByAttributes(array('id' => $user_id));
            }

            return isset($users->username) ? $users->username : 'N/A';
        }
    }
