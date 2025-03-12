<?php

    class CskhRegisterPackage extends RegisterPackage
    {

        const SUCCESS = 1;
        const ERROR   = 0;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('so_tb, create_date, action, package_code', 'required'),
                array('so_tb', 'length', 'max' => 15),
                array('action', 'length', 'max' => 3),
                array('package_code', 'length', 'max' => 50),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, so_tb, create_date, action, package_code', 'safe', 'on' => 'search'),
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
                'so_tb'        => 'Số thuê bao',
                'create_date'  => 'Ngày tương tác',
                'action'       => 'Hành động',
                'package_code' => 'Mã gói',
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
        public function search($so_tb = '')
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria = new CDbCriteria;

            $criteria->compare('id', $this->id, TRUE);
            if ($so_tb != '') {

                $so_tb = self::makePhoneNumberStandard($so_tb);

                $criteria->condition = "so_tb='" . $so_tb . "'";
            } else {
                $criteria->compare('so_tb', $this->so_tb, TRUE);
            }
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('action', $this->action, TRUE);
            $criteria->compare('package_code', $this->package_code, TRUE);

            return new CActiveDataProvider($this, array(
                'criteria'   => $criteria,
                'pagination' => array(
                    'pageSize' => 30,
                    'params'   => array(
                        "RecanForm[msisdn]" => $so_tb,
                    ),
                ),
            ));
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return RegisterPackage the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public static function makePhoneNumberStandard($phoneNumber)
        {
            $phoneNumberStandard = '';
            if ($phoneNumber != '') {
                if (substr($phoneNumber, 0, 1) == '0') {
                    $phoneNumberStandard = substr($phoneNumber, 1, strlen($phoneNumber));
                } else if (substr($phoneNumber, 0, 2) == '84') {
                    $phoneNumberStandard = substr($phoneNumber, 2, strlen($phoneNumber));
                }
                $phoneNumberStandard = '84' . $phoneNumberStandard;
            }

            return $phoneNumberStandard;
        }

        public static function getStatus($status)
        {
            $data = array(
                self::ERROR   => 'Thất bại',
                self::SUCCESS => 'Thành công',
            );

            return isset($data[$status]) ? $data[$status] : '';
        }
    }
