<?php

class WOrderEinvoice extends OrderEinvoice
{
	const INVOICED = 10; // đã phát hành
	const INVOICING = 1; // chờ phát hành
	const NOT_INVOICE = 0; // không phát hành
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, c_name, c_email, c_address, c_phone', 'required', 'on' => 'create'),
			array('number', 'numerical', 'integerOnly'=>true),
			array('order_id, key, e_invoice_file_url, status, c_name, c_email, c_tax_code, c_address, c_note, c_phone', 'length', 'max'=>255),
			array('create_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_id, key, number, e_invoice_file_url, status, create_date, c_name, c_email, c_tax_code, c_address, c_note, c_phone', 'safe', 'on'=>'search'),
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
			'id' 					=> 'ID',
			'order_id' 				=> 'Order',
			'key' 					=> 'Key',
			'number' 				=> 'Number',
			'e_invoice_file_url' 	=> 'E Invoice File Url',
			'status' 				=> 'Status',
			'create_date' 			=> 'Create Date',
			'c_name' 				=> 'Tên cá nhân/Công ty',
			'c_email' 				=> 'Email',
			'c_tax_code' 			=> 'Mã số thuế',
			'c_address' 			=> 'Địa chỉ viết hóa đơn',
			'c_note' 				=> 'Thông tin thêm',
			'c_phone' 				=> 'Số điện thoại',
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
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('key',$this->key,true);
		$criteria->compare('number',$this->number);
		$criteria->compare('e_invoice_file_url',$this->e_invoice_file_url,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('c_name',$this->c_name,true);
		$criteria->compare('c_email',$this->c_email,true);
		$criteria->compare('c_tax_code',$this->c_tax_code,true);
		$criteria->compare('c_address',$this->c_address,true);
		$criteria->compare('c_note',$this->c_note,true);
		$criteria->compare('c_phone',$this->c_phone,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WOrderEinvoice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	protected function beforeSave()
	{
		if($this->isNewRecord){
			$this->create_date = date('Y-m-d H:i:s');
		}
		return TRUE;
	}

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	public static function getFkey($key){
		$arr_key = explode('-',$key);
		$new_key = $arr_key[count($arr_key) -1];
		$arr_new_key = explode('_',$new_key);
		$final_key = $arr_new_key[0];
		return $final_key;
	}

}
