<?php

    /**
     * This is the model class for table "{{sim}}".
     *
     * The followings are the available columns in table '{{sim}}':
     *
     * @property string  $id
     * @property string  $serial_number
     * @property string  $msisdn
     * @property string  $short_description
     * @property string  $description
     * @property string  $price
     * @property integer $type
     * @property string  $personal_id
     * @property string  $personal_id_create_date
     * @property string  $personal_id_create_place
     * @property integer $personal_id_type
     * @property string  $full_name
     * @property string  $birthday
     * @property string  $address
     * @property integer $status
     * @property string  $photo_face_url
     * @property string  $photo_id_card_url_1
     * @property string  $photo_id_card_url_2
     * @property string  $photo_order_board_url
     * @property integer $gender
     * @property string  $country
     * @property string  $confirm_code
     * @property string  $register_for
     * @property string  $customer_type_vnpt_net
     * @property string  $store_id
     * @property string  $esim_qrcode
     */
    class Sim extends CActiveRecord
    {
        CONST TYPE_PREPAID  = 1;
        CONST TYPE_POSTPAID = 2;

        public $term;
        public $price_term;
        public $raw_data;

        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{sim}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('id, store_id', 'required'),
                array('type, personal_id_type, status, gender', 'numerical', 'integerOnly' => TRUE),
                array('id, personal_id', 'length', 'max' => 100),
                array('serial_number, msisdn, short_description, description, personal_id_create_place, full_name, country, confirm_code, register_for, customer_type_vnpt_net', 'length', 'max' => 255),
                array('price, store_id', 'length', 'max' => 10),
                array('address, photo_face_url, photo_id_card_url_1, photo_id_card_url_2, photo_order_board_url', 'length', 'max' => 400),
                array('personal_id_create_date, birthday', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, serial_number, msisdn, short_description, description, price, type, personal_id, personal_id_create_date, personal_id_create_place, personal_id_type, full_name, birthday, address, status, photo_face_url, photo_id_card_url_1, photo_id_card_url_2, photo_order_board_url, gender, country, confirm_code, register_for, customer_type_vnpt_net, store_id', 'safe', 'on' => 'search'),
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
                'id'                       => 'ID',
                'serial_number'            => 'Serial Number',
                'msisdn'                   => 'Số thuê bao',
                'short_description'        => 'Short Description',
                'description'              => 'Description',
                'price'                    => 'Price',
                'type'                     => 'Type',
                'personal_id'              => 'Personal',
                'personal_id_create_date'  => 'Personal Id Create Date',
                'personal_id_create_place' => 'Personal Id Create Place',
                'personal_id_type'         => 'Personal Id Type',
                'full_name'                => 'Full Name',
                'birthday'                 => 'Birthday',
                'address'                  => 'Address',
                'status'                   => 'Status',
                'photo_face_url'           => 'Photo Face Url',
                'photo_id_card_url_1'      => 'Photo Id Card Url 1',
                'photo_id_card_url_2'      => 'Photo Id Card Url 2',
                'photo_order_board_url'    => 'Photo Order Board Url',
                'gender'                   => 'Gender',
                'country'                  => 'Country',
                'confirm_code'             => 'Confirm Code',
                'register_for'             => 'Register For',
                'customer_type_vnpt_net'   => 'Customer Type Vnpt Net',
                'store_id'                 => 'Store',
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
            $criteria->compare('serial_number', $this->serial_number, TRUE);
            $criteria->compare('msisdn', $this->msisdn, TRUE);
            $criteria->compare('short_description', $this->short_description, TRUE);
            $criteria->compare('description', $this->description, TRUE);
            $criteria->compare('price', $this->price, TRUE);
            $criteria->compare('type', $this->type);
            $criteria->compare('personal_id', $this->personal_id, TRUE);
            $criteria->compare('personal_id_create_date', $this->personal_id_create_date, TRUE);
            $criteria->compare('personal_id_create_place', $this->personal_id_create_place, TRUE);
            $criteria->compare('personal_id_type', $this->personal_id_type);
            $criteria->compare('full_name', $this->full_name, TRUE);
            $criteria->compare('birthday', $this->birthday, TRUE);
            $criteria->compare('address', $this->address, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('photo_face_url', $this->photo_face_url, TRUE);
            $criteria->compare('photo_id_card_url_1', $this->photo_id_card_url_1, TRUE);
            $criteria->compare('photo_id_card_url_2', $this->photo_id_card_url_2, TRUE);
            $criteria->compare('photo_order_board_url', $this->photo_order_board_url, TRUE);
            $criteria->compare('gender', $this->gender);
            $criteria->compare('country', $this->country, TRUE);
            $criteria->compare('confirm_code', $this->confirm_code, TRUE);
            $criteria->compare('register_for', $this->register_for, TRUE);
            $criteria->compare('customer_type_vnpt_net', $this->customer_type_vnpt_net, TRUE);
            $criteria->compare('store_id', $this->store_id, TRUE);

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
         * @return Sim the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
