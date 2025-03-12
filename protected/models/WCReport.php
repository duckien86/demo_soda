<?php

/**
 * This is the model class for table "{{wc_report}}".
 *
 * The followings are the available columns in table '{{wc_report}}':
 * @property integer $id
 * @property integer $match_id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $team_selected
 * @property integer $lucky_number
 * @property string $create_time
 * @property integer $status
 */
class WCReport extends CActiveRecord
{
	CONST ACTIVE 	= 1;
	CONST INACTIVE 	= 0;
	CONST WINNER   	= 10;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{wc_report}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('match_id, lucky_number, status', 'numerical', 'integerOnly'=>true),
			array('name, phone, email', 'length', 'max'=>255),
			array('team_selected', 'length', 'max'=>20),
			array('create_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, match_id, name, phone, email, team_selected, lucky_number, create_time, status', 'safe', 'on'=>'search'),
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
			'match_id' => 'Match',
			'name' => 'Name',
			'phone' => 'Phone',
			'email' => 'Email',
			'team_selected' => 'Team Selected',
			'lucky_number' => 'Lucky Number',
			'create_time' => 'Create Time',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('match_id',$this->match_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('team_selected',$this->team_selected,true);
		$criteria->compare('lucky_number',$this->lucky_number);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WCReport the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
