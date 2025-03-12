<?php

/**
 * This is the model class for table "{{survey_report}}".
 *
 * The followings are the available columns in table '{{survey_report}}':
 * @property integer $id
 * @property integer $survey_id
 * @property string $user_id
 * @property string $order_id
 * @property integer $question_id
 * @property integer $answer_id
 * @property string $content
 * @property integer $is_right
 * @property string $create_date
 */
class ASurveyReport extends SurveyReport
{
	public $start_date;
	public $end_date;

	public $user;
	public $question;
	public $answer;
	public $phone;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('survey_id, user_id, order_id, question_id, answer_id', 'required'),
			array('survey_id, question_id, answer_id, is_right', 'numerical', 'integerOnly'=>true),
			array('user_id', 'length', 'max'=>255),
			array('order_id', 'length', 'max'=>100),
			array('content, create_date, user, question, answer, phone', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, survey_id, user_id, order_id, question_id, answer_id, content, is_right, create_date, start_date, end_date, user, question, answer, phone', 'safe', 'on'=>'search'),
			array('end_date', 'compare', 'compareAttribute' => 'start_date', 'operator' => '>=', 'allowEmpty' => FALSE, 'message' => Yii::t('adm/label','end_date_must_greater'), 'on' => 'search'),
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
			'id' 			=> Yii::t('adm/label', 'id'),
			'survey_id' 	=> Yii::t('adm/label', 'survey_id'),
			'user_id' 		=> Yii::t('adm/label', 'customer'),
			'order_id' 		=> Yii::t('adm/label', 'order_id'),
			'question_id'	=> Yii::t('adm/label', 'question'),
			'answer_id' 	=> Yii::t('adm/label', 'answer'),
			'content' 		=> Yii::t('adm/label', 'content'),
			'is_right' 		=> Yii::t('adm/label', 'right_answer'),
			'create_date'   => Yii::t('adm/label', 'create_date'),

			'start_date'  	=> Yii::t('adm/label', 'start_date'),
			'end_date'  	=> Yii::t('adm/label', 'finish_date'),
			'user' 			=> Yii::t('adm/label', 'customer'),
			'question'		=> Yii::t('adm/label', 'question'),
			'answer' 		=> Yii::t('adm/label', 'answer'),
			'order' 		=> Yii::t('adm/label', 'order_id'),
			'phone' 		=> Yii::t('adm/label', 'phone_survey'),
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

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.survey_id',$this->survey_id);
		$criteria->compare('t.user_id',$this->user_id,true);
		$criteria->compare('t.order_id',$this->order_id,true);
		$criteria->compare('t.question_id',$this->question_id);
		$criteria->compare('t.answer_id',$this->answer_id);
		$criteria->compare('t.content',$this->content,true);
		$criteria->compare('t.is_right',$this->is_right);

		if($this->start_date && $this->end_date){
			$this->start_date	= str_replace('/','-',$this->start_date);
			$this->start_date 	= date('Y-m-d H:i:s',strtotime($this->start_date));

			$this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
			$this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';

			$criteria->addCondition("t.create_date >= '$this->start_date' AND t.create_date <= '$this->end_date'");
		}

		if($dataProvider){
			return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'sort'       => array(
					'defaultOrder' => 't.create_date DESC',
				),
				'pagination' => array(
					'pageSize' => 50,
				),
			));
		}else{
			$criteria->order = 't.create_date DESC';
			return ASurveyReport::model()->findAll($criteria);
		}

	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SurveyReport the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
