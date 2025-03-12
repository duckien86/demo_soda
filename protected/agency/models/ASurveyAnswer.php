<?php

/**
 * This is the model class for table "{{survey_answer}}".
 *
 * The followings are the available columns in table '{{survey_answer}}':
 * @property integer $id
 * @property integer $question_id
 * @property string $content
 * @property integer $type
 * @property integer $is_right
 * @property integer $sort_order
 * @property integer $status
 */
class ASurveyAnswer extends SurveyAnswer
{
	CONST ANSWER_ACTIVE = 1;
	CONST ANSWER_INACTIVE = 0;

	CONST TYPE_AVAILABLE = 1;
	CONST TYPE_CUSTOMIZE = 2;

	CONST RIGHT_ANSWER = 1;
	CONST WRONG_ANSWER = 0;

	CONST ANSWER_USER_INPUT_ID = -1;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('question_id, content', 'required'),
			array('question_id, type, is_right, sort_order, status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, question_id, content, type, is_right, sort_order, status', 'safe', 'on'=>'search'),
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
			'question_id' 	=> Yii::t('adm/label', 'question_id'),
			'content' 		=> Yii::t('adm/label', 'answer_content'),
			'type' 			=> Yii::t('adm/label', 'answer_type'),
			'is_right' 		=> Yii::t('adm/label', 'right_answer'),
			'sort_order' 	=> Yii::t('adm/label', 'sort_order'),
			'status' 		=> Yii::t('adm/label', 'status'),
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
		$criteria->compare('question_id',$this->question_id);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('is_right',$this->is_right);
		$criteria->compare('sort_order',$this->sort_order);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SurveyAnswer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return array
	 */
	public static function getAllAnswerType()
	{
		return array(
			self::TYPE_AVAILABLE => Yii::t('adm/label','answer_available'),
			self::TYPE_CUSTOMIZE => Yii::t('adm/label','user_input'),
		);
	}

	/**
	 * @param $status int
	 * @return bool
	 */
	public static function isActive($status){
		return ($status == self::ANSWER_ACTIVE) ? true : false;
	}

	/**
	 * @param $is_right int
	 * @return bool
	 */
	public static function isRight($is_right){
		return ($is_right == self::RIGHT_ANSWER) ? true : false;
	}

	/**
	 * @param $question_id int
	 * @return static[]
	 */
	public static function getListAnswersByQuestionId($question_id)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 't.question_id = :question_id';
		$criteria->params = array(
			':question_id' => $question_id
		);
		$criteria->order = 't.sort_order IS NULL ASC, t.sort_order ASC';
		return ASurveyAnswer::model()->findAll($criteria);
	}

	/**
	 * @param $id
	 * @return string
	 */
	public static function getAnswerContent($id)
	{
		$content = '';
		$model = ASurveyAnswer::model()->findByPk($id);
		if($model){
			$content = $model->content;
		}
		return $content;
	}
}
