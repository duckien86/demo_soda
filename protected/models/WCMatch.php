<?php

/**
 * This is the model class for table "{{wc_match}}".
 *
 * The followings are the available columns in table '{{wc_match}}':
 * @property integer $id
 * @property string $team_name_1
 * @property string $team_name_2
 * @property integer $type
 * @property string $start_time
 * @property integer $score_1
 * @property integer $score_2
 * @property string $flag_1
 * @property string $flag_2
 * @property string $team_code_1
 * @property string $team_code_2
 * @property string $status
 * @property string $create_time
 */
class WCMatch extends CActiveRecord
{

	CONST TYPE_QUARTERFINAL = 4;
	CONST TYPE_THIRD_PLACE  = 3;
	CONST TYPE_SEMIFINAL 	= 2;
	CONST TYPE_GRANDFINAL   = 1;

	CONST ACTIVE = 1;
	CONST INACTIVE = 0;
	CONST COMPLETE = 10;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{wc_match}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, score_1, score_2', 'numerical', 'integerOnly'=>true),
			array('team_name_1, team_name_2, team_code_1, team_code_2', 'length', 'max'=>20),
			array('flag_1, flag_2, status', 'length', 'max'=>255),
			array('start_time, create_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, team_name_1, team_name_2, type, start_time, score_1, score_2, flag_1, flag_2, team_code_1, team_code_2, status', 'safe', 'on'=>'search'),
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
			'team_name_1' => 'Team Name 1',
			'team_name_2' => 'Team Name 2',
			'type' => 'Type',
			'start_time' => 'Start Time',
			'score_1' => 'Score 1',
			'score_2' => 'Score 2',
			'flag_1' => 'Flag 1',
			'flag_2' => 'Flag 2',
			'team_code_1' => 'Team Code 1',
			'team_code_2' => 'Team Code 2',
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
		$criteria->compare('team_name_1',$this->team_name_1,true);
		$criteria->compare('team_name_2',$this->team_name_2,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('score_1',$this->score_1);
		$criteria->compare('score_2',$this->score_2);
		$criteria->compare('flag_1',$this->flag_1,true);
		$criteria->compare('flag_2',$this->flag_2,true);
		$criteria->compare('team_code_1',$this->team_code_1,true);
		$criteria->compare('team_code_2',$this->team_code_2,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WCMatch the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
