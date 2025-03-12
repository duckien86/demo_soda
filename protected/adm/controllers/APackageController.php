<?php

    class APackageController extends AController
    {
        /**
         * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
         * using two-column layout. See 'protected/views/layouts/column2.php'.
         */
        public $layout        = '//layouts/column2';
        public $defaultAction = 'admin';

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
                    'users'   => array('@'),
                ),
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                    'actions' => array('create', 'update'),
                    'users'   => array('admin'),
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
            $model = new APackage;
            $model->scenario = 'create';

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['APackage'])) {
                $model->attributes     = $_POST['APackage'];
                $model->id             = Utils::generateRandomString(32);
                $model->slug           = Utils::unsign_string($model->slug);
                $model->price          = (int)preg_replace('/\./', '', $model->price);
                $model->price_discount = (int)preg_replace('/\./', '', $model->price_discount);
                if($model->price_stb){
                    $model->price_stb          = (int)preg_replace('/\./', '', $model->price_stb);
                }else{
                    $model->price_stb = null;
                }
                if($model->price_no_stb){
                    $model->price_no_stb          = (int)preg_replace('/\./', '', $model->price_no_stb);
                }else{
                    $model->price_no_stb = null;
                }
                if (isset($model->sms_external)) {
                    if (isset($_POST['APackage']['free_sms_external'])) {
                        if ($_POST['APackage']['free_sms_external'] == -1) {
                            $model->sms_external = -1;
                        } else {
                            if (!empty($model->sms_external)) {
                                $model->sms_external = (int)preg_replace('/\./', '', $model->sms_external);
                            }
                        }
                    }
                }
                if (isset($model->sms_internal)) {
                    if (isset($_POST['APackage']['free_sms_internal'])) {
                        if ($_POST['APackage']['free_sms_internal'] == -1) {
                            $model->sms_internal = -1;
                        } else {
                            if (!empty($model->sms_internal)) {
                                $model->sms_internal = (int)preg_replace('/\./', '', $model->sms_internal);
                            }
                        }
                    }
                }
                if (isset($model->call_external)) {
                    if (isset($_POST['APackage']['free_call_external'])) {
                        if ($_POST['APackage']['free_call_external'] == -1) {
                            $model->call_external = -1;
                        } else {
                            if (!empty($model->call_external)) {
                                $model->call_external = (int)preg_replace('/\./', '', $model->call_external);
                            }
                        }
                    }
                }
                if (isset($model->call_internal)) {
                    if (isset($_POST['APackage']['free_call_internal'])) {
                        if ($_POST['APackage']['free_call_internal'] == -1) {
                            $model->call_internal = -1;
                        } else {
                            if (!empty($model->call_internal)) {
                                $model->call_internal = (int)preg_replace('/\./', '', $model->call_internal);
                            }
                        }
                    }
                }
                if (isset($model->price_discount)) {
                    if (isset($_POST['APackage']['free_price_discount'])) {
                        if ($_POST['APackage']['free_price_discount'] == -1) {
                            $model->price_discount = -1;
                        } else {
                            if (!empty($model->price_discount)) {
                                $model->price_discount = (int)preg_replace('/\./', '', $model->price_discount);
                            }
                        }
                    }
                }
                if (isset($model->min_age)) {
                    if (!empty($model->min_age)) {
                        $model->min_age = (int)preg_replace('/\./', '', $model->min_age);
                    }
                }
                if (isset($model->max_age)) {
                    if (!empty($model->max_age)) {
                        $model->max_age = (int)preg_replace('/\./', '', $model->max_age);
                    }
                }
                if (isset($model->data)) {
                    if (isset($_POST['APackage']['free_data'])) {
                        if ($_POST['APackage']['free_data'] == -1) {
                            $model->data = -1;
                        } else {
                            if (!empty($model->data)) {
                                $model->data = (int)preg_replace('/\./', '', $model->data);
                            }
                        }
                    }
                }
                // get upload file
                $model->thumbnail_1 = str_replace(Yii::app()->params->upload_dir_path, '', $model->thumbnail_1);
                $model->thumbnail_2 = str_replace(Yii::app()->params->upload_dir_path, '', $model->thumbnail_2);
                $model->thumbnail_3 = str_replace(Yii::app()->params->upload_dir_path, '', $model->thumbnail_3);
                if ($model->save()) {
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
            $model->scenario = 'upload';
            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['APackage'])) {

                $model->attributes     = $_POST['APackage'];
                $model->slug           = Utils::unsign_string($model->slug);
                $model->price          = (int)preg_replace('/\./', '', $model->price);
                $model->price_discount = (int)preg_replace('/\./', '', $model->price_discount);
                if($model->price_stb){
                    $model->price_stb          = (int)preg_replace('/\./', '', $model->price_stb);
                }else{
                    $model->price_stb = null;
                }
                if($model->price_no_stb){
                    $model->price_no_stb          = (int)preg_replace('/\./', '', $model->price_no_stb);
                }else{
                    $model->price_no_stb = null;
                }
                if (isset($model->sms_external)) {
                    if (isset($_POST['APackage']['free_sms_external'])) {
                        if ($_POST['APackage']['free_sms_external'] == -1) {
                            $model->sms_external = -1;
                        } else {
                            if (!empty($model->sms_external)) {
                                $model->sms_external = (int)preg_replace('/\./', '', $model->sms_external);
                            }
                        }
                    }


                }
                if (isset($model->sms_internal)) {
                    if (isset($_POST['APackage']['free_sms_internal'])) {
                        if ($_POST['APackage']['free_sms_internal'] == -1) {
                            $model->sms_internal = -1;
                        } else {
                            if (!empty($model->sms_internal)) {
                                $model->sms_internal = (int)preg_replace('/\./', '', $model->sms_internal);
                            }
                        }
                    }
                }
                if (isset($model->call_external)) {
                    if (isset($_POST['APackage']['free_call_external'])) {
                        if ($_POST['APackage']['free_call_external'] == -1) {
                            $model->call_external = -1;
                        } else {
                            if (!empty($model->call_external)) {
                                $model->call_external = (int)preg_replace('/\./', '', $model->call_external);
                            }
                        }
                    }
                }
                if (isset($model->call_internal)) {
                    if (isset($_POST['APackage']['free_call_internal'])) {
                        if ($_POST['APackage']['free_call_internal'] == -1) {
                            $model->call_internal = -1;
                        } else {
                            if (!empty($model->call_internal)) {
                                $model->call_internal = (int)preg_replace('/\./', '', $model->call_internal);
                            }
                        }
                    }
                }
                if (isset($model->price_discount)) {
                    if (isset($_POST['APackage']['free_price_discount'])) {
                        if ($_POST['APackage']['free_price_discount'] == -1) {
                            $model->price_discount = -1;
                        } else {
                            if (!empty($model->price_discount)) {
                                $model->price_discount = (int)preg_replace('/\./', '', $model->price_discount);
                            }
                        }
                    }
                }
                if (isset($model->min_age)) {
                    if (!empty($model->min_age)) {
                        $model->min_age = (int)preg_replace('/\./', '', $model->min_age);
                    }
                }
                if (isset($model->max_age)) {
                    if (!empty($model->max_age)) {
                        $model->max_age = (int)preg_replace('/\./', '', $model->max_age);
                    }
                }
                if (isset($model->data)) {
                    if (isset($_POST['APackage']['free_data'])) {
                        if ($_POST['APackage']['free_data'] == -1) {
                            $model->data = -1;
                        } else {
                            if (!empty($model->data)) {
                                $model->data = (int)preg_replace('/\./', '', $model->data);
                            }
                        }
                    }
                }
                // get upload file
                $model->thumbnail_1 = str_replace(Yii::app()->params->upload_dir_path, '', $model->thumbnail_1);
                $model->thumbnail_2 = str_replace(Yii::app()->params->upload_dir_path, '', $model->thumbnail_2);
                $model->thumbnail_3 = str_replace(Yii::app()->params->upload_dir_path, '', $model->thumbnail_3);

                if ($model->save()) {
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
            $dataProvider = new CActiveDataProvider('APackage');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model = new APackage();
            $model->unsetAttributes();  // clear any default values
            $model->scenario = 'admin';

            if (isset($_GET['APackage']))
                $model->attributes = $_GET['APackage'];

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
         * @return APackage the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {

            $model = APackage::model()->findByPk($id);
            if ($model === NULL){
                throw new CHttpException(404, 'The requested page does not exist.');
            }
            $model->data_original = $model->attributes;

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param APackage $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'apackage-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }

        /**
         * action upload image
         */
        public function actionThumbnail1()
        {
            $dir_upload = Yii::app()->params->upload_dir_path . 'package';
            $time       = date("Ymdhis");
            $DS         = DIRECTORY_SEPARATOR;
            if (isset($_POST['tempFileName1']) && $_POST['tempFileName1'] != '') {
                // file temporary
                $fileTemporary = $_POST['tempFileName1'];
                // temporary folder
                $temporaryFolder = $dir_upload . '/temp/';
                if (!file_exists($temporaryFolder)) {
                    mkdir($temporaryFolder, 0777, TRUE);
                }
                // get upload file info
                $fileUploadInfo = pathinfo($fileTemporary);


                $fileUploadNewName = Utils::unsign_string($fileUploadInfo['filename']) . '-' . time();

                // init folder contain file
                $destinationFolder = $dir_upload . $DS . $time . $DS;

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
         * action upload image
         */
        public function actionThumbnail2()
        {
            $dir_upload = Yii::app()->params->upload_dir_path . 'package';
            $time       = date("Ymdhis");
            $DS         = DIRECTORY_SEPARATOR;
            if (isset($_POST['tempFileName2']) && $_POST['tempFileName2'] != '') {
                // file temporary
                $fileTemporary = $_POST['tempFileName2'];
                // temporary folder
                $temporaryFolder = $dir_upload . '/temp/';
                if (!file_exists($temporaryFolder)) {
                    mkdir($temporaryFolder, 0777, TRUE);
                }
                // get upload file info
                $fileUploadInfo = pathinfo($fileTemporary);


                $fileUploadNewName = Utils::unsign_string($fileUploadInfo['filename']) . '-' . time();

                // init folder contain file
                $destinationFolder = $dir_upload . $DS . $time . $DS;

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
         * action upload image
         */
        public function actionThumbnail3()
        {
            $dir_upload = Yii::app()->params->upload_dir_path . 'package';
            $time       = date("Ymdhis");
            $DS         = DIRECTORY_SEPARATOR;
            if (isset($_POST['tempFileName3']) && $_POST['tempFileName3'] != '') {
                // file temporary
                $fileTemporary = $_POST['tempFileName3'];
                // temporary folder
                $temporaryFolder = $dir_upload . '/temp/';
                if (!file_exists($temporaryFolder)) {
                    mkdir($temporaryFolder, 0777, TRUE);
                }
                // get upload file info
                $fileUploadInfo = pathinfo($fileTemporary);


                $fileUploadNewName = Utils::unsign_string($fileUploadInfo['filename']) . '-' . time();

                // init folder contain file
                $destinationFolder = $dir_upload . $DS . $time . $DS;

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
            $dir_upload = 'package';
            Yii::import('ext.UploadHandler.UploadHandler');

            $dir_root = dirname(Yii::app()->request->scriptFile);
            $dir_root = str_replace('adm', '', $dir_root);
            $DS       = DIRECTORY_SEPARATOR;

            $upload_dir = $dir_root . $DS . 'uploads' . $DS . $dir_upload . $DS . 'temp' . $DS;
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, TRUE);
            }

            $max_upload_size   = 999 * 1024 * 1024;
            $accept_file_types = 'jpg|jpeg|png|gif';
            $options_arr       = array(
                'script_url'        => Yii::app()->createUrl('aPackage/deleteFile'),
                'upload_dir'        => $upload_dir,
                'upload_url'        => $dir_root . $DS . 'uploads' . $DS . $dir_upload . $DS . 'temp' . $DS,
                'max_file_size'     => $max_upload_size,
                'accept_file_types' => '/\.(' . $accept_file_types . ')$/i',
            );
            $upload_handler    = new UploadHandler($options_arr);
        }

        /**
         * map package nation
         *
         * @param $id
         */
        public function actionMapNation($id)
        {
            $modelPackage = $this->loadModel($id);
            $nations      = ANations::listNation($id);

            $this->render('map_nation', array(
                'modelPackage' => $modelPackage,
                'nations'      => $nations,
            ));
        }

        public function actionAddNation()
        {
            $package_id   = Yii::app()->request->getParam('package_id', '');
            $nation_code  = Yii::app()->request->getParam('nation_code', '');
            $type         = Yii::app()->request->getParam('type', '');
            $modelPackage = APackage::model()->findByPk($package_id);
            $status       = FALSE;
            $msg          = '';
            if ($modelPackage) {
                $package_nation = APackagesNations::model()->find('nation_code=:nation_code AND package_id=:package_id AND type=:type',
                    array(
                        ':nation_code' => $nation_code,
                        ':package_id'  => $package_id,
                        ':type'        => $type,
                    ));

                if ($package_nation) {
                    if ($package_nation->delete()) {
                        $status = TRUE;
                    }
                } else {
                    $package_nation              = new APackagesNations();
                    $package_nation->package_id  = $package_id;
                    $package_nation->nation_code = $nation_code;
                    $package_nation->type        = $type;
                    if ($package_nation->save()) {
                        $status = TRUE;
                    }
                }
            } else {
                $msg = Yii::t('adm/label', 'error_exception');
            }
            echo CJSON::encode(
                array(
                    'status' => $status,
                    'msg'    => $msg
                )
            );

            Yii::app()->end();
        }


        public function actionSetStatus()
        {
            $result          = FALSE;
            $id              = Yii::app()->getRequest()->getParam('id', FALSE);
            $status          = Yii::app()->getRequest()->getParam('status', FALSE);
            $model           = $this->loadModel($id);
            $model->scenario = 'setStatus';
            if ($model) {
                $model->status = $status;
                if ($model->update()) {

                    $result = TRUE;
                    Yii::app()->user->setFlash('success', Yii::t('adm/label', 'Thành công'));

                } else {
                    $result = FALSE;
                    Yii::app()->user->setFlash('error', Yii::t('adm/label', 'Thất bại'));
                }
            }
            echo CJSON::encode($result);
            exit();
        }
    }
