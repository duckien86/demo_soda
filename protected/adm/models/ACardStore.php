<?php

class ACardStore extends CardStore
{

	CONST CARD_NEW 			= 0;
	CONST CARD_PENDING		= 1;
	CONST CARD_ACTIVATED 	= 2;
	CONST CARD_FAILED 		= 8;
	CONST CARD_SUCCESS		= 10;

	CONST TYPE_CARD 	= "card";   //Mua mã thẻ
	CONST TYPE_TOPUP 	= "topup";  //Nạp thẻ trực tiếp

	CONST SEARCH_CREATE_DATE 	= 1;
	CONST SEARCH_EXPIRED_DATE 	= 2;
	CONST SEARCH_ACTIVE_DATE 	= 3;
	CONST SEARCH_RELEASE_DATE 	= 4;

	public $type_search_date;
	public $start_date;
	public $end_date;

	public $upload;
	public $upload_raw = array();
	public $upload_msg;
	public $upload_error;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, import_code, serial, pin, value, status, expired_date', 'required'),
			array('id, value, status, store_id', 'numerical', 'integerOnly'=>true),
			array('import_code', 'length', 'max'=>20),
			array('serial, pin, type, note, purchase_by, order_id, user_create', 'length', 'max'=>255),
			array('create_date, active_date, release_date, start_date, end_date, type_search_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, import_code, serial, pin, value, status, expired_date, create_date, active_date, release_date, type, note, purchase_by, order_id, user_create, store_id, start_date, end_date, type_search_date', 'safe', 'on'=>'search'),
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
			'id' 			=> Yii::t('adm/label','id'),
			'import_code' 	=> Yii::t('adm/label','import_code'),
			'serial' 		=> Yii::t('adm/label','serial'),
			'pin' 			=> Yii::t('adm/label','card_pin'),
			'value' 		=> Yii::t('adm/label','card_value'),
			'status' 		=> Yii::t('adm/label','status'),
			'expired_date' 	=> Yii::t('adm/label','expire_date'),
			'create_date' 	=> Yii::t('adm/label','create_date'),
			'active_date' 	=> Yii::t('adm/label','active_date'),
			'release_date' 	=> Yii::t('adm/label','release_date'),
			'type' 			=> Yii::t('adm/label','card_type'),
			'note' 			=> Yii::t('adm/label','note'),
			'purchase_by' 	=> Yii::t('adm/label','purchase_by'),
			'order_id' 		=> Yii::t('adm/label','order_id'),
			'user_create' 	=> Yii::t('adm/label','user_create'),
			'store_id' 		=> Yii::t('adm/label','store_id'),

