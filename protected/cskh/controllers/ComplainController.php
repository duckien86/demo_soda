<?php

    class ComplainController extends AController
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
         * Creates a new model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         */
        public function actionCreate()
        {
            $model = new Complain;

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['Complain'])) {
                $model->attributes = $_POST['Complain'];
                if ($model->save())
                    $this->redirect(array('admin'));
            }

            $this->render('create', array(
                'model' => $model,
            ));
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

            if (isset($_POST['Complain'])) {
                $model->attributes = $_POST['Complain'];
                if ($model->save())
                    $this->redirect(array('admin'));
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
            $dataProvider = new CActiveDataProvider('Complain');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $start_date    = date('d/m/Y'); // Set default date là ngày hiện tại.
            $end_date      = date('d/m/Y', strtotime('+1 days')); // Set default date là ngày hiện tại.
            $model         = new Complain();
            $model_r       = new Complain();
            $categories_id = 0;
            $unit_id       = 0;

            if (isset($_POST['Complain'])) {

                $start_date = $_POST['Complain']['start_date'];
                $end_date   = $_POST['Complain']['end_date'];
            }
            if (isset($_POST['categories_id']) && ($_POST['categories_id'] != '')) {
                $categories_id          = $_POST['categories_id'];
                $model_r->categories_id = $categories_id;
            }
            if (isset($_POST['unit_id']) && ($_POST['unit_id'] != '')) {
                $unit_id          = $_POST['unit_id'];
                $model_r->unit_id = $unit_id;
            }

            $model_r->start_date = $start_date;
            $model_r->end_date   = $end_date;
            $user                = $model->getListKTVComplain($start_date, $end_date);
            $user_data           = $model->getListKtvByParent($unit_id);

            foreach ($user_data as $key_free => $value_free) {
                foreach ($user as $key => $value) {
                    if ($value->id == $key_free && $value->total_complain_by_ktv != 0) {
                        $user_data[$key_free]['total'] = $value->total_complain_by_ktv;
                    }
                }
            }

            $start_date = date('Y-m-d', strtotime(str_replace('/', '-', $start_date))) . ' 00:00:00';
            $end_date   = date('Y-m-d', strtotime(str_replace('/', '-', $end_date))) . ' 23:59:59';
            //Lấy danh sách chương trình và tổng số cuộc gọi chưa giải quyết.


            $criteria         = new CDbCriteria;
            $criteria->select = "t.categories_id,count(t.id) as total_of_categories_callback";

            if (isset($categories_id) && $categories_id != 0) {
                $criteria->condition = "t.call_time between '" . $start_date . "' and '" . $end_date . "' and t.user_id is null and cc.id=" . $categories_id;
            } else {
                $criteria->condition = "t.call_time between '" . $start_date . "' and '" . $end_date . "' and t.user_id is null and t.status = " . Complain::CALLBACK;
            }
            $criteria->join  = "LEFT JOIN cc_tbl_categories cc ON cc.id = t.categories_id ";
            $criteria->group = "t.categories_id";

            $data_callback = Complain::model()->findAll($criteria);


            //Pending.
            $criteria_pending         = new CDbCriteria;
            $criteria_pending->select = "t.categories_id,count(t.id) as total_of_categories_pending";

            if (isset($categories_id) && $categories_id != 0) {
                $criteria_pending->condition = "t.call_time between '" . $start_date . "' and '" . $end_date . "' and t.user_id is null and cc.id=" . $categories_id;
            } else {
                $criteria_pending->condition = "t.call_time between '" . $start_date . "' and '" . $end_date . "' and t.user_id is null";
            }
            $criteria_pending->join  = "LEFT JOIN cc_tbl_categories cc ON cc.id = t.categories_id ";
            $criteria_pending->group = "t.categories_id";

            $data_pending = Complain::model()->findAll($criteria_pending);


            //Lấy tổng số cuộc gọi của tất cả chương trình
            $criteria_called = new CDbCriteria;

            $criteria_called->select = "t.categories_id,count(t.id) as total_of_categories_called";
            if (isset($categories_id) && $categories_id != 0) {
                $criteria_called->condition = "t.call_time between '" . $start_date . "' and '" . $end_date . "' and t.user_id is not null and t.categories_id= " . $categories_id;
            } else {
                $criteria_called->condition = "t.call_time between '" . $start_date . "' and '" . $end_date . "' and t.user_id is not null";
            }
            $criteria_called->join = "LEFT JOIN cc_tbl_categories cc ON cc.id = t.categories_id ";
            $criteria->group       = "t.categories_id";
            $data_called           = Complain::model()->findAll($criteria_called);

            $data = self::actionDataStatic($data_pending, $data_callback, $data_called);
            $this->render('admin', array(
                'model_r'    => $model_r,
                'model'      => $data,
                'start_date' => $start_date,
                'end_date'   => $end_date,
                'user_data'  => $user_data,
            ));
        }

        /**
         * Xử lý data;
         */
        public
        function actionDataStatic($data_pending, $data_callback, $data_called)
        {
            $data = array();
            if (!empty($data_pending)) {
                foreach ($data_pending as $pending) {
                    $data[$pending->categories_id]['categories_id']   = $pending->categories_id;
                    $data[$pending->categories_id]['total_pending']   = $pending->total_of_categories_pending;
                    $data[$pending->categories_id]['categories_name'] = $pending->categories_name;
                }
            }
            if (!empty($data_callback)) {
                foreach ($data_callback as $callback) {
                    $data[$callback->categories_id]['categories_id']   = $callback->categories_id;
                    $data[$callback->categories_id]['total_callback']  = $callback->total_of_categories_callback;
                    $data[$callback->categories_id]['categories_name'] = $callback->categories_name;
                }
            }

            if (!empty($data_called)) {
                foreach ($data_called as $called) {
                    $data[$called->categories_id]['categories_id']   = $called->categories_id;
                    $data[$called->categories_id]['total_called']    = $called->total_of_categories_called;
                    $data[$called->categories_id]['categories_name'] = $called->categories_name;
                }
            }

            return $data;
        }

        /**
         *  View phân công từng cuộc gọi cho từng người.
         */
        public
        function actionAdminAssignment()
        {
            $show  = FALSE;
            $model = new Complain();
            $this->render('admin_assignment', array(
                'model' => $model,
            ));
        }


        public
        function actionComplainList()
        {
            $model = new Complain('search');
            $model->unsetAttributes();  // clear any default values
            $model->start_date = date('d/m/Y');
            $model->end_date   = date('d/m/Y', strtotime('+1days'));
            if (isset($_GET['Complain'])) {
                $model->attributes = $_GET['Complain'];
            }

            $this->render('complain_list', array(
                'model' => $model,
            ));
        }

        /*
         * Lấy dữ liệu ajax form detail
         */
        public
        function actionAjaxSubmitAssignmentForm()
        {


            $model = new Complain('search');
            $show  = FALSE;
            $model->unsetAttributes();  // clear any default values
            if (isset($_POST['Complain'])) {
                $model->attributes = $_POST['Complain'];
                $show              = TRUE;

                return $this->render('ajax_admin_assignment',
                    array(
                        'model' => $model,
                        'show'  => $show
                    ));
            }
        }

        /**
         * Phân lịch cho từng khai thác viên.
         */
        public
        function actionSubmitAssignFormDetail()
        {
            $model = new Complain();
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'assignment-complain-form') {
                if ($_POST['Complain']['start_date'] && $_POST['Complain']['end_date']) {

                    $model->start_date = $_POST['Complain']['start_date'];
                    $model->end_date   = $_POST['Complain']['end_date'];
                }
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            if (isset($_POST['Complain']) && !empty($_POST['Complain']['categories_id'])) {

                $criteria            = new CDbCriteria;
                $start_date          = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['Complain']['start_date']))) . ' 00:00:00';
                $end_date            = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['Complain']['end_date']))) . ' 23:59:59';
                $criteria->select    = "*";
                $criteria->condition = "t.call_time between ('" . $start_date . "') 
                                        and ('" . $end_date . "') 
                                        and t.user_id is null and t.categories_id =" . $_POST['Complain']['categories_id'];

                $criteria->join = "INNER JOIN cc_tbl_categories cc ON cc.id = t.categories_id ";
                $data           = Complain::model()->findAll($criteria);

                $stt = 0;
                if (isset($_POST['Complain']['number_assignment']) && $_POST['Complain']['number_assignment'] != '') {
                    foreach ($data as $key => $value) {
                        $stt++;
                        if ($stt <= $_POST['Complain']['number_assignment']) {
                            $data[$key]->user_id = $_POST['Complain']['user_id'];
                            $data[$key]->update();
                        }
                    }
                }

                return $this->actionAdmin();

            } else {
                return $this->actionAdmin();
            }
        }

        public
        function actionPopupDetail($categories_id)
        {
            $model = new Complain('search');
            $model->unsetAttributes();  // clear any default values

            if (isset($_GET['Complain'])) {
                $model->attributes = $_GET['Complain'];
            }

            $data = $this->renderPartial('admin_assignment', array(
                'model' => $model,
            ));
        }


        public
        function actionAssignmentUser()
        {
            $msg                 = "Thành công";
            $array_complain_user = array();
            if (Yii::app()->request->isAjaxRequest) {
                if (isset($_POST['complain'])) {
                    if ($_POST['complain'] && $_POST['Complain']['ktv']) {
                        foreach ((array)$_POST['complain'] as $complain_id) {
                            Yii::app()->db->createCommand('UPDATE cc_tbl_complain SET user_id=:user_id where id=:id')
                                ->bindValue(':id', $complain_id)->bindValue(':user_id', $_POST['Complain']['ktv'])->query();
                        }
                    }
                    $model          = new Complain('search');
                    $model->user_id = $_POST['Complain']['ktv'];
                    $data           = $this->renderPartial('ajax_assignment_detail',
                        array('model' => $model)
                    );
                    echo $data;
                }
                Yii::app()->end();
            }
        }


        /**
         * Lấy chi tiết Cuộc gọi chưa xử theo thể loại (popup Ajax).
         */

        public
        function actionDetailByCate()
        {
            $cate_id    = Yii::app()->getRequest()->getParam('cate_id', 0);
            $start_date = Yii::app()->getRequest()->getParam('start_date', 0);
            $end_date   = Yii::app()->getRequest()->getParam('end_date', 0);

            $start_date = date('Y-m-d', strtotime(str_replace('/', '-', $start_date))) . ' 00:00:00';
            $end_date   = date('Y-m-d', strtotime(str_replace('/', '-', $end_date))) . ' 23:59:59';

            if (!empty($cate_id)) {
                $criteria         = new CDbCriteria;
                $criteria->select = " DISTINCT(t.id), t.*,c.name as categories_name, u.name as provice ";
                if (SUPER_ADMIN || ADMIN) {
                    $criteria->condition = " t.categories_id=" . $cate_id . " and user_id is null and t.call_time between ('" . $start_date . "') and ('" . $end_date . "')";
                } else {
                    $criteria->condition = "cc.monitor_id =" . Yii::app()->user->id . " and  t.categories_id=" . $cate_id . " and user_id is null and t.call_time between ('" . $start_date . "') and ('" . $end_date . "')";
                }

                $criteria->join = "LEFT JOIN cc_tbl_categories_unit cc ON cc.categories_id = t.categories_id 
                                    LEFT JOIN cc_{{unit}} u ON u.id = cc.unit_id
                                    LEFT JOIN cc_{{categories}} c ON c.id = cc.categories_id
                                    ";

                $model = Complain::model()->findAll($criteria);

                $title = $this->getAttribute();
                $data  = $this->renderPartial(
                    'admin_detail_by_cate',
                    array('model' => $model, 'id' => $cate_id, 'title' => $title)
                );
                echo $data;
                exit();
            }

        }

        /**
         * Lấy tiêu đề ở bảng không dùng Criteria và yii gridview.
         */
        public
        function getAttribute()
        {
            return array(
                'msisdn'          => 'Số điện thoại',
                'content'         => 'Nội dung khiếu nại',
                'call_time'       => 'Thời gian khiếu nại',
                'categories_name' => 'Chương trình',
                'priority'        => 'Mức độ ưu tiên',
                'status'          => 'Trạng thái',
                'provice'         => 'Đơn vị',

            );
        }


        /**
         * Returns the data model based on the primary key given in the GET variable.
         * If the data model is not found, an HTTP exception will be raised.
         *
         * @param integer $id the ID of the model to be loaded
         *
         * @return Complain the loaded model
         * @throws CHttpException
         */
        public
        function loadModel($id)
        {
            $model = Complain::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param Complain $model the model to be validated
         */
        protected
        function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'complain-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }

        /**
         * Ajax kiểm tra số cuộc gọi nhiều hơn số cuộc gọi của chương trình.
         */
        public function actiongetNumComplainByCate()
        {
            $cate_id    = Yii::app()->getRequest()->getParam('cate_id', 0);
            $query_date = Yii::app()->getRequest()->getParam('query_date', date('Y-m-d'));
            $start_date = date('Y-m-d', strtotime(str_replace('/', '-', $query_date))) . ' 00:00:00';
            $end_date   = date('Y-m-d', strtotime(str_replace('/', '-', $query_date))) . ' 23:59:59';

            $criteria = new CDbCriteria;

            $criteria->select    = "count(*) as total_of_categories";
            $criteria->condition = "user_id is null and categories_id =" . $cate_id . " and call_time between ('" . $start_date . "') and ('" . $end_date . "')";
            $data                = Complain::model()->find($criteria);
            echo $data->total_of_categories;
        }

        public function getCateById($id)
        {
            $categories = Categories::model()->findByAttributes(array('id' => $id));

            return $categories->name;
        }

        public function actionGetCateByUnit()
        {
            $unit = Yii::app()->getRequest()->getParam('unit_id', 0);

            $criteria            = new CDbCriteria;
            $criteria->select    = "t.*";
            $criteria->condition = "cu.unit_id = " . $unit;
            $criteria->join      = "INNER JOIN cc_{{categories_unit}} cu ON cu.categories_id = t.id";
            $categories          = Categories::model()->findAll($criteria);

            $return = CHtml::listData($categories, 'id', 'name');
            foreach ($return as $k => $v) {
                echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
            }
        }

        public function actionSetResultAjax()
        {
            $complain_id  = Yii::app()->getRequest()->getParam('complain_id', FALSE);
            $content      = Yii::app()->getRequest()->getParam('content', FALSE);
            $complain_log = ComplainLog::model()->findByAttributes(array('complain_id' => $complain_id));
            if (!$complain_log) {
                $complain_log = new ComplainLog();
            }
            $complain_log->complain_id = $complain_id;
            $complain_log->content     = $content;
            $complain_log->create_time = date('Y-m-d');
//
            if ($complain_log->save()) {
                echo TRUE;
            }
            exit();
        }


        /**
         * Action change status
         */
        public function actionChangeStatus()
        {
            $result = FALSE;
            $id     = $_POST['pk'];
            $status = $_POST['value'];
            $model  = Complain::model()->findByAttributes(array('id' => $id));
            if ($model) {
                $model->status = $status;
                if ($status == Complain::CALLBACK) {
                    $model->user_id = NULL;
                }
                if ($model->save()) {
                    $result = TRUE;
                    if ($status == Complain::CALLBACK && !SUPER_ADMIN && !ADMIN && !Yii::app()->user->checkAccess("LeaderShift")) {
                        Yii::app()->user->setFlash('success', 'Cuộc gọi đã được chuyển sang ktv thuộc ca trực khác xử lý !');
                    } else {
                        Yii::app()->user->setFlash('success', 'Update trạng thái thành công !');
                    }
                } else {
                    $result = FALSE;
                    Yii::app()->user->setFlash('error', Yii::t('adm/label', 'alert_fail'));
                }
            }
            echo CJSON::encode($result);
            exit();
        }


    }
