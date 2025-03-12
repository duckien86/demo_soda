<?php

    /**
     * This is the model class for table "cc_tbl_complain".
     *
     * The followings are the available columns in table 'cc_tbl_complain':
     *
     * @property integer $id
     * @property integer $user_id
     * @property integer $unit_id
     * @property string  $msisdn
     * @property string  $call_time
     * @property integer $categories_id
     * @property integer $priority
     * @property integer $status
     * @property integer $content
     */
    class Complain extends ModelBase
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'cc_tbl_complain';
        }

        const PENDING    = 1;
        const OVERTIME   = 2;
        const CALLED     = 3;
        const CALLBACK   = 4;
        const NOT_ASSIGN = 0;

        const NORMAL   = 10;
        const PRIORITY = 1;

        public $ktv;
        public $provice;
        public $total_of_categories;
        public $query_date;
        public $start_date;
        public $end_date;
        public $categories_name;
        public $total_complain_by_ktv;
        public $number_assignment;
        public $total_of_categories_pending;
        public $total_of_categories_called;
        public $total_of_categories_callback;
        public $unit_id;
        public $content_result;


        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('id, categories_id, priority, status', 'numerical', 'integerOnly' => TRUE),
                array('msisdn, content', 'length', 'max' => 255),
                array('call_time', 'safe'),
                array('start_date, end_date', 'required', 'on' => 'search'),
                array('number_assignment', 'authenticateAssign'),
                array('categories_id', 'required', 'on' => 'search'),

                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, user_id, unit_id, msisdn, call_time, categories_id, priority, status, start_date, end_date', 'safe', 'on' => 'search'),
            );
        }


        /**
         * Authenticates the number of assign.
         * This is the 'authenticate' validator as declared in rules().
         */
        public function authenticateAssign($attribute, $params)
        {
            if ($this->$attribute) {
                $start_date          = date('Y-m-d', strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $end_date            = date('Y-m-d', strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
                $criteria            = new CDbCriteria;
                $criteria->select    = "count(*) as total_of_categories";
                $criteria->condition = "user_id is null 
                                        and categories_id =" . $this->categories_id . " 
                                        and t.call_time between ('" . $start_date . "') and ('" . $end_date . "')";
                $data                = Complain::model()->find($criteria);

                if ($this->$attribute > $data->total_of_categories) {
                    $this->addError($attribute, 'Số khiếu nại phải nhỏ hơn ' . $data->total_of_categories);
                }
                if ($data->total_of_categories == 0) {
                    $this->addError($attribute, 'Không có đơn khiếu nại nào!');
                }
            }
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
                'id'                  => 'ID',
                'msisdn'              => 'Số điện thoại',
                'content'             => 'Nội dung cuộc gọi',
                'call_time'           => 'Thời gian gọi',
                'categories_id'       => 'Chương trình',
                'priority'            => 'Mức độ ưu tiên',
                'status'              => 'Trạng thái',
                'categories_name'     => 'Chương trình',
                'total_of_categories' => 'Tổng số chưa giải quyết',
                'ktv'                 => 'Khai thác viên', // Khi chưa phân
                'user_id'             => 'Khai thác viên',
                'provice'             => 'Đơn vị',
                'number_assignment'   => 'Số lượng',
                'unit_id'             => 'Đơn vị',
                'content_result'      => 'Kết quả cuộc gọi',
                'query_date'          => 'Thời gian',
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
            $criteria->compare('msisdn', $this->msisdn, TRUE);
            $criteria->compare('content', $this->content, TRUE);
            $criteria->compare('call_time', $this->call_time, TRUE);
            $criteria->compare('priority', $this->priority);
            $criteria->compare('status', $this->status);
            if (SUPER_ADMIN || ADMIN) {
                $criteria->compare('categories_id', $this->categories_id, TRUE);
            } else if (Yii::app()->user->checkAccess('LeaderShift')) {
                $criteria->select    = "t.*,cc.categories_id as categories_id";
                $criteria->condition = "cc.monitor_id =" . Yii::app()->user->id . " and user_id is null";
                $criteria->join      = "LEFT JOIN cc_tbl_categories_unit cc ON cc.categories_id = t.categories_id ";
            }

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
            ));
        }

        /**
         * Danh sách cuộc hẹn gọi lại
         *
         * @return CActiveDataProvider
         */
        public function search_list_call_back($unit_id = '')
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
            if (SUPER_ADMIN || ADMIN) {
                $criteria = new CDbCriteria();

                $criteria->select = 't.*';
                if (!empty($cate_string)) {
                    $criteria->condition = "categories_id IN (" . $cate_string . ") and status = " . Complain::CALLBACK;
                } else if ($unit_id == '') {
                    $criteria->compare('status', Complain::CALLBACK);
                } else {
                    $criteria->compare('id', 0);
                }

                return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
            } else if (Yii::app()->user->checkAccess("LeaderShift")) {
                $criteria         = new CDbCriteria();
                $criteria->select = "t.*";
                if (!empty($cate_string)) {
                    $criteria->condition = "t.status=" . Complain::CALLBACK . " and u.parent_id = " . Yii::app()->user->id . " and categories_id IN (" . $cate_string . ")";
                } else if ($unit_id == '') {
                    $criteria->condition = "t.status=" . Complain::CALLBACK . " and u.parent_id = " . Yii::app()->user->id;
                } else {
                    $criteria->condition = "t.status=" . Complain::CALLBACK . " and u.parent_id = " . Yii::app()->user->id;
                }
                $criteria->join = " INNER JOIN {{users}} u ON u.id = t.user_id";

                return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,

                ));
            } else {
                $criteria         = new CDbCriteria();
                $criteria->select = "t.*";

                if (!empty($cate_string)) {
                    $criteria->condition = "status=" . Complain::CALLBACK . " and user_id = " . Yii::app()->user->id . " and categories_id IN (" . $cate_string . ")";
                } else if ($unit_id == '') {
                    $criteria->condition = "status=" . Complain::CALLBACK . " and user_id = " . Yii::app()->user->id;
                } else {
                    $criteria->condition = "status=" . Complain::CALLBACK . " and user_id = " . Yii::app()->user->id;
                }

                return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
            }
        }


        /**
         * @param string $date
         * Tìm kiếm cuộc gọi theo ngày
         *
         * @return CActiveDataProvider
         */
        public function search_by_date($date = '')
        {
            $criteria            = new CDbCriteria;
            $start_date          = date('Y-m-d', strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
            $end_date            = date('Y-m-d', strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            $criteria->select    = "cc.name as categories_name,count(t.id) as total_of_categories,t.categories_id";
            $criteria->condition = "t.call_time between ('" . $start_date . "') and ('" . $end_date . "')";
            $criteria->join      = "INNER JOIN cc_tbl_categories cc ON cc.id = t.categories_id ";

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
            ));
        }


        /**
         * @param $categories_id
         *Tìm kiếm theo thể loại.
         *
         * @return CActiveDataProvider
         */
        public function search_by_categories($categories_id)
        {
            if (!empty($categories_id)) {
                $criteria = new CDbCriteria;
                if (SUPER_ADMIN || ADMIN) {
                    $criteria->condition = "categories_id ='" . $categories_id . "'";
                } else {
                    $criteria->select    = "t.*,cc.categories_id as categories_id";
                    $criteria->condition = "cc.monitor_id =" . Yii::app()->user->id . " and  t.categories_id=" . $categories_id . " and user_id is null";
                    $criteria->join      = "LEFT JOIN cc_tbl_categories_unit cc ON cc.categories_id = t.categories_id ";
                }

                return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
            }

        }

        /*
         * View theo user.
         */
        public function search_by_user()
        {
            $criteria = new CDbCriteria;

            $start_date = date('Y-m-d', strtotime(str_replace('/', '-', $this->start_date))) . " 00:00:00";
            $end_date   = date('Y-m-d', strtotime(str_replace('/', '-', $this->end_date))) . " 23:59:59";

            $criteria->compare('msisdn', $this->msisdn, TRUE);
            $criteria->compare('content', $this->content, TRUE);
            if ($this->categories_id != '') {
                $criteria->condition = "t.call_time between '" . $start_date . "' and '" . $end_date . "' and t.categories_id=" . $this->categories_id;
            } else {
                $criteria->condition = "t.call_time between '" . $start_date . "' and '" . $end_date . "'";
            }
            $criteria->compare('priority', $this->priority);
            $criteria->compare('status', $this->status);
            $criteria->order = "priority";
            if (!SUPER_ADMIN && !ADMIN) {
                $criteria->compare('user_id', Yii::app()->user->id, TRUE);
            }

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
         * @return Complain the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public function afterFind()
        {
            if ($this->call_time && $this->status == self::PENDING) {
                if (strtotime($this->call_time) <= strtotime(date('Y-m-d h:i:s'))) {
                    $command = Yii::app()->db->createCommand('UPDATE cc_tbl_complain SET status=:status where id=:id')
                        ->bindValue(':id', $this->id)->bindValue(':status', self::OVERTIME)->query();
                }
            }

            if ($this->categories_id) {
                $criteria            = new CDbCriteria;
                $criteria->select    = "count(*) as total_of_categories";
                $criteria->condition = "categories_id=" . $this->categories_id . " and status !=" . self::CALLED;

                $query_sum                 = Complain::model()->findAll($criteria);
                $this->total_of_categories = $query_sum[0]['total_of_categories'];

                $categories = Categories::model()->findByAttributes(array('id' => $this->categories_id));

                if ($categories) {
                    $this->categories_name = $categories->name;
                }
            }
            $complain_log = ComplainLog::model()->findByAttributes(array('complain_id' => $this->id));

            if ($complain_log) {
                $this->content_result = $complain_log->content;
            }

            parent::afterFind(); // TODO: Change the autogenerated stub
        }

        /**
         * @return array
         * Lấy danh sách chương trình theo đơn vị
         */
        public function getListCategories()
        {
            $criteria         = new CDbCriteria;
            $criteria->select = " t.id, t.name";
            if (!SUPER_ADMIN && !ADMIN) {
                $criteria->condition = "cc.monitor_id= " . Yii::app()->user->id;
            }
            $criteria->join = "INNER JOIN cc_tbl_categories_unit cc ON cc.categories_id = t.id";
            $categories     = Categories::model()->findAll($criteria);

            return CHtml::listData($categories, 'id', 'name');
        }

        /**
         * @return array
         * Lấy tất cả danh sách chương trình
         */
        public function getListAllCategories()
        {
            $criteria         = new CDbCriteria;
            $criteria->select = " t.id, t.name";
            $categories       = Categories::model()->findAll($criteria);

            return CHtml::listData($categories, 'id', 'name');
        }


        /**
         * @return array
         * Lấy danh sách khai thác viên theo chương trình.
         */
        public function getListKTV()
        {
            $criteria         = new CDbCriteria;
            $criteria->select = "DISTINCT(u.id), u.username as ktv";
            if (!SUPER_ADMIN && !ADMIN) {
                $criteria->condition = "cc.monitor_id=" . Yii::app()->user->id;
            }
            $criteria->join = "INNER JOIN cc_tbl_categories_unit cc ON cc.categories_id = t.id 
                                   RIGHT JOIN tbl_users u ON u.parent_id = cc.monitor_id";
            $ktv            = Categories::model()->findAll($criteria);

            return CHtml::listData($ktv, 'id', 'ktv');
        }

        /**
         * @param $query_date
         * Lấy danh sách khai thác viên và tổng số khiếu nại chưa xử lý theo chương trình
         *
         * @return array|mixed|null
         */
        public function getListKTVComplain($start_date, $end_date)
        {
            $criteria         = new CDbCriteria;
            $start_date       = date('Y-m-d', strtotime(str_replace('/', '-', $start_date))) . ' 00:00:00';
            $end_date         = date('Y-m-d', strtotime(str_replace('/', '-', $end_date))) . ' 23:59:59';
            $criteria->select = "DISTINCT(u.id),u.username as ktv, count(t.user_id) as total_complain_by_ktv";
            if (!SUPER_ADMIN && !ADMIN) {
                $criteria->condition = "cc.monitor_id=" . Yii::app()->user->id . " 
                and t.status !=2 AND t.call_time between ('" . $start_date . "') and ('" . $end_date . "')";
            } else {
                $criteria->condition = "t.status !=2 and t.call_time between ('" . $start_date . "') and ('" . $end_date . "')";
            }
            $criteria->join  = "LEFT JOIN cc_tbl_categories_unit cc ON cc.categories_id = t.id
                                RIGHT JOIN tbl_users u ON u.id = t.user_id  ";
            $criteria->group = " ktv";
            $ktv             = Complain::model()->findAll($criteria);

            return $ktv;
        }

        /**
         * Lấy danh sách khai thác theo trưởng ca.
         */
        public function getListKtvByParent($unit_id = '')
        {
            $criteria         = new CDbCriteria;
            $criteria->select = "t.id, t.username";
            if (!SUPPER_ADMIN) {


                $criteria->condition = "parent_id =" . Yii::app()->user->id;
            } else if ($unit_id != '') {
                $criteria->condition = "cu.unit_id =" . $unit_id;
                $criteria->join      = " INNER JOIN cc_{{unit_user}} cu ON cu.user_id = t.parent_id ";
            } else {
                $criteria->condition = "username not in ('Admin','AdminCskh','AdminCms','LeaderShift','AdminChild')";
            }
            $data   = User::model()->findAll($criteria);
            $result = array();
            foreach ($data as $value) {
                $result[$value->id]['username'] = $value->username;
                $result[$value->id]['total']    = 0;
            }

            return $result;


        }

        /**
         * Lấy mức độ ưu tiên
         */
        public function getPriority()
        {
            return array(
                self::NORMAL   => 'Thông thường',
                self::PRIORITY => 'Ưu tiên',
            );
        }

        /**
         * Lấy mức độ ưu tiên theo id
         */
        public function getPriorityById($id)
        {
            $priority = "";
            if ($id == '') {
                $id = $this->priority;
            }
            switch ($id) {
                case 10:
                    $priority = "Ưu tiên";
                    break;
                case 1:
                    $priority = "Thông thường";
                    break;
                default:
            }

            return $priority;
        }

        /**
         * Lấy tên theo id
         */
        public function getUserByid($id)
        {
            $name = '';
            $user = User::model()->findByAttributes(array('id' => $id));
            if ($user) {
                $name = $user->username;
            }

            return $name;
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
                    $status = "Chưa xử lý";
                    break;
                case 1:
                    $status = "Đang xử lý";
                    break;
                case 2:
                    $status = "Đã xử lý";
                    break;
                case 3:
                    $status = "Qúa hạn";
                    break;
                case 4:
                    $status = "Hẹn gọi lại";
                    break;
                default:
            }

            return $status;
        }

        public function getAllStatus()
        {
            return array(
                self::NOT_ASSIGN => 'Chưa xử lý',
                self::PENDING    => 'Đang xử lý',
                self::OVERTIME   => 'Quá hạn',
                self::CALLED     => 'Đã xử lý',
                self::CALLBACK   => 'Hẹn gọi lại',
            );
        }

        /**
         * Lấy toàn bộ đơn vị.
         */
        public function getAllUnit()
        {
            $unit = Unit::model()->findAll();

            return CHtml::listData($unit, 'id', 'name');
        }


        public function afterSave()
        {
            /**
             *  Lưu vào bảng log.
             */
            if ($this->id) {
                $complain_log = ComplainLog::model()->findByAttributes(array('complain_id' => $this->id));
                if (!$complain_log) {
                    $complain_log              = new ComplainLog();
                    $complain_log->complain_id = $this->id;
                    $complain_log->status      = $this->status;
                    $complain_log->create_time = date('Y-m-d');
                    $complain_log->save();
                }
            }

            parent::afterSave();
        }

        /**
         * Lấy danh sách thể loại
         */
        public function getAllCategories()
        {
            $data = Categories::model()->findAll();

            return CHtml::listData($data, 'id', 'name');
        }


    }
