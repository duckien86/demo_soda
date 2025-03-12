<?php

    class ANewsCommentsController extends AController
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
         * Updates a particular model.
         * If update is successful, the browser will be redirected to the 'view' page.
         *
         * @param integer $id the ID of the model to be updated
         */
        public function actionUpdate($id)
        {
            $model    = $this->loadModel($id);

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['ANewsComments'])) {
                $model->attributes = $_POST['ANewsComments'];
                if($model->validate()){
                    $model->save();
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
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model = new ANewsComments('search');
            $model->unsetAttributes();  // clear any default values
            $model->start_date        = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
            $model->end_date          = date('d/m/Y');
            if (isset($_REQUEST['ANewsComments']))
                $model->attributes = $_REQUEST['ANewsComments'];

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
         * @return ANewsComments the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = ANewsComments::model()->findByPk($id);
            if ($model === NULL){
                throw new CHttpException(404, 'The requested page does not exist.');
            }
            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param ANewsComments $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'anewscomments-form') {
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
            $model  = ANewsComments::model()->findByPk($id);
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
