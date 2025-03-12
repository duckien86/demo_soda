<?php

class WWCMatch extends WCMatch
{
	CONST ACTIVE = 1;
	CONST INACTIVE = 0;
	CONST COMPLETE = 10;

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
	 * @return WWCMatch the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getAllMatch($type = null, $filter = false)
	{
		$criteria = new CDbCriteria();
		$criteria->order = 't.type ASC, t.create_time ASC';
		if(!empty($type)){
			$criteria->condition = 't.type = :type';
			$criteria->params = array(
				':type' => $type
 			);
		}
		if($filter){
			$data = array();
			$models = WWCMatch::model()->findAll($criteria);
			foreach ($models as $model){
				$data[$model->id] = $model->team_name_1 . ' - ' . $model->team_name_2;
			}
			return $data;
		}else{
			$criteria->addCondition('t.status = '.WWCMatch::ACTIVE);
			return WWCMatch::model()->findAll($criteria);
		}
	}

	public static function getListType(){
		return array(
			self::TYPE_QUARTERFINAL => 'Tứ kết',
			self::TYPE_SEMIFINAL	=> 'Bán kết',
			self::TYPE_THIRD_PLACE	=> 'Tranh hạng ba',
			self::TYPE_GRANDFINAL	=> 'Chung kết',
		);
	}

	public static function getTypeLabel($type){
		$data = self::getListType();
		return isset($data[$type]) ? $data[$type] : $type;
	}
}
