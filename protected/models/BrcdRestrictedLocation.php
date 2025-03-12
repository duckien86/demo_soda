<?php

/**
 * This is the model class for table "{{brcd_restricted_location}}".
 *
 * The followings are the available columns in table '{{brcd_restricted_location}}':
 * @property integer $id
 * @property string $name
 * @property string $province_code
 * @property string $distrist_code
 * @property string $ward_code
 * @property integer $status
 * @property string $param_1
 * @property string $param_2
 */
class BrcdRestrictedLocation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{brcd_restricted_location}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status', 'numerical', 'integerOnly'=>true),
			array('name, province_code, distrist_code, ward_code, param_1, param_2', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, province_code, distrist_code, ward_code, status, param_1, param_2', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'province_code' => 'Province Code',
			'distrist_code' => 'Distrist Code',
			'ward_code' => 'Ward Code',
			'status' => 'Status',
			'param_1' => 'Param 1',
			'param_2' => 'Param 2',
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
		$criteria->compare('province_code',$this->province_code,true);
		$criteria->compare('distrist_code',$this->distrist_code,true);
		$criteria->compare('ward_code',$this->ward_code,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('param_1',$this->param_1,true);
		$criteria->compare('param_2',$this->param_2,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BrcdRestrictedLocation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
