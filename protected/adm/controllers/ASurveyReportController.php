<?php

    class ASurveyReportController extends AController
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
            $model = $this->loadModel($id);
            $this->render('view', array(
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
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model = new ASurveyReport('search');
            $model->unsetAttributes();  // clear any default values
            $model->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
            $model->end_date   = date('d/m/Y');

            if (isset($_REQUEST['ASurveyReport'])){
                $model->attributes = $_REQUEST['ASurveyReport'];
                $model->start_date = $_REQUEST['ASurveyReport']['start_date'];
                $model->end_date   = $_REQUEST['ASurveyReport']['end_date'];

                $start_date = date('Y/m/d', strtotime(str_replace('/','-',$model->start_date)));
                $end_date = date('Y/m/d', strtotime(str_replace('/','-',$model->end_date)));

                if($start_date > $end_date){
                    $model->addError('end_date', Yii::t('adm/label','end_date_must_greater'));
                }
            }

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
         * @return ASurveyReport the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = ASurveyReport::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param ASurveyReport $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'asurveyreport-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }

    }
