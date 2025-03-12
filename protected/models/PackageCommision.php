<?php

/**
 * This is the model class for table "{{package_commision}}".
 *
 * The followings are the available columns in table '{{package_commision}}':
 * @property integer $id
 * @property string $package_code
 * @property string $percent_collaborator
 * @property string $percent_agency
 * @property string $vnd_collaborator
 * @property string $vnd_agency
 * @property integer $status
 */
class PackageCommision extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{package_commision}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, package_code', 'required'),
			array('id, status', 'numerical', 'integerOnly'=>true),
			array('package_code', 'length', 'max'=>255),
			array('percent_collaborator, percent_agency, vnd_collaborator, vnd_agency', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, package_code, percent_collaborator, percent_agency, vnd_collaborator, vnd_agency, status', 'safe', 'on'=>'search'),
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
			'package_code' => 'Package Code',
			'percent_collaborator' => 'Percent Collaborator',
			'percent_agency' => 'Percent Agency',
			'vnd_collaborator' => 'Vnd Collaborator',
			'vnd_agency' => 'Vnd Agency',
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
		$criteria->compare('package_code',$this->package_code,true);
		$criteria->compare('percent_collaborator',$this->percent_collaborator,true);
		$criteria->compare('percent_agency',$this->percent_agency,true);
		$criteria->compare('vnd_collaborator',$this->vnd_collaborator,true);
		$criteria->compare('vnd_agency',$this->vnd_agency,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PackageCommision the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