			'start_date' 	=> Yii::t('adm/label','start_date'),
			'end_date' 		=> Yii::t('adm/label','finish_date'),
			'type_search_date' => Yii::t('adm/label','type_search_date'),
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
		$criteria->compare('import_code',$this->import_code,true);
		$criteria->compare('serial',$this->serial,true);
		$criteria->compare('pin',$this->pin,true);
		$criteria->compare('value',$this->value);
		$criteria->compare('status',$this->status);
		$criteria->compare('expired_date',$this->expired_date,true);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('active_date',$this->active_date,true);
		$criteria->compare('release_date',$this->release_date,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('purchase_by',$this->purchase_by,true);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('user_create',$this->user_create,true);
		$criteria->compare('store_id',$this->store_id,true);

		if($this->start_date && $this->end_date && $this->type_search_date){
			$column = self::getTypeSearchDateColumn($this->type_search_date);

			$this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
			$this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';

			$criteria->addCondition("t.$column is not NULL AND t.$column >= '$this->start_date' AND t.$column <= '$this->end_date'");
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ACardStore the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * @return array
	 */
	public static function getListStatus(){
		return array(
			self::CARD_NEW 			=> 'Chưa kích hoạt',
			self::CARD_PENDING		=> 'Đang xử lý',
			self::CARD_ACTIVATED 	=> 'Đã kích hoạt',
			self::CARD_FAILED 		=> 'Lỗi',
			self::CARD_SUCCESS 		=> 'Đã sử dụng',
		);
	}

	public static function getStatusLabel($status){
		$data = self::getListStatus();
		return isset($data[$status]) ? $data[$status] : $status;
	}

	public static function getStatusLabelClass($status){
		switch ($status){
			case self::CARD_NEW:
				return "text-info";
			case self::CARD_ACTIVATED:
				return "text-danger";
			case self::CARD_FAILED:
				return "text-danger";
			case self::CARD_SUCCESS:
				return "text-succes";
			default:
				return "text-secondary";
		}
	}

	public static function getListType(){
		return array(
			self::TYPE_CARD 	=> Yii::t('adm/label', 'type_buy_card'),
			self::TYPE_TOPUP 	=> Yii::t('adm/label', 'type_topup'),
		);
	}

	public static function getTypeLabel($type){
		$data = self::getListType();
		return (isset($data[$type])) ? $data[$type] : Yii::t('adm/label','not_used');
	}

	public static function getListTypeSearchDate(){
		return array(
			self::SEARCH_CREATE_DATE	=> Yii::t('adm/label','search_import_date'),
			self::SEARCH_EXPIRED_DATE 	=> Yii::t('adm/label','search_expired_date'),
			self::SEARCH_ACTIVE_DATE 	=> Yii::t('adm/label','search_active_date'),
			self::SEARCH_RELEASE_DATE 	=> Yii::t('adm/label','search_release_date'),
		);
	}

	public static function getTypeSearchDate($search_type){
		$data = self::getListTypeSearchDate();
		return (isset($data[$search_type])) ? $data[$search_type] : $search_type;
	}

	public static function getTypeSearchDateColumn($search_type){
		switch ($search_type){
			case self::SEARCH_CREATE_DATE:
				return 'create_date';
			case self::SEARCH_EXPIRED_DATE:
				return 'expired_date';
			case self::SEARCH_ACTIVE_DATE:
				return 'active_date';
			case self::SEARCH_RELEASE_DATE:
				return 'release_date';
			default:
				return 'create_date';
		}
	}

	/**
	 * @param $data static[]
	 * @return int
	 */
	public static function insertBatch($data){
		if(empty($data)){
			return 0;
		}
		if(!isset(Yii::app()->user->name)){
			return -2;
		}
		$batch = array();
		$user_create = Yii::app()->user->name;
		$create_date = date('Y-m-d H:i:s');
		foreach ($data as $model){
			$batch[] = array(
				'serial'		=> $model->serial,
				'pin'			=> $model->pin,
				'value'			=> $model->value,
				'status'		=> ACardStore::CARD_NEW,
				'import_code'	=> $model->import_code,
				'expired_date'	=> $model->expired_date,
				'create_date'	=> $create_date,
				'active_date'	=> '',
				'type'			=> '',
				'note'			=> '',
				'purchase_by'	=> '',
				'order_id'		=> '',
				'user_create'	=> $user_create,
			);
		}
		$connection = Yii::app()->db->getSchema()->getCommandBuilder();
		$command = $connection->createMultipleInsertCommand('tbl_card_store', $batch);
		return $command->execute();
	}

	/**
	 * @var $ignore_fail boolean bỏ qua các thẻ lỗi
	 * @return array
	 */
	public static function getListCardPin($ignore_fail = false)
	{
		$criteria = new CDbCriteria();
		$criteria->select = 't.pin';
		if($ignore_fail){
			$criteria->addCondition('t.status != '.ACardStore::CARD_FAILED);
		}
		$data = ACardStore::model()->findAll($criteria);
		$list_card_pin = array();
		if($data){
			foreach ($data as $card){
				$list_card_pin[] = $card->pin;
			}
		}
		return $list_card_pin;
	}


	public static function generateImportCode(){
		return "CI".strtotime(date('YmdHis'));
	}


	/**
	 * @param $uploadedFile CUploadedFile
	 * @return static[]
	 */
	public function getUploadFileContent($uploadedFile){
		$raw = array();
		$content = array();

		$url = $uploadedFile->tempName;
		$handle = fopen($url, "r");
		$first = true;
		$pattern = '/^[0-9\s\r\n\t\,]+$/';
		while(!feof($handle)) {
			$line = fgets($handle);
			if (trim($line) != '') {
				//bỏ qua dòng đâu nếu không phải dữ liệu thẻ
				if($first){
					$first=false;
					if(!preg_match($pattern, $line)){
						continue;
					}
				}

				$raw[] = $line;

				$data_arr = explode(',',$line);
				$model = new ACardStore();
				$model->serial     		= isset($data_arr[0]) ? trim($data_arr[0]) : '';
				$model->pin        		= isset($data_arr[1]) ? trim($data_arr[1]) : '';
				$model->value      		= isset($data_arr[2]) ? trim($data_arr[2]) : '';
				$expire = isset($data_arr[3]) ? trim($data_arr[3]) : '';
				if(!empty($expire) && strtotime($expire) != -1){
					$model->expired_date = date('Y-m-d H:i:s', strtotime($expire));
				}

				$content[] = $model;
			}
		}
		fclose($handle);
		$this->upload_raw = $raw;

		return $content;
	}


	/**
	 * @param $content static[]
	 * @return bool
	 */
	public function validateUploadFileContent($content){
		$pattern = '/^[0-9]+$/';
		$db_data_card_pin = array_merge(ACardStore::getListCardPin(true), ACardStoreBusiness::getListCardPin(true));
		$file_data_card_pin = array();
		$time = date('Y-m-d H:i:s');

		$raw = $this->upload_raw;
		if(empty($raw)){
			$this->upload_msg 	= Yii::t('adm/label','upload_file_data_invalid') + 1;
			$this->upload_error = Yii::t('adm/label','upload_file_not_found');
			return false;
		}
		if(count($content) != count($raw)){
			$this->upload_msg 	= Yii::t('adm/label','upload_file_data_invalid');
			$this->upload_error = Yii::t('adm/label','upload_file_data_not_match');
			return false;
		}
		for($i=0; $i < count($content); $i++){
			$model = $content[$i];

			if(empty($model->serial) || empty($model->pin) || empty($model->value) || empty($model->expired_date)){
				$this->upload_msg = Yii::t('adm/label','upload_file_data_invalid');
				$this->upload_error = "\"$raw[$i]\"";
				return false;
			}

			if (!preg_match($pattern, $model->serial)){
				$this->upload_msg 	= Yii::t('adm/label','card_invalid_serial');
				$this->upload_error = "\"$model->serial\"";
				return false;
			}

			if (!preg_match($pattern, $model->value)){
				$this->upload_msg 	= Yii::t('adm/label','card_invalid_serial');
				$this->upload_error = "\"$model->value\"";
				return false;
			}

			if(date('Y-m-d H:i:s',strtotime($model->expired_date)) < $time) {
				$this->upload_msg = Yii::t('adm/label','card_invalid_expire');
				$this->upload_error = "\"$model->expired_date\"";
				return false;
			}

			if(count($file_data_card_pin) > 0){
				if(in_array($model->pin, $file_data_card_pin)){
					$this->upload_msg = Yii::t('adm/label','card_duplicate_pin');
					$this->upload_error = "\"$model->pin\"";
					return false;
				}
			}
			if(count($db_data_card_pin) > 0){
				if(in_array($model->pin, $db_data_card_pin)){
					$this->upload_msg = Yii::t('adm/label','card_exist_pin');
					$this->upload_error = "\"$model->pin\"";
					return false;
				}
			}

			$file_data_card_pin[] = $model->pin;
		}
		return true;
	}

	public static function sendResponseToKDOL($msgContent, $emailContent){
		$list_user 		= User::getAllUsersKDOL();
		$list_msisdn 	= array();
		$list_email 	= array();

		if(!empty($list_user)){
			foreach ($list_user as $user) {
				if(!empty($user->phone_2)){
					$list_msisdn[] = $user->phone_2;
				}else{
					$list_msisdn[] = $user->phone;
				}
				$list_email[] = $user->email;
			}
		}


//		echo $msgContent;

		echo $emailContent;

		CVarDumper::dump($list_user, 10, true);
		CVarDumper::dump($list_msisdn, 10, true);
		CVarDumper::dump($list_email, 10, true);
		die();



		$otp = new OtpForm();
		$otp->sentMtVNP(null,null,null);
		$otp->sentMail(null, null, null, null);


	}
	

}
