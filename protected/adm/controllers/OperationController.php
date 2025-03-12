<?php

    class OperationController extends Controller
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
            $model                   = new AOrders();
            $model_details           = new AOrderDetails();
            $model_state             = new AOrderState();
            $model_shipper_order     = new AShipperOrder();
            $model_logs_sim          = new ALogsSim();
            $model_sim               = new ASim();
            $post                    = 0;
            $model->scenario         = "operation";
            $model_details->scenario = "operation";
            $model_state->scenario   = "operation";
            if (isset($_POST['AOrders'])) {

                if (isset($_POST['AOrders']['id']) && !empty($_POST['AOrders']['id'])) {
                    $post                          = 1;
                    $model_details->order_id       = $_POST['AOrders']['id'];
                    $model_state->order_id         = $_POST['AOrders']['id'];
                    $model->id                     = $_POST['AOrders']['id'];
                    $model_shipper_order->order_id = $_POST['AOrders']['id'];
                    $model_sim->order_id           = $_POST['AOrders']['id'];
                    $model_logs_sim->order_id      = $_POST['AOrders']['id'];

                }
            }

            return $this->render('index', array(
                'model'               => $model,
                'model_details'       => $model_details,
                'model_state'         => $model_state,
                'model_shipper_order' => $model_shipper_order,
                'model_sim'           => $model_sim,
                'model_logs_sim'      => $model_logs_sim,
                'post'                => $post,
            ));
        }

        public function actionCheckMsisdn()
        {
            $model           = new ASim();
            $post            = 0;
            $data_output     = array();
            $model->scenario = "check_msisdn";

            $type = array(
                ASim::KEEP         => 'backend_keep_msisdn',
                ASim::CANCEL_KEEP  => 'backend_remove_keep',
                ASim::CREATE_SIM   => 'backend_check_instance_sim',
                ASim::DKTT         => 'backend_check_dktt',
                ASim::HMTS         => 'backend_check_hmts',
                ASim::PACKAGE_PRE  => 'web_check_package',
                ASim::PACKAGE_POST => 'web_check_package',
            );
            if (isset($_POST['ASim'])) {
                $store = '';
                $flag  = FALSE;
                if (isset($_POST['ASim']['msisdn'])) {
                    $model->msisdn   = $_POST['ASim']['msisdn'];
                    $model->action   = $_POST['ASim']['action'];
                    $store = $_POST['ASim']['store_id'];
                    $sim             = ASim::model()->findByAttributes(array('msisdn' => $_POST['ASim']['msisdn']));
//                    if ($sim) {
//                        $store = $sim->store_id;
//                    }
                    if ($model->action == ASim::KEEP || $model->action == ASim::CANCEL_KEEP) {
                        $flag = TRUE;
                    }
                    if ($model->validate()) {
                        $post        = 1;
                        $data_output = $model->checkMsisdn($model->msisdn, $type[$model->action], $store, $flag);
                    } else {
                        $model->getErrors();
                    }
                }

            }

            return $this->render('check_msisdn', array(
                'model'       => $model,
                'post'        => $post,
                'data_output' => $data_output,
            ));
        }

        public function actionOpenPackage()
        {
            $model           = new ASim();
            $post            = 0;
            $data_output     = array();
            $model->scenario = "open_package";

            $type = 'backend_register_package';
            if (isset($_POST['ASim'])) {
                $store = '';
                $flag  = FALSE;
                if (isset($_POST['ASim']['msisdn'])) {
                    $model->msisdn = $_POST['ASim']['msisdn'];
                    $package_code  = $model->package_code = $_POST['ASim']['package_code'];
                    if ($model->validate()) {
                        $post        = 1;
                        $data_output = $model->checkMsisdn($model->msisdn, $type, $store, $flag, $package_code);
                    } else {
                        $model->getErrors();
                    }
                }
            }

            return $this->render('open_package', array(
                'model'       => $model,
                'post'        => $post,
                'data_output' => $data_output,
            ));
        }

        /**
         * Returns the data model based on the primary key given in the GET variable.
         * If the data model is not found, an HTTP exception will be raised.
         *
         * @param integer $id the ID of the model to be loaded
         *
         * @return AOrders the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {

            $model = AOrders::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist . ');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param AOrders $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'cskh - orders - form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }

        public function actionChangeDataOrders()
        {
            $id    = $_POST['pk'];
            $value = $_POST['value'];
            $name  = $_POST['name'];

            if ($id && $name) {

                $orders = AOrders::model()->findByAttributes(array('id' => $id));
                if ($orders) {
                    $orders->$name = $value;

                    $orders->update();
                }
            }
        }

        public function actionChangeDataOrderDetails()
        {
            $order_id = $_POST['pk']['order_id'];
            $itemid   = $_POST['pk']['item_id'];
            $value    = $_POST['value'];
            $name     = $_POST['name'];
            if ($itemid && $name && $order_id) {
                $order_details = AOrderDetails::model()->findByAttributes(array('order_id' => $order_id, 'item_id' => $itemid));
                if ($order_details) {
                    $order_details->$name = $value;
                    $order_details->update();
                }
            }
        }

        public function actionChangeDataOrderState()
        {
            $id    = $_POST['pk'];
            $value = $_POST['value'];
            $name  = $_POST['name'];

            if ($id && $name) {
                $order_state = AOrderState::model()->findByAttributes(array('id' => $id));
                if ($order_state) {
                    $order_state->$name = $value;
                    $order_state->update();
                }
            }
        }

        public function actionChangeDataShipperOrder()
        {
            $id    = $_POST['pk'];
            $value = $_POST['value'];
            $name  = $_POST['name'];

            if ($id && $name) {
                $order_state = AShipperOrder::model()->findByAttributes(array('id' => $id));
                if ($order_state) {
                    $order_state->$name = $value;
                    $order_state->update();
                }
            }
        }

        public function actionDeleteState($id)
        {
            $order_state = AOrderState::model()->findByAttributes(array('id' => $id));
            if ($order_state->delete()) {
                echo TRUE;
                exit();
            }
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {

                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        }
        public function actionDeleteDetails($id, $type)
        {
            $order_detail = AOrderDetails::model()->findByAttributes(array('order_id' => $id, 'type'=>$type));
            if ($order_detail->delete()) {
                echo TRUE;
                exit();
            }
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {

                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        }

        public function actionDeleteLogSim($id)
        {
            $logsim = ALogsSim::model()->findByAttributes(array('id' => $id));
            if ($logsim->delete()) {
                echo TRUE;
                exit();
            }
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {

                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        }

        public function actionChangeLogsSim()
        {
            $id    = $_POST['pk'];
            $value = $_POST['value'];
            $name  = $_POST['name'];

            if ($id && $name) {
                $log_sim = ALogsSim::model()->findByAttributes(array('id' => $id));
                if ($log_sim) {
                    $log_sim->$name = $value;
                    $log_sim->update();
                }
            }
        }

        public function actionChangeSim()
        {
            $id    = $_POST['pk'];
            $value = $_POST['value'];
            $name  = $_POST['name'];

            if ($id && $name) {
                $log_sim = ASim::model()->findByAttributes(array('id' => $id));
                if ($log_sim) {
                    $log_sim->$name = $value;
                    $log_sim->update();
                }
            }
        }


        public function actionAdminFT()
        {
            $model                   = new AFTOrders();
            $model_details           = new AFTOrderDetails();
            $model_log               = new AFTLogs();
            $model_files             = new AFTFiles();
            $post                    = 0;
            $model->scenario         = "operation";
            $model_details->scenario = "operation";
            if (isset($_POST['AOrders'])) {

                if (isset($_POST['AOrders']['id']) && !empty($_POST['AOrders']['id'])) {
                    $post                          = 1;
                    $model->id                     = $_POST['AOrders']['id'];
                    $model_details->order_id       = $_POST['AOrders']['id'];
                    $model_log->object_id          = $_POST['AOrders']['id'];
                    $model_files->object_id        = $_POST['AOrders']['id'];
                }
            }

            return $this->render('admin_ft', array(
                'model'               => $model,
                'model_details'       => $model_details,
                'model_log'           => $model_log,
                'model_files'         => $model_files,
                'post'                => $post,
            ));
        }

    }
