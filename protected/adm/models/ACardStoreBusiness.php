<?php

class ACardStoreBusiness extends CardStoreBusiness
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

	public $quantity;
	public $order_code;
	public $order_date;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, import_code, serial, pin, value, status, expired_date', 'required', 'on' => 'create'),
			array('id, value, status, store_id', 'numerical', 'integerOnly'=>true),
			array('import_code', 'length', 'max'=>20),
			array('serial, pin, type, note, purchase_by, order_id, user_create', 'length', 'max'=>255),
			array('create_date, active_date, release_date, start_date, end_date, type_search_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, import_code, serial, pin, value, status, expired_date, create_date,
			 	active_date, release_date, type, note, purchase_by, order_id, user_create, store_id',
				'safe', 'on' => 'search, search_export'),
			array('start_date, end_date, type_search_date', 'safe', 'on' => 'search'),
			array('start_date, end_date','required', 'on' => 'search_export'),


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
			'order_code'	=> Yii::t('adm/label', 'order_id'),
			'order_date' 	=> Yii::t('adm/label','create_date'),
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
		$criteria->compare('import_code',$this->import_code,false);
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
			'sort'       => array(
				'defaultOrder' => 't.create_date DESC',
			),
			'pagination' => array(
				'params'   => array(
					'get'                          		=> 1,
					'ACardStoreBusiness[start_date]' 	=> $this->start_date,
					'ACardStoreBusiness[end_date]'      => $this->end_date,
					'ACardStoreBusiness[serial]'   		=> $this->serial,
					'ACardStoreBusiness[pin]'   		=> $this->pin,
					'ACardStoreBusiness[import_code]'   => $this->import_code,
					'ACardStoreBusiness[value]'   		=> $this->value,
					'ACardStoreBusiness[status]'   		=> $this->status,
				),
				'pageSize' => 20,
			),
		));
	}

	public function searchReportImport()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;
		$criteria->compare('t.import_code',$this->import_code,true);
		$criteria->select = 't.create_date, t.import_code, t.user_create, count(*) as quantity';
		$criteria->group = 't.import_code';

		if($this->start_date && $this->end_date){
			$this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
			$this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
			$criteria->addCondition("t.create_date is not NULL AND t.create_date >= '$this->start_date' AND t.create_date <= '$this->end_date'");
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'       => array(
				'defaultOrder' => 't.create_date DESC',
			),
			'pagination' => array(
				'params'   => array(
					'get'                          		=> 1,
					'ACardStoreBusiness[start_date]' 	=> $this->start_date,
					'ACardStoreBusiness[end_date]'      => $this->end_date,
					'ACardStoreBusiness[import_code]'   => $this->import_code,
				),
				'pageSize' => 20,
			),
		));
	}

	public function searchReportExport()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;
		$criteria->join = 'INNER JOIN tbl_orders od ON t.order_id = od.id';
		$criteria->select = 'od.create_time as order_date, od.code as order_code, t.serial, t.value, t.status, t.note';
		$criteria->compare('od.code',$this->order_code,true);
		$criteria->compare('t.status',$this->status,true);

		if($this->start_date && $this->end_date){
			$this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
			$this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
			$criteria->addCondition("od.create_time is not NULL AND od.create_time >= '$this->start_date' AND od.create_time <= '$this->end_date'");
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'       => array(
				'defaultOrder' => 'od.create_time DESC, t.value ASC, t.serial ASC',
			),
			'pagination' => array(
				'params'   => array(
					'get'                          		=> 1,
					'ACardStoreBusiness[start_date]' 	=> $this->start_date,
					'ACardStoreBusiness[end_date]'      => $this->end_date,
					'ACardStoreBusiness[order_code]'    => $this->order_code,
					'ACardStoreBusiness[status]'    	=> $this->status,
				),
				'pageSize' => 20,
			),

		));
	}

	public function searchReportRemain()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;

		$criteria->distinct = true;
		$criteria->select = 't.value';
		$criteria->group = 't.value';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'       => array(
				'defaultOrder' => 't.value ASC',
			),
			'pagination' => array(
				'pageSize' => 20,
			),
		));
	}

	public function searchReportSynthetic($dataProvider = true)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;

		$criteria->distinct = true;
		$criteria->select = 't.value';
		$criteria->group  = 't.value';

		if($dataProvider){
			return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'sort'       => array(
					'defaultOrder' => 't.value ASC',
				),
				'pagination' => array(
					'pageSize' => 20,
				),
			));
		}else{
			$criteria->order = 't.value ASC';
			return ACardStoreBusiness::model()->findAll($criteria);
		}
	}

	public function searchReportCard()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;
		$criteria->join = 'INNER JOIN tbl_orders od ON t.order_id = od.id';
