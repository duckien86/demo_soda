<?php

    /**
     * This is the model class for table "cc_tbl_categories".
     *
     * The followings are the available columns in table 'cc_tbl_categories':
     *
     * @property integer $id
     * @property string  $name
     * @property string  $description
     * @property integer $type
     * @property string  $create_date
     * @property string  $start_date
     * @property string  $end_date
     * @property string  $last_update
     * @property integer $created_by
     * @property integer $status
     */
    class Categories extends ModelBase
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'cc_tbl_categories';
        }

        public $unit_id;
        public $ktv;
        public $call_pending;
        public $called;

        const PENDING  = 0;
        const INACTIVE = 2;
        const ACTIVE   = 1;

        const OB_SERVEY    = 0;
        const OB_TELESALES = 1;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('start_date, end_date, name, unit_id,type', 'required'),
                array('id, type, created_by, status,type', 'numerical', 'integerOnly' => TRUE),
                array('name', 'length', 'max' => 255),
                array('name', 'unique'),
                array('description', 'length', 'max' => 1000),
                array('create_date, start_date, end_date, last_update', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, description, unit_id, type, create_date, start_date, end_date, last_update,type, created_by, status', 'safe', 'on' => 'search'),
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
                'id'           => 'ID',
                'name'         => 'Tên chương trình',
                'description'  => 'Kịch bản',
                'type'         => 'Thể loại',
                'create_date'  => 'Create Date',
                'start_date'   => 'Ngày bắt đầu',
                'end_date'     => 'Ngày kết thúc',
                'last_update'  => 'Last Update',
                'created_by'   => 'Created By',
                'status'       => 'Trạng thái',
                'called'       => 'Số khách hàng đã gọi',
                'call_pending' => 'Số khách hàng cần gọi',
                'unit_id'      => 'Đơn vị phụ trách',
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

            $criteria->compare('id', $this->id);
            $criteria->compare('name', $this->name, TRUE);
            $criteria->compare('description', $this->description, TRUE);
            $criteria->compare('type', $this->type);
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('start_date', $this->start_date, TRUE);
            $criteria->compare('end_date', $this->end_date, TRUE);
            $criteria->compare('unit_id', $this->unit_id, TRUE);
            $criteria->compare('last_update', $this->last_update, TRUE);
            $criteria->compare('created_by', $this->created_by);
            $criteria->compare('status', $this->status);

            return new CActiveDataProvider($this, array(
                'criteria'   => $criteria,
                'pagination' => array(
                    'pageSize' => 10,
                ),
            ));
        }

        /**
         * Tìm kiếm cho bảng làm việc cá nhân.
         */
        public function search_table_profile($unit_id = '')
        {
            $cate_string = '';
            if ($unit_id != '') {
                $cate_array       = array();
                $check            = new CDbCriteria();
                $check->select    = "*";
                $check->condition = "unit_id = " . $unit_id;
                $check->group     = "categories_id";
                $categories       = UnitCategories::model()->findAll($check);

                if ($categories) {
                    $stt = 0;
                    foreach ($categories as $key => $cate) {
                        if (!in_array($cate->categories_id, $cate_array)) {
                            if ($stt == count($categories) - 1) {
                                $cate_string .= $cate->categories_id;
                            } else {
                                $cate_string .= $cate->categories_id . ",";
                            }
                        }
                        $cate_array[] = $cate->categories_id;
                        $stt++;
                    }
                }
            }
            $criteria = new CDbCriteria;

            $criteria->compare('name', $this->name, TRUE);
            $criteria->compare('description', $this->description, TRUE);
            $criteria->compare('start_date', $this->start_date, TRUE);
            if ($unit_id != '' && $cate_string != '') {
                $criteria->condition = "id IN (" . $cate_string . ")";
            } else if ($cate_string == '' && $unit_id != '') {
                $criteria->compare('id', 0);
            }

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
            ));
        }

        public function afterFind()
        {
            $unit  = array();
            $query = UnitCategories::model()->findAll('categories_id', array('categories_id' => $this->id));
            if ($query) {
                foreach ($query as $row) {
                    $unit[] = $row->unit_id;
                }
            }
            $this->unit_id = $unit;

            if ($this->id) {
                $criteria            = new CDbCriteria();
                $criteria->select    = "count(*) as call_pending, count(*) as called";
                $criteria->condition = "c.categories_id = " . $this->id;
                $criteria->join      = "INNER JOIN cc_{{complain}} c ON c.categories_id = t.id";
                $data                = Categories::model()->findAll($criteria)[0];
                if ($data) {
                    $this->call_pending = $data->call_pending;
                    $this->called       = $data->called;
                }
            }
            parent::afterFind(); // TODO: Change the autogenerated stub
        }


        /**
         * @return array
         * Lấy danh sách đơn vị.
         */
        public function getAllCskhUser()
        {
            $unit = Unit::model()->findAll();

            return CHtml::listData($unit, 'id', 'name');

        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return Categories the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public function beforeSave()
        {

            $this->create_date = date('Y-m-d');
            $this->created_by  = Yii::app()->user->id;
            $this->start_date  = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
            $this->end_date    = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 00:00:00';

            return parent::beforeSave(); // TODO: Change the autogenerated stub
        }

        /**
         * Thêm vào bảng cc_tbl_categories_unit
         */
        public function afterSave()
        {
            if (isset($_POST['Categories']['unit_id'])) {
                foreach ($_POST['Categories']['unit_id'] as $unit_id) {
                    $command = Yii::app()->db->createCommand('INSERT INTO cc_tbl_categories_unit (categories_id, unit_id)values (:categories_id , :unit_id)')
                        ->bindValue(':categories_id', $this->id)
                        ->bindValue(':unit_id', $unit_id);
                    if ($command->query()) {
                        $result = TRUE;
                    }
                }
            }
            parent::afterSave(); // TODO: Change the autogenerated stub
        }

        /**
         * @return string
         * Lấy tiêu đề status.
         */
        public function getStatus($id = '')
        {
            $status = "";
            if ($id == '') {
                $id = $this->status;
            }
            switch ($id) {
                case 0:
                    $status = "Chờ duyệt";
                    break;
                case 2:
                    $status = "Khóa";
                    break;
                case 1:
                    $status = "Kích hoạt";
                    break;
                default:
            }

            return $status;
        }

        /**
         * Loại chương trình
         */
        public function getAllType()
        {
            return array(
                self::OB_SERVEY    => 'OB Servey',
                self::OB_TELESALES => 'OB Telesales'
            );
        }

        /**
         * Loại chương trình
         */
        public function getType($unit)
        {
            $data = array(
                self::OB_SERVEY    => 'OB Servey',
                self::OB_TELESALES => 'OB Telesales'
            );

            return isset($data[$unit]) ? $data[$unit] : '';
        }
    }
