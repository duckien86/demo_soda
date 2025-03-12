<?php

/**
 * This is the model class for table "{{prepaid_to_postpaid}}".
 *
 * The followings are the available columns in table '{{prepaid_to_postpaid}}':
 * @property int 	$id
 * @property string $msisdn
 * @property string $order_id
 * @property string $package_code
 * @property string $full_name
 * @property string $personal_id
 * @property string $province_code
 * @property string $district_code
 * @property string $ward_code
 * @property string $address_detail
 * @property string $promo_code
 * @property string $otp
 * @property string $receive_date
 * @property string $finish_date
 * @property string $request_id
 * @property string $create_date
 * @property int    $status
 * @property string $user_id
 * @property string $note
 * @property string $sale_office_code
 */
class APrepaidToPostpaid extends PrepaidToPostpaid
{
	CONST PTP_FAIL 			= 0;	//Thất bại
	CONST PTP_APPROVE 		= 1;	//Chờ duyệt
	CONST PTP_PROCESSING 	= 2;	//Đang xử lí
	CONST PTP_OUT_OF_DATE 	= 8;	//Quá hạn
	CONST PTP_COMPLETE 		= 10;	//Hoàn thành

	public $package;
	public $user;
	public $province;
	public $address;

	public $start_date;
	public $end_date;

