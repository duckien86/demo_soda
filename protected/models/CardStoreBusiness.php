<?php

/**
 * This is the model class for table "{{card_store_business}}".
 *
 * The followings are the available columns in table '{{card_store_business}}':
 * @property integer $id
 * @property string $import_code
 * @property string $serial
 * @property string $pin
 * @property integer $value
 * @property integer $status
 * @property string $expired_date
 * @property string $create_date
 * @property string $active_date
 * @property string $release_date
 * @property string $type
 * @property string $note
 * @property string $purchase_by
 * @property string $order_id
 * @property string $user_create
 * @property integer $store_id
 */
class CardStoreBusiness extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_card_store_business';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, import_code, serial, pin, value, status, expired_date', 'required'),
			array('id, value, status, store_id', 'numerical', 'integerOnly'=>true),
			array('import_code', 'length', 'max'=>20),
			array('serial, pin, type, note, purchase_by, order_id, user_create', 'length', 'max'=>255),
			array('create_date, active_date, release_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, import_code, serial, pin, value, status, expired_date, create_date, active_date, release_date, type, note, purchase_by, order_id, user_create, store_id', 'safe', 'on'=>'search'),
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
			'import_code' => 'Import Code',
			'serial' => 'Serial',
			'pin' => 'Pin',
			'value' => 'Value',
			'status' => 'Status',
			'expired_date' => 'Expired Date',
			'create_date' => 'Create Date',
			'active_date' => 'Active Date',
			'release_date' => 'Release Date',
			'type' => 'Type',
			'note' => 'Note',
			'purchase_by' => 'Purchase By',
			'order_id' => 'Order',
			'user_create' => 'User Create',
			'store_id' => 'Store ID',
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
		$criteria->compare('import_code',$this->import_code,true);
		$criteria->compare('serial',$this->serial,true);
		$criteria->compare('pin',$this->pin,true);
		$criteria->compare('value',$this->value);
		$criteria->compare('status',$this->status);
		$criteria->compare('expired_date',$this->expired_date,true);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('active_date',$this->active_date,true);
		$criteria->compare('release_date',$this->release_date,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('purchase_by',$this->purchase_by,true);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('user_create',$this->user_create,true);
		$criteria->compare('store_id',$this->store_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CardStoreBusiness the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_freedoo_tourist;
	}
}
