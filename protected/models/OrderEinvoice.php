<?php

/**
 * This is the model class for table "{{order_einvoice}}".
 *
 * The followings are the available columns in table '{{order_einvoice}}':
 * @property integer $id
 * @property string $order_id
 * @property string $key
 * @property integer $number
 * @property string $e_invoice_file_url
 * @property string $status
 * @property string $create_date
 * @property string $c_name
 * @property string $c_email
 * @property string $c_tax_code
 * @property string $c_address
 * @property string $c_note
 * @property string $c_phone
 */
class OrderEinvoice extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{order_einvoice}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('number', 'numerical', 'integerOnly'=>true),
			array('order_id, key, e_invoice_file_url, status, c_name, c_email, c_tax_code, c_address, c_note, c_phone', 'length', 'max'=>255),
			array('create_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_id, key, number, e_invoice_file_url, status, create_date, c_name, c_email, c_tax_code, c_address, c_note, c_phone', 'safe', 'on'=>'search'),
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
			'key' => 'Key',
			'number' => 'Number',
			'e_invoice_file_url' => 'E Invoice File Url',
			'status' => 'Status',
			'create_date' => 'Create Date',
			'c_name' => 'C Name',
			'c_email' => 'C Email',
			'c_tax_code' => 'C Tax Code',
			'c_address' => 'C Address',
			'c_note' => 'C Note',
			'c_phone' => 'C Phone',
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
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('key',$this->key,true);
		$criteria->compare('number',$this->number);
		$criteria->compare('e_invoice_file_url',$this->e_invoice_file_url,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('c_name',$this->c_name,true);
		$criteria->compare('c_email',$this->c_email,true);
		$criteria->compare('c_tax_code',$this->c_tax_code,true);
		$criteria->compare('c_address',$this->c_address,true);
		$criteria->compare('c_note',$this->c_note,true);
		$criteria->compare('c_phone',$this->c_phone,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrderEinvoice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
