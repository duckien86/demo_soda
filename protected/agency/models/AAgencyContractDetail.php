<?php

class AAgencyContractDetail extends AgencyContractDetail
{

	CONST TYPE_PERCENT = 1;
	CONST TYPE_VALUE   = 2;

	CONST ITEM_SIM_PREPAID = 'SIM_PREPAID';
	CONST ITEM_SIM_POSTPAID = 'SIM_POSTPAID';

	public $type;
	public $price_discount;
	public $price;
	public $package_name;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('contract_id, item_id', 'required'),
			array('contract_id, quantity, price_discount_percent, price_discount_amount', 'numerical', 'integerOnly'=>true),
			array('type, item_id', 'length', 'max'=>255),
			array('price_discount, price, package_name', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('contract_id, item_id, quantity, price_discount_percent, price_discount_amount', 'safe', 'on'=>'search'),
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
			'contract_id' => 'Contract',
			'item_id' => 'Item',
			'quantity' => 'Quantity',
			'price_discount_percent' => 'Price Discount Percent',
			'price_discount_amount' => 'Price Discount Amount',
			'type' => 'Type',
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

		$criteria->compare('contract_id',$this->contract_id);
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('price_discount_percent',$this->price_discount_percent);
		$criteria->compare('price_discount_amount',$this->price_discount_amount);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AAgencyContractDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @param      $contract_id
	 * @param bool $dataProvider
	 *
	 * @return array|mixed|null
	 */
	public static function getListDetailsByContractId($contract_id, $dataProvider = FALSE)
	{
		$criteria = new CDbCriteria();
		$criteria->select = "t.*, 
			(SELECT name FROM tbl_package WHERE code = t.item_id) AS 'package_name',
			(SELECT price FROM tbl_package WHERE code = t.item_id) AS 'price',
			(SELECT type price FROM tbl_package WHERE code = t.item_id) AS 'type'
		";
		$criteria->condition = 'contract_id=:contract_id';
		$criteria->params    = array(':contract_id' => $contract_id);

		if ($dataProvider) {
			return new CActiveDataProvider(self::model(), array(
				'criteria'   => $criteria,
				'pagination' => array(
					'pageSize' => 50,
				)
			));
		} else {
			return self::model()->findAll($criteria);
		}
	}

	/**
	 * @param $details
	 *
	 * @return bool
	 */
	public function validateContractDetails($details)
	{
		if (!empty($details) && is_array($details)) {
			foreach ($details as $key => $item) {
				if($item['type'] == AAgencyContractDetail::TYPE_PERCENT){
					if($item['price_discount'] > 100){
						$this->addError('detail', Yii::t('adm/label', 'price_discount_percent_invalid', array('{code}' => $item['name'])));
						return FALSE;
					}
				}
			}
		}

		return TRUE;
	}

	/**
	 * @param AAgencyContract 		$contract
	 * @param AAgencyContractDetail $details
	 */
	public function setContractDetails($contract, $details)
	{
		foreach ($details as $key => $item) {
			$quantity       = (int)preg_replace('/\./', '', $item['quantity']);
			$price_discount = (int)preg_replace('/\./', '', $item['price_discount']);

			if ($price_discount > 0) {

				$criteria = new CDbCriteria();
				$criteria->condition = "t.contract_id = :contract_id AND t.item_id = :item_id";
				$criteria->params = array(
					':contract_id' 	=> $contract->id,
					':item_id'		=> $key,
				);
				$modelDetails = AAgencyContractDetail::model()->find($criteria);
				if (!$modelDetails) {
					$modelDetails              = new AAgencyContractDetail();
					$modelDetails->contract_id = $contract->id;
					$modelDetails->item_id     = $key;
				}
				$modelDetails->quantity = $quantity;

				if ($item['type'] == self::TYPE_PERCENT) {
					$modelDetails->price_discount_percent = $price_discount;
					$modelDetails->price_discount_amount  = 0;
				} else {
					$modelDetails->price_discount_amount  = $price_discount;
					$modelDetails->price_discount_percent = 0;
				}
				$modelDetails->save();
			}
		}
	}

	/**
	 * @param $contract_id
	 *
	 * @return array|mixed|null
	 */
	public function getArrayDetailsByContractId($contract_id)
	{
		$results = array();
		$details = self::getListDetailsByContractId($contract_id);
		if ($details) {
			foreach ($details as $item) {
				if ($item->price_discount_amount > 0) {
					$price_discount = $item->price_discount_amount;
					$type           = AAgencyContractDetail::TYPE_VALUE;
				} else {
					$price_discount = ($item->price_discount_percent) ? $item->price_discount_percent : 0;
					$type           = AAgencyContractDetail::TYPE_PERCENT;
				}
				$data_item['quantity']       = $item->quantity;
				$data_item['type']           = $type;
				$data_item['price_discount'] = $price_discount;
				$results[$item->item_id]     = $data_item;
			}
		}

		return $results;
	}

	/**
	 * @param $item_id
	 * @param $quantity
	 * @param $percent
	 * @param $amount
	 *
	 * @return string
	 */
	public function getAmountDetail($item_id, $quantity, $percent, $amount)
	{
		$total   = 0;
		$package = APackage::model()->findByAttributes(array('code' => $item_id));
		if ($package && $quantity) {
			if ($percent > 0) {
				$total = ($package->price - ($package->price * $percent / 100)) * $quantity;
			} else {
				$total = ($package->price - $amount) * $quantity;
			}
		}

		return number_format($total, 0, '', '.') . 'đ';
	}
}
