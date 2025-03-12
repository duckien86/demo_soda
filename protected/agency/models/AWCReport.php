<?php

class AWCReport extends WCReport
{

	public $match;
	public $match_type;
	public $info;

	public $count;

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
			array('create_time, match, match_type, info, count', 'safe'),
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
			'id' 			=> Yii::t('adm/label','id'),
			'match_id' 		=> 'Trận đấu',
			'name' 			=> 'Tên người dự đoán',
			'phone' 		=> 'Số điện thoại',
			'email' 		=> 'E-mail',
			'team_selected' => 'Đội lựa chọn',
			'lucky_number' 	=> 'Số may mắn',
			'create_time' 	=> 'Thời gian tạo',
			'status' 		=> Yii::t('adm/label','status'),
			'match_type'	=> 'Vòng đấu',
			'info'			=> 'Thông tin người dự đoán',
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
	public function search($dataProvider = TRUE)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->join = 'INNER JOIN tbl_wc_match m ON t.match_id = m.id';
		$criteria->select = "t.*, CONCAT(m.team_name_1, ' - ', m.team_name_2) as 'match', m.type as 'match_type'";

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.match_id',$this->match_id);
//		$criteria->compare('t.name',$this->name,true);
//		$criteria->compare('t.phone',$this->phone,true);
//		$criteria->compare('t.email',$this->email,true);
		$criteria->compare('t.team_selected',$this->team_selected,true);
		$criteria->compare('t.lucky_number',$this->lucky_number);
//		$criteria->compare('t.create_time',$this->create_time,true);
		$criteria->compare('t.status',$this->status);

		$criteria->compare('m.type', $this->match_type);
		if(!empty($this->info)){
			$criteria->addCondition("t.name LIKE '%$this->info%' OR t.phone LIKE '%$this->info%' OR t.email LIKE '%$this->info%'");
		}

		if($dataProvider){
			return new CActiveDataProvider($this, array(
				'criteria'   => $criteria,
				'sort'       => array(
					'defaultOrder' => 't.create_time ASC',
				),
				'pagination' => array(
					'pageSize' => 10,
					'params'   => array(
						'get'                          	=> 1,
						'AWCReport[match_id]' 			=> $this->match_id,
						'AWCReport[match_type]'      	=> $this->match_type,
						'AWCReport[team_selected]'      => $this->team_selected,
						'AWCReport[lucky_number]'      	=> $this->lucky_number,
						'AWCReport[status]'      		=> $this->status,
					),
				),
			));
		}else{
			$criteria->order = 't.create_time ASC';
			return AWCReport::model()->findAll($criteria);
		}

	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AWCReport the static model class
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

	public function getBtnGift(){
		if($this->status == AWCReport::ACTIVE){
			return true;
		}else{
			return false;
		}
	}

	public function getBtnBan(){
		if($this->status == AWCReport::WINNER){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Kiểm tra xem người dùng đã được trao thưởng dự đoán vòng worldcup hiện tại chưa
	 */
	public function isUserRewarded($type)
	{
		$criteria = new CDbCriteria();
		$criteria->join = 'INNER JOIN tbl_wc_match m ON t.match_id = m.id';
		$criteria->condition = 't.status = :status AND m.type = :type AND (t.phone = :phone OR t.email = :email)';
		$criteria->params = array(
			':status' 	=> AWCReport::WINNER,
			':type'		=> $type,
			':phone'	=> $this->phone,
			':email'	=> $this->email,
 		);
		$model = AWCReport::model()->find($criteria);
		if($model){
			return TRUE;
		}
		return FALSE;
	}

}
