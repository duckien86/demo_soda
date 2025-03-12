<?php

/**
 * This is the model class for table "{{roles}}".
 *
 * The followings are the available columns in table '{{roles}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $parent_id
 * @property integer $status
 * @property string $scope
 */
class Roles extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{roles}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parent_id, status', 'numerical', 'integerOnly'=>true),
			array('name, description, scope', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, parent_id, status, scope', 'safe', 'on'=>'search'),
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
			'description' => 'Description',
			'parent_id' => 'Parent',
			'status' => 'Status',
			'scope' => 'Scope',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('scope',$this->scope,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Roles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Lấy tên chức vụ
	 *
	 * @param $id 	integer
	 * @return string
	 */
	public function getRoleName($id)
	{
		$role_name = '';
		$criteria = new CDbCriteria();
		$criteria->condition = "t.id = '$id' AND t.name NOT IN ('ADMIN','STAFF')";
		$model = Roles::model()->find($criteria);
		if($model){
			if(empty($model->parent_id)){
				$role_name = $model->name;
			}else{
				$parent = Roles::model()->findByPk($model->parent_id);
				$role_name = $parent->name;
			}
		}
		return $role_name;
	}
}
