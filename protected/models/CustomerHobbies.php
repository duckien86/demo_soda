<?php

/**
 * This is the model class for table "sc_tbl_customer_hobbies".
 *
 * The followings are the available columns in table 'sc_tbl_customer_hobbies':
 * @property string $sso_id
 * @property integer $sc_tbl_hobbies_id
 */
class CustomerHobbies extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sc_tbl_customer_hobbies';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sc_tbl_hobbies_id', 'numerical', 'integerOnly'=>true),
			array('sso_id', 'length', 'max'=>255),
            array('sc_tbl_hobbies_id+sso_id', 'application.extensions.validators.uniqueMultiColumnValidator', 'caseSensitive' => TRUE),
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
			'sso_id' => 'Sso',
			'sc_tbl_hobbies_id' => 'Sc Tbl Hobbies',
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

		$criteria->compare('sso_id',$this->sso_id,true);
		$criteria->compare('sc_tbl_hobbies_id',$this->sc_tbl_hobbies_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 *
	 * @param string $className active record class name.
	 *
	 * @return CustomerHobbies the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
