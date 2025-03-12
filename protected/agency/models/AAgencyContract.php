<?php

class AAgencyContract extends AgencyContract
{

	public $user_search;
	public $detail;

	public $company;
	public $address;
	public $username;
	public $fullname;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code', 'unique', 'message' => Yii::t('adm/label', 'err_code_unique')),
			array('agency_id, start_date, finish_date', 'required'),
			array('status', 'numerical', 'integerOnly' => TRUE),
			array('agency_id, code, note', 'length', 'max' => 255),
			array('code, create_time, last_update, start_date, finish_date, detail, company, address, username, fullname', 'safe'),
			array(
				'finish_date',
				'compare',
				'compareAttribute' => 'start_date',
				'operator'         => '>=',
				'allowEmpty'       => FALSE,
				'message'          => Yii::t('adm/label', 'err_start_end_date')
			),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, agency_id, create_time, last_update, start_date, finish_date, note, status, create_by, user_search, company, address, username, fullname', 'safe', 'on' => 'search'),
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
			'id'          => Yii::t('adm/label', 'id'),
			'code'        => Yii::t('adm/label', 'code_contract'),
			'agency_id'   => Yii::t('adm/label', 'agency_id'),
			'create_time' => Yii::t('adm/label', 'create_time'),
			'last_update' => Yii::t('adm/label', 'last_update'),
			'start_date'  => Yii::t('adm/label', 'start_date'),
			'finish_date' => Yii::t('adm/label', 'finish_date'),
			'note'        => Yii::t('adm/label', 'note'),
			'status'      => Yii::t('adm/label', 'status'),
			'create_by'   => Yii::t('adm/label', 'create_by'),
			'user_search' => Yii::t('adm/label', 'user_id_contract'),

			'username'    => "Tài khoản",
			'company'     => "Công ty/ Doanh nghiệp",
			'fullname'    => "Người đại diện",
			'address'    =>  Yii::t('adm/label', 'address'),
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
		$criteria->compare('code',$this->code,true);
//		$criteria->compare('agency_id',$this->agency_id,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('last_update',$this->last_update,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('finish_date',$this->finish_date,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_by',$this->create_by);

		$criteria->addCondition("t.agency_id = '".Yii::app()->user->agency."'");

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AAgencyContract the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function beforeSave()
	{
		if ($this->isNewRecord) {
			$this->code        = 'A' . rand(1000, 9999);
			while(!$this->validate('code')){
				$this->code        = 'A' . rand(1000, 9999);
			}
			$this->status      = self::CONTRACT_PENDING;
			$this->create_time = date('Y-m-d H:i:s', time());
			$this->create_by   = Yii::app()->user->id;
		}
		$this->last_update = date('Y-m-d H:i:s', time());

		return TRUE;
	}

	public function getAllStatus()
	{
		return array(
			self::CONTRACT_PENDING  => Yii::t('adm/label', 'pending'),
			self::CONTRACT_ACTIVE   => Yii::t('adm/label', 'contract_active'),
			self::CONTRACT_COMPLETE => Yii::t('adm/label', 'complete'),
		);
	}

	/**
	 * @param $status
	 *
	 * @return mixed
	 */
	public function getStatusLabel($status)
	{
		$array_status = $this->getAllStatus();

		return isset($array_status[$status]) ? $array_status[$status] : $status;
	}

	public function getBtnUpdate()
	{
		$orders = AAgencyOrder::model()->findAllByAttributes(array('agency_id' => $this->agency_id));
		if ($this->status == self::CONTRACT_PENDING && empty($orders)) {
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * @param $id
	 *
	 * @return string
	 */
	public function getFileUrl($id)
	{
		$dir_root   = '../';
		$criteria = new CDbCriteria();
		$criteria->condition = "t.object = :object AND t.object_id = :object_id";
		$criteria->params = array(
			':object' => AAgencyFile::OBJECT_FILE_CONTRACTS,
			':object_id' => $id
		);
		$modelFiles = AAgencyFile::model()->find($criteria);
		if ($modelFiles) {
			$file_name = $dir_root . $modelFiles->folder_path;

			return CHtml::link(Yii::t('adm/label', 'view_file'), $file_name, array('target' => '_blank', 'title' => ''));
		}

		return FALSE;
	}

	public static function getCurrentAgencyContractActive()
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 't.agency_id = :agency_id AND t.status = :status';
		$criteria->params = array(
			':agency_id' => Yii::app()->user->agency,
			':status' => AAgencyContract::CONTRACT_ACTIVE,
		);
		$model = AgencyContract::model()->find($criteria);
		return ($model) ? $model->id : null;
	}

	public static function getContractCode($id)
	{
		$result = '';
		if(!empty($id)){
			$cache_key = "AAgencyContract_getContractCode_$id";
			$result = Yii::app()->cache->get($cache_key);
			if(!$result){
				$model = AAgencyContract::model()->findByPk($id);
				$result = ($model) ? $model->code : $id;
			}

		}
		return $result;
	}

}
