<?php

    /**
     * This is the model class for table "{{users}}".
     *
     * The followings are the available columns in table '{{users}}':
     *
     * @property integer $id
     * @property string  $username
     * @property string  $password
     * @property string  $fullname
     * @property string  $email
     * @property string  $phone
     * @property integer $genre
     * @property string  $birthday
     * @property string  $address
     * @property string  $description
     * @property integer $status
     * @property string  $token
     * @property string  $avatar
     * @property string  $created_at
     * @property string  $updated_at
     * @property string  $cp_id
     * @property string  $invite_code
     * @property string  $is_admin
     */
    class Users extends CActiveRecord
    {
        const ACTIVE        = 10;
        const TEXT_ACTIVE   = 'ACTIVE';
        const PENDING       = 2;
        const TEXT_PENDING  = 'PENDING';
        const INACTIVE      = 0;
        const TEXT_INACTIVE = 'INACTIVE';

        const NOT_ADMIN_SOCIAL      = 0;
        const TEXT_NOT_ADMIN_SOCIAL = 'NOT_ADMIN';
        const ADMIN_SOCIAL          = 10;
        const TEXT_ADMIN_SOCIAL     = 'ADMIN';

        const SUB_ADMIN_SOCIAL      = 1;
        const TEXT_SUB_ADMIN_SOCIAL = 'SUB_ADMIN';


        /**
         * @return string the associated database table name
         */


        public function tableName()
        {
            return '{{users}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('username, password', 'required'),
                array('genre, status', 'numerical', 'integerOnly' => TRUE),
                array('email, phone', 'required', 'on' => 'api'),
                array('email', 'email', 'on' => 'api'),
                array('phone', 'authenticateMsisdn', 'on' => 'api'),
                array('phone', 'unique', 'className' => 'Users', 'attributeName' => 'phone', 'message' => 'Số điện thoại này đã được đăng ký!', 'on' => 'api'),
                array('email', 'unique', 'className' => 'Users', 'attributeName' => 'email', 'message' => 'Email đã tồn tại!', 'on' => 'api'),
                array('username, fullname, email, phone, birthday, address, description', 'length', 'max' => 255),
                array('password, token, avatar,cp_id, is_admin', 'length', 'max' => 500),
                array('created_at', 'safe'),
                array('invite_code', 'length', 'max' => 20),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, username, password, cp_id, is_admin, fullname, email, phone, genre, birthday, address, description, status, token, avatar, created_at, updated_at', 'safe', 'on' => 'search'),
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
                'id'          => 'ID',
                'username'    => 'Username',
                'password'    => 'Password',
                'fullname'    => 'Fullname',
                'email'       => 'Email',
                'phone'       => 'Phone',
                'genre'       => 'Genre',
                'birthday'    => 'Birthday',
                'address'     => 'Address',
                'description' => 'Description',
                'status'      => 'Status',
                'token'       => 'Token',
                'avatar'      => 'Avatar',
                'created_at'  => 'Created At',
                'updated_at'  => 'Updated At',
            );
        }

        public function authenticateMsisdn($attribute, $params)
        {
            if ($this->$attribute) {
                $this->$attribute = self::makePhoneNumberStandard($this->$attribute);
                if (preg_match("/^84[0-9]{9,11}$/i", $this->$attribute) === 0) {
                    $this->addError($attribute,
                        'Số điện thoại không đúng định dạng!');
                }
            }
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
            $criteria->compare('username', $this->username, TRUE);
            $criteria->compare('password', $this->password, TRUE);
            $criteria->compare('fullname', $this->fullname, TRUE);
            $criteria->compare('email', $this->email, TRUE);
            $criteria->compare('phone', $this->phone, TRUE);
            $criteria->compare('genre', $this->genre);
            $criteria->compare('birthday', $this->birthday, TRUE);
            $criteria->compare('address', $this->address, TRUE);
            $criteria->compare('description', $this->description, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('token', $this->token, TRUE);
            $criteria->compare('avatar', $this->avatar, TRUE);
            $criteria->compare('created_at', $this->created_at, TRUE);
            $criteria->compare('updated_at', $this->updated_at, TRUE);

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
         * @return Users the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }

