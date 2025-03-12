<?php

    class AFTContracts extends FTContracts
    {
        CONST CONTRACT_PENDING  = 0;
        CONST CONTRACT_ACTIVE   = 1;
        CONST CONTRACT_COMPLETE = 10;

        public $user_search;
        public $detail;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('code', 'unique', 'message' => Yii::t('adm/label', 'err_code_unique')),
                array('user_id, start_date, finish_date', 'required'),
                array('user_id, status', 'numerical', 'integerOnly' => TRUE),
                array('code, note', 'length', 'max' => 255),
                array('code, create_time, last_update, start_date, finish_date, detail', 'safe'),
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
                array('id, code, user_id, create_time, last_update, start_date, finish_date, note, status, create_by, user_search', 'safe', 'on' => 'search'),
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
                'user' => array(self::BELONGS_TO, 'AFTUsers', 'user_id'),);
        }

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'          => Yii::t('adm/label', 'id'),
                'code'        => Yii::t('adm/label', 'code_contract'),
                'user_id'     => Yii::t('adm/label', 'user_id_contract'),
                'create_time' => Yii::t('adm/label', 'create_time'),
                'last_update' => Yii::t('adm/label', 'last_update'),
                'start_date'  => Yii::t('adm/label', 'start_date'),
                'finish_date' => Yii::t('adm/label', 'finish_date'),
                'note'        => Yii::t('adm/label', 'note'),
                'status'      => Yii::t('adm/label', 'status'),
                'create_by'   => Yii::t('adm/label', 'create_by'),
                'user_search' => Yii::t('adm/label', 'user_id_contract'),
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

            $criteria       = new CDbCriteria;
            $criteria->with = array('user');

            $criteria->compare('t.id', $this->id, TRUE);
            $criteria->compare('t.code', $this->code, TRUE);
            $criteria->compare('t.user_id', $this->user_id);
            $criteria->compare('t.create_time', $this->create_time, TRUE);
            $criteria->compare('t.last_update', $this->last_update, TRUE);
            $criteria->compare("DATE_FORMAT(t.start_date, '%d/%m/%Y')", $this->start_date);
            $criteria->compare("DATE_FORMAT(t.finish_date, '%d/%m/%Y')", $this->finish_date);
            $criteria->compare('t.note', $this->note, TRUE);
            $criteria->compare('t.status', $this->status);
            /*user_id from user || system_user*/
            $tempCriteria = new CDbCriteria();
            $tempCriteria->compare('user.username', $this->user_search, TRUE);
            $criteria->mergeWith($tempCriteria, 'AND');

            return new CActiveDataProvider($this, array(
                'criteria'   => $criteria,
                'sort'       => array('defaultOrder' => 't.id desc'),
                'pagination' => array(
                    'pageSize' => 50,
                )
            ));
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return AFTContracts the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public function beforeSave()
        {
            if ($this->isNewRecord) {
                $this->code        = 'CT' . rand(1000, 9999);
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

        /**
         * @param $id
         *
         * @return string
         */
        public function getFileUrl($id)
        {
            $dir_root   = '../';
            $modelFiles = AFTFiles::model()->find('object_id=:object_id', array(':object_id' => $id));
            if ($modelFiles) {
                $file_name = $dir_root . $modelFiles->folder_path;
                if (is_file($file_name) && file_exists($file_name)) {
                    return CHtml::link(Yii::t('adm/label', 'view_file'), $file_name, array('target' => '_blank', 'title' => ''));
                }
            }

            return FALSE;
        }

        /**
         * Lấy toàn bộ hợp đồng.
         */
        public function getAllContract()
        {
            $contracts = AFTContracts::model()->findAll('status = :status', array(':status' => self::CONTRACT_ACTIVE));

            return CHtml::listData($contracts, 'id', 'code');
        }

        /**
         * @param $user_tourist
         * Lấy danh sach hợp dồng theo khách hàng
         *
         * @return array
         */
        public function getContractByUsers($user_tourist)
        {
            $data = array();

            if ($user_tourist) {
                $contract = AFTContracts::model()->findAll('user_id =:user_id and status =:status',
                    array(
                        ':user_id' => $user_tourist,
                        ':status'  => AFTContracts::CONTRACT_ACTIVE
                    )
                );

                return CHtml::listData($contract, 'id', 'code');
            }

            return $data;
        }

        /**
         * Lấy code Contract by id
         */
        public function getContractCode($contract_id)
        {
            $data = '';
            if ($contract_id) {
                $contracts = AFTContracts::model()->findByAttributes(array('id' => $contract_id));
                if ($contracts) {
                    $data = $contracts->code;
                }
            }

            return $data;
        }

        /**
         * @param $user_id
         *
         * @return string
         */
        public function getUserNameByUserId($user_id)
        {
            $model = '';
            if ($user_id) {
                $model = AFTUsers::model()->find('id=:id', array(':id' => $user_id));
            }

            return ($model) ? CHtml::encode($model->username) : $user_id;
        }

        public function getBtnUpdate()
        {
            if ($this->status == self::CONTRACT_PENDING) {
                return TRUE;
            }

            return FALSE;
        }
    }