	public $total;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'length', 'max'=>100),
			array('status', 'numerical', 'integerOnly'=>true),
			array('msisdn, order_id, package_code, full_name, personal_id, province_code, district_code, ward_code, address_detail, promo_code, otp, request_id, user_id, sale_office_code', 'length', 'max'=>255),
			array('receive_date, finish_date, create_date, package, user, start_date, end_date, province, address, total, note', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, msisdn, order_id, package_code, full_name, personal_id, province_code, district_code, ward_code, address_detail, promo_code, otp, receive_date, finish_date, request_id, create_date, status, user_id, package, user, start_date, end_date, province, address, total, note, sale_office_code', 'safe', 'on'=>'search'),
			array('end_date', 'compare', 'compareAttribute' => 'start_date', 'operator' => '>=', 'allowEmpty' => FALSE, 'message' => Yii::t('adm/label','end_date_must_greater'), 'on' => 'search'),
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
			'id' 				=> Yii::t('adm/label', 'id'),
			'msisdn' 			=> Yii::t('adm/label', 'msisdn'),
			'order_id' 			=> Yii::t('adm/label', 'order_id'),
			'package_code' 		=> Yii::t('adm/label', 'package'),
			'full_name' 		=> Yii::t('adm/label', 'full_name'),
			'personal_id' 		=> Yii::t('adm/label', 'personal_id'),
			'province_code' 	=> Yii::t('adm/label', 'province_code'),
			'district_code'		=> Yii::t('adm/label', 'district_code'),
			'ward_code' 		=> Yii::t('adm/label', 'ward_code'),
			'address_detail' 	=> Yii::t('adm/label', 'address_detail'),
			'promo_code' 		=> Yii::t('adm/label', 'promo_code_ptp'),
			'otp' 				=> Yii::t('adm/label', 'otp'),
			'receive_date' 		=> Yii::t('adm/label', 'receive_date'),
			'finish_date' 		=> Yii::t('adm/label', 'finish_date_ptp'),
			'request_id' 		=> Yii::t('adm/label', 'request_id_ptp'),
			'create_date' 		=> Yii::t('adm/label', 'create_date'),
			'status' 			=> Yii::t('adm/label', 'status'),
			'user_id' 			=> Yii::t('adm/label', 'user_id_ptp'),
			'note' 				=> Yii::t('adm/label', 'note'),
			'sale_office_code' 	=> Yii::t('adm/label', 'sale_office'),

			'package'		=> Yii::t('adm/label', 'package'),
			'user'			=> Yii::t('adm/label', 'user_id_ptp'),
			'province'		=> Yii::t('adm/label', 'province'),
			'address'		=> Yii::t('adm/label', 'address'),
			'start_date'   	=> Yii::t('adm/label', 'start_date'),
			'end_date'   	=> Yii::t('adm/label', 'finish_date'),
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
		$criteria->compare('msisdn',$this->msisdn,true);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('package_code',$this->package_code,true);
		$criteria->compare('full_name',$this->full_name,true);
		$criteria->compare('personal_id',$this->personal_id,true);

		$criteria->compare('promo_code',$this->promo_code,true);
		$criteria->compare('otp',$this->otp,true);
		$criteria->compare('receive_date',$this->receive_date,true);
		$criteria->compare('finish_date',$this->finish_date,true);
		$criteria->compare('request_id',$this->request_id,true);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('note',$this->note,true);

		if (!ADMIN && !SUPER_ADMIN) {
			if (Yii::app()->user->province_code && (!isset(Yii::app()->user->sale_offices_id)
					|| empty(Yii::app()->user->sale_offices_id))
			) {
				$criteria->compare('t.province_code', Yii::app()->user->province_code);
			} else if (Yii::app()->user->province_code && isset(Yii::app()->user->sale_offices_id)
				&& !empty(Yii::app()->user->sale_offices_id)
				&& (!isset(Yii::app()->user->brand_offices_id) || empty(Yii::app()->user->brand_offices_id))
			) {
				$criteria->compare('t.sale_office_code', Yii::app()->user->sale_offices_id);
			} else if (isset(Yii::app()->user->brand_offices_id) && !empty(Yii::app()->user->brand_offices_id)) {
				$criteria->compare("t.address_detail", Yii::app()->user->brand_offices_id, FALSE);
			}
		}else{
			$criteria->compare('province_code',$this->province_code,true);
			$criteria->compare('district_code',$this->district_code,true);
			$criteria->compare('ward_code',$this->ward_code,true);
			$criteria->compare('address_detail',$this->address_detail,true);
			$criteria->compare('sale_office_code',$this->sale_office_code,true);
		}

		if($this->start_date && $this->end_date){

			$this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
			$this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';

			$criteria->addCondition("t.create_date >= '$this->start_date' AND t.create_date <= '$this->end_date'");
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'       => array(
				'defaultOrder' => 't.create_date DESC',
			),
			'pagination' => array(
				'pageSize' => 50,
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PrepaidToPostpaid the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public static  function getListStatusLabel()
	{
		return array(
			self::PTP_APPROVE 		=> Yii::t('adm/label','ptp_approve'),
			self::PTP_PROCESSING 	=> Yii::t('adm/label','ptp_processing'),
			self::PTP_FAIL 			=> Yii::t('adm/label','ptp_fail'),
			self::PTP_OUT_OF_DATE	=> Yii::t('adm/label','ptp_out_of_date'),
			self::PTP_COMPLETE 		=> Yii::t('adm/label','ptp_complete'),
		);
	}

	public static function getStatusLabel($status)
	{
		$data = self::getListStatusLabel();

		return (isset($data[$status])) ? $data[$status] : $status;
	}

	public static function getBtnActionClass($status)
	{
		switch ($status){
			case self::PTP_APPROVE:
				return 'btn-primary';
				break;
			case self::PTP_PROCESSING:
				return 'btn-info';
				break;
			case self::PTP_OUT_OF_DATE:
				return 'btn-warning';
				break;
			case self::PTP_FAIL:
				return 'btn-danger';
				break;
			case self::PTP_COMPLETE:
				return 'btn-success';
				break;
			default:
				return 'btn-default';
		}
	}

	public static function getLabelStatusClass($status)
	{
		switch ($status){
			case self::PTP_APPROVE:
				return 'text-primary';
				break;
			case self::PTP_PROCESSING:
				return 'text-info';
				break;
			case self::PTP_OUT_OF_DATE:
				return 'text-warning';
				break;
			case self::PTP_FAIL:
				return 'text-danger';
				break;
			case self::PTP_COMPLETE:
				return 'text-success';
				break;
			default:
				return 'text-muted';
		}
	}

	public static  function getListStatusLabelReport()
	{
		return array(
			self::PTP_OUT_OF_DATE 	=> Yii::t('adm/label','ptp_out_of_date'),
			self::PTP_FAIL 	 		=> Yii::t('adm/label','ptp_fail'),
			self::PTP_COMPLETE 		=> Yii::t('adm/label','ptp_complete'),
		);
	}

	public function searchReportDetail()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('msisdn',$this->msisdn,true);
//		$criteria->compare('order_id',$this->order_id,true);
//		$criteria->compare('package_code',$this->package_code,true);
//		$criteria->compare('full_name',$this->full_name,true);
//		$criteria->compare('personal_id',$this->personal_id,true);
//
//		$criteria->compare('promo_code',$this->promo_code,true);
//		$criteria->compare('otp',$this->otp,true);
//		$criteria->compare('receive_date',$this->receive_date,true);
//		$criteria->compare('finish_date',$this->finish_date,true);
//		$criteria->compare('request_id',$this->request_id,true);
//		$criteria->compare('create_date',$this->create_date,true);
//		$criteria->compare('status',$this->status);
//		$criteria->compare('user_id',$this->user_id,true);
//		$criteria->compare('note',$this->note,true);

		if (!ADMIN && !SUPER_ADMIN) {
			if (Yii::app()->user->province_code && (!isset(Yii::app()->user->sale_offices_id)
					|| empty(Yii::app()->user->sale_offices_id))
			) {
				$criteria->compare('t.province_code', Yii::app()->user->province_code);
			} else if (Yii::app()->user->province_code && isset(Yii::app()->user->sale_offices_id)
				&& !empty(Yii::app()->user->sale_offices_id)
				&& (!isset(Yii::app()->user->brand_offices_id) || empty(Yii::app()->user->brand_offices_id))
			) {
				$criteria->compare('t.sale_office_code', Yii::app()->user->sale_offices_id);
			} else if (isset(Yii::app()->user->brand_offices_id) && !empty(Yii::app()->user->brand_offices_id)) {
				$criteria->compare("t.address_detail", Yii::app()->user->brand_offices_id, FALSE);
			}
		}else{
			$criteria->compare('province_code',$this->province_code,true);
			$criteria->compare('district_code',$this->district_code,true);
			$criteria->compare('ward_code',$this->ward_code,true);
			$criteria->compare('address_detail',$this->address_detail,true);
			$criteria->compare('sale_office_code',$this->sale_office_code,true);
		}

		if($this->start_date && $this->end_date){

			$this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
			$this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';

			$criteria->addCondition("t.create_date >= '$this->start_date' AND t.create_date <= '$this->end_date'");
		}
		if(!isset($this->status)){
			$criteria->addCondition("t.status = ".APrepaidToPostpaid::PTP_FAIL." OR t.status = ".APrepaidToPostpaid::PTP_COMPLETE." OR t.status = ".APrepaidToPostpaid::PTP_OUT_OF_DATE);
		}else{
			$criteria->compare('status',$this->status,true);
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'       => array(
				'defaultOrder' => 't.create_date DESC',
			),
			'pagination' => array(
				'pageSize' => 50,
			),
		));
	}

	public function searchReportSynthetic()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

