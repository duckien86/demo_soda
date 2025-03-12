<?php

/**
 * This is the model class for table "{{incentives_details}}".
 *
 * The followings are the available columns in table '{{incentives_details}}':
 * @property string $id
 * @property string $order_id
 * @property string $order_province_code
 * @property integer $order_status
 * @property string $order_note
 * @property string $order_create_date
 * @property string $active_date
 * @property string $phone_customer
 * @property string $item_id
 * @property string $item_name
 * @property string $item_price
 * @property string $item_term
 * @property string $item_price_term
 * @property string $amount
 * @property string $create_date
 * @property string $affiliate_click_id
 * @property integer $affiliate_channel
 * @property string $affiliate_username
 * @property string $affiliate_province_code
 * @property integer $sub_type
 * @property integer $campaign_category_id
 * @property integer $n_postback
 * @property integer $period
 */
class IncentivesDetails extends CActiveRecord
{
	CONST CAMPAIGN_PT_TB_TS = 1; // phát triển thuê bao trả sau
	CONST CAMPAIGN_TD_TKC = 2; // tiêu dùng tài khoản chính

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{incentives_details}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, order_id', 'required'),
			array('order_status, affiliate_channel, sub_type, campaign_category_id, n_postback, period', 'numerical', 'integerOnly'=>true),
			array('id, order_id, order_province_code, order_note, phone_customer, item_id, item_name, affiliate_click_id, affiliate_username, affiliate_province_code', 'length', 'max'=>255),
			array('item_price, item_term, item_price_term, amount', 'length', 'max'=>10),
			array('order_create_date, active_date, create_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_id, order_province_code, order_status, order_note, order_create_date, active_date, phone_customer, item_id, item_name, item_price, item_term, item_price_term, amount, create_date, affiliate_click_id, affiliate_channel, affiliate_username, affiliate_province_code, sub_type, campaign_category_id, n_postback, period', 'safe', 'on'=>'search'),
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
			'active_date' => 'Active Date',
			'phone_customer' => 'Phone Customer',
			'item_id' => 'Item',
			'item_name' => 'Item Name',
			'item_price' => 'Item Price',
			'item_term' => 'Item Term',
			'item_price_term' => 'Item Price Term',
			'amount' => 'Amount',
			'create_date' => 'Create Date',
			'affiliate_click_id' => 'Affiliate Click',
			'affiliate_channel' => 'Affiliate Channel',
			'affiliate_username' => 'Affiliate Username',
			'affiliate_province_code' => 'Affiliate Province Code',
			'sub_type' => 'Sub Type',
			'campaign_category_id' => 'Campaign Category',
			'n_postback' => 'N Postback',
			'period' => 'Period',
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
		$criteria->compare('active_date',$this->active_date,true);
		$criteria->compare('phone_customer',$this->phone_customer,true);
		$criteria->compare('item_id',$this->item_id,true);
		$criteria->compare('item_name',$this->item_name,true);
		$criteria->compare('item_price',$this->item_price,true);
		$criteria->compare('item_term',$this->item_term,true);
		$criteria->compare('item_price_term',$this->item_price_term,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('affiliate_click_id',$this->affiliate_click_id,true);
		$criteria->compare('affiliate_channel',$this->affiliate_channel);
		$criteria->compare('affiliate_username',$this->affiliate_username,true);
		$criteria->compare('affiliate_province_code',$this->affiliate_province_code,true);
		$criteria->compare('sub_type',$this->sub_type);
		$criteria->compare('campaign_category_id',$this->campaign_category_id);
		$criteria->compare('n_postback',$this->n_postback);
		$criteria->compare('period',$this->period);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return IncentivesDetails the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
