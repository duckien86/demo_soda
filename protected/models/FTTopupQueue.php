<?php

/**
 * This is the model class for table "tbl_topup_queue".
 *
 * The followings are the available columns in table 'tbl_topup_queue':
 * @property integer $id
 * @property string $serial
 * @property string $pin
 * @property integer $value
 * @property string $create_date
 * @property string $msisdn
 * @property string $user_create
 * @property integer $status
 * @property string $topup_date
 * @property string $note
 * @property string $order_id
 */
class FTTopupQueue extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_topup_queue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('serial, pin, value', 'required'),
			array('value, status', 'numerical', 'integerOnly'=>true),
			array('serial, pin, msisdn, user_create, order_id', 'length', 'max'=>255),
			array('create_date, topup_date, note', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, serial, pin, value, create_date, msisdn, user_create, status, topup_date, note, order_id', 'safe', 'on'=>'search'),
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
			'serial' => 'Serial',
			'pin' => 'Pin',
			'value' => 'Value',
			'create_date' => 'Create Date',
			'msisdn' => 'Msisdn',
			'user_create' => 'User Create',
			'status' => 'Status',
			'topup_date' => 'Topup Date',
			'note' => 'Note',
			'order_id' => 'Order',
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
		$criteria->compare('serial',$this->serial,true);
		$criteria->compare('pin',$this->pin,true);
		$criteria->compare('value',$this->value);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('msisdn',$this->msisdn,true);
		$criteria->compare('user_create',$this->user_create,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('topup_date',$this->topup_date,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('order_id',$this->order_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_freedoo_tourist;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FTTopupQueue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
