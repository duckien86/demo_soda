<?php

    class APostCategoryController extends AController
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
            $model = new APostCategory;

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['APostCategory'])) {
                $model->attributes = $_POST['APostCategory'];
                $DS                = DIRECTORY_SEPARATOR;
                $model->icon       = str_replace('..' . $DS, '', $model->icon);
                if ($model->save()) {
                    //add to cache
//                    APostCategory::getListPostCategory();
                    $this->redirect(array('view', 'id' => $model->id));
                }
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

            if (isset($_POST['APostCategory'])) {
                $model->attributes = $_POST['APostCategory'];
                $DS                = DIRECTORY_SEPARATOR;
                $model->icon       = str_replace('..' . $DS, '', $model->icon);
                if ($model->save()) {
                    //add to cache
//                    APostCategory::getListPostCategory();
                    $this->redirect(array('view', 'id' => $model->id));
                }
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
            $dataProvider = new CActiveDataProvider('APostCategory');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {

            $model = new APostCategory('search');
            $model->unsetAttributes();  // clear any default values
            if (isset($_GET['APostCategory']))
                $model->attributes = $_GET['APostCategory'];

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
         * @return APostCategory the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = APostCategory::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param APostCategory $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'apost-category-form') {
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

            $status = Yii::app()->request->getParam('status', FALSE);
            $model  = APostCategory::model()->findByPk($id);
            if ($model) {
                $model->status = $status;
                if ($model->update()) {
                    $result = TRUE;
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
            $dir_upload = '..' . $DS . 'social' . $DS . 'uploads' . $DS . 'posts_category' . $DS;
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
            $dir_upload = '..' . $DS . 'social' . $DS . 'uploads' . $DS . 'posts_category' . $DS;
            $dir_upload = $dir_upload . $DS . 'temp' . $DS;
            if (!is_dir($dir_upload)) {
                mkdir($dir_upload, 0777, TRUE);
            }

            $max_upload_size   = 999 * 1024 * 1024;
            $accept_file_types = 'jpg|jpeg|png|gif';
            $options_arr       = array(
                'script_url'        => Yii::app()->controller->createUrl('aPostCategory/delete'),
                'upload_dir'        => $dir_upload,
                'upload_url'        => $dir_upload,
                'max_file_size'     => $max_upload_size,
                'accept_file_types' => '/\.(' . $accept_file_types . ')$/i',
            );
            $upload_handler    = new UploadHandler($options_arr);
        }
    }
