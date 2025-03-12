<?php

    class APostsController extends AController
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
            $model = new APosts;

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['APosts'])) {
                $model->attributes = $_POST['APosts'];
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

            if (isset($_POST['APosts'])) {
                $model->attributes = $_POST['APosts'];
                $DS                = DIRECTORY_SEPARATOR;
                $model->image      = str_replace('..' . $DS, '', $model->image);
                if ($model->status) {
                    $model->status = APosts::ACTIVE;
                } else {
                    $model->status = APosts::INACTIVE;
                }
                if ($model->save())
                    $this->redirect(array('view', 'id' => $model->id));
            }

            //view checkbox
            if ($model->status == APosts::ACTIVE) {
                $model->status = 1;
            } else {
                $model->status = 0;
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
            $dataProvider = new CActiveDataProvider('APosts');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model = new APosts('search');
            $model->unsetAttributes();  // clear any default values
            if (isset($_GET['APosts']))
                $model->attributes = $_GET['APosts'];

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
         * @return APosts the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = APosts::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param APosts $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'aposts-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }

        /**
         * Action change status
         */
        public function actionChangeStatus()
        {
            $result = FALSE;
            $id     = Yii::app()->request->getParam('id', FALSE);
            $sso_id = Yii::app()->request->getParam('sso_id', FALSE);

            $status = Yii::app()->request->getParam('status', FALSE);

            $model = APosts::model()->findByPk($id);
            if ($status == APosts::ACTIVE || $status == APosts::PENDING) {
                if ($model->status != APosts::INACTIVE) {
                    if ($model) {
                        $model->status = $status;
                        if ($model->update()) {
                            $result = TRUE;
                        }
                    }
                } else {
                    $data = $this->renderPartial('_popup_status_info', array('id' => $id, 'status' => $status, 'sso_id' => $sso_id));
                    echo $data;
                }
            } else {
                $data = $this->renderPartial('_popup_status_info', array('id' => $id, 'status' => $status, 'sso_id' => $sso_id));
                echo $data;
            }

            echo $result;
            exit();
        }

        /**
         * Action change status
         */
        public function actionUpdateStatusInfo()
        {
            $result = FALSE;
            $info   = Yii::app()->request->getParam('info', FALSE);
            $id     = Yii::app()->request->getParam('id', FALSE);
            $status = Yii::app()->request->getParam('status', FALSE);
            $sso_id = Yii::app()->request->getParam('sso_id', FALSE);

            $model = APosts::model()->findByPk($id);

            if ($model) {
                $model->status = $status;
                $model->note   = $info;
                if ($model->update()) {
                    if ($status == APosts::INACTIVE) {
                        if (ACustomers::setPoint($sso_id, -3, ACustomers::POINT_EVENT_POST, "Bài viết bị admin ẩn")) {
                            $result = TRUE;
                        }
                    } else {
                        if (ACustomers::setPoint($sso_id, +3, ACustomers::POINT_EVENT_POST, "Bài viết được admin phục hồi")) {
                            $result = TRUE;
                        }
                    }
                }
            }

            echo $result;
            exit();
        }

        /**
         * action upload image
         */
        public function actionImages()
        {
            $time       = date("Ymdhis");
            $DS         = DIRECTORY_SEPARATOR;
            $dir_upload = '..' . $DS . 'social' . $DS . 'uploads' . $DS . 'posts' . $DS;
            if (isset($_POST['tempFileName']) && $_POST['tempFileName'] != '') {
                // file temporary
                $fileTemporary = $_POST['tempFileName'];
                // temporary folder
                $temporaryFolder = $dir_upload . '/temp/';
                if (!file_exists($temporaryFolder)) {
                    mkdir($temporaryFolder, 0777, TRUE);
                }
                // get upload file info
                $fileUploadInfo = pathinfo($fileTemporary);


                $fileUploadNewName = Utils::unsign_string($fileUploadInfo['filename']) . '-' . time();

                // init folder contain file
                $destinationFolder = $dir_upload . $time . $DS;

                // check and create folder;
                if (!file_exists($destinationFolder)) {
                    mkdir($destinationFolder, 0777, TRUE);
                    mkdir($destinationFolder . 'images/', 0777, TRUE);
                }

                // folder destination
                $destinationFolder .= 'images/';

                // copy temporary file to image file folder and delete in temporary folder
                copy($temporaryFolder . $fileTemporary, $destinationFolder . $fileUploadNewName . '.' . $fileUploadInfo['extension']);
                unlink($temporaryFolder . $fileTemporary);

                //save model
                $file_name = $destinationFolder . $fileUploadNewName . '.' . $fileUploadInfo['extension'];
                echo CJSON::encode(array(
                    'status'    => TRUE,
                    'file_name' => $file_name,
                    'msg'       => '',
                ));
            } else {
                echo CJSON::encode(array(
                    'status'    => FALSE,
                    'file_name' => '',
                    'msg'       => 'Vui lòng chọn file để upload',
                ));
            }

            exit();
        }

        /**
         * Receive book file, upload via ajax
         *
         * @throws CException if uploading is failure
         */
        public function actionUpload()
        {
            Yii::import('ext.UploadHandler.UploadHandler');

            $DS         = DIRECTORY_SEPARATOR;
            $dir_upload = '..' . $DS . 'social' . $DS . 'uploads' . $DS . 'posts' . $DS;
            $dir_upload = $dir_upload . $DS . 'temp' . $DS;
            if (!is_dir($dir_upload)) {
                mkdir($dir_upload, 0777, TRUE);
            }

            $max_upload_size   = 999 * 1024 * 1024;
            $accept_file_types = 'jpg|jpeg|png|gif';
            $options_arr       = array(
                'script_url'        => Yii::app()->controller->createUrl('aPosts/delete'),
                'upload_dir'        => $dir_upload,
                'upload_url'        => $dir_upload,
                'max_file_size'     => $max_upload_size,
                'accept_file_types' => '/\.(' . $accept_file_types . ')$/i',
            );
            $upload_handler    = new UploadHandler($options_arr);
        }
    }
