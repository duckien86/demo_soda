<?php

/**
 * This is the model class for table "tbl_logs".
 *
 * The followings are the available columns in table 'tbl_logs':
 * @property integer $id
 * @property string $object_name
 * @property integer $object_id
 * @property string $data_json_before
 * @property string $data_json_after
 * @property string $create_time
 * @property string $active_by
 */
class FTLogs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_logs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('object_name, object_id', 'required'),
			array('object_id, active_by', 'numerical', 'integerOnly'=>true),
			array('object_name', 'length', 'max'=>255),
			array('data_json_before, data_json_after, create_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, object_name, object_id, data_json_before, data_json_after, create_time, active_by', 'safe', 'on'=>'search'),
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
			'object_name' => 'Object Name',
			'object_id' => 'Object',
			'data_json_before' => 'Data Json Before',
			'data_json_after' => 'Data Json After',
			'create_time' => 'Create Time',
			'active_by' => 'active_by',
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
		$criteria->compare('object_name',$this->object_name,true);
		$criteria->compare('object_id',$this->object_id);
		$criteria->compare('data_json_before',$this->data_json_before,true);
		$criteria->compare('data_json_after',$this->data_json_after,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('active_by',$this->active_by,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_freedoo_tourist;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FTLogs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
