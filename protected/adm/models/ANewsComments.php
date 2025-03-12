<?php

class ANewsComments extends NewsComments
{
	public $start_date;
	public $end_date;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('news_id, comment_parent, status', 'numerical', 'integerOnly'=>true),
			array('ip, username, email', 'length', 'max'=>255),
			array('content, created_on', 'safe'),
			array('end_date', 'checkDate', 'on' => 'search'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, news_id, ip, comment_parent, username, email, content, status, created_on', 'safe', 'on'=>'search'),
		);
	}

	public function checkDate()
	{
		if(!empty($this->start_date) && !empty($this->end_date)){
			$start_date = date('Y-m-d', strtotime(str_replace('/','-',$this->start_date)));
			$end_date = date('Y-m-d', strtotime(str_replace('/','-',$this->end_date)));
			if($end_date < $start_date){
				$this->addError('end_date', 'Ngày kết thúc phải lớn hơn ngày bắt đầu');
				return FALSE;
			}
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
			'id' 				=> Yii::t('adm/label', 'id'),
			'news_id' 			=> Yii::t('adm/label', 'news_id'),
			'ip' 				=> 'IP',
			'comment_parent' 	=> Yii::t('adm/label', 'news_comment_parent_id'),
			'username' 			=> Yii::t('adm/label', 'user_comment'),
			'email' 			=> Yii::t('adm/label', 'email'),
			'content' 			=> Yii::t('adm/label', 'content'),
			'status' 			=> Yii::t('adm/label', 'status'),
			'created_on' 		=> Yii::t('adm/label', 'create_date'),

			'start_date'		=> Yii::t('adm/label', 'start_date'),
			'end_date'			=> Yii::t('adm/label', 'finish_date'),
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
		$criteria->compare('news_id',$this->news_id);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('comment_parent',$this->comment_parent);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('status',$this->status);

		if(!empty($this->start_date)){
			$start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . " 00:00:00";
			$criteria->addCondition("t.created_on >= '$start_date'");
		}
		if(!empty($this->end_date)){
			$end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . " 23:59:59";
			$criteria->addCondition("t.created_on <= '$end_date'");
		}

		return new CActiveDataProvider($this, array(
			'criteria'		=>	$criteria,
			'pagination' 	=> array(
				'params' => array(
					"ANewsComments[start_date]"       => $this->start_date,
					"ANewsComments[end_date]"         => $this->end_date,
				),
				'pageSize' => 10,
			),
			'sort' => array(
				'defaultOrder' => 'id DESC',
			)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ANewsComments the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getListStatus(){
		return array(
			ANewsComments::ACTIVE => Yii::t('adm/label', 'active'),
			ANewsComments::INACTIVE => Yii::t('adm/label', 'inactive'),
		);
	}

	public static function getStatusLabel($status){
		$data = self::getListStatus();
		return (isset($data[$status])) ? $data[$status] : $status;
	}

}
