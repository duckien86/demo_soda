<?php

/**
 * This is the model class for table "{{shipper}}".
 *
 * The followings are the available columns in table '{{shipper}}':
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $full_name
 * @property string $avatar
 * @property string $phone_1
 * @property string $phone_2
 * @property string $address_detail
 * @property string $district_code
 * @property string $province_code
 * @property string $ward_code
 * @property integer $brand_office_id
 * @property string $email
 * @property string $otp
 * @property integer $gender
 * @property string $birthday
 * @property string $personal_id
 * @property string $personal_id_create_date
 * @property string $personal_id_create_place
 * @property string $status
 */
class Shipper extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{shipper}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, status', 'required'),
			array('brand_office_id, gender', 'numerical', 'integerOnly'=>true),
			array('id, username, password, full_name, avatar, phone_1, phone_2, address_detail, district_code, province_code, ward_code, email, otp, personal_id_create_place, status', 'length', 'max'=>255),
			array('personal_id', 'length', 'max'=>100),
			array('birthday, personal_id_create_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, password, full_name, avatar, phone_1, phone_2, address_detail, district_code, province_code, ward_code, brand_office_id, email, otp, gender, birthday, personal_id, personal_id_create_date, personal_id_create_place, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'full_name' => 'Full Name',
			'avatar' => 'Avatar',
			'phone_1' => 'Phone 1',
			'phone_2' => 'Phone 2',
			'address_detail' => 'Address Detail',
			'district_code' => 'District Code',
			'province_code' => 'Province Code',
			'ward_code' => 'Ward Code',
			'brand_office_id' => 'Brand Office',
			'email' => 'Email',
			'otp' => 'Otp',
			'gender' => 'Gender',
			'birthday' => 'Birthday',
			'personal_id' => 'Personal',
			'personal_id_create_date' => 'Personal Id Create Date',
			'personal_id_create_place' => 'Personal Id Create Place',
			'status' => 'Status',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('full_name',$this->full_name,true);
		$criteria->compare('avatar',$this->avatar,true);
		$criteria->compare('phone_1',$this->phone_1,true);
		$criteria->compare('phone_2',$this->phone_2,true);
		$criteria->compare('address_detail',$this->address_detail,true);
		$criteria->compare('district_code',$this->district_code,true);
		$criteria->compare('province_code',$this->province_code,true);
		$criteria->compare('ward_code',$this->ward_code,true);
		$criteria->compare('brand_office_id',$this->brand_office_id);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('otp',$this->otp,true);
		$criteria->compare('gender',$this->gender);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('personal_id',$this->personal_id,true);
		$criteria->compare('personal_id_create_date',$this->personal_id_create_date,true);
		$criteria->compare('personal_id_create_place',$this->personal_id_create_place,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Shipper the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
