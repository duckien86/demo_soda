<?php

    class AssignmentShiftController extends AController
    {
        /**
         * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
         * using two-column layout. See 'protected/views/layouts/column2.php'.
         */
        public $layout = '//layouts/column2';

        /**
         * @return array action filters
         */
        public function filters()
        {
            return array(
//			'accessControl', // perform access control for CRUD operations
                //'postOnly + delete', // we only allow deletion via POST request
                'rights', // perform access control for CRUD operations
            );
        }

        /**
         * Specifies the access control rules.
         * This method is used by the 'accessControl' filter.
         *
         * @return array access control rules
         */
        public function accessRules()
        {
            return array(
                array('allow',  // allow all users to perform 'index' and 'view' actions
                    'actions' => array('index', 'view'),
                    'users'   => array('*'),
                ),
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                    'actions' => array('create', 'update'),
                    'users'   => array('@'),
                ),
                array('allow', // allow admin user to perform 'admin' and 'delete' actions
                    'actions' => array('admin', 'delete'),
                    'users'   => array('admin'),
                ),
                array('deny',  // deny all users
                    'users' => array('*'),
                ),
            );
        }

        /**
         * Displays a particular model.
         *
         * @param integer $id the ID of the model to be displayed
         */
        public function actionView($id)
        {
            $this->render('view', array(
                'model' => $this->loadModel($id),
            ));
        }

        /**
         * Tạo mới ca trực cho view từng nhân viên.
         * If creation is successful, the browser will be redirected to the 'view' page.
         */
        public function actionCreate()
        {
            $model = new AssignmentShift;

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);
            if (isset($_POST['Admin'])) {
                $model->user_id    = $_POST['Admin']['user_id'];
                $model->shift_date = $_POST['Admin']['shift_date_admin'];
                $model->unit_id    = $_POST['Admin']['unit_id'];
            }

            if (isset($_POST['AssignmentShift'])) {

                $model->attributes  = $_POST['AssignmentShift'];
                $model->create_date = date('Y-m-d');
                $model->approved_id = Yii::app()->user->id;
                if ($model->save())
                    $this->redirect(array('admin'));
            }

            $this->render('create', array(
                'model' => $model,
//                'shift_info' => $shift_info,
            ));
        }


        /**
         * Tạo mới ca trực cho view admin.
         * If creation is successful, the browser will be redirected to the 'view' page.
         */
        public function actionCreateAdmin()
        {
            $model = new AssignmentShift;

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);
            if (isset($_POST['Admin'])) {
                $model->shift_date = $_POST['Admin']['shift_date_admin'];
            }

            if (isset($_POST['AssignmentShift'])) {
                $data = self::importManyAssignShift($_POST['AssignmentShift']);
                if (!empty($data)) {
                    foreach ($data as $value) {
                        $model              = new AssignmentShift();
                        $model->attributes  = $value;
                        $model->create_date = date('Y-m-d');
                        $model->approved_id = Yii::app()->user->id;
                        $model->save();
                    }
                    $this->redirect(array('admin'));
                }
            }

            $this->render('create_admin', array(
                'model' => $model,
//                'shift_info' => $shift_info,
            ));
        }

        public function importManyAssignShift($data)
        {
            $result = array();
            if (!empty($data['user_id'])) {
                foreach ($data['user_id'] as $key => $user_id) {
                    $result[$key]['user_id']    = isset($user_id) ? $user_id : 0;
                    $result[$key]['unit_id']    = $data['unit_id'];
                    $result[$key]['shift_date'] = $data['shift_date'];
                    $result[$key]['shift']      = $data['shift'];
                    $result[$key]['note']       = $data['note'];
                }
            }

            return $result;
        }

        public function actionAdminShift()
        {
            $model = new AssignmentShift('search');
            $model->unsetAttributes();  // clear any default values
            $shift_defi = self::getAllShift();
            if (SUPER_ADMIN || ADMIN) {
                $shift_info = $model->getShiftInfo();
            } else {
                $shift_info = $model->getInforAssignByUser(Yii::app()->user->id);
            }

            if (isset($_GET['AssignmentShift'])) {
                $model->attributes = $_GET['AssignmentShift'];
            }

            $this->render('admin_shift', array(
                'model'      => $model,
                'shift_info' => $shift_info,
                'shift_defi' => $shift_defi,
            ));
        }


        /*
         * Lấy dữ liệu lịch làm việc cho view nhân viên
         */
        public function actionGetInforAssignByUser()
        {
            $id                  = Yii::app()->getRequest()->getParam('user_id');
            $criteria            = new CDbCriteria;
            $criteria->select    = 'id, shift, shift_date';
            $criteria->condition = 'user_id =' . $id;
            $assignment_shift    = AssignmentShift::model()->findAll($criteria);
            $data                = array(
                'calendar'    => array(),
                'total_user'  => 0,
                'total_shift' => 0,
            );
            $start_time_default  = strtotime(date('Y-m') . "-01");
            $end_time_default    = strtotime(date('Y-m-d'));
            $total_shif          = 0;
            foreach ($assignment_shift as $key => $shift) {
                if (strtotime($shift->shift_date) >= $start_time_default && strtotime($shift->shift_date) <= $end_time_default) {
                    $total_shif++;
                }
                $data['calendar'][$key]['id']    = $shift->id;
                $data['calendar'][$key]['title'] = 'Trực ca ' . $shift->shift;
                $data['calendar'][$key]['start'] = $shift->shift_date;
            }
            $data['total_shift'] = $total_shif;
            echo CJSON::encode($data);
        }

        /*
         * Lấy dữ liệu lịch làm việc cho view đơn vị.
         */
        public function actionGetInforAssignByDistrict()
        {
            $unit_id = Yii::app()->getRequest()->getParam('unit_id');

            $shift_query = Shift::model()->findAll();
            $range_date  = self::getRangeDate();
            $start_time  = strtotime(date('Y-m-d', strtotime($range_date->min_date)));
            $end_time    = strtotime(date('Y-m-d', strtotime($range_date->max_date)));

            $start_time_default = strtotime(date('Y-m') . "-01");
            $end_time_default   = strtotime(date('Y-m-d'));

            $total_user = 0;
            $total_shif = 0;
            $data       = array(
                'calendar'    => array(),
                'total_user'  => 0,
                'total_shift' => 0,
            );
            $total_key  = $data_key = array();
            for ($i = $start_time; $i <= $end_time; $i += 86400) {
                foreach ($shift_query as $key => $shift) {
                    $data_key[$i][$key]            = array();
                    $criteria[$i][$key]            = new CDbCriteria;
                    $criteria[$i][$key]->select    = "count(t.id) as total";
                    $criteria[$i][$key]->condition = "t.shift_date ='" . date("Y-m-d", $i) . "' and t.shift=" . $shift->id . " and cd.unit_id=" . $unit_id;
                    $criteria[$i][$key]->join      = "INNER JOIN tbl_users u ON u.id = t.user_id
                                                      LEFT JOIN cc_tbl_unit_user cd ON cd.user_id = u.parent_id";
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

            echo CJSON::encode($data);
        }


        /**
         * Updates a particular model.
         * If update is successful, the browser will be redirected to the 'view' page.
         *
         * @param integer $id the ID of the model to be updated
         */
        public function actionUpdate($id)
        {
            $model = $this->loadModel($id);

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['AssignmentShift'])) {
                $model->attributes = $_POST['AssignmentShift'];
                if ($model->save())
                    $this->redirect(array('view', 'id' => $model->id));
            }

            $this->render('update', array(
                'model' => $model,
            ));
        }

        /**
         * Deletes a particular model.
         * If deletion is successful, the browser will be redirected to the 'admin' page.
         *
         * @param integer $id the ID of the model to be deleted
         */
        public function actionDelete($id)
        {
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }

        /**
         * Lists all models.
         */
        public function actionIndex()
        {
            $dataProvider = new CActiveDataProvider('AssignmentShift');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model = new AssignmentShift('search');
            $model->unsetAttributes();  // clear any default values
            // Lấy danh sách ca trực
            $shift_info = $model->getShiftInfo();
            if (isset($_GET['AssignmentShift']))
                $model->attributes = $_GET['AssignmentShift'];
            //Lấy thông tin ca trực.
            $shift_defi = self::getAllShift();
            $this->render('admin', array(
                'model'      => $model,
                'shift_info' => $shift_info,
                'shift_defi' => $shift_defi,
            ));
        }

        /**
         * Returns the data model based on the primary key given in the GET variable.
         * If the data model is not found, an HTTP exception will be raised.
         *
         * @param integer $id the ID of the model to be loaded
         *
         * @return AssignmentShift the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = AssignmentShift::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param AssignmentShift $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'assignment-shift-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }

        /*
         * Chi tiết trong từng ca trực
         */
        public function actiondetailShift()
        {

            if (isset($_POST)) {
                if (isset($_POST['AssignmentShift']['title'])) {
                    $title = $_POST['AssignmentShift']['title'];
                    $name  = explode(' (', $title);
                }
                if (isset($_POST['AssignmentShift']['shift_date_admin'])) {
                    $shift_date_admin = $_POST['AssignmentShift']['shift_date_admin'];
                }
                if (isset($_POST['AssignmentShift']['user_id'])) {
                    $user_id = $_POST['AssignmentShift']['user_id'];
                }


                if (isset($name[0])) {
                    $shift = Shift::model()->findByAttributes(array('name' => $name[0]));

                    $model = new AssignmentShift();

                    return $this->render('detail_shift', array(
                        'model'            => $model,
                        'id'               => $name[0],
                        'shift_date_admin' => $shift_date_admin,
                        'user_id'          => $user_id,
                        'shift_id'         => $shift->id,
                    ));
                }
            }
        }


        /**
         * Thêm một ca trực.
         */
        public function actionAddShift()
        {
            $result   = FALSE;
            $array_id = Yii::app()->getRequest()->getParam('array_id', array());
            if (!empty($array_id)) {
                foreach ($array_id as $id) {
                    $command = Yii::app()->db->createCommand('DELETE FROM cc_tbl_assignment_shift where id=:id')->bindValue(':id', $id);
                    if ($command->query()) {
                        $result = TRUE;
                    }
                }
            }
            echo json_encode($result);

        }

        /**
         * Thêm 1 ca trực trong detail ca trực.
         */
        public function actionAddShiftDetail()
        {
            $msg             = "Thêm thành công!!";
            $array_add_shift = array();
            if (Yii::app()->request->isAjaxRequest) {
                if (!empty($_POST['AssignmentShift']['user_id'])) {

                    foreach ($_POST['AssignmentShift']['user_id'] as $user_id) {

                        if ($user_id != '') {
                            $array_add_shift[] = array(
                                'user_id'     => $user_id,
                                'unit_id'     => 1,
                                'shift'       => $_POST['add_shift_id'],
                                'shift_date'  => $_POST['add_shift_date_admin'],
                                'note'        => $_POST['AssignmentShift']['note'],
                                'create_date' => date('Y-m-d'),
                                'approved_id' => Yii::app()->user->id,
                            );
                        }
                    }
                    if (!empty($array_add_shift)) {
                        $build               = new CDbCommandBuilder(Yii::app()->db->schema);
                        $command_license_key = $build->createMultipleInsertCommand('cc_tbl_assignment_shift', $array_add_shift);
                        $command_license_key->execute();
                    }
                }


                $array_return['message'] = $msg;
                echo json_encode($array_return);
                Yii::app()->end();
            }
        }

        /**
         * Lấy danh sách Ktv theo đơn vị bằng aJax.
         */
        public function actionGetUserByUnit()
        {
            $unit_id                  = Yii::app()->getRequest()->getParam("unit_id", FALSE);
            $criteria_unit            = new CDbCriteria();
            $criteria_unit->select    = "cuu.user_id as user_id";
            $criteria_unit->condition = "t.id='" . $unit_id . "' or t.parent_id='" . $unit_id . "'";
            $criteria_unit->join      = "INNER JOIN cc_tbl_unit_user cuu ON cuu.unit_id =t.id";
            $unit                     = Unit::model()->findAll($criteria_unit);
            $unit_array               = array();
            if (!empty($unit)) {
                foreach ($unit as $key => $value) {
                    if (!in_array($value->id, $unit_array)) {
                        array_push($unit_array, $value->user_id);
                    }
                }
            }
            $stt = 0;

            $string_unit = "";
            foreach ($unit_array as $key => $value) {
                if ($stt == 0) {
                    $string_unit = "'" . $value . "'";
                } else {
                    $string_unit .= ",'" . $value . "'";
                }
                $stt++;
            }
            $criteria = new CDbCriteria();
            if (!empty($string_unit)) {
                $criteria->condition = "parent_id IN (" . $string_unit . ")";
            } else {
                $criteria->condition = "parent_id =99999";
            }
            $data   = User::model()->findAll($criteria);
            $return = CHtml::listData($data, 'id', 'username');

            foreach ($return as $k => $v) {
                echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
            }
        }

        /**
         * Lấy danh sách thông tin ca trực.
         */
        public function getAllShift()
        {
            $data = Shift::model()->findAll();

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


    }
