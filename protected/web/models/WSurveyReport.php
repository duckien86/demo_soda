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
class WSurveyReport extends SurveyReport
{

	public $question;

	public $count;
	
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
			array('content, create_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, survey_id, user_id, order_id, question_id, answer_id, content, is_right, create_date', 'safe', 'on'=>'search'),
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
			'id' 			=> Yii::t('web/portal', 'id'),
			'survey_id' 	=> Yii::t('web/portal', 'survey'),
			'user_id' 		=> Yii::t('web/portal', 'user'),
			'order_id' 		=> Yii::t('web/portal', 'order_id'),
			'question_id' 	=> Yii::t('web/portal', 'question'),
			'answer_id' 	=> Yii::t('web/portal', 'answer'),
			'content' 		=> Yii::t('web/portal', 'content'),
			'is_right' 		=> Yii::t('web/portal', 'is_right'),
			'create_date' 	=> Yii::t('web/portal', 'create_date'),
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
		$criteria->compare('survey_id',$this->survey_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('question_id',$this->question_id);
		$criteria->compare('answer_id',$this->answer_id);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('is_right',$this->is_right);
		$criteria->compare('create_date',$this->create_date);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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

	/**
	 * @param $list_survey_report array
	 * @return boolean
	 */
	public static function batchInsert($list_survey_report)
	{
		$rows = array();
		if(!empty($list_survey_report)){
			foreach ($list_survey_report as $survey_report){
				$rows[] = array(
					'survey_id'		=> $survey_report->survey_id,
					'user_id'		=> $survey_report->user_id,
					'order_id'		=> $survey_report->order_id,
					'question_id'	=> $survey_report->question_id,
					'answer_id'		=> $survey_report->answer_id,
					'content'		=> $survey_report->content,
					'is_right'		=> $survey_report->is_right,
					'create_date'	=> $survey_report->create_date,
				);
			}
			$build = new CDbCommandBuilder(Yii::app()->db->schema);
			$command = $build->createMultipleInsertCommand('tbl_survey_report', $rows);
			if($command->execute()){
				return true;
			}
		}
		return false;
	}

	/**
	 * @param $survey_id
	 * @return bool
	 */
	public static function isSurveyAvailableForCurrentUser($survey_id, $order_id)
	{
		$user_id = (isset(Yii::app()->user->sso_id)) ? Yii::app()->user->sso_id : '';

		$criteria = new CDbCriteria();
		if(!empty($user_id)){
			$criteria->condition = 't.survey_id = :survey_id AND (t.user_id = :user_id OR t.order_id = :order_id)';
			$criteria->params = array(
				':survey_id' => $survey_id,
				':user_id' => $user_id,
				':order_id' => $order_id,
			);
		}else{
			$criteria->condition = 't.survey_id = :survey_id AND t.order_id = :order_id';
			$criteria->params = array(
				':survey_id' => $survey_id,
				':order_id' => $order_id,
			);
		}

		$model = WSurveyReport::model()->find($criteria);
		if($model){
			return false;
		}
		return true;
	}

	/**
	 * @param $survey_id
	 * @return int | null
	 */
	public static function getSurveyDoneQuantity($survey_id)
	{
		$criteria = new CDbCriteria();
		$criteria->select = "COUNT(DISTINCT t.user_id) as count";
		$criteria->condition = "t.survey_id = :survey_id";
		$criteria->params = array(
			':survey_id' => $survey_id
		);
		$data = WSurveyReport::model()->findAll($criteria);
		return $data[0]->count;
	}


}
