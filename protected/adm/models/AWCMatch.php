<?php

class AWCMatch extends WCMatch
{

	public $hour;
	public $minute;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('team_code_1, team_code_2, start_time, type', 'required'),
			array('team_code_2','checkUnique'),
			array('type, score_1, score_2', 'numerical', 'integerOnly'=>true),
			array('team_name_1, team_name_2, team_code_1, team_code_2', 'length', 'max'=>20),
			array('flag_1, flag_2, status', 'length', 'max'=>255),
			array('start_time, create_time, hour, minute, count', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, team_name_1, team_name_2, type, start_time, score_1, score_2, flag_1, flag_2, team_code_1, team_code_2, status', 'safe', 'on'=>'search'),
		);
	}

	public function checkUnique(){
		if(!empty($this->team_code_1) && !empty($this->team_code_2)){
			if($this->team_code_1 == $this->team_code_2){
				$this->addError('team_code_2', 'Hai đội phải khác nhau');
				return FALSE;
			}else{
				$criteria = new CDbCriteria();
				$criteria->condition = '(t.team_code_1 = :team_code_1 AND t.team_code_2 = :team_code_2) 
									OR (t.team_code_1 = :team_code_2 AND t.team_code_2 = :team_code_1)';
				$criteria->params = array(
					':team_code_1' => $this->team_code_1,
					':team_code_2' => $this->team_code_2,
				);
				if(!$this->isNewRecord){
					$criteria->addCondition('t.id != '.$this->id);
				}
				$model = AWCMatch::model()->find($criteria);
				if($model){
					$this->addError('team_code_2', 'Trận đấu này đã tồn tại');
					return FALSE;
				}
			}
		}
		RETURN TRUE;
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
			'id' 			=> Yii::t('adm/label','id'),
			'team_name_1' 	=> 'Tên đội 1',
			'team_name_2' 	=> 'Tên đội 2',
			'type' 			=> 'Loại trận đấu',
			'start_time' 	=> 'Thời gian bắt đầu trận đấu',
			'score_1' 		=> 'Số bàn đội 1',
			'score_2'	 	=> 'Số bàn đội 2',
			'flag_1' 		=> 'Cờ đội 1',
			'flag_2' 		=> 'Cờ đội 2',
			'team_code_1' 	=> 'Mã đội 1',
			'team_code_2' 	=> 'Mã đội 2',
			'status' 		=> 'Trạng thái',
			'create_time'	=> 'Ngày tạo',
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
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'   => $criteria,
			'sort'       => array(
				'defaultOrder' => 't.type ASC, t.create_time ASC',
			),
			'pagination' => array(
				'pageSize' => 10,
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AWCMatch the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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

	public static function getListStatus()
	{
		return array(
			self::ACTIVE 	=> Yii::t('adm/label','active'),
			self::INACTIVE 	=> Yii::t('adm/label','inactive'),
			self::COMPLETE 	=> Yii::t('adm/label','complete'),
		);
	}

	public static function getStatusLabel($status)
	{
		$data = self::getListStatus();
		return isset($data[$status]) ? $data[$status] : $status;
	}

	protected function beforeSave()
	{
		if($this->isNewRecord){
			$this->create_time = date('Y-m-d H:i:s');
		}
		$this->start_time = date('Y-m-d H:i:s', strtotime($this->start_time));
		return TRUE;
	}

	public function getBtnUpdate(){
		if($this->status == AWCMatch::COMPLETE){
			return false;
		}else{
			return true;
		}
	}

	public function getBtnDelete(){
		if($this->status == AWCMatch::COMPLETE){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * @param null $type
	 * @return array
	 */
	public static function getAllMatch($type = null){
		$data = array();

		if($type){
			$criteria = new CDbCriteria();
			$criteria->condition = 't.type = :type';
			$criteria->params = array(
				':type' => $type
			);
			$models = AWCMatch::model()->findAll($criteria);
		}else{
			$models = AWCMatch::model()->findAll();
		}
		foreach ($models as $model){
			$data[$model->id] = $model->team_name_1 . ' - ' . $model->team_name_2;
		}
		return $data;
	}

	public function getRewardLimit()
	{
		switch ($this->type){
			case self::TYPE_QUARTERFINAL:
				return 10;
			case self::TYPE_SEMIFINAL:
				return 10;
			case self::TYPE_THIRD_PLACE:
				return 3;
			case self::TYPE_GRANDFINAL:
				return 3;
			default:
				return 0;
		}
	}

	public static function getRewardUsed($match_id)
	{
		$criteria = new CDbCriteria();

		$criteria->select = "COUNT(*) as 'count'";
		$criteria->condition = 't.match_id = :match_id AND t.status = :status';
		$criteria->params = array(
			':match_id' => $match_id,
			':status' => AWCReport::WINNER,
		);

		$data = AWCReport::model()->findAll($criteria);
		if ($data) {
			return $data[0]->count;
		} else{
			return 0;
		}
	}
}
