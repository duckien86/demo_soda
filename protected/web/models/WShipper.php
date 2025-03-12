<?php

    class WShipper extends Shipper
    {
        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('id, password', 'required'),
                array('id, username, password, full_name, avatar, phone_1, phone_2, address_detail, district_code, province_code, status', 'length', 'max' => 255),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, username, password, full_name, avatar, phone_1, phone_2, address_detail, district_code, province_code, status', 'safe', 'on' => 'search'),
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
                'username'       => 'Username',
                'password'       => 'Password',
                'full_name'      => 'Full Name',
                'avatar'         => 'Avatar',
                'phone_1'        => 'Phone 1',
                'phone_2'        => 'Phone 2',
                'address_detail' => 'Address Detail',
                'district_code'  => 'District Code',
                'province_code'  => 'Province Code',
                'status'         => 'Status',
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
            $criteria->compare('username', $this->username, TRUE);
            $criteria->compare('password', $this->password, TRUE);
            $criteria->compare('full_name', $this->full_name, TRUE);
            $criteria->compare('avatar', $this->avatar, TRUE);
            $criteria->compare('phone_1', $this->phone_1, TRUE);
            $criteria->compare('phone_2', $this->phone_2, TRUE);
            $criteria->compare('address_detail', $this->address_detail, TRUE);
            $criteria->compare('district_code', $this->district_code, TRUE);
            $criteria->compare('province_code', $this->province_code, TRUE);
            $criteria->compare('status', $this->status, TRUE);

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
         * @return WShipper the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public static function getShipperDetail($order_id, $shipper_id){
            $shipper_detail = array(
                    'full_name' => '',
                    'phone' => ''
                );
            if($shipper_id){
                $shipper = WShipper::model()->findByPk($shipper_id);
                if($shipper){
                    $shipper_detail =  array(
                        'full_name' => $shipper->full_name,
                        'phone' => $shipper->phone_2
                    );
                }

            }else{
                $user = Yii::app()->db->createCommand()
                    ->select('username, phone')
                    ->from('tbl_users t')
                    ->where('t.id in (select user_id from tbl_logs_sim where order_id = :order_id)', array(':order_id'=>$order_id))
                    ->queryRow();
                if($user){
                    $shipper_detail =  array(
                        'full_name' => $user['username'],
                        'phone' => $user['phone']
                    );
                }
            }
            return $shipper_detail;
        }
    }
