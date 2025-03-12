<?php

    class AAgencyContractController extends AController
    {
        /**
         * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
         * using two-column layout. See 'protected/views/layouts/column2.php'.
         */
        public $layout        = '//layouts/column1';
        public $defaultAction = 'admin';
        public $dir_contracts = 'agency/contract';

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
                    'actions' => array('create', 'update', 'changeStatus'),
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
            $contract_details = AAgencyContractDetail::getListDetailsByContractId($id, TRUE);
            $this->render('view', array(
                'model'            => $this->loadModel($id),
                'contract_details' => $contract_details,
            ));
        }

        public function actionCreate()
        {
            $model                = new AAgencyContract();
            $modelDetail          = new AAgencyContractDetail();
            $modelFiles           = new AAgencyFile();
            $modelFiles->scenario = 'file_contract';
            $packages             = array();
            $details_data         = array();
            $time                 = date("Ymdhis");
            $DS                   = DIRECTORY_SEPARATOR;

            $packages = APackage::getAllAgencyPackage();

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);
            if (isset($_POST['AAgencyContract'])) {
                $model->attributes = $_POST['AAgencyContract'];
                if (!is_dir(Yii::app()->params->upload_dir_path . $this->dir_contracts)) {
                    mkdir(Yii::app()->params->upload_dir_path . $this->dir_contracts, 0777, TRUE);
                }

                $uploadedFile = CUploadedFile::getInstance($modelFiles, 'folder_path');
                if (isset($uploadedFile) && $uploadedFile != NULL) {
                    $uploads_dir           = str_replace('../', '', Yii::app()->params->upload_dir_path . $this->dir_contracts);
                    $modelFiles->file_name = $time . Utils::unsign_string($uploadedFile->name);
                    $modelFiles->file_ext  = $uploadedFile->extensionName;
                    $modelFiles->file_size = $uploadedFile->size;
                    $file_name             = $modelFiles->file_name . '.' . $modelFiles->file_ext;
                    if ($uploadedFile->saveAs(realpath(Yii::app()->getBasePath() . '/' . Yii::app()->params->upload_dir_path . $this->dir_contracts . '/') . '/' . $file_name)) {
                        $modelFiles->folder_path = $uploads_dir . $DS . $file_name;
                    }
                }

                if ($model->start_date) {
                    $model->start_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $model->start_date)));
                }
                if ($model->finish_date) {
                    $model->finish_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $model->finish_date)));
                }
                $details         = (isset($_POST['AAgencyContractDetail'])) ? $_POST['AAgencyContractDetail'] : null;
                $details_data    = $details;
                $validate_detail = $modelDetail->validateContractDetails($details);

                if ($model->validate()) {
                    if ($modelFiles->folder_path) {
                        if ($validate_detail) {
                            if ($model->save()) {
                                $modelFiles->status = AAgencyFile::FILE_ACTIVE;
                                $modelFiles->setFile($model);
                                if(!empty($details)){
                                    $modelDetail->setContractDetails($model, $details);
                                }
                                $this->redirect(array('view', 'id' => $model->id));
                            }
                        } else {
                            $model->addError('detail', Yii::t('adm/label', 'error_detail_contract'));
                        }
                    } else {
                        $modelFiles->addError('folder_path', Yii::t('adm/label', 'contract_file_empty'));
                    }
                }
            }

            $model->start_date  = ($model->start_date) ? date('d/m/Y', strtotime($model->start_date)) : '';
            $model->finish_date = ($model->start_date) ? date('d/m/Y', strtotime($model->finish_date)) : '';
            $this->render('create', array(
                'model'       => $model,
                'modelDetail' => $modelDetail,
                'modelFiles'  => $modelFiles,
                'packages'    => $packages,
                'details'     => $details_data,//array contract details
            ));
        }


        public function actionUpdate($id)
        {
            $model        = $this->loadModel($id);
            if($model->status != AAgencyContract::CONTRACT_PENDING){
                throw new CHttpException(404, 'The requested page does not exist.');
            }

            $modelDetail  = new AAgencyContractDetail();

            $packages     = APackage::getAllAgencyPackage();

            $details_data = $modelDetail->getArrayDetailsByContractId($id);
            $time         = date("Ymdhis");
            $DS           = DIRECTORY_SEPARATOR;
            $modelFiles   = AAgencyFile::model()->find('object_id=:object_id', array(':object_id' => $id));
            if ($modelFiles) {
                $modelFiles->old_file = $modelFiles->folder_path;
            } else {
                $modelFiles = new AAgencyFile();
            }
            $modelFiles->scenario = 'file_contract';

            // Uncomment the following line if AJAX validation is needed
            //$this->performAjaxValidation($model);

            if (isset($_POST['AAgencyContract'])) {
                $model->attributes = $_POST['AAgencyContract'];
                if (!is_dir(Yii::app()->params->upload_dir_path . $this->dir_contracts)) {
                    mkdir(Yii::app()->params->upload_dir_path . $this->dir_contracts, 0777, TRUE);
                }

                $uploadedFile = CUploadedFile::getInstance($modelFiles, 'folder_path');
                if (isset($uploadedFile) && $uploadedFile != NULL) {
                    $uploads_dir           = str_replace('../', '', Yii::app()->params->upload_dir_path . $this->dir_contracts);
                    $modelFiles->file_name = $time . Utils::unsign_string($uploadedFile->name);
                    $modelFiles->file_ext  = $uploadedFile->extensionName;
                    $modelFiles->file_size = $uploadedFile->size;
                    $file_name             = $modelFiles->file_name . '.' . $modelFiles->file_ext;
                    if ($uploadedFile->saveAs(realpath(Yii::app()->getBasePath() . '/' . Yii::app()->params->upload_dir_path . $this->dir_contracts . '/') . '/' . $file_name)) {
                        $modelFiles->folder_path = $uploads_dir . $DS . $file_name;
                    }
                }

                if ($model->start_date) {
                    $model->start_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $model->start_date)));
                }
                if ($model->finish_date) {
                    $model->finish_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $model->finish_date)));
                }
                $details         = (isset($_POST['AAgencyContractDetail'])) ? $_POST['AAgencyContractDetail'] : null;
                $details_data    = $details;
                $validate_detail = $modelDetail->validateContractDetails($details);

                if ($model->validate()) {
                    if ($modelFiles->folder_path) {
                        if ($validate_detail) {
                            if ($model->save()) {
                                $modelFiles->status = AAgencyFile::FILE_ACTIVE;
                                $modelFiles->setFile($model);
                                if(!empty($details)){
                                    $modelDetail->setContractDetails($model, $details);
                                }
                                $this->redirect(array('view', 'id' => $model->id));
                            }
                        } else {
                            $model->addError('detail', Yii::t('adm/label', 'error_detail_contract'));
                        }
                    } else {
                        $modelFiles->addError('folder_path', Yii::t('adm/label', 'contract_file_empty'));
                    }
                }
            }

            $model->start_date  = date('d/m/Y', strtotime($model->start_date));
            $model->finish_date = date('d/m/Y', strtotime($model->finish_date));

            $this->render('update', array(
                'model'       => $model,
                'modelDetail' => $modelDetail,
                'modelFiles'  => $modelFiles,
                'packages'    => $packages,
                'details'     => $details_data,//array contract details
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
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model = new AAgencyContract('search');
            $model->unsetAttributes();  // clear any default values
            if (isset($_GET['AAgencyContract']))
                $model->attributes = $_GET['AAgencyContract'];

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
         * @return AAgencyContract the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = AAgencyContract::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param AFTContracts $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'aagencycontract-form') {
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
            $id     = Yii::app()->request->getParam('id');
            $status = Yii::app()->request->getParam('status');
            $model  = AAgencyContract::model()->findByPk($id);

            if($status == AAgencyContract::CONTRACT_ACTIVE){
                $criteria = new CDbCriteria();
                $criteria->condition = "
                    t.agency_id = :agency_id
                    AND t.status = :status
                    AND t.id != :id
                ";
                $criteria->params = array(
                    ':agency_id'    => $model->agency_id,
                    ':status'       => AAgencyContract::CONTRACT_ACTIVE,
                    ':id'           => $model->id,

                );
                $contract = AAgencyContract::model()->find($criteria);
                if($contract){
                    echo CJSON::encode($result);
                    exit();
                }
            }

            if ($model) {
                $model->status = $status;
                if ($model->save()) {
                    $result = TRUE;
                }
            }


            echo CJSON::encode($result);
            exit();
        }

    }
