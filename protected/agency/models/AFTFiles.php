<?php

    class AFTFiles extends FTFiles
    {
        CONST FILE_ACTIVE   = 1;
        CONST FILE_INACTIVE = 0;
        public $old_file;

        CONST OBJECT_FILE_SIM               = 'TOrdersFileSim';
        CONST OBJECT_FILE_ACCEPT_PAYMENT    = 'TOrders';
        CONST OBJECT_FILE_CONTRACTS         = 'AFTContracts';
        CONST OBJECT_FILE_SIM_KIT           = 'AFTOrders';
        CONST OBJECT_FILE_CARD_IMPORT       = 'ACardStoreBusiness';
        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('folder_path', 'required', 'on' => array('insert', 'update')),
                array('object, object_id, file_name, file_ext', 'required'),
                array('object_id, file_size, status', 'numerical', 'integerOnly' => TRUE),
                array('object', 'length', 'max' => 50),
                array('file_name', 'length', 'max' => 500),
                array('file_ext', 'length', 'max' => 10),
                array('folder_path', 'length', 'max' => 1000),
                array('create_date, extra_info', 'safe'),
                array('folder_path', 'file', 'on'    => 'insert, update',
                                             'types' => 'jpg, jpeg, png'
                ),
                array('folder_path', 'file', 'on'    => 'file_contract',
                                             'types' => 'jpg, jpeg, png, pdf'
                ),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, object, object_id, file_name, file_ext, file_size, folder_path, create_date, extra_info, status', 'safe', 'on' => 'search'),
            );
        }

        /**
         * @return array relational rules.
         */
        public function relations()
        {
            // NOTE: you may need to adjust the relation name and the related
            // class name for the relations automatically generated below.
            return array();
        }

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'          => 'ID',
                'object'      => 'Object',
                'object_id'   => 'Object',
                'file_name'   => 'File Name',
                'file_ext'    => 'File Ext',
                'file_size'   => 'File Size',
                'folder_path' => 'Folder Path',
                'create_date' => 'Create Date',
                'extra_info'  => 'Extra Info',
                'status'      => 'Status',
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

            $criteria = new CDbCriteria;

            $criteria->compare('id', $this->id, TRUE);
            $criteria->compare('object', $this->object, TRUE);
            $criteria->compare('object_id', $this->object_id, TRUE);
            $criteria->compare('file_name', $this->file_name, TRUE);
            $criteria->compare('file_ext', $this->file_ext, TRUE);
            $criteria->compare('file_size', $this->file_size);
            $criteria->compare('folder_path', $this->folder_path, TRUE);
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('extra_info', $this->extra_info, TRUE);
            $criteria->compare('status', $this->status);

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
            ));
        }


        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return AFTFiles the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public function beforeSave()
        {
//            $this->status      = self::FILE_ACTIVE;
            $this->create_date = date('Y-m-d H:i:s', time());

            return TRUE;
        }

        /**
         * @param $object
         *
         * @return bool
         */
        public function setFile($object)
        {
            $this->object    = get_class($object);
            $this->object_id = $object->id;
            if ($this->save()) {
                $dir_old_file = '/../' . $this->old_file;
                if (!empty($this->old_file) && ($this->old_file != $this->folder_path) && file_exists(realpath(Yii::app()->getBasePath() . $dir_old_file))) {
                    $this->deleteFile($dir_old_file);
                }

                return TRUE;
            }

            return FALSE;
        }

        /**
         * delete exists
         *
         * @param $file_name
         */
        public function deleteFile($file_name)
        {
            $dir_root = '../';
            if ($file_name) {
                $src = realpath(Yii::app()->getBasePath() . $dir_root . $file_name);
                if ($src) {
                    unlink($src);
                }
            }
        }


        /**
         * Returns string url of file
         * @param $object string
         * @param $object_id int
         * @return AFTFiles
         */
        public static function getFile($object, $object_id){
            $criteria = new CDbCriteria();
            $criteria->condition = 't.object = :object AND t.object_id = :object_id';
            $criteria->params = array(
                ':object' 	 => $object,
                ':object_id' => $object_id,
            );

            return AFTFiles::model()->find($criteria);
        }

        /**
         * Returns string url of file
         * @param $object string
         * @param $object_id int
         * @return string
         */
        public static function getFileUrl($object, $object_id){
            $url = '';
            $criteria = new CDbCriteria();
            $criteria->condition = 't.object = :object AND t.object_id = :object_id';
            $criteria->params = array(
                ':object' 	 => $object,
                ':object_id' => $object_id,
            );

            $file = AFTFiles::model()->find($criteria);
            if($file){
                $baseUrl = Yii::app()->baseUrl.'/../';
                $fileUrl = $file->folder_path . $file->file_name . '.' . $file->file_ext;
                $url = $baseUrl . $fileUrl;
            }
            return $url;
        }

        /**
         * Returns string url of file
         * @param $object string
         * @param $object_id int
         * @return string
         */
        public static function getFileName($object, $object_id){
            $name = '';
            $criteria = new CDbCriteria();
            $criteria->condition = 't.object = :object AND t.object_id = :object_id';
            $criteria->params = array(
                ':object' 	 => $object,
                ':object_id' => $object_id,
            );

            $file = AFTFiles::model()->find($criteria);
            if($file){
                $name = $file->file_name . '.' .$file->file_ext;
            }
            return $name;
        }


        /**
         * @param $object
         * @param $object_id
         * @return static[]
         */
        public static function getALlFiles($object = null, $object_id = null)
        {
            $criteria = new CDbCriteria();
            if(!empty($object)){
                $criteria->addCondition("t.object = '".$object."'");
            }
            if(!empty($object_id)){
                $criteria->addCondition('t.object_id = ' .$object_id);
            }
            $criteria->order = 't.create_date ASC';
            return AFTFiles::model()->findAll($criteria);
        }
    }


