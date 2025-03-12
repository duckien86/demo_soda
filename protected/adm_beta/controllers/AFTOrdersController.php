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
            $model_logs    = new AFTLogs();
            $model_details = new AFTOrderDetails();
            $this->render('view', array(
                'model'         => $this->loadModel($id),
                'model_logs'    => $model_logs,
                'model_details' => $model_details,
                'id'            => $id,
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
            if (isset($_GET['AFTOrders']))
                $model->attributes = $_GET['AFTOrders'];

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
                    $data = $this->renderPartial('_popup_confirm_status',
                        array(
                            'model'  => $orders,
                            'status' => $status,
                            'show'   => TRUE,
                        )
                    );
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
            $order_id = Yii::app()->request->getParam('order_id', FALSE);
            if ($order_id) {
                $orders = AFTOrders::model()->findByAttributes(array('id' => $order_id));
            }
            $data = $this->renderPartial('_popup_send_sms',
                array('model' => $orders)
            );
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
                    $model_logs->object_name      = get_class($model);
                    $model_logs->object_id        = $id;
                    $model_logs->object_id        = $id;
                    $model_logs->data_json_before = CJSON::encode(array('status' => $old_status));
                    $model_logs->data_json_after  = CJSON::encode(array('status' => $status));
                    $model_logs->create_time      = date('Y-m-d H:i:s');
                    $model_logs->active_by        = Yii::app()->user->id;
                    if ($model_logs->save()) {
                        if ($status == AFTOrders::ORDER_APPROVED) {
                            $mt_content = Yii::t('tourist/mt_content', 'message_confirm_order', array(
                                '{order_code}'    => $model->code,
                                '{orderer_name}'  => $model->orderer_name,
                                '{province_name}' => AProvince::model()->getProvinceVnp($model->province_code),
                            ));
                            self::sendSmsDefaukt($model->orderer_phone, $mt_content, $model->code, 'confirm_order');
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


                    if (copy($uploadedFile->tempName, $upload_dir . $DS . $uploadedFile->name)) {
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
                            $files->file_name   = explode('.', $uploadedFile->name)[0];
                            $files->file_ext    = explode('.', $uploadedFile->name)[1];
                            $files->file_size   = filesize($uploadedFile->tempName);
                            $files->folder_path = 'uploads/' . $dir_upload . '/' . 'sim/' . $order_id . '/' . date('Y') . '/' . date('m') . '/' . date('d');
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
                                $data = $this->renderPartial('_popup_pending_join', array('order_id' => $order_id, 'show' => TRUE, 'length_serial' => $_POST['AFTFiles']['length_serial']));
                            }
                        }
                    }

                }
            }
            echo $data;
            exit();
        }

        /**
         * Send SMS deatail
         */
        public function actionSendSMS()
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

        public static function sendSmsDefaukt($msisdn, $mt_content, $order_code, $type)
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

        public static function sentMtVNP($msisdn, $msgBody, &$api_url, &$http_code = '', &$rs = '')
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

    }
