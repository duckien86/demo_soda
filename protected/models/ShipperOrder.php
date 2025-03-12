<?php

/**
 * This is the model class for table "{{shipper_order}}".
 *
 * The followings are the available columns in table '{{shipper_order}}':
 * @property string $id
 * @property string $order_id
 * @property string $shipper_id
 * @property string $assign_date
 * @property string $delivery_date
 * @property string $finish_date
 * @property string $ship_cost
 * @property string $assign_by
 * @property string $note
 * @property integer $status
 */
class ShipperOrder extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{shipper_order}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status', 'numerical', 'integerOnly'=>true),
			array('order_id, shipper_id, assign_by, note', 'length', 'max'=>255),
			array('ship_cost', 'length', 'max'=>10),
			array('assign_date, delivery_date, finish_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_id, shipper_id, assign_date, delivery_date, finish_date, ship_cost, assign_by, note, status', 'safe', 'on'=>'search'),
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
			'shipper_id' => 'Shipper',
			'assign_date' => 'Assign Date',
			'delivery_date' => 'Delivery Date',
			'finish_date' => 'Finish Date',
			'ship_cost' => 'Ship Cost',
			'assign_by' => 'Assign By',
			'note' => 'Note',
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
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('shipper_id',$this->shipper_id,true);
		$criteria->compare('assign_date',$this->assign_date,true);
		$criteria->compare('delivery_date',$this->delivery_date,true);
		$criteria->compare('finish_date',$this->finish_date,true);
		$criteria->compare('ship_cost',$this->ship_cost,true);
		$criteria->compare('assign_by',$this->assign_by,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ShipperOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
