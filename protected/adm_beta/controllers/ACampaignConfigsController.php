<?php

    class ACampaignConfigsController extends Controller
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
            $model = new ACampaignConfigs;

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (isset($_POST['ACampaignConfigs'])) {
                $model->attributes = $_POST['ACampaignConfigs'];

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

            if (isset($_POST['ACampaignConfigs'])) {
                $model->attributes = $_POST['ACampaignConfigs'];
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
            $dataProvider = new CActiveDataProvider('ACampaignConfigs');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model = new ACampaignConfigs('search');
            $model->unsetAttributes();  // clear any default values
            if (isset($_GET['ACampaignConfigs']))
                $model->attributes = $_GET['ACampaignConfigs'];

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
         * @return ACampaignConfigs the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = ACampaignConfigs::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param ACampaignConfigs $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'acampaign-configs-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }

        /**
         * Action Set Status
         */
        public function actionSetStatus()
        {
            $result          = FALSE;
            $id              = Yii::app()->getRequest()->getParam('id');
            $status          = Yii::app()->getRequest()->getParam('status');
            $model           = $this->loadModel($id);
            $model->scenario = 'setStatus';
            if ($model) {
                $model->status = $status;
                if ($model->update()) {
                    $result = TRUE;
                    Yii::app()->user->setFlash('success', Yii::t('adm/label', 'alert_success'));
                } else {
                    $result = FALSE;
                    Yii::app()->user->setFlash('error', Yii::t('adm/label', 'alert_fail'));
                }
            }
            echo CJSON::encode($result);
            exit();
        }

        /**
         * Manages all models.
         */
        /**
         * Hiện popup tra mã xác thực.
         */
        public function actionShowForm()
        {

            $data = array();
            $id   = Yii::app()->request->getParam('id', FALSE);
            if ($id) {
                $model          = new AOrders();
                $campaignConfig = ACampaignConfigs::model()->findByAttributes(array('id' => $id));
                if ($campaignConfig) {

                    $data = $this->renderPartial('_popup_create_link',
                        array(
                            'campaignConfig' => $campaignConfig
                        )
                    );
                }
            }
            echo $data;
            exit();
        } /**
     * Manages all models.
     */
        /**
         * Hiện popup tra mã xác thực.
         */
        public function actionCreateLink()
        {
            $link_new = '';
            $id       = Yii::app()->request->getParam('id', FALSE);
            if ($id) {
                $campaignConfig = ACampaignConfigs::model()->findByAttributes(array('id' => $id));
                if ($campaignConfig) {

                    $link_new = "https://freedoo.vnpt.vn?&utm_source=" .
                        $campaignConfig->utm_source . "&utm_campaign=" . $campaignConfig->utm_campaign;
                }
            }
            echo $link_new;
            exit();
        }
    }