//		$criteria->select = 'od.create_time as create_date, od.code as order_code, t.order_id, t.value, count(*) as quantity';
//		$criteria->group  = 't.value, t.order_id';
//		$criteria->compare('od.code',$this->order_code,true);

		$criteria->distinct = true;
		$criteria->select = 'od.create_time as create_date, od.code as order_code, t.order_id';
		$criteria->group = 't.order_id';
		$criteria->compare('od.code',$this->order_code,true);

		if($this->start_date && $this->end_date){
			$this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
			$this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
			$criteria->addCondition("od.create_time is not NULL AND od.create_time >= '$this->start_date' AND od.create_time <= '$this->end_date'");
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'       => array(
				'defaultOrder' => 'od.create_time DESC',
			),
			'pagination' => array(
				'params'   => array(
					'get'                          		=> 1,
					'ACardStoreBusiness[start_date]' 	=> $this->start_date,
					'ACardStoreBusiness[end_date]'      => $this->end_date,
					'ACardStoreBusiness[order_code]'    => $this->order_code,
				),
				'pageSize' => 20,
			),
		));
	}

	public function searchReportCardDetail()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria = new CDbCriteria();

		$criteria->join = 'INNER JOIN tbl_orders od ON t.order_id = od.id';
		$criteria->select = 't.*, od.code as order_code, od.create_time as create_date';
		$criteria->compare('t.serial',$this->serial,true);
		$criteria->compare('t.pin',$this->pin,true);
		$criteria->compare('t.value',$this->value,true);
		$criteria->compare('t.status',$this->status,true);

		$criteria->addCondition('t.order_id = '.$this->order_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'       => array(
				'defaultOrder' => 'od.create_time DESC, t.value ASC, t.serial ASC, t.status DESC',
			),
			'pagination' => array(
				'pageSize' => 20,
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ACardStoreBusiness the static model class
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
			self::CARD_SUCCESS 		=> 'Thành công',
		);
	}

	public static function getListStatusExport()
	{
		return array(
			self::CARD_PENDING		=> 'Đang xử lý',
			self::CARD_ACTIVATED 	=> 'Đã kích hoạt',
			self::CARD_FAILED 		=> 'Lỗi',
			self::CARD_SUCCESS 		=> 'Thành công',
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
			case self::CARD_PENDING:
				return "text-primary";
			case self::CARD_ACTIVATED:
				return "text-warning";
			case self::CARD_FAILED:
				return "text-danger";
			case self::CARD_SUCCESS:
				return "text-success";
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
	 * @param $data
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
				'status'		=> ACardStoreBusiness::CARD_NEW,
				'import_code'	=> $model->import_code,
				'expired_date'	=> $model->expired_date,
				'create_date'	=> $create_date,
				'active_date'	=> null,
				'release_date'	=> null,
				'type'			=> null,
				'note'			=> null,
				'purchase_by'	=> null,
				'order_id'		=> null,
				'user_create'	=> $user_create,
			);
		}
		$connection = Yii::app()->db_freedoo_tourist->getSchema()->getCommandBuilder();
		$command = $connection->createMultipleInsertCommand('tbl_card_store_business', $batch);
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
		$data = ACardStoreBusiness::model()->findAll($criteria);
		$list_card_pin = array();
		if($data){
			foreach ($data as $card){
				$list_card_pin[] = $card->pin;
			}
		}
		return $list_card_pin;
	}


	public static function generateImportCode()
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 't.object = :object';
		$criteria->params = array(
			':object'	 => AFTFiles::OBJECT_FILE_CARD_IMPORT,
		);
		$criteria->limit = 1;
		$criteria->order = 't.object_id DESC';
		$last = AFTFiles::model()->find($criteria);
		if($last){
			$code = intval($last->object_id) +1;
		}else{
			$code = 1;
		}
		return $code;
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
				$model = new ACardStoreBusiness();
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

			if (!preg_match($pattern, $model->serial)){
				$this->upload_msg 	= Yii::t('adm/label','card_invalid_serial');
				$this->upload_error = "\"$model->serial\"";
				return false;
			}

			if (!preg_match($pattern, $model->value)){
				$this->upload_msg 	= Yii::t('adm/label','card_invalid_serial');
				$this->upload_error = "\"$model->serial\"";
				return false;
			}

			if(!strtotime($model->expired_date) || strtotime($model->expired_date) == -1){
				$this->upload_msg = Yii::t('adm/label','card_invalid_expire');
				$this->upload_error = "\"$model->expired_date\"";
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


	public static function getListAvailableCardQuantity()
	{
		$criteria = new CDbCriteria();
		$criteria->select = "t.value, count(*) as quantity";
		$criteria->condition = "t.status = :status";
		$criteria->params = array(
			':status' => ACardStore::CARD_NEW
		);
		$criteria->group = "t.value";
		$criteria->order = "t.value DESC";

		return ACardStoreBusiness::model()->findAll($criteria);
		
		
	}


	/**
	 * @param $value 		int 	- mệnh giá thẻ
	 * @param $status 		int 	- trạng thái thẻ
	 * @param $action 		string 	- import | export | remain_before | remain_after ( nhập | xuất | tồn kho trước | sau)
	 * @param $start_date 	string 	- ngày bắt đầu
	 * @param $end_date 	string	- ngày kết thúc
	 * @return int
	 */
	public static function getCardQuantityByValue($value, $status = null, $action = null, $start_date = null, $end_date = null)
	{
		$criteria = new CDbCriteria();
		$criteria->select = "t.value, count(*) as quantity";
		$criteria->condition = "t.value = :value";
		$criteria->params = array(
			':value' => $value
		);
		if($status !== null && empty($action)){
			$criteria->addCondition("t.status = ".$status);
		}
		$criteria->group = "t.value";

		if(!empty($action)){
			$start_date = date("Y-m-d", strtotime(str_replace('/', '-', $start_date))) . ' 00:00:00';
			$end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $end_date))) . ' 23:59:59';

			switch ($action){
				case 'remain_before':
					$criteria->join = 'LEFT JOIN tbl_orders od ON t.order_id = od.id';
					$criteria->addCondition("t.create_date < '$start_date' AND ((t.status = ".ACardStoreBusiness::CARD_NEW.") OR (od.create_time > '$start_date'))");
					break;
				case 'import':
					$criteria->addCondition("t.create_date is not NULL AND t.create_date >= '$start_date' AND t.create_date <= '$end_date'");
					break;
				case 'export':
					$criteria->join = 'INNER JOIN tbl_orders od ON t.order_id = od.id';
					$criteria->addCondition("od.create_time is not NULL AND od.create_time >= '$start_date' AND od.create_time <= '$end_date'");
					if($status){
						$criteria->addCondition('t.status = '.$status);
					}
					break;
				case 'remain_after':
					$criteria->join = 'LEFT JOIN tbl_orders od ON t.order_id = od.id';
					$criteria->addCondition("t.create_date < '$end_date' AND ((t.status = ".ACardStoreBusiness::CARD_NEW.") OR (od.create_time > '$end_date'))");
					break;
			}
		}

		$data = ACardStoreBusiness::model()->findAll($criteria);
		if($data){
			return $data[0]->quantity;
		}else{
			return 0;
		}
	}


	/**
	 * @param $value
	 * @param $quantity
	 * @param $order_id
	 * @param $purchase_by
	 * @return bool
	 */
	public static function exportCard($value, $quantity, $order_id, $purchase_by){
		$criteria = new CDbCriteria();
		$criteria->condition = "t.value = :value AND t.status = :status";
		$criteria->params = array(
			':value' 	=> $value,
			':status' 	=> ACardStoreBusiness::CARD_NEW
		);
		$criteria->limit = $quantity;
		$criteria->order = "t.expired_date ASC, t.create_date ASC";
		$list_card = ACardStoreBusiness::model()->findAll($criteria);

		if(count($list_card) < $quantity){
			return false;
		}
		$arr_card_id = array();
		foreach ($list_card as $card){
			$arr_card_id[] = $card->id;
		}

		$a= implode(',',$arr_card_id);
		$status = ACardStoreBusiness::CARD_PENDING;
		$command = Yii::app()->db_freedoo_tourist->createCommand(
			"UPDATE tbl_card_store_business 
			SET order_id = :order_id, purchase_by = :purchase_by, status = :status
			WHERE id IN ($a)")
			->bindParam(':order_id', $order_id)
			->bindParam(':purchase_by', $purchase_by)
			->bindParam(':status', $status);

		$result = $command->execute();

		if($result){
			return true;
		}else{
			return false;
		}
	}

	public static function getListCardImport($import_code, $group = null)
	{
		$criteria = new CDbCriteria();
		$criteria->select = 't.value, count(*) as quantity';
		$criteria->condition = 't.import_code = :import_code';
		$criteria->params = array(
			':import_code' => $import_code
		);
		$criteria->order = 't.value ASC';
		if($group){
			$criteria->group = 't.' . $group;
		}

		return ACardStoreBusiness::model()->findAll($criteria);
	}

	public static function getListCardExport($order_id)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 't.order_id = :order_id';
		$criteria->params = array(
			':order_id' => $order_id
		);
		$criteria->order = 't.value ASC, t.status ASC';
		return ACardStoreBusiness::model()->findAll($criteria);
	}


	/**
	 * @param $order_id int
	 * @param $value int
	 * @param  $status int | array
	 * @return int
	 */
	public static function getQuantityCardExport($order_id, $value, $status = null)
	{
		$criteria = new CDbCriteria();
		$criteria->select = 't.value, count(*) as quantity';
		$criteria->condition = 't.order_id = :order_id AND t.value = :value';
		$criteria->params = array(
			':order_id' => $order_id,
			':value' => $value
		);
		if($status !== null){
			if(is_array($status)){
				$arr_status = implode(',',$status);
				$criteria->addCondition('t.status IN ('.$arr_status.')');
			}else{
				$criteria->addCondition('t.status = '.$status);
			}
		}
		$data = ACardStoreBusiness::model()->findAll($criteria);
		if($data){
			return $data[0]->quantity;
		}else{
			return 0;
		}
	}
}
