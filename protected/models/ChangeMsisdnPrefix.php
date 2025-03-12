<?php

/**
 * This is the model class for table "{{change_msisdn_prefix}}".
 *
 * The followings are the available columns in table '{{change_msisdn_prefix}}':
 * @property string $prefix_old
 * @property string $prefix_old_long
 * @property string $prefix_new
 * @property string $start_time
 */
class ChangeMsisdnPrefix extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{change_msisdn_prefix}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prefix_old, prefix_old_long, prefix_new, start_time', 'required'),
			array('prefix_old, prefix_old_long, prefix_new', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('prefix_old, prefix_old_long, prefix_new, start_time', 'safe', 'on'=>'search'),
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
			'prefix_old' => 'Prefix Old',
			'prefix_old_long' => 'Prefix Old Long',
			'prefix_new' => 'Prefix New',
			'start_time' => 'Start Time',
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

		$criteria->compare('prefix_old',$this->prefix_old,true);
		$criteria->compare('prefix_old_long',$this->prefix_old_long,true);
		$criteria->compare('prefix_new',$this->prefix_new,true);
		$criteria->compare('start_time',$this->start_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ChangeMsisdnPrefix the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @param $msisdn string
	 * @return bool
	 */
	public static function searchByPrefix($msisdn)
	{
		$now = date('Y-m-d H:i:s');

		$criteria = new CDbCriteria();
		$criteria->condition = "
			((SUBSTR('$msisdn',1,4) = t.prefix_old)
			OR (SUBSTR('$msisdn',1,5) = t.prefix_old_long))
			AND t.start_time <= '$now' 
		";

		$model = self::model()->find($criteria);
		if($model){
			return true;
		}else{
			return false;
		}
	}


}
