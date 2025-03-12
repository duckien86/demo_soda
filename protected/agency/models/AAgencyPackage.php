<?php

/**
 * This is the model class for table "{{agency_package}}".
 *
 * The followings are the available columns in table '{{agency_package}}':
 * @property integer $id
 * @property string $agency_id
 * @property string $package_code
 */
class AAgencyPackage extends AgencyPackage
{
	const DISPLAY_IN_BUY_PACKAGE = 1; 
	const DISPLAY_IN_BUY_SIM = 2;
	const DISPLAY_IN_BUY_PACKAGE_AND_SIM = 3;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('agency_id, package_code, display_type', 'required'),
			array('agency_id, package_code', 'length', 'max'=>255),
			array('package_code', 'checkUnique'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, agency_id, package_code', 'safe', 'on'=>'search'),
		);
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'agency_id' => Yii::t('adm/label', 'agency_id'),
			'package_code' =>  Yii::t('adm/label', 'package_id'),
			'display_type' =>  Yii::t('adm/label', 'display_type'),
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
		$criteria->compare('agency_id',$this->agency_id,true);
		$criteria->compare('package_code',$this->package_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' => 100,
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AgencyPackage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function checkUnique(){
		if($this->isNewRecord){
			$agency_package = AAgencyPackage::model()->findAllByAttributes(array('agency_id' => $this->agency_id, 'package_code' => $this->package_code));

			if(!empty($agency_package)){
				$this->addError('package_code', Yii::t('adm/label', 'package_exist'));
			}
		}
		return true;
	}

	public static function getDisplayTypeLabels(){
		return array(
			self::DISPLAY_IN_BUY_PACKAGE => Yii::t('adm/label', 'display_in_buy_package'),
			self::DISPLAY_IN_BUY_SIM => Yii::t('adm/label', 'display_in_buy_sim'),
			self::DISPLAY_IN_BUY_PACKAGE_AND_SIM => Yii::t('adm/label', 'display_in_buy_package_and_sim'),
		);
	}
}
