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
