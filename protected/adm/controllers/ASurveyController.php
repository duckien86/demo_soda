<?php

    class ASurveyController extends AController
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
//                'accessControl', // perform access control for CRUD operations
//                'postOnly + delete', // we only allow deletion via POST request
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
            $model = new ASurvey();

            // Uncomment the following line if AJAX validation is needed
             $this->performAjaxValidation($model);

            if (isset($_POST['ASurvey'])) {
                $model->attributes = $_POST['ASurvey'];
                if ($model->save()) {
                    $this->redirect(array('admin'));
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
            $model    = $this->loadModel($id);
            if($model->limit == ASurvey::UN_LIMIT){
                $model->un_limit = ASurvey::UN_LIMIT;
                $model->limit = null;
            }

//             Uncomment the following line if AJAX validation is needed
             $this->performAjaxValidation($model);

            if (isset($_POST['ASurvey'])) {
                $model->attributes = $_POST['ASurvey'];
                if ($model->save()) {
                    $this->redirect(array('admin'));
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
            $dataProvider = new CActiveDataProvider('ASurvey');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model = new ASurvey('search');
            $model->unsetAttributes();  // clear any default values
            if (isset($_GET['ASurvey']))
                $model->attributes = $_GET['ASurvey'];

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
         * @return ASurvey the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = ASurvey::model()->findByPk($id);
            if ($model === NULL){
                throw new CHttpException(404, 'The requested page does not exist.');
            }
            if (!strtotime($model->start_date) || $model->start_date == '0000-00-00 00:00:00') {
                $model->start_date = '';
            } else {
                $model->start_date = date('d/m/Y', strtotime($model->start_date));
            }
            if (!strtotime($model->end_date) || $model->end_date == '0000-00-00 00:00:00') {
                $model->end_date = '';
            } else {
                $model->end_date = date('d/m/Y', strtotime($model->end_date));
            }
            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param ASurvey $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'asurvey-form') {
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
            $model  = ASurvey::model()->findByPk($id);
            if ($model) {
                $model->status = $status;
                if ($model->update()) {
                    $result = TRUE;
                }
            }

            echo CJSON::encode($result);
            exit();
        }
    }
