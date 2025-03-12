<?php

/**
 * This is the model class for table "{{agency_file}}".
 *
 * The followings are the available columns in table '{{agency_file}}':
 * @property string $id
 * @property string $object
 * @property string $object_id
 * @property string $file_name
 * @property string $file_ext
 * @property integer $file_size
 * @property string $folder_path
 * @property string $create_date
 * @property string $extra_info
 * @property integer $status
 */
class AgencyFile extends CActiveRecord
{

	CONST OBJECT_FILE_CONTRACTS = 'AAgencyContract';

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{agency_file}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('object, object_id, file_name, file_ext, status', 'required'),
			array('file_size, status', 'numerical', 'integerOnly'=>true),
			array('object', 'length', 'max'=>50),
			array('object_id', 'length', 'max'=>11),
			array('file_name', 'length', 'max'=>500),
			array('file_ext', 'length', 'max'=>10),
			array('folder_path', 'length', 'max'=>1000),
			array('create_date, extra_info', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, object, object_id, file_name, file_ext, file_size, folder_path, create_date, extra_info, status', 'safe', 'on'=>'search'),
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
			'object' => 'Object',
			'object_id' => 'Object',
			'file_name' => 'File Name',
			'file_ext' => 'File Ext',
			'file_size' => 'File Size',
			'folder_path' => 'Folder Path',
			'create_date' => 'Create Date',
			'extra_info' => 'Extra Info',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('object',$this->object,true);
		$criteria->compare('object_id',$this->object_id,true);
		$criteria->compare('file_name',$this->file_name,true);
		$criteria->compare('file_ext',$this->file_ext,true);
		$criteria->compare('file_size',$this->file_size);
		$criteria->compare('folder_path',$this->folder_path,true);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('extra_info',$this->extra_info,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AgencyFile the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
