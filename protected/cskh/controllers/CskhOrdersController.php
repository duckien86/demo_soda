<?php

class CskhOrdersController extends AController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
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
                'actions' => array('index', 'view','orderFiber'),
                'users' => array('@'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update','orderFiber'),
                'users' => array('admin'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'orderFiber'),
                'users' => array('admin'),
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
        $order_state = CskhOrderState::getListOrderState($id, TRUE, 30);//order history
        $order_detail = CskhOrderState::getDetailOrder($id);
        $order_shipper = CskhOrders::getShipperDetail($id);
        $logs_sim = CskhLogsSim::getLogs($id);
        $this->render('view', array(
            'model' => $this->loadModel($id),
            'order_state' => $order_state,
            'order_detail' => $order_detail,
            'order_shipper' => $order_shipper,
            'logs_sim' => $logs_sim,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new CskhOrders;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['CskhOrders'])) {
            $model->attributes = $_POST['CskhOrders'];
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

        if (isset($_POST['CskhOrders'])) {
            $model->attributes = $_POST['CskhOrders'];
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
        $dataProvider = new CActiveDataProvider('CskhOrders');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new CskhOrders('search');
        $model->unsetAttributes();  // clear any default values
        $model_search = new CskhOrders('search');
        $model_search->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
        $model_search->end_date = date('d/m/Y');
        $model->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
        $model->end_date = date('d/m/Y');
        $post = FALSE;
        $model->scenario = "admin";
        if (isset($_GET['CskhOrders'])) {
            if (isset($_GET['CskhOrders']['sim']) && $_GET['CskhOrders']['sim'] != '') {
                $post = TRUE;
            }
            $model->attributes = $_GET['CskhOrders'];
            if (isset($_GET['CskhOrders']['province_code']) && $_GET['CskhOrders']['province_code'] != '') {
                $post = TRUE;

                $model->province_code = $_GET['CskhOrders']['province_code'];
            }
            if (isset($_GET['CskhOrders']['sale_office_code']) && $_GET['CskhOrders']['sale_office_code'] != '') {
                $model->sale_office_code = $_GET['CskhOrders']['sale_office_code'];

                $post = TRUE;
            }
            if (isset($_GET['CskhOrders']['brand_offices_id']) && $_GET['CskhOrders']['brand_offices_id'] != '') {
                $model->brand_offices_id = $_GET['CskhOrders']['brand_offices_id'];

                $post = TRUE;
            }
            if (isset($_GET['CskhOrders']['sim']) && $_GET['CskhOrders']['sim'] != '') {
                $model->sim = $_GET['CskhOrders']['sim'];

                $post = TRUE;
            }
            if (isset($_GET['CskhOrders']['phone_contact']) && $_GET['CskhOrders']['phone_contact'] != '') {
                $model->phone_contact = $_GET['CskhOrders']['phone_contact'];

                $post = TRUE;
            }

            if (isset($_GET['CskhOrders']['start_date']) && $_GET['CskhOrders']['start_date'] != '') {
                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['CskhOrders']['start_date'])));
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['CskhOrders']['end_date'])));
            } else {
                $model->start_date = '';
                $model->end_date = '';
            }
        }

        if (isset($_POST['CskhOrders'])) {
            $post = TRUE;
            if ($_POST['CskhOrders']['start_date'] != '' && $_POST['CskhOrders']['end_date'] != '') {

                $model->attributes = $_POST['CskhOrders'];
                $model_search->attributes = $_POST['CskhOrders'];
                $model_search->start_date = $_POST['CskhOrders']['start_date'];
                $model_search->end_date = $_POST['CskhOrders']['end_date'];

                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['CskhOrders']['start_date'])));
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['CskhOrders']['end_date'])));

            }
            if (isset($_POST['CskhOrders']['sale_office_code']) && $_POST['CskhOrders']['sale_office_code'] != '') {
                $model->sale_office_code = $_POST['CskhOrders']['sale_office_code'];
                $model_search->sale_office_code = $_POST['CskhOrders']['sale_office_code'];
            }
            if (isset($_POST['CskhOrders']['brand_offices_id']) && $_POST['CskhOrders']['brand_offices_id'] != '') {
                $model->brand_offices_id = $_POST['CskhOrders']['brand_offices_id'];
                $model_search->brand_offices_id = $_POST['CskhOrders']['brand_offices_id'];
            }
            if ($_POST['CskhOrders']['period'] != '') {
                $model->period = $model_search->period = $_POST['CskhOrders']['period'];
            }
            $model->status_shipper = $model_search->status_shipper = $_POST['CskhOrders']['status_shipper'];


            if (!$model->validate()) {
                $model->getErrors();
            }
        }

        $this->render('admin', array(
            'model' => $model,
            'post' => $post,
            'model_search' => $model_search,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdminRecycle()
    {
        $model = new CskhOrders('search');
        $model->unsetAttributes();  // clear any default values
        $model_search = new CskhOrders('search');
        $model_search->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
        $model_search->end_date = date('d/m/Y');
        $model->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
        $model->end_date = date('d/m/Y');
        $post = FALSE;
        $model->scenario = "admin";
        if (isset($_GET['CskhOrders'])) {

            $model->attributes = $_GET['CskhOrders'];
            if (isset($_GET['CskhOrders']['province_code']) && $_GET['CskhOrders']['province_code'] != '') {
                $post = TRUE;

                $model->province_code = $_GET['CskhOrders']['province_code'];
            }
            if (isset($_GET['CskhOrders']['sale_office_code']) && $_GET['CskhOrders']['sale_office_code'] != '') {
                $model->sale_office_code = $_GET['CskhOrders']['sale_office_code'];

                $post = TRUE;
            }
            if (isset($_GET['CskhOrders']['start_date']) && $_GET['CskhOrders']['start_date'] != '') {
                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['CskhOrders']['start_date'])));
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['CskhOrders']['end_date'])));
            } else {
                $model->start_date = '';
                $model->end_date = '';
            }
            if (isset($_GET['CskhOrders']['brand_offices_id']) && $_GET['CskhOrders']['brand_offices_id'] != '') {
                $model->brand_offices_id = $_GET['CskhOrders']['brand_offices_id'];

                $post = TRUE;
            }
            if (isset($_GET['CskhOrders']['sim']) && $_GET['CskhOrders']['sim'] != '') {
                $model->sim = $_GET['CskhOrders']['sim'];

                $post = TRUE;
            }
            if (isset($_GET['CskhOrders']['phone_contact']) && $_GET['CskhOrders']['phone_contact'] != '') {
                $model->phone_contact = $_GET['CskhOrders']['phone_contact'];

                $post = TRUE;
            }
        }

        if (isset($_POST['CskhOrders'])) {
            $post = TRUE;
            if ($_POST['CskhOrders']['start_date'] != '' && $_POST['CskhOrders']['end_date'] != '') {

                $model->attributes = $_POST['CskhOrders'];
                $model_search->attributes = $_POST['CskhOrders'];
                $model_search->start_date = $_POST['CskhOrders']['start_date'];
                $model_search->end_date = $_POST['CskhOrders']['end_date'];

                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['CskhOrders']['start_date'])));
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['CskhOrders']['end_date'])));


            }
            if (isset($_POST['CskhOrders']['sale_office_code']) && $_POST['CskhOrders']['sale_office_code'] != '') {
                $model->sale_office_code = $_POST['CskhOrders']['sale_office_code'];
                $model_search->sale_office_code = $_POST['CskhOrders']['sale_office_code'];
            }
            if (isset($_POST['CskhOrders']['brand_offices_id']) && $_POST['CskhOrders']['brand_offices_id'] != '') {
                $model->brand_offices_id = $_POST['CskhOrders']['brand_offices_id'];
                $model_search->brand_offices_id = $_POST['CskhOrders']['brand_offices_id'];
            }


            if (!$model->validate()) {
                $model->getErrors();
            }
        }

        $this->render('admin_recycle', array(
            'model' => $model,
            'post' => $post,
            'model_search' => $model_search,
        ));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param integer $id the ID of the model to be loaded
     *
     * @return CskhOrders the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = CskhOrders::model()->findByPk($id);
        if ($model === NULL)
            throw new CHttpException(404, 'The requested page does not exist.');

        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param CskhOrders $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'CskhOrders-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Lấy danh sách quận huyện theo tỉnh
     */
    public function actionGetDistrictByProvice()
    {

        if (!SUPER_ADMIN && !ADMIN) {
            if (Yii::app()->user->id) {
                $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                if ($user) {
                    if ($user->district_code != "") {
                        $district = District::model()->findByAttributes(array('code' => $user->district_code));
                        if ($district) {
                            echo CHtml::tag('option', array('value' => $user->district_code), CHtml::encode($district->name), TRUE);
                        }
                    }
                }
            }

        } else {
            $provice_code = Yii::app()->getRequest()->getParam("province_code", FALSE);
            if ($provice_code) {
                $criteria = new CDbCriteria();

                $criteria->condition = "province_code='" . $provice_code . "'";

                $data = District::model()->findAll($criteria);
                $return = CHtml::listData($data, 'code', 'name');
                echo "<option value=''>Chọn tất cả</option>";
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }
        }
    }

    /**
     * Lấy danh sách quận huyện theo tỉnh
     */
    public function actionGetBrandOfficesByDistrict()
    {
        if (!SUPER_ADMIN && !ADMIN) {
            if (Yii::app()->user->id) {
                $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                if ($user) {
                    if ($user->brand_offices_id != "") {
                        $brand = BrandOffices::model()->findByAttributes(array('id' => $user->ward_code));
                        if ($brand) {
                            echo CHtml::tag('option', array('value' => $user->brand_offices_id), CHtml::encode($brand->name), TRUE);
                        }
                    }
                }
            }
        } else {
            $district_code = Yii::app()->getRequest()->getParam("district_code", FALSE);
            if ($district_code) {
                $criteria = new CDbCriteria();

                $criteria->condition = "district_code='" . $district_code . "'";

                $data = BrandOffices::model()->findAll($criteria);
                $return = CHtml::listData($data, 'id', 'name');
                echo "<option value=''>Chọn tất cả</option>";
                foreach ($return as $k => $v) {
                    echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
                }
            }
        }
    }

    public function actionGetBrandOfficeByWard()
    {
        $ward_code = Yii::app()->getRequest()->getParam("ward_code", FALSE);
        if ($ward_code) {
            $criteria = new CDbCriteria();

            $criteria->condition = "ward_code='" . $ward_code . "'";

            $data = BrandOffices::model()->findAll($criteria);
            $return = CHtml::listData($data, 'id', 'name');
            echo "<option value=''>Chọn tất cả</option>";
            foreach ($return as $k => $v) {
                echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
            }
        }
    }

    /**
     * Láy danh sách người đại diện
     */
    public function actionProxy()
    {
        $value = Yii::app()->getRequest()->getParam("value", FALSE);
        $type = Yii::app()->getRequest()->getParam("type", FALSE);
        $order_id = Yii::app()->getRequest()->getParam("order_id", FALSE);

        if ($value && $type && $order_id) {

            $model = new User();
            if ($type == CskhOrders::SALE_OFFICE_PERSON) {
                $model->sale_offices_id = $value;
            } else {
                $model->brand_offices_id = $value;
            }
            $data = $this->renderPartial('_show_proxy',
                array('model' => $model, 'value' => $value, 'type' => $type, 'order_id' => $order_id)
            );

            echo $data;
            exit();
        }
    }

    public function actionOrderFiber()
    {
        $model = new CskhOrders('order_fiber');
        $model->unsetAttributes();  // clear any default values
        $model_search = new CskhOrders('order_fiber');
        $model_search->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
        $model_search->end_date = date('d/m/Y');
        $model->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
        $model->end_date = date('d/m/Y');
        $model->scenario = "orderFiber";
        if (isset($_REQUEST['CskhOrders'])) {
            $model->attributes = $_POST['CskhOrders'];
            $model_search->attributes = $_POST['CskhOrders'];
            $model_search->start_date = $_POST['CskhOrders']['start_date'];
            $model_search->end_date = $_POST['CskhOrders']['end_date'];

            $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['CskhOrders']['start_date'])));
            $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['CskhOrders']['end_date'])));
            $data = $model->searchfiber(TRUE);
        }
        $this->render('order_fiber', array(
            'model' => $model,
            'model_search' => $model_search,
            'data' =>$data
        ));
    }


}
