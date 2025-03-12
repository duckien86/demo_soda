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
class SurveyQuestion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{survey_question}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('survey_id', 'required'),
			array('type, survey_id, sort_order, point, status', 'numerical', 'integerOnly'=>true),
			array('first_label, last_label', 'length', 'max'=>255),
			array('content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, content, first_label, last_label, type, survey_id, sort_order, point, status', 'safe', 'on'=>'search'),
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
			'content' => 'Content',
			'first_label' => 'First Label',
			'last_label' => 'Last Label',
			'type' => 'Type',
			'survey_id' => 'Survey',
			'sort_order' => 'Sort Index',
			'point' => 'Point',
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
}
