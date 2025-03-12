<?php

/**
 * This is the model class for table "{{affiliate_manager}}".
 *
 * The followings are the available columns in table '{{affiliate_manager}}':
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $status
 * @property integer $type
 * @property string $url_redirect
 * @property string $postback_url
 * @property string $create_date
 * @property string $default_source
 */
class AffiliateManager extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{affiliate_manager}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status, type', 'numerical', 'integerOnly'=>true),
			array('name, code, default_source', 'length', 'max'=>255),
			array('url_redirect, postback_url', 'length', 'max'=>1000),
			array('create_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, code, status, type, url_redirect, postback_url, create_date, default_source', 'safe', 'on'=>'search'),
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
			'code' => 'Code',
			'status' => 'Status',
			'type' => 'Type',
			'url_redirect' => 'Url Redirect',
			'postback_url' => 'Postback Url',
			'create_date' => 'Create Date',
			'default_source' => 'Default Source',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('type',$this->type);
		$criteria->compare('url_redirect',$this->url_redirect,true);
		$criteria->compare('postback_url',$this->postback_url,true);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('default_source',$this->default_source,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AffiliateManager the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
