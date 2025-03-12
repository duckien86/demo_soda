<?php

/**
 * This is the model class for table "{{survey_question}}".
 *
 * The followings are the available columns in table '{{survey_question}}':
 * @property integer $id
 * @property string $content
 * @property string $first_label
 * @property string $last_label
 * @property integer $type
 * @property integer $survey_id
 * @property integer $sort_order
 * @property integer $point
 * @property integer $status
 */
class ASurveyQuestion extends SurveyQuestion
{
	CONST TYPE_CHOOSE_ONE 	= 1;
	CONST TYPE_CHOOSE_MANY 	= 2;
	CONST TYPE_CUSTOMIZE 	= 3;

	public $survey;

	public $answer;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('survey_id, content, type', 'required'),
			array('type, survey_id, sort_order, point, status', 'numerical', 'integerOnly'=>true),
			array('first_label, last_label', 'length', 'max'=>255),
			array('survey', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, content, first_label, last_label, type, survey_id, sort_order, point, status, survey', 'safe', 'on'=>'search'),
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
			'content' 		=> Yii::t('adm/label', 'question_content'),
			'first_label' 	=> Yii::t('adm/label', 'first_label'),
			'last_label' 	=> Yii::t('adm/label', 'last_label'),
			'type' 			=> Yii::t('adm/label', 'question_type'),
			'survey_id' 	=> Yii::t('adm/label', 'survey_id'),
			'sort_order' 	=> Yii::t('adm/label', 'sort_order'),
			'point' 		=> Yii::t('adm/label', 'point'),
			'status' 		=> Yii::t('adm/label', 'status'),
			'survey'		=> Yii::t('adm/label', 'survey_id'),
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
		$criteria->compare('content',$this->content,true);
		$criteria->compare('first_label',$this->first_label,true);
		$criteria->compare('last_label',$this->last_label,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('survey_id',$this->survey_id);
		$criteria->compare('sort_order',$this->sort_order);
		$criteria->compare('point',$this->point);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SurveyQuestion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return array
	 */
	public static function getAllQuestionType()
	{
		return array(
			self::TYPE_CHOOSE_ONE 	=> Yii::t('adm/label','choose_one'),
			self::TYPE_CHOOSE_MANY 	=> Yii::t('adm/label','choose_many'),
			self::TYPE_CUSTOMIZE 		=> Yii::t('adm/label','user_input'),
		);
	}

	/**
	 * @param $type int
	 * @return mixed
	 */
	public static function getQuestionType($type)
	{
		$array_type = ASurveyQuestion::getAllQuestionType();

		return (isset($array_type[$type])) ? $array_type[$type] : $type;
	}

	/**
	 * @param $survey_id int
	 * @return static[]
	 */
	public static function getListQuestionBySurveyId($survey_id)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 't.survey_id = :survey_id';
		$criteria->params = array(
			':survey_id' => $survey_id
		);
		$criteria->order = 't.sort_order IS NULL ASC, t.sort_order ASC';
		return ASurveyQuestion::model()->findAll($criteria);
	}

	public function beforeDelete()
	{
		$list_answer = ASurveyAnswer::getListAnswersByQuestionId($this->id);
		if(!empty($list_answer)){
			foreach ($list_answer as $answer){
				$answer->delete();
			}
		}
		return true;
	}

	/**
	 * @param $id
	 * @return string
	 */
	public static function getQuestionContent($id)
	{
		$content = '';
		$model = ASurveyQuestion::model()->findByPk($id);
		if($model){
			$content = $model->content;
		}
		return $content;
	}
}
