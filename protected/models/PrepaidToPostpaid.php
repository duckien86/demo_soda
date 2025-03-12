<?php

/**
 * This is the model class for table "{{prepaid_to_postpaid}}".
 *
 * The followings are the available columns in table '{{prepaid_to_postpaid}}':
 * @property string $id
 * @property string $msisdn
 * @property string $order_id
 * @property string $package_code
 * @property string $full_name
 * @property string $personal_id
 * @property string $province_code
 * @property string $district_code
 * @property string $ward_code
 * @property string $address_detail
 * @property string $promo_code
 * @property string $otp
 * @property string $receive_date
 * @property string $finish_date
 * @property string $request_id
 * @property string $create_date
 * @property int    $status
 * @property string $user_id
 * @property string $note
 */
class PrepaidToPostpaid extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{prepaid_to_postpaid}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'length', 'max'=>100),
			array('status', 'numerical', 'integerOnly'=>true),
			array('msisdn, order_id, package_code, full_name, personal_id, province_code, district_code, ward_code, address_detail, promo_code, otp, request_id, user_id', 'length', 'max'=>255),
			array('receive_date, finish_date, create_date, note', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, msisdn, order_id, package_code, full_name, personal_id, province_code, district_code, ward_code, address_detail, promo_code, otp, receive_date, finish_date, request_id, create_date, status, user_id, note', 'safe', 'on'=>'search'),
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
			'msisdn' => 'Msisdn',
			'order_id' => 'Order',
			'package_code' => 'Package',
			'full_name' => 'Full Name',
			'personal_id' => 'Personal',
			'province_code' => 'Province Code',
			'district_code' => 'District Code',
			'ward_code' => 'Ward Code',
			'address_detail' => 'Address Detail',
			'promo_code' => 'Promo Code',
			'otp' => 'Otp',
			'receive_date' => 'Receive Date',
			'finish_date' => 'Finish Date',
			'request_id' => 'Request',
			'create_date' => 'Create Date',
			'status' => 'Status',
			'user_id' => 'User',
			'note' => 'Note',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('msisdn',$this->msisdn,true);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('package_code',$this->package_code,true);
		$criteria->compare('full_name',$this->full_name,true);
		$criteria->compare('personal_id',$this->personal_id,true);
		$criteria->compare('province_code',$this->province_code,true);
		$criteria->compare('district_code',$this->district_code,true);
		$criteria->compare('ward_code',$this->ward_code,true);
		$criteria->compare('address_detail',$this->address_detail,true);
		$criteria->compare('promo_code',$this->promo_code,true);
		$criteria->compare('otp',$this->otp,true);
		$criteria->compare('receive_date',$this->receive_date,true);
		$criteria->compare('finish_date',$this->finish_date,true);
		$criteria->compare('request_id',$this->request_id,true);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('note',$this->note,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PrepaidToPostpaid the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
