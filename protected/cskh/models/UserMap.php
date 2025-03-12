<?php

    /**
     * This is the model class for table "cc_tbl_user_map".
     *
     * The followings are the available columns in table 'cc_tbl_user_map':
     *
     * @property string  $id
     * @property string  $user_id
     * @property string  $username_ext
     * @property string  $password_ext
     * @property integer $login
     * @property string  $last_login
     * @property integer $status
     */
    class UserMap extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'cc_tbl_user_map';
        }

        public $username;
        public $unit;
        public $fullname;
        public $online_user;

        const ONLINE  = 1;
        const OFFLINE = 0;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('login, status', 'numerical', 'integerOnly' => TRUE),
                array('user_id, username_ext, password_ext', 'length', 'max' => 255),
                array('last_login', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, user_id, username_ext, password_ext, login, last_login, status', 'safe', 'on' => 'search'),
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

        public function afterFind()
        {
            if ($this->user_id) {
                $user = User::model()->findByAttributes(array('id' => $this->user_id));
                if ($user) {
                    $this->username = $user->username;
                    $profile        = Profile::model()->findByAttributes(array('user_id' => $this->user_id));
                    if ($profile) {
                        $this->fullname = $profile->firstname . " " . $profile->lastname;
                    }
                }
            }
        }

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'           => 'ID',
                'user_id'      => 'id',
                'username_ext' => 'Username Ext',
                'password_ext' => 'Password Ext',
                'login'        => 'Login',
                'last_login'   => 'Last Login',
                'status'       => 'Status',
                'username'     => 'Khai thác viên',
                'unit'         => 'Đơn vị',
                'online_user'  => 'Số người online',
                'fullname'     => 'Tên đầy đủ',
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
            $criteria->compare('user_id', $this->user_id, TRUE);
            $criteria->compare('username_ext', $this->username_ext, TRUE);
            $criteria->compare('password_ext', $this->password_ext, TRUE);
            $criteria->compare('login', self::ONLINE);
            $criteria->compare('last_login', $this->last_login, TRUE);
            $criteria->compare('status', $this->status);

            return new CActiveDataProvider($this, array(
                'criteria'   => $criteria,
            ));
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return UserMap the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
