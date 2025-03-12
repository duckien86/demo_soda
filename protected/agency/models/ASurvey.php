<?php

/**
 * This is the model class for table "{{survey}}".
 *
 * The followings are the available columns in table '{{survey}}':
 * @property integer $id
 * @property string $name
 * @property string $short_des
 * @property integer $point
 * @property string $start_date
 * @property string $end_date
 * @property integer $status
 * @property integer $limit
 */
class ASurvey extends Survey
{
	CONST SURVEY_ACTIVE = 1;
	CONST SURVEY_INACTIVE = 0;

	CONST UN_LIMIT = -1;

	public $un_limit;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, point, start_date, end_date', 'required'),
			array('point, status, limit, un_limit', 'numerical', 'integerOnly'=>true),
			array('name, short_des', 'length', 'max'=>255),
			array('end_date', 'compare', 'compareAttribute' => 'start_date', 'operator' => '>=', 'message' => Yii::t('adm/label','end_date_must_greater')),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, short_des, point, start_date, end_date, status, limit, un_limit', 'safe', 'on'=>'search'),
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
			'name' 			=> Yii::t('adm/label', 'name'),
			'short_des' 	=> Yii::t('adm/label', 'short_des'),
			'point' 		=> Yii::t('adm/label', 'point'),
			'start_date' 	=> Yii::t('adm/label', 'start_date'),
			'end_date' 		=> Yii::t('adm/label', 'finish_date'),
			'status' 		=> Yii::t('adm/label', 'status'),
			'limit' 		=> Yii::t('adm/label', 'survey_limit'),
			'un_limit'		=> Yii::t('adm/label', 'un_limit'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('short_des',$this->short_des,true);
		$criteria->compare('point',$this->point);
//		$criteria->compare("DATE_FORMAT(t.start_date, '%d/%m/%Y')", date("d/m/Y",strtotime($this->start_date)));
//		$criteria->compare("DATE_FORMAT(t.end_date, '%d/%m/%Y')", date("d/m/Y",strtotime($this->end_date)));
		$criteria->compare('status',$this->status);
		$criteria->compare('limit',$this->limit);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'     => array(
				'defaultOrder' => 't.end_date DESC',
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Survey the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string
	 */
	public function getStatusLabel()
	{
		return ($this->status == self::SURVEY_ACTIVE) ? Yii::t('adm/label', 'active') : Yii::t('adm/label', 'inactive');
	}

	protected function beforeSave()
	{
		$this->start_date = str_replace('/','-',$this->start_date);
		$this->start_date = date('Y-m-d H:i:s', strtotime($this->start_date));
		$this->end_date = str_replace('/','-',$this->end_date);
		$this->end_date = date('Y-m-d H:i:s', strtotime($this->end_date));
		if($this->un_limit == self::UN_LIMIT){
			$this->limit = self::UN_LIMIT;
		}
		return true;
	}


	public function beforeDelete()
	{
		$list_question = ASurveyQuestion::getListQuestionBySurveyId($this->id);
		if(!empty($list_question)){
			foreach ($list_question as $question) {
				$question->delete();
			}
		}
		return true;
	}
}
