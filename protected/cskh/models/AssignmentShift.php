<?php

    /**
     * This is the model class for table "cc_tbl_assignment_shift".
     *
     * The followings are the available columns in table 'cc_tbl_assignment_shift':
     *
     * @property integer $id
     * @property integer $user_id
     * @property integer province_id
     * @property integer $shift
     * @property string  $shift_date
     * @property string  $note
     * @property string  $create_date
     * @property string  $approved_id
     */
    class AssignmentShift extends ModelBase
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'cc_tbl_assignment_shift';
        }

        const CA1 = 1;
        const CA2 = 2;
        const CA3 = 3;
        const CA4 = 4;
        const CA5 = 5;
        public $total;
        public $username;
        public $ktv_id;
        public $delete_shift;
        public $min_date;
        public $max_date;
        public $unit_name;
        public $unit_id;


        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('user_id, unit_id', 'required'),
                array('id, user_id, unit_id, shift, approved_id', 'numerical', 'integerOnly' => TRUE),
                array('note', 'length', 'max' => 500),
                array('shift_date, create_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, user_id, unit_id, shift, shift_date, note, create_date', 'safe', 'on' => 'search'),
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
                'user_id'      => 'Khai thác viên',
                'unit_id'      => 'Đơn vị',
                'unit_name'    => 'Đơn vị',
                'shift'        => 'Ca làm',
                'shift_date'   => 'Ngày làm',
                'note'         => 'Ghi chú',
                'create_date'  => 'Ngày tạo',
                'delete_shift' => 'Hủy',
                'username'     => 'Khai thác viên',
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
            $criteria->compare('user_id', $this->user_id);
            $criteria->compare('province_id', $this->province_id);
            $criteria->compare('shift', $this->shift);
            $criteria->compare('shift_date', $this->shift_date, TRUE);
            $criteria->compare('note', $this->note, TRUE);
            $criteria->compare('approved_id', $this->approved_id, TRUE);
            $criteria->compare('create_date', $this->create_date, TRUE);

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
            ));
        }

        /**
         * @param $shift_id
         * @param $shift_date_admin
         * View chi tiết cac trực
         *
         * @return CActiveDataProvider
         */
        public function search_shift($shift_id, $shift_date_admin)
        {

            $criteria = new CDbCriteria;

            $criteria->select    = '*';
            $criteria->condition = "shift_date ='" . $shift_date_admin . "' and shift=" . $shift_id;

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
            ));
        }

        public function afterFind()
        {
            if ($this->user_id) {
                $user = User::model()->findByAttributes(array('id' => $this->user_id));
                if ($user) {
                    $this->username = $user->username;
                }
                $criteria            = new CDbCriteria;
                $criteria->select    = 'cu.name as unit_name';
                $criteria->condition = "t.user_id =" . $this->user_id;
                $criteria->join      = "INNER JOIN {{users}} u ON u.id = t.user_id
                                   INNER JOIN cc_tbl_unit_user cuu On cuu.user_id = u.parent_id
                                   INNER JOIN cc_tbl_unit cu ON cu.id = cuu.unit_id";

                $query = AssignmentShift::model()->findAll($criteria);
                if ($query) {
                    $this->unit_name = $query[0]->unit_name;
                }
            }

            parent::afterFind(); // TODO: Change the autogenerated stub
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return AssignmentShift the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * @return array
         * Lấy danh sách khai tháng viên
         */
        public function getCskhUser()
        {
            $criteria         = new CDbCriteria;
            $criteria->select = '*,u.id as user_id, u.username as username';
            if (SUPER_ADMIN || ADMIN || Yii::app()->user->checkAccess('AdminCskh')) {
                $criteria->condition = 't.itemname in ("KTV","LeaderShift")';
            } else if (Yii::app()->user->checkAccess('LeaderShift')) {
                $criteria->condition = 't.itemname = "KTV" and u.parent_id =' . Yii::app()->user->id;
            }
            $criteria->join = 'INNER JOIN {{users}} u on u.id = t.userid';
            $user_cskh      = Authassignment::model()->findAll($criteria);

            return CHtml::listData($user_cskh, 'user_id', 'username');
        }

        /**
         * @return array
         * Lấy danh sách ca trực.
         */
        public function getAllShift()
        {
            return array(
                self::CA1 => 'ca 1',
                self::CA2 => 'ca 2',
                self::CA3 => 'ca 3',
                self::CA4 => 'ca 4',
                self::CA5 => 'ca 5',
            );
        }

        /**
         * @return array
         *
         */
        public function getInforAssignByUser($id = '')
        {
            $criteria         = new CDbCriteria;
            $criteria->select = 'id, shift, shift_date';
            if ($id != '') {
                $criteria->condition = 'user_id =' . $id;
            }
            $assignment_shift = AssignmentShift::model()->findAll($criteria);
            $data             = array(
                'calendar'    => array(),
                'total_user'  => 0,
                'total_shift' => 0,
            );
            $total_shif       = 0;
            foreach ($assignment_shift as $key => $shift) {
                $total_shif++;
                $data['calendar'][$key]['title'] = 'Trực ca ' . $shift->shift;
                $data['calendar'][$key]['start'] = $shift->shift_date;
            }
            $data['total_shift'] = $total_shif;

            return $data;
        }

        /**
         * @return array
         * Thông tin ca trực Admin vỉew
         *
         */
        public function getShiftInfo()
        {
            $shift_query = Shift::model()->findAll();
            $range_date  = self::getRangeDate();
            $start_time  = strtotime(date('Y-m-d', strtotime($range_date->min_date)));
            $end_time    = strtotime(date('Y-m-d', strtotime($range_date->max_date)));

            $total_user         = 0;
            $total_shif         = 0;
            $data               = array(
                'calendar'    => array(),
                'total_user'  => 0,
                'total_shift' => 0,
            );
            $start_time_default = strtotime(date('Y-m') . "-01");
            $end_time_default   = strtotime(date('Y-m-d'));
            $total_key          = array();
            for ($i = $start_time; $i <= $end_time; $i += 86400) {
                foreach ($shift_query as $key => $shift) {
                    $data_key[$i][$key]            = array();
                    $criteria[$i][$key]            = new CDbCriteria;
                    $criteria[$i][$key]->select    = "count(*) as total";
                    $criteria[$i][$key]->condition = "shift_date ='" . date("Y-m-d", $i) . "' and shift=" . $shift->id;
                    $total_key[$i][$key]           = AssignmentShift::model()->findAll($criteria[$i][$key]);
                    if ($total_key[$i][$key][0]->total != 0) {
                        if ($i <= $end_time_default && $i >= $start_time_default) {
                            $total_user += $total_key[$i][$key][0]->total;
                            $total_shif++;
                        }
                        $data_key[$i][$key]['title'] = $shift->name . ' (' . $total_key[$i][$key][0]->total . ' KTV)';
                        $data_key[$i][$key]['start'] = date("Y-m-d H:i:s", $i);
                    }
                }
                $data['calendar'] = array_merge($data['calendar'], $data_key[$i]);
            }
            $data['total_user']  = $total_user;
            $data['total_shift'] = $total_shif;


            return $data;
        }

        /**
         * @return array
         * Thông tin ca trực Admin vỉew khi chọn.
         *
         */
        public function getShiftInfoByChange($unit_id)
        {
            $shift_query = Shift::model()->findAll();
            $range_date  = self::getRangeDate();
            $start_time  = strtotime(date('Y-m-d', strtotime($range_date->min_date)));
            $end_time    = strtotime(date('Y-m-d', strtotime($range_date->max_date)));

            $data      = array();
            $total_key = array();
            for ($i = $start_time; $i <= $end_time; $i += 86400) {
                foreach ($shift_query as $key => $shift) {
                    $data_key[$i][$key]            = array();
                    $criteria[$i][$key]            = new CDbCriteria;
                    $criteria[$i][$key]->select    = "count(*) as total";
                    $criteria[$i][$key]->condition = "shift_date ='" . date("Y-m-d", $i) . "' and shift=" . $shift->id;
                    $total_key[$i][$key]           = AssignmentShift::model()->findAll($criteria[$i][$key]);
                    if ($total_key[$i][$key][0]->total != 0) {
                        $data_key[$i][$key]['title'] = $shift->name . ' (' . $total_key[$i][$key][0]->total . ' KTV)';
                        $data_key[$i][$key]['start'] = date("Y-m-d H:i:s", $i);
                    }
                }
                $data = array_merge($data, $data_key[$i]);
            }


            return $data;
        }

        /**
         * Lấy khoảng thời gian min và max của lịch làm việc.
         */
        public function getRangeDate()
        {
            $criteria         = new CDbCriteria;
            $criteria->select = "min(shift_date) as min_date, max(shift_date) as max_date";
            $range_date       = AssignmentShift::model()->findAll($criteria)[0];

            return $range_date;
        }

        /**
         * @param $shift_id
         * @param $shift_date_admin
         * Lấy danh sach user cskh không thuộc ca trực ở dropdowlist thêm user trong chi tiết ca trực.
         *
         * @return array
         */
        public function getAddShiftDate($shift_id, $shift_date_admin)
        {
            $criteria         = new CDbCriteria;
            $criteria->select = 'u.id as ktv_id, u.username as username';
            if (SUPER_ADMIN || ADMIN || Yii::app()->user->checkAccess('AdminCskh')) {
                $criteria->condition = "  a.itemname in ('KTV','LeaderShift') and (t.user_id is Null OR t.user_id not in (select t.user_id from cc_tbl_assignment_shift t 
                where t.shift_date ='" . $shift_date_admin . "' and t.shift =" . $shift_id . ")) 
                ";
            } else if (Yii::app()->user->checkAccess('LeaderShift')) {
                $criteria->condition = " u.parent_id =" . Yii::app()->user->id . " and a.itemname in ('KTV') 
                and (t.user_id not in (select t.user_id from cc_tbl_assignment_shift t 
                where t.shift_date ='" . $shift_date_admin . "' and t.shift =" . $shift_id . ") 
                OR t.user_id is Null )";
            }
            $criteria->join = 'RIGHT JOIN {{users}} u on u.id = t.user_id INNER JOIN {{authassignment}} a on a.userid=u.id';
            $result         = AssignmentShift::model()->findAll($criteria);

            return CHtml::listData($result, 'ktv_id', 'username');
        }


        /**
         * Lấy toàn bộ đơn vị.
         */
        public function getAllUnit()
        {
            $unit = Unit::model()->findAll();

            return CHtml::listData($unit, 'id', 'name');
        }

        /**
         * @param $obj
         * Convert object -> array();
         *
         * @return array
         */
        public function toArray($obj)
        {
            if (is_object($obj)) $obj = (array)$obj;
            if (is_array($obj)) {
                $new = array();
                foreach ($obj as $key => $val) {
                    $new[$key] = $this->toArray($val);
                }
            } else {
                $new = $obj;
            }

            return $new;
        }

        public function getUserByUnit($unit_id = '')
        {
            $criteria         = new CDbCriteria;
            $criteria->select = "*";
            if ($unit_id == '') {
                $criteria->condition = "cuu.unit_id= " . $this->unit_id;
            } else {
                $criteria->condition = "cuu.unit_id= " . $unit_id;
            }
            $criteria->join = "LEFT JOIN cc_tbl_unit_user cuu ON cuu.user_id = t.parent_id";

            $data = User::model()->findAll($criteria);

            return CHtml::listData($data, 'id', 'username');
        }
    }
