<?php

/**
 * This is the model class for table "{{agency_contract_detail}}".
 *
 * The followings are the available columns in table '{{agency_contract_detail}}':
 * @property integer $contract_id
 * @property string $item_id
 * @property integer $quantity
 * @property integer $price_discount_percent
 * @property integer $price_discount_amount
 */
class AgencyContractDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{agency_contract_detail}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('contract_id, item_id', 'required'),
			array('contract_id, quantity, price_discount_percent, price_discount_amount', 'numerical', 'integerOnly'=>true),
			array('item_id', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('contract_id, item_id, quantity, price_discount_percent, price_discount_amount', 'safe', 'on'=>'search'),
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
			'contract_id' => 'Contract',
			'item_id' => 'Item',
			'quantity' => 'Quantity',
			'price_discount_percent' => 'Price Discount Percent',
			'price_discount_amount' => 'Price Discount Amount',
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

		$criteria->compare('contract_id',$this->contract_id);
		$criteria->compare('item_id',$this->item_id,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('price_discount_percent',$this->price_discount_percent);
		$criteria->compare('price_discount_amount',$this->price_discount_amount);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AgencyContractDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
