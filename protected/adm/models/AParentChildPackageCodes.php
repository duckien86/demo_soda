<?php

/**
 * This is the model class for table "{{parent_child_package_codes}}".
 *
 * The followings are the available columns in table '{{parent_child_package_codes}}':
 * @property string $id
 * @property string $parent_code
 * @property string $child_code
 */
class AParentChildPackageCodes extends ParentChildPackageCodes
{
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parent_code, child_code', 'required'),
			array('parent_code, child_code', 'length', 'max'=>255),
			array('parent_code', 'checkUnique'),
			array('parent_code', 'checkParentChildUnique'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('parent_code, child_code', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parent_code' => Yii::t('adm/label', 'parent_code'),
			'child_code' => Yii::t('adm/label', 'child_code'),
		);
	}
	public function checkUnique(){
		if($this->isNewRecord){
			$package_codes = self::model()->findAllByAttributes(array('child_code' => $this->child_code, 'parent_code' => $this->parent_code));

			if(!empty($package_codes)){
				$this->addError('parent_code', 'Gói cha con đã tồn tại');
			}
		}
		return true;
	}
	public function checkParentChildUnique(){
		if($this->child_code == $this->parent_code){
			$this->addError('parent_code', 'Gói cha không được giống gói con');
		}
		return true;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ParentChildPackageCodes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
