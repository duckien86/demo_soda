<?php

class WWCReport extends WCReport
{
	CONST ACTIVE 	= 1;
	CONST INACTIVE 	= 0;
	CONST WINNER   	= 10;

	public $score_1;
	public $score_2;
	public $match_type;
	public $match;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('match_id, name, phone, email, team_selected, lucky_number', 'required'),
			array('match_id, status', 'numerical', 'integerOnly'=>true),
			array('name, phone, email', 'length', 'max'=>255),
			array('name', 'checkName'),
			array('match_id', 'checkUnique'),
			array('lucky_number', 'checkLuckyNumber'),
			array('email', 'email'),
			array('phone', 'msisdn_validation'),
			array('team_selected', 'length', 'max'=>20),
			array('score_1, score_2', 'numerical', 'integerOnly'=>true, 'min' => 0),
			array('create_time, match_type', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, match_id, name, phone, email, team_selected, lucky_number, create_time, status', 'safe', 'on'=>'search'),
		);
	}

	public function checkName()
	{
		if(!empty($this->name)){
			$name = Utils::unsign_string($this->name, ' ', TRUE);
			if(preg_match('/^[a-zA-z\s]+$/',$name) == TRUE){
				return TRUE;
			}else{
				$this->addError('name', 'Họ tên không hợp lệ!');
			}
		}
		return FALSE;
	}

	/**
	 * check if phone is valid
	 *
	 * @return bool
	 */
	public function msisdn_validation($attribute)
	{
		if ($this->phone) {
			$input = $this->$attribute;
			if (preg_match("/^0[0-9]{9,10}$/i", $input) == TRUE || preg_match("/^84[0-9]{9,11}$/i", $input) == TRUE) {
				return TRUE;
			} else {
				$this->addError($attribute, Yii::t('web/portal', 'msisdn_validation'));
			}
		}

		return FALSE;
	}

	public function checkLuckyNumber()
	{
		if(!empty($this->lucky_number)){
			if(preg_match("/^[0-9]{2,4}$/i", $this->lucky_number) == TRUE){
				TRUE;
			}else{
				$this->addError('lucky_number', 'Số may mắn không hợp lệ! Số may mắn được ghép từ số bàn đội thắng và số bàn đội thua');
				return FALSE;
			}
		}
		return TRUE;
	}

	public function checkUnique(){
		if(!empty($this->match_id) && (!empty($this->phone) || !empty($this->email)))
		{
			$criteria = new CDbCriteria();
			$criteria->condition = 't.match_id = :match_id AND (t.phone = :phone OR t.email = :email)';
			$criteria->params = array(
				':match_id' => $this->match_id,
				':phone' 	=> $this->phone,
				':email' 	=> $this->email,
			);
			$match = WWCReport::model()->find($criteria);
			if($match){
				$this->addError('match_id', 'Bạn đã dự đoán trận đấu này rồi');
				return false;
			}
		}
		return TRUE;
	}

	protected function beforeSave()
	{
		if($this->isNewRecord){
			$this->create_time = date('Y-m-d H:i:s');
			$this->status = WCReport::ACTIVE;
		}
		return TRUE;
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
			'id' 			=> Yii::t('web/portal','id'),
			'match_id' 		=> Yii::t('web/portal','match'),
			'name' 			=> Yii::t('web/portal','name'),
			'phone' 		=> Yii::t('web/portal','phone'),
			'email' 		=> Yii::t('web/portal','email'),
			'team_selected' => 'Đội thắng',
			'lucky_number' 	=> 'Số may mắn',
			'create_time' 	=> Yii::t('web/portal','create_time'),
			'status' 		=> Yii::t('web/portal','status'),
			'score_1'		=> 'Bàn thắng',
			'score_2'		=> 'Bàn thắng',
			'match_type'	=> 'Vòng đấu',
			'match'			=> 'Trận đấu',
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

		$criteria->join = 'INNER JOIN tbl_wc_match m ON t.match_id = m.id';
		$criteria->select = "t.*, m.type as 'match_type'";

		$criteria->compare('t.status',WWCReport::WINNER);

		if(!empty($this->match_id)){
			$criteria->compare('t.match_id',$this->match_id);
		}
		if(!empty($this->match_type)){
			$criteria->compare('m.type',$this->match_type);
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'       => array(
				'defaultOrder' => 'm.type ASC, t.create_time ASC, t.name ASC',
			),
			'pagination' => array(
				'pageSize' => 20,
				'params'   => array(
					'get'                          	=> 1,
					'WWCReport[match_id]' 			=> $this->match_id,
					'WWCReport[match_type]'      	=> $this->match_type,
				),
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WWCReport the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getListStatus(){
		return array(
			self::ACTIVE 	=> Yii::t('adm/label', 'active'),
			self::INACTIVE 	=> Yii::t('adm/label', 'inactive'),
			self::WINNER 	=> Yii::t('adm/label', 'winner'),
		);
	}

	public static function getStatusLabel($status){
		$data = self::getListStatus();
		return isset($data[$status]) ? $data[$status] : $status;
	}
}
