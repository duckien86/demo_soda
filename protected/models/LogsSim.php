<?php

/**
 * This is the model class for table "{{logs_sim}}".
 *
 * The followings are the available columns in table '{{logs_sim}}':
 * @property string $id
 * @property string $create_date
 * @property string $msisdn
 * @property string $order_id
 * @property integer $status
 * @property integer $user_id
 * @property integer $type_user
 * @property string $note
 * @property string $extra_params
 */
class LogsSim extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{logs_sim}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('msisdn, status, user_id, type_user', 'required'),
			array('status, user_id, type_user', 'numerical', 'integerOnly'=>true),
			array('msisdn, order_id, note, extra_params', 'length', 'max'=>255),
			array('create_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, create_date, msisdn, order_id, status, user_id, type_user, note, extra_params', 'safe', 'on'=>'search'),
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
			'msisdn' => 'Msisdn',
			'order_id' => 'Order',
			'status' => 'Status',
			'user_id' => 'User',
			'type_user' => 'Type User',
			'note' => 'Note',
			'extra_params' => 'Extra Params',
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
		$criteria->compare('msisdn',$this->msisdn,true);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('type_user',$this->type_user);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('extra_params',$this->extra_params,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LogsSim the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
