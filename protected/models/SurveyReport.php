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
class SurveyReport extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{survey_report}}';
	}

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
			'id' => 'ID',
			'survey_id' => 'Survey',
			'user_id' => 'User',
			'order_id' => 'Order',
			'question_id' => 'Question',
			'answer_id' => 'Answer',
			'content' => 'Content',
			'is_right' => 'Is Right',
			'create_date' => 'Create Date',
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
}
