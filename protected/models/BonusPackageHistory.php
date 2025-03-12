<?php

/**
 * This is the model class for table "sc_tbl_bonus_package_history".
 *
 * The followings are the available columns in table 'sc_tbl_bonus_package_history':
 * @property string $id
 * @property string $sso_id
 * @property string $package_code
 * @property string $create_date
 * @property string $expire_date
 * @property integer $point_amount
 * @property string $transaction_id
 */
class BonusPackageHistory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sc_tbl_bonus_package_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('point_amount', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>20),
			array('sso_id, package_code, transaction_id', 'length', 'max'=>255),
			array('create_date, expire_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sso_id, package_code, create_date, expire_date, point_amount, transaction_id', 'safe', 'on'=>'search'),
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
			'sso_id' => 'Sso',
			'package_code' => 'Package Code',
			'create_date' => 'Create Date',
			'expire_date' => 'Expire Date',
			'point_amount' => 'Point Amount',
			'transaction_id' => 'Transaction',
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
		$criteria->compare('sso_id',$this->sso_id,true);
		$criteria->compare('package_code',$this->package_code,true);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('expire_date',$this->expire_date,true);
		$criteria->compare('point_amount',$this->point_amount);
		$criteria->compare('transaction_id',$this->transaction_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BonusPackageHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
