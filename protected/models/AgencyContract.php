<?php

/**
 * This is the model class for table "{{agency_contract}}".
 *
 * The followings are the available columns in table '{{agency_contract}}':
 * @property string $id
 * @property string $code
 * @property integer $agency_id
 * @property string $create_time
 * @property string $last_update
 * @property string $start_date
 * @property string $finish_date
 * @property string $note
 * @property integer $status
 * @property integer $create_by
 */
class AgencyContract extends CActiveRecord
{
	CONST CONTRACT_PENDING  = 0;
	CONST CONTRACT_ACTIVE   = 1;
	CONST CONTRACT_COMPLETE = 10;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{agency_contract}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, agency_id', 'required'),
			array('agency_id, status, create_by', 'numerical', 'integerOnly'=>true),
			array('code, note', 'length', 'max'=>255),
			array('create_time, last_update, start_date, finish_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, agency_id, create_time, last_update, start_date, finish_date, note, status, create_by', 'safe', 'on'=>'search'),
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
			'code' => 'Code',
			'agency_id' => 'Agency',
			'create_time' => 'Create Time',
			'last_update' => 'Last Update',
			'start_date' => 'Start Date',
			'finish_date' => 'Finish Date',
			'note' => 'Note',
			'status' => 'Status',
			'create_by' => 'Create By',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('agency_id',$this->agency_id);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('last_update',$this->last_update,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('finish_date',$this->finish_date,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_by',$this->create_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AgencyContract the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
