<?php

    class ACommentsController extends Controller
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
            $model = new AComments;

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['AComments'])) {
                $model->attributes = $_POST['AComments'];
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

            if (isset($_POST['AComments'])) {
                $model->attributes = $_POST['AComments'];
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
            $dataProvider = new CActiveDataProvider('AComments');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model = new AComments('search');
            $model->unsetAttributes();  // clear any default values
            if (isset($_GET['AComments']))
                $model->attributes = $_GET['AComments'];

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
         * @return AComments the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = AComments::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param AComments $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'acomments-form') {
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
            $sso_id = Yii::app()->request->getParam('sso_id', FALSE);

            $model = AComments::model()->findByPk($id);
            if ($status == AComments::ACTIVE) {
                if ($model->status != AComments::INACTIVE) {
                    if ($model) {
                        $model->status = $status;
                        if ($model->update()) {
                            $result = TRUE;
                        }
                    }
                }else{
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


            $model = AComments::model()->findByPk($id);

            if ($model && $sso_id && $status) {
                $model->status = $status;
                $model->note   = $info;

                if ($model->update()) {
                    if ($status == AComments::INACTIVE) {
                        if (ACustomers::setPoint($sso_id, -1, ACustomers::POINT_EVENT_COMMENT, "Bình luận bị admin ẩn")) {
                            $result = TRUE;
                        }
                    }else{
                        if (ACustomers::setPoint($sso_id, +1, ACustomers::POINT_EVENT_COMMENT, "Bình luận được admin phục hồi")) {
                            $result = TRUE;
                        }
                    }
                }
            }

            echo $result;
            exit();
        }
    }
