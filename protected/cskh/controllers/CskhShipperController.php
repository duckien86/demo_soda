<?php

    class CskhShipperController extends AController
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
                'rights', // we only allow deletion via POST request
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
            return array();
        }

        /**
         * Displays a particular model.
         *
         * @param integer $id the ID of the model to be displayed
         */
        public function actionView($id)
        {
            if (Yii::app()->request->isAjaxRequest) {

                $start_date         = Yii::app()->request->getParam("start_date", FALSE);
                $end_date           = Yii::app()->request->getParam("end_date", FALSE);
                $data               = array();
                $total_renueve_date = $total_order = '';
                $model_detail       = new CskhOrders();
                if ($start_date && $end_date) {
                    $start_date = date("Y-m-d 00:00:00", strtotime(str_replace('/', '-', $start_date)));
                    $end_date   = date("Y-m-d 23:59:59", strtotime(str_replace('/', '-', $end_date)));

                    //Lấy tổng doanh thu theo thời gian của shipper.
                    $criteria            = new CDbCriteria();
                    $criteria->select    = "sum(od.price) as total_renueve_date";
                    $criteria->condition = "create_date >='$start_date' and create_date<= '$end_date' and t.shipper_id='$id'";
                    $criteria->join      = "INNER JOIN {{order_details}} as od ON od.order_id =t.id";
                    $total               = CskhOrders::model()->findAll($criteria)[0];

                    //Lấy tổng số đơn hàng của shipper theo thời gian
                    $total_order = Orders::model()->count('shipper_id=' . $id . ' and create_date>="' . $start_date . '" and create_date <= "' . $end_date . '"');
                    if ($total) {
                        $total_renueve_date = $total->total_renueve_date;
                    }
                    $data = $model_detail->search_detail($start_date, $end_date, $id);
                }

                $data = $this->renderPartial('_renueve_order_detail',
                    array(
                        'data'               => $data,
                        'total_renueve_date' => $total_renueve_date,
                        'total_order'        => $total_order));

                echo $data;
                Yii::app()->end();
            }
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
            $model = new CskhShipper();

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);
            self::performAjaxValidation($model);
            if (isset($_POST['CskhShipper'])) {
                $model->attributes = $_POST['CskhShipper'];
                $model->id         = md5(date('Y-m-d h:i:s'));
                if ($model->save())
                    $this->actionAdmin();
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
            self::performAjaxValidation($model);
            if (isset($_POST['CskhShipper'])) {
                $model->attributes = $_POST['CskhShipper'];
                if ($model->save())
                    $this->actionAdmin();
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
            $dataProvider = new CActiveDataProvider('CskhShipper');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model = new CskhShipper('search');
            $model->unsetAttributes();  // clear any default values
            if (isset($_GET['CskhShipper'])) {
                $model->attributes = $_GET['CskhShipper'];
            }
            if (isset($_POST['CskhShipper'])) {
                $model->attributes = $_POST['CskhShipper'];
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
         * @return CskhShipper the loaded model
         * @throws CHttpException
         */
        public function loadModel($id)
        {
            $model = CskhShipper::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Performs the AJAX validation.
         *
         * @param CskhShipper $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_GET['ajax']) && $_GET['ajax'] === 'cskh-shipper-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }


        /**
         * Lấy danh sách quận huyện theo tỉnh
         */
        public function actionGetDistrictByProvice()
        {

            $provice_code = Yii::app()->getRequest()->getParam("province_code", FALSE);
            if ($provice_code) {
                $criteria = new CDbCriteria();

                $criteria->condition = "province_code='" . $provice_code . "'";

                $data   = District::model()->findAll($criteria);
                $return = CHtml::listData($data, 'code', 'name');
                echo "<option> </option>";
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }

        }

        /**
         * Lấy danh sách quận huyện theo tỉnh
         */
        public function actionGetWardByDistrict()
        {

            $district_code = Yii::app()->getRequest()->getParam("district_code", FALSE);
            if ($district_code) {
                $criteria = new CDbCriteria();

                $criteria->condition = "district_code='" . $district_code . "'";

                $data   = Ward::model()->findAll($criteria);
                $return = CHtml::listData($data, 'code', 'name');
                echo "<option> </option>";
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }

        }

        public function actionGetBrandOfficeByWard()
        {

            $ward_code = Yii::app()->getRequest()->getParam("ward_code", FALSE);
            if ($ward_code) {
                $criteria = new CDbCriteria();

                $criteria->condition = "ward_code='" . $ward_code . "'";

                $data   = BrandOffices::model()->findAll($criteria);
                $return = CHtml::listData($data, 'code', 'name');
                echo "<option> </option>";
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }

        }

        /**
         * Báo cáo doanh thu shipper.
         */
        public function actionReportRenueveShipper()
        {
            $model = new CskhShipper('search');
            $model->unsetAttributes();  // clear any default values
            if (isset($_POST['CskhShipper'])) {
                $model->attributes = $_POST['CskhShipper'];
            }
            if (isset($_GET['CskhShipper'])) {
                $model->attributes = $_GET['CskhShipper'];
            }

            return $this->render('report', array('model' => $model));
        }
    }
