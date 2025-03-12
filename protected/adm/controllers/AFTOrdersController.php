<?php

    class AFTOrdersController extends AController
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
//			'postOnly + delete', // we only allow deletion via POST request
                'rights',
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
            $model = $this->loadModel($id);
            $model_user = AFTUsers::getUserByContract($model->contract_id);
            $model_logs    = new AFTLogs();
            $model_details = new AFTOrderDetails();

            $this->render('view', array(
                'model'         => $model,
                'model_user'    => $model_user,
                'model_logs'    => $model_logs,
                'model_details' => $model_details,
            ));
        }

        /**
         * Creates a new model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         */
        public function actionCreate()
        {
            $model = new AFTOrders;

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['AFTOrders'])) {
                $model->attributes = $_POST['AFTOrders'];
                if ($model->save())
                    $this->redirect(array('view', 'id' => $model->id));
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

            if (isset($_POST['AFTOrders'])) {
                $model->attributes = $_POST['AFTOrders'];
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
            $dataProvider = new CActiveDataProvider('AFTOrders');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model = new AFTOrders('search');
            $model->unsetAttributes();  // clear any default values
            $model->type = AFTOrders::TYPE_SIM;
            if (isset($_REQUEST['AFTOrders']))
                $model->attributes = $_REQUEST['AFTOrders'];

            $this->render('admin', array(
                'model' => $model,
            ));
        }

        /**
         * Returns the data model based on the primary key given in the GET variable.
         * If the data model is not found, an HTTP exception will be raised.
         *
         * @param integer $id the ID of the model to be loaded
         *
         * @return AFTOrders the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = AFTOrders::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param AFTOrders $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'aftorders-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }


        /**
         * Hiện popup phân công.
         */
        public function actionShowPopupConfirmStatus()
        {
            $id     = Yii::app()->request->getParam('id', FALSE);
            $status = Yii::app()->request->getParam('status', FALSE);
            $data   = '';
            if ($id && $status) {
                $orders = AFTOrders::model()->findByAttributes(array('id' => $id));
                if ($status >= $orders->status) {

                    $user = AFTUsers::getUserByContract($orders->contract_id);
                    if($status == AFTOrders::ORDER_APPROVED && $user->user_type == AFTUsers::USER_TYPE_CTV){
                        $data = $this->renderPartial('_popup_approved_status',
                            array(
                                'model'  => $orders,
                                'status' => $status,
                                'show'   => TRUE,
                            )
                        );
                    }else{
                        $data = $this->renderPartial('_popup_confirm_status',
                            array(
                                'model'  => $orders,
                                'status' => $status,
                                'show'   => TRUE,
                            )
                        );
                    }
                } else {
                    $data = $this->renderPartial('_popup_confirm_status',
                        array(
                            'model'  => $orders,
                            'status' => $status,
                            'show'   => FALSE,
                        )
                    );
                }
            }


            echo $data;
            exit();
        }


        /**
         * Hiện popup phân công.
         */
        public function actionShowPopupAssign()
        {
            $order_id = Yii::app()->request->getParam('order_id', FALSE);
            if ($order_id) {
                $orders = AFTOrders::model()->findByAttributes(array('id' => $order_id));
            }
            $data = $this->renderPartial('_popup_assign',
                array('model' => $orders)
            );
            echo $data;
            exit();
        }

        /**
         * Hiện popup phân công.
         */
        public function actionShowPopupSendSms()
        {
            $data = '';
            $order_id = Yii::app()->request->getParam('order_id', FALSE);
            if ($order_id) {
                $orders = AFTOrders::model()->findByAttributes(array('id' => $order_id));

                $data = $this->renderPartial('_popup_send_sms', array(
                    'model' => $orders
                ), TRUE);
            }
            echo $data;
            exit();
        }


        /**
         * Hiện popup phân công.
         */
        public function actionShowPopupJoinKit()
        {
            $order_id = Yii::app()->request->getParam('order_id', FALSE);
            $total    = 0;
            $first    = TRUE;
            $files    = new AFTFiles();
            if ($order_id) {
                $orders = AFTOrders::model()->findByAttributes(array('id' => $order_id));
                if ($orders) {
                    $orders->store_id = AFTOrders::STORE_ID_DEFAULT;
                    $total = AFTOrders::getTotalSim($order_id);
                    if ($orders->total_success != '' && $orders->total_success != 0) {
                        $first = FALSE;
                        $total = $total - $orders->total_success;
                    }
                }
            }
            $data = $this->renderPartial('_popup_join_kit',
                array(
                    'model' => $orders,
                    'total' => $total,
                    'files' => $files,
                    'first' => $first,
                )
            );
            echo $data;
            exit();
        }

        /**
         * Phân công
         */
        public function actionAssign()
        {
            $order_id = Yii::app()->request->getParam('order_id', FALSE);
            $user_id  = Yii::app()->request->getParam('user_id', FALSE);
            $return   = FALSE;
            if ($order_id && $user_id) {
                $orders          = AFTOrders::model()->findByAttributes(array('id' => $order_id));
                $orders->user_id = $user_id;
                if ($orders->status < AFTOrders::ORDER_ASSIGNED) {
                    $orders->status = AFTOrders::ORDER_ASSIGNED;
                }
                if ($orders->update()) {
                    $return = TRUE;
                }
            }
            echo CJSON::encode($return);
            exit();
        }

        /**
         * Action Set Status
         */
        public function actionSetStatus()
        {
            $result          = FALSE;
            $id              = Yii::app()->getRequest()->getParam('id', FALSE);
            $status          = Yii::app()->getRequest()->getParam('status', FALSE);
            $model           = $this->loadModel($id);
            $model->scenario = 'setStatus';
            if ($model) {
                $old_status    = $model->status;
                $model->status = $status;
                if ($model->update()) {
                    $model_logs                   = new AFTLogs();
                    $model_logs->object_name      = AFTLogs::OBJECT_ORDER;
                    $model_logs->object_id        = $id;
                    $model_logs->data_json_before = CJSON::encode(array('status' => $old_status));
                    $model_logs->data_json_after  = CJSON::encode(array('status' => $status));
                    $model_logs->create_time      = date('Y-m-d H:i:s');
                    $model_logs->active_by        = Yii::app()->user->id;
                    if ($model_logs->save()) {
                        if ($status == AFTOrders::ORDER_APPROVED) {
                            $user = AFTUsers::getUserByContract($model->contract_id);
                            $mt_content = Yii::t('tourist/mt_content', 'message_confirm_order', array(
                                '{order_code}'    => $model->code,
                                '{orderer_name}'  => $model->orderer_name,
                                '{province_name}' => AProvince::model()->getProvinceVnp($model->province_code),
                            ));
                            if($user && $user->user_type != AFTUsers::USER_TYPE_CTV){
                                self::sendSmsDefaukt($model->orderer_phone, $mt_content, $model->code, 'confirm_order');
                            }
                            $model->sendNotification();
                        }
                        $result = TRUE;
//                        Yii::app()->user->setFlash('success', Yii::t('adm/label', 'alert_success'));
                    }
                } else {
                    $result = FALSE;
//                    Yii::app()->user->setFlash('error', Yii::t('adm/label', 'alert_fail'));
                }
            }

            echo CJSON::encode($result);
            exit();
        }

        /**
         * Lấy định dạng file mẫu.
         */
        public function actionGetFileTemplate()
        {
            $filename = '../adm/themes/gentelella/upload_temp/sim_upload.txt';

            header("Content-Length: " . filesize($filename));
            header('Content-Type: application/octet-stream');
            header("Content-Disposition: attachment; filename='sim_upload.txt'");
            readfile($filename);
        }

        public function actionUploadSim()
        {
            $data = FALSE;
            Yii::import('ext.UploadHandler.UploadHandler');
            $uploadedFile = CUploadedFile::getInstanceByName('AFTFiles[filename]');
            if (isset($_POST['AFTFiles']['order_id'])) {
                $order_id = $_POST['AFTFiles']['order_id'];
                $data     = $this->renderPartial('_popup_pending_join', array('order_id' => $order_id, 'show' => FALSE));
                if ($uploadedFile) {
                    $dir_upload = 'tourist';
                    $dir_root   = dirname(Yii::app()->request->scriptFile);
                    $dir_root   = str_replace('adm', '', $dir_root);
                    $DS         = DIRECTORY_SEPARATOR;

                    $upload_dir = $dir_root . $DS . 'uploads' . $DS . $dir_upload . $DS . 'sim' . $DS . $order_id . $DS . date('Y') . $DS . date('m') . $DS . date('d');
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, TRUE);
                    }

                    $destination_filename = explode('.', $uploadedFile->name)[0] . time();
                    $destination_fileext  = explode('.', $uploadedFile->name)[1];

                    if (copy($uploadedFile->tempName, $upload_dir . $DS . $destination_filename . '.' . $destination_fileext)) {
                        $order = AFTOrders::model()->findByAttributes(array('id' => $order_id));
                        if ($order) {
                            $files              = new AFTFiles();
                            $files->object      = 'AFTOrders';
                            $files->object_id   = $order_id;
                            $files->file_name   = $destination_filename;
                            $files->file_ext    = $destination_fileext;
                            $files->file_size   = filesize($uploadedFile->tempName);
                            $files->folder_path = 'uploads/' . $dir_upload . '/' . 'sim/' . $order_id . '/' . date('Y') . '/' . date('m') . '/' . date('d');
                            $files->create_date = date('Y-m-d H:i:s');
                            $files->status      = 0;// Khởi tạo.
                            $files->scenario    = 'upload';
                            $data               = $this->renderPartial('_popup_confirm_join_kit', array('files' => $files, 'order_id' => $order_id, 'length_serial' => $_POST['AFTFiles']['length_serial']));

                        }
                        if(isset($_POST['AFTOrders']['store_id'])){
                            $order->store_id = $_POST['AFTOrders']['store_id'];
                            $order->update();
                        }
                    }

                }
            }

            echo $data;
            exit();
        }

        public function actionUploadSimAfterConfirm()
        {
            $object        = Yii::app()->getRequest()->getParam('object', FALSE);
            $order_id      = Yii::app()->getRequest()->getParam('order_id', FALSE);
            $object_id     = Yii::app()->getRequest()->getParam('object_id', FALSE);
            $file_name     = Yii::app()->getRequest()->getParam('file_name', FALSE);
            $file_ext      = Yii::app()->getRequest()->getParam('file_ext', FALSE);
            $file_size     = Yii::app()->getRequest()->getParam('file_size', FALSE);
            $length_serial = Yii::app()->getRequest()->getParam('length_serial', FALSE);
            $folder_path   = Yii::app()->getRequest()->getParam('folder_path', FALSE);
            $data          = array();

            if ($object && $order_id && $object_id && $file_name && $file_ext && $file_size && $folder_path && $length_serial) {

                $order = AFTOrders::model()->findByAttributes(array('id' => $order_id));
                if ($order) {
                    $order->status      = AFTOrders::ORDER_JOIN_KIT;
                    $order->data_status = 0;
                    $files              = AFTFiles::model()->findByAttributes(array('object_id' => $order_id));
//                            if (!$files) {
                    $files = new AFTFiles();
//                            }
                    $files->object      = 'AFTOrders';
                    $files->object_id   = $order_id;
                    $files->file_name   = $file_name;
                    $files->file_ext    = $file_ext;
                    $files->file_size   = $file_size;
                    $files->folder_path = $folder_path;
                    $files->create_date = date('Y-m-d H:i:s');
                    $files->status      = 0;// Khởi tạo.
                    $files->scenario    = 'upload';
                    if ($files->save() && $order->update()) {

                        $mt_content = Yii::t('tourist/mt_content', 'message_start_join_kit', array(
                            '{order_code}'    => $order->code,
                            '{orderer_name}'  => $order->orderer_name,
                            '{province_name}' => AProvince::model()->getProvinceVnp($order->province_code),
                        ));
                        self::sendSmsDefaukt($order->orderer_phone, $mt_content, $order->code, 'upload_sim');
                        $data = $this->renderPartial('_popup_pending_join', array('order_id' => $order_id, 'show' => TRUE, 'length_serial' => $length_serial));
                    }
                }
            }
            echo $data;
            exit();
        }

        /**
         * Send SMS deatail
         */
        public
        function actionSendSMS()
        {
            $msisdn     = Yii::app()->request->getParam('msisdn', FALSE);
            $mt_content = Yii::app()->request->getParam('content', FALSE);
            $order_code = Yii::app()->request->getParam('order_code', FALSE);
            $result     = FALSE;
            $logMsg     = array();
            $logMsg[]   = array('Start Send MT tourist order' . $order_code . ' Log', 'Start process:' . __LINE__, 'I', time());

            //send MT
            $flag = Utils::sentMtVNP($msisdn, $mt_content, $mtUrl, $http_code);
            if ($flag) {
                $result   = TRUE;
                $logMsg[] = array("msisdn:{$msisdn}", 'SentMT ok:' . __LINE__, 'T', time());
                $logMsg[] = array($mt_content, 'msgBody:' . __LINE__, 'T', time());
            } else {
                $logMsg[] = array("msisdn:{$msisdn}", "SentMT Fail:", 'T', time());
                $logMsg[] = array($mt_content, 'msgBody:' . __LINE__, 'T', time());
            }

            $logMsg[] = array($mtUrl, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());

            $logFolder = "Log_send_mt_tourist/" . date("Y/m/d");

            $logObj = ATraceLog::getInstance($logFolder);
            $logObj->setLogFile($order_code . '.log');
            $logMsg[] = array($order_code, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            echo CHtml::encode($result);
            exit();
        }

        public
        static function sendSmsDefaukt($msisdn, $mt_content, $order_code, $type)
        {

            $result   = FALSE;
            $logMsg   = array();
            $logMsg[] = array('Start Send MT tourist order' . $order_code . ' Log', 'Start process:' . __LINE__, 'I', time());

            //send MT
            $flag = self::sentMtVNP($msisdn, $mt_content, $mtUrl, $http_code);
            if ($flag) {
                $result   = TRUE;
                $logMsg[] = array("msisdn:{$msisdn}", 'SentMT ok:' . __LINE__, 'T', time());
                $logMsg[] = array($mt_content, 'msgBody:' . __LINE__, 'T', time());
            } else {
                $logMsg[] = array("msisdn:{$msisdn}", "SentMT Fail:", 'T', time());
                $logMsg[] = array($mt_content, 'msgBody:' . __LINE__, 'T', time());
            }

            $logMsg[] = array($mtUrl, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());

            $logFolder = "Log_send_mt_tourist/" . date("Y/m/d") . '/' . $type;

            $logObj = ATraceLog::getInstance($logFolder);
            $logObj->setLogFile($order_code . '.log');
            $logMsg[] = array($order_code, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            return TRUE;
        }

        public
        static function sentMtVNP($msisdn, $msgBody, &$api_url, &$http_code = '', &$rs = '')
        {
            $msisdn = CFunction_MPS::makePhoneNumberStandard($msisdn);
            $mtseq  = time() . rand(1000, 9999);

            $smsMtRequest = array(
                'username'   => 'freedoo01',
                'password'   => 'CentEch2o17FREEdoo',
                'dest'       => $msisdn,
                'msgtype'    => 'Text',
                'cpid'       => '',
                'src'        => 'FREEDOO',
                'procresult' => 0,
                'mtseq'      => $mtseq,
                'msgbody'    => $msgBody,
                'serviceid'  => '',
            );

            $api_url = str_replace('?', '', $GLOBALS['config_common']['api']['sms_gw']) . '?' . http_build_query($smsMtRequest);

            $rs = Utils::cUrlGet($api_url, 10, $http_code);
            if ($http_code == '200' || $rs == '200') {
                return TRUE;
            }

            return FALSE;
        }

        public function actionUploadFileAcceptPayment()
        {
            $result = array(
                'error' => '',
                'success' => ''
            );

            if(isset($_POST['AFTOrders']['id'])){
                $order_id = $_POST['AFTOrders']['id'];
                $payment_method = $_POST['AFTOrders']['payment_method'];
                $model = AFTOrders::model()->findByPk($order_id);
                $model->payment_method = $payment_method;
                $model->save(false);

                date_default_timezone_set('Asia/Ho_Chi_Minh');

                $dir_upload = Yii::app()->params->upload_dir_path . 'tourist/torders';
                $time = date("Ymdhis");
                $DS = DIRECTORY_SEPARATOR;
                $folder = 'accepted_payment_files';
                $object = AFTOrders::OBJECT_FILE_ACCEPT_PAYMENT;

                $ext = array('jpg', 'jpeg', 'png', 'pdf', 'csv');
                $size = 999 * 1024 * 1024;

                if ($uploadedFile = CUploadedFile::getInstanceByName('AFTOrders[accepted_payment_files]')) {

                    if($uploadedFile->size > $size){
                        $result['error'] = Yii::t('tourist/label','upload_file_invalid_size');
                    }
                    $file_ext = pathinfo($uploadedFile->name, PATHINFO_EXTENSION);
                    if(is_array($ext)){
                        if(!in_array($file_ext, $ext)){
                            $result['error'] = Yii::t('tourist/label','upload_file_invalid_ext',array(
                                '{ext}' => implode(', ',$ext)
                            ));
                        }
                    }else{
                        if($file_ext != $ext){
                            $result['error'] = Yii::t('tourist/label','upload_file_invalid_ext',array(
                                '{ext}' => $ext
                            ));
                        }
                    }
                    if(empty($result['error'])){
                        $fileTemporary = $uploadedFile->tempName;

                        // init folder contain file
                        $destinationFolder = $dir_upload . $DS . $folder . $DS. $time . $DS;

                        // check and create folder;
                        if (!file_exists($destinationFolder)) {
                            mkdir($destinationFolder, 0777, TRUE);
                        }

                        // copy temporary file to image file folder and delete in temporary folder
                        if($uploadedFile->saveAs($destinationFolder . $uploadedFile->name)){
                            //save model
                            $file = AFTFiles::model()->findByAttributes(array(
                                'object' => $object,
                                'object_id' => $order_id
                            ));
                            if (!$file) {
                                $file = new AFTFiles();
                                $file->object = $object;
                                $file->object_id = $order_id;
                            }
                            $file->file_name = explode('.', $uploadedFile->name)[0];
                            $file->file_ext = explode('.', $uploadedFile->name)[1];
                            $file->file_size = filesize($destinationFolder . $uploadedFile->name);
                            $file->folder_path = 'uploads/' . 'tourist/torders' . $DS . $folder . $DS. $time . $DS;
                            $file->status = 0;
                            $file->save(false);

                            $result['error'] = '';
                            $result['success'] = true;
                        }else{
                            $result['error'] = 'Upload File không thành công';
                            $result['success'] = true;
                        }
                    }
                }
            }
            echo CJSON::encode($result);
            Yii::app()->end();
        }

    }