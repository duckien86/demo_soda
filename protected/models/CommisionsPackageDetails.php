<?php

/**
 * This is the model class for table "{{commisions_package_details}}".
 *
 * The followings are the available columns in table '{{commisions_package_details}}':
 * @property string $id
 * @property string $order_id
 * @property string $order_province_code
 * @property integer $order_status
 * @property string $order_note
 * @property string $order_create_date
 * @property string $phone_customer
 * @property string $item_id
 * @property string $item_name
 * @property string $item_price
 * @property string $item_price_original
 * @property string $amount
 * @property string $renewal_count
 * @property string $create_date
 * @property string $affiliate_click_id
 * @property integer $affiliate_channel
 * @property string $affiliate_username
 * @property string $affiliate_province_code
 * @property integer $package_type
 * @property integer $sub_type
 */
class CommisionsPackageDetails extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{commisions_package_details}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_status, affiliate_channel, package_type, sub_type', 'numerical', 'integerOnly'=>true),
			array('order_id, order_province_code, order_note, phone_customer, item_id, item_name, item_price, item_price_original, affiliate_click_id, affiliate_username, affiliate_province_code', 'length', 'max'=>255),
			array('amount, renewal_count', 'length', 'max'=>10),
			array('order_create_date, create_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_id, order_province_code, order_status, order_note, order_create_date, phone_customer, item_id, item_name, item_price, item_price_original, amount, renewal_count, create_date, affiliate_click_id, affiliate_channel, affiliate_username, affiliate_province_code, package_type, sub_type', 'safe', 'on'=>'search'),
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
			'order_id' => 'Order',
			'order_province_code' => 'Order Province Code',
			'order_status' => 'Order Status',
			'order_note' => 'Order Note',
			'order_create_date' => 'Order Create Date',
			'phone_customer' => 'Phone Customer',
			'item_id' => 'Item',
			'item_name' => 'Item Name',
			'item_price' => 'Item Price',
			'item_price_original' => 'Item Price Original',
			'amount' => 'Amount',
			'renewal_count' => 'Renewal Count',
			'create_date' => 'Create Date',
			'affiliate_click_id' => 'Affiliate Click',
			'affiliate_channel' => 'Affiliate Channel',
			'affiliate_username' => 'Affiliate Username',
			'affiliate_province_code' => 'Affiliate Province Code',
			'package_type' => 'Package Type',
			'sub_type' => 'Sub Type',
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
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('order_province_code',$this->order_province_code,true);
		$criteria->compare('order_status',$this->order_status);
		$criteria->compare('order_note',$this->order_note,true);
		$criteria->compare('order_create_date',$this->order_create_date,true);
		$criteria->compare('phone_customer',$this->phone_customer,true);
		$criteria->compare('item_id',$this->item_id,true);
		$criteria->compare('item_name',$this->item_name,true);
		$criteria->compare('item_price',$this->item_price,true);
		$criteria->compare('item_price_original',$this->item_price_original,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('renewal_count',$this->renewal_count,true);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('affiliate_click_id',$this->affiliate_click_id,true);
		$criteria->compare('affiliate_channel',$this->affiliate_channel);
		$criteria->compare('affiliate_username',$this->affiliate_username,true);
		$criteria->compare('affiliate_province_code',$this->affiliate_province_code,true);
		$criteria->compare('package_type',$this->package_type);
		$criteria->compare('sub_type',$this->sub_type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CommisionsPackageDetails the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
