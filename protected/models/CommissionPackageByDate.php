<?php

/**
 * This is the model class for table "{{commission_package_by_date}}".
 *
 * The followings are the available columns in table '{{commission_package_by_date}}':
 * @property string $id
 * @property string $create_date
 * @property integer $type
 * @property integer $total
 * @property integer $total_income
 * @property integer $status
 * @property string $amount
 * @property integer $affiliate_channel
 * @property string $order_province_code
 * @property string $item_id
 */
class CommissionPackageByDate extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{commission_package_by_date}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('type, total, total_income, status, affiliate_channel', 'numerical', 'integerOnly'=>true),
			array('id, item_id', 'length', 'max'=>11),
			array('amount', 'length', 'max'=>10),
			array('order_province_code', 'length', 'max'=>255),
			array('create_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, create_date, type, total, total_income, status, amount, affiliate_channel, order_province_code, item_id', 'safe', 'on'=>'search'),
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
			'create_date' => 'Create Date',
			'type' => 'Type',
			'total' => 'Total',
			'total_income' => 'Total Income',
			'status' => 'Status',
			'amount' => 'Amount',
			'affiliate_channel' => 'Affiliate Channel',
			'order_province_code' => 'Order Province Code',
			'item_id' => 'Item',
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
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('total',$this->total);
		$criteria->compare('total_income',$this->total_income);
		$criteria->compare('status',$this->status);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('affiliate_channel',$this->affiliate_channel);
		$criteria->compare('order_province_code',$this->order_province_code,true);
		$criteria->compare('item_id',$this->item_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CommissionPackageByDate the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
