<?php

class APackageCommision extends PackageCommision
{
	CONST ACTIVE = 1;
	CONST INACTIVE = 0;


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
	 * @return APackageCommision the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * @param $code
	 * @return static
	 */
	public static function getPackageCommisionByCode($code)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 't.package_code = :code AND t.status = :status';
		$criteria->params = array(
			':code' => $code,
			':status' => APackageCommision::ACTIVE
		);

		$cache_key = 'APackageCommision_getPackageCommisionByCode' . $code;
		$results   = Yii::app()->cache->get($cache_key);
		if (!$results) {
			$results = APackageCommision::model()->find($criteria);
			Yii::app()->cache->set($cache_key, $results, 60*5);
		}
		return $results;
	}

}