//		$criteria->compare('id',$this->id);
//		$criteria->compare('msisdn',$this->msisdn,true);
//		$criteria->compare('order_id',$this->order_id,true);
//		$criteria->compare('package_code',$this->package_code,true);
//		$criteria->compare('full_name',$this->full_name,true);
//		$criteria->compare('personal_id',$this->personal_id,true);
//
//		$criteria->compare('promo_code',$this->promo_code,true);
//		$criteria->compare('otp',$this->otp,true);
//		$criteria->compare('receive_date',$this->receive_date,true);
//		$criteria->compare('finish_date',$this->finish_date,true);
//		$criteria->compare('request_id',$this->request_id,true);
//		$criteria->compare('create_date',$this->create_date,true);
//		$criteria->compare('status',$this->status);
//		$criteria->compare('user_id',$this->user_id,true);
//		$criteria->compare('note',$this->user_id,note);

		$criteria->distinct = true;
		$criteria->select = 't.province_code';

		if (!ADMIN && !SUPER_ADMIN) {
			if (Yii::app()->user->province_code && (!isset(Yii::app()->user->sale_offices_id)
					|| empty(Yii::app()->user->sale_offices_id))
			) {
				$criteria->compare('t.province_code', Yii::app()->user->province_code);
			} else if (Yii::app()->user->province_code && isset(Yii::app()->user->sale_offices_id)
				&& !empty(Yii::app()->user->sale_offices_id)
				&& (!isset(Yii::app()->user->brand_offices_id) || empty(Yii::app()->user->brand_offices_id))
			) {
				$criteria->compare('t.sale_office_code', Yii::app()->user->sale_offices_id);
			} else if (isset(Yii::app()->user->brand_offices_id) && !empty(Yii::app()->user->brand_offices_id)) {
				$criteria->compare("t.address_detail", Yii::app()->user->brand_offices_id, FALSE);
			}
		}else{
			$criteria->compare('province_code',$this->province_code,true);
			$criteria->compare('district_code',$this->district_code,true);
			$criteria->compare('ward_code',$this->ward_code,true);
			$criteria->compare('address_detail',$this->address_detail,true);
			$criteria->compare('sale_office_code',$this->sale_office_code,true);
		}

		if($this->start_date && $this->end_date){

			$this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
			$this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';

			$criteria->addCondition("t.create_date >= '$this->start_date' AND t.create_date <= '$this->end_date'");
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'       => array(
				'defaultOrder' => 't.create_date DESC',
			),
			'pagination' => array(
				'pageSize' => 50,
			),
		));
	}

	public static function getTotalReceiveByProvince($province_code, $start_date = null, $end_date = null)
	{
		$total = 0;
		$criteria = new CDbCriteria();
		$criteria->select = 'count(*) as total';
		$criteria->condition = 't.province_code = :province_code';
		$criteria->params = array(
			':province_code' => $province_code,
		);
		if($start_date && $end_date){
			$criteria->addCondition("t.create_date >= '$start_date' AND t.create_date <= '$end_date'");
		}
		$data = APrepaidToPostpaid::model()->findAll($criteria);
		if($data){
			$total = $data[0]->total;
		}
		return $total;
	}

	public static function getTotalSuccessByProvince($province_code, $start_date = null, $end_date = null)
	{
		$total = 0;
		$criteria = new CDbCriteria();
		$criteria->select = 'count(*) as total';
		$criteria->condition = 't.province_code = :province_code AND t.status = :status';
		$criteria->params = array(
			':province_code' => $province_code,
			':status' 	     => APrepaidToPostpaid::PTP_COMPLETE,
		);
		if($start_date && $end_date){
			$criteria->addCondition("t.create_date >= '$start_date' AND t.create_date <= '$end_date'");
		}
		$data = APrepaidToPostpaid::model()->findAll($criteria);
		if($data){
			$total = $data[0]->total;
		}
		return $total;
	}


}
