<?php

class CskhCtvUsersController extends AController
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
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'addPhoneToList', 'removePhoneList'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
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
        $model = new CskhCtvUsers();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['CskhCtvUsers'])) {
            $model->attributes = $_POST['CskhCtvUsers'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->user_id));
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

        if (isset($_POST['CskhCtvUsers'])) {
            $model->attributes = $_POST['CskhCtvUsers'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->user_id));
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
        $dataProvider = new CActiveDataProvider('CskhCtvUsers');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $data = array();
        $model = new CskhCtvUsers('search');
        $form = new CskhCtvUsers('search');
        $model->unsetAttributes();  // clear any default values
        $model->scenario = 'admin';


        $post = FALSE;

        if (isset($_POST['CskhCtvUsers'])) {

            $post = TRUE;
            $form->info_search = $model->info_search = isset($_POST['CskhCtvUsers']['info_search']) ? $_POST['CskhCtvUsers']['info_search'] : '';
            $form->input_type = $model->input_type = isset($_POST['CskhCtvUsers']['input_type']) ? $_POST['CskhCtvUsers']['input_type'] : '';

            if ($form->validate()) {

                $model->$_POST['CskhCtvUsers']['input_type'] = $model->info_search;

                if ($model->input_type == 'mobile') {
                    $standard_phone = self::makePhoneNumberStandard($model->info_search);
                    $criteria = new CDbCriteria();
                    $criteria->condition = "mobile ='$model->info_search' OR mobile ='$standard_phone'";
                    $ctv_user = CskhCtvUsers::model()->find($criteria);
                } else {
                    $ctv_user = CskhCtvUsers::model()->findByAttributes(array($_POST['CskhCtvUsers']['input_type'] => $model->info_search));
                }
                if (isset($ctv_user->user_id)) {
                    $data = $this->loadModel($ctv_user->user_id);
                }
            }
        }


        $this->render('admin', array(
            'model' => $model,
            'form' => $form,
            'data' => $data,
            'post' => $post,
        ));
    }

    /*
     * Phone list
     */

    public function actionPhonelist()
    {
        $data = array();
        $model = new CskhPhoneList();
        $form = new CskhCtvActions('search');
        $form_validate = new CskhCtvActions('search');
        if (isset($_POST['CskhCtvActions']) || isset($_REQUEST['CskhCtvActions'])) {

            if (isset($_POST['CskhCtvActions']['start_date']) && $_POST['CskhCtvActions']['start_date'] != '') {

                $form->start_date = $_POST['CskhCtvActions']['start_date'];
                $model->start_date = $form_validate->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['CskhCtvActions']['start_date']))) . " 00:00:00";
            }
            if (isset($_POST['CskhCtvActions']['end_date']) && $_POST['CskhCtvActions']['end_date'] != '') {
                $form->end_date = $_POST['CskhCtvActions']['end_date'];
                $model->end_date = $form_validate->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['CskhCtvActions']['end_date']))) . " 23:59:59";
            }
            $data = $model->getlistphone();
            $data = new CArrayDataProvider($data, array(
                'keyField' => FALSE,
                'pagination' => array(
                    'params'   => array(
                        'CskhCtvActions[start_date]'    => isset($_POST['CskhCtvActions']['start_date']) ? $_POST['CskhCtvActions']['start_date'] : '',
                        'CskhCtvActions[end_date]'       => isset($_POST['CskhCtvActions']['end_date']) ? $_POST['CskhCtvActions']['end_date'] : ''),
                    'pageSize' => 1000,
                ),
            ));

        }
        $this->render('phonelist', array(
            'model' => $model,
            'form' => $form,
            'form_validate' => $form_validate,
            'data' => $data,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionCommision()
    {
        // Khởi tạo biến
        $data_overview = array();
        $model = new CskhCtvActions('search');
        $model_publisher = new CskhCtvPublisherAward('search');
        $form = new CskhCtvActions('search');
        $form_validate = new CskhCtvActions('search');
        $model->unsetAttributes();  // clear any default values
        $model->scenario = 'admin';
        $post = 0;
        $type = '';
        $user_id = '';

        if (isset($_POST['CskhCtvActions']) || isset($_REQUEST['CskhCtvActions'])) {
            if (isset($_POST['CskhCtvActions'])) { // Xóa cache reset lại query theo cache (filter search)
                Yii::app()->cache->delete('start_date_commisson_ctv_1');
                Yii::app()->cache->delete('end_date_commisson_ctv_1');
                Yii::app()->cache->delete('user_id_commisson_ctv_1');
                Yii::app()->cache->delete('type_commisson_ctv_1');
                Yii::app()->cache->delete('start_date_commisson_ctv_2');
                Yii::app()->cache->delete('end_date_commisson_ctv_2');
                Yii::app()->cache->delete('user_id_commisson_ctv_2');
                Yii::app()->cache->delete('type_commisson_ctv_2');
            }
            if (!isset($_POST['CskhCtvActions'])) { // Nếu yêu cầu phân trang hoặc filter search.
                $_POST['CskhCtvActions'] = $_REQUEST['CskhCtvActions'];
            }
            if (isset($_REQUEST['CskhCtvActions']['type'])) { // Lưu lại type khi phân trang hoặc filter search.
                $type = $_REQUEST['CskhCtvActions']['type'];
            }
            if (isset($_REQUEST['CskhCtvActions']['order_code'])) { // Search theo order_code
                if (!empty($_REQUEST['CskhCtvActions']['order_code'])) {
                    $post = 2;
                    $model->order_code = $_REQUEST['CskhCtvActions']['order_code'];
                } else if (empty($_REQUEST['CskhCtvActions']['order_code'])) {
                    $post = 2;

                }
            }

            $model->attributes = $_POST['CskhCtvActions'];
            $form->attributes = $_POST['CskhCtvActions'];
            $form_validate->attributes = $_POST['CskhCtvActions'];


            // Format datetime cho querry
            if (isset($_POST['CskhCtvActions']['start_date']) && $_POST['CskhCtvActions']['start_date'] != '') {

                $form->start_date = $_POST['CskhCtvActions']['start_date'];
                $model->start_date = $form_validate->start_date = $model_publisher->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['CskhCtvActions']['start_date']))) . " 00:00:00";
            }
            if (isset($_POST['CskhCtvActions']['end_date']) && $_POST['CskhCtvActions']['end_date'] != '') {
                $form->end_date = $_POST['CskhCtvActions']['end_date'];
                $model->end_date = $form_validate->end_date = $model_publisher->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['CskhCtvActions']['end_date']))) . " 23:59:59";
            }

            if ($form_validate->validate()) { // Validate form
                $post = 1;
                $model->input_type = isset($_POST['CskhCtvActions']['input_type']) ? $_POST['CskhCtvActions']['input_type'] : '';
                if ($model->input_type == 'mobile') {
                    $model->info_search = self::makePhoneNumberStandard($model->info_search);

                }

                $model_users = new CskhCtvUsers();
                $model_users->$_POST['CskhCtvActions']['input_type'] = $model->info_search;

                $ctv_user = CskhCtvUsers::model()->findByAttributes(array($_POST['CskhCtvActions']['input_type'] => $model->info_search));

                if (isset($ctv_user->user_id)) {
                    $user_id = $ctv_user->user_id;
                    //Lấy dữ liệu tổng quan.
                    $data_overview[0] = array(
                        'user_name' => $ctv_user->user_name,
                        'ctv_type' => CskhCtvUsers::getTypeOfCtvById($ctv_user->user_id),
                        'owner_code' => $ctv_user->owner_code,
                        'inviter_code' => $ctv_user->inviter_code,
                        'province_code' => Province::model()->getProvince($ctv_user->province_code),
                        'bank_account' => CskhCtvUserBankAccount::getAccountNumber($ctv_user->user_id),
                        'bank_name' => CskhCtvBanks::getNameByUserId($ctv_user->user_id),
                        'ctv_2' => (isset($ctv_user->owner_code) && !empty($ctv_user->owner_code))
                            ? $model->getCtv2($ctv_user->owner_code) : 0,
                        'total_sim' => $model->getTotal(1, $ctv_user->user_id),
                        'total_package' => $model->getTotal(2, $ctv_user->user_id),
                        'commision_sim' => $model->getCommision(1, $ctv_user->user_id),
                        'commision_package' => $model->getCommision(2, $ctv_user->user_id),
                        'commision_award' => $model->getCommisionAward($ctv_user->user_id),
                        'renueve_sim' => $model->getRenueve(1, $ctv_user->user_id),
                        'renueve_package' => $model->getRenueve(2, $ctv_user->user_id),
                    );
                    $data_overview = new CArrayDataProvider($data_overview, array(
                        'keyField' => FALSE,
                    ));


                }
            }
        }

        $this->render('commision', array(
            'model' => $model,
            'form' => $form,
            'form_validate' => $form_validate,
            'data_overview' => $data_overview,
            'post' => $post,
            'user_id' => $user_id,
            'type' => $type,
            'model_publisher' => $model_publisher,
        ));
    }


    public function actionPaid()
    {
        $data = array();
        $model = new CskhCtvCommissionStatisticMonth('search');
        $form = new CskhCtvCommissionStatisticMonth('search');
        $model->unsetAttributes();  // clear any default values
        $model->scenario = 'admin';


        $post = FALSE;

        if (isset($_POST['CskhCtvCommissionStatisticMonth'])) {

            $post = TRUE;
            $model->attributes = $form->attributes = $_POST['CskhCtvCommissionStatisticMonth'];
            $form->info_search = $_POST['CskhCtvCommissionStatisticMonth']['info_search'];
            $form->input_type = $model->input_type = $_POST['CskhCtvCommissionStatisticMonth']['input_type'];
            $form->month = $_POST['CskhCtvCommissionStatisticMonth']['month'];
            $form->year = $_POST['CskhCtvCommissionStatisticMonth']['year'];

            if ($form->validate()) {
                $model->input_type = $form->input_type = isset($_POST['CskhCtvCommissionStatisticMonth']['input_type']) ? $_POST['CskhCtvCommissionStatisticMonth']['input_type'] : '';

                if ($model->input_type == 'mobile') {
                    $model->info_search = self::makePhoneNumberStandard($model->info_search);
                }
                $ctv_user = CskhCtvUsers::model()->findByAttributes(array($_POST['CskhCtvCommissionStatisticMonth']['input_type'] => $model->info_search));
                if (isset($ctv_user->user_id)) {
                    $model->publisher_id = $ctv_user->user_id;
                }
            }
        }

        $this->render('paid', array(
            'model' => $model,
            'form' => $form,
            'data' => $data,
            'post' => $post,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param integer $id the ID of the model to be loaded
     *
     * @return CskhCtvUsers the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = CskhCtvUsers::model()->findByPk($id);
        if ($model === NULL)
            throw new CHttpException(404, 'The requested page does not exist.');

        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param CskhCtvUsers $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'actv-users-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


    /**
     * Kiểm tra mã xác thực.
     */
    public function actionGetInfo()
    {
        $data = 0;
        $user_id = Yii::app()->request->getParam('user_id', FALSE);

        if ($user_id) {
            $model_change = new CskhCtvRequestChangePublisherInfo();
            $model_change->publisher_id = $user_id;
            $data = $this->renderPartial('_popup_get_info_change', array('model' => $model_change, 'user_id' => $user_id));

        }
        echo $data;
        exit();
    }

    public static function makePhoneNumberStandard($phoneNumber)
    {
        $phoneNumberStandard = '';
        if ($phoneNumber != '') {
            if (substr($phoneNumber, 0, 1) == '0') {
                $phoneNumberStandard = substr($phoneNumber, 1, strlen($phoneNumber));
            } else if (substr($phoneNumber, 0, 2) == '84') {
                $phoneNumberStandard = substr($phoneNumber, 2, strlen($phoneNumber));
            }
            $phoneNumberStandard = '84' . $phoneNumberStandard;
        }

        return $phoneNumberStandard;
    }

    /*
     * Xử lý thêm số điện thoại vào api alowlist
     */
    public function actionAddPhoneToList()
    {
        $msisdn = Yii::app()->request->getParam('msisdn');
        $service_code = Yii::app()->request->getParam('service_code');
        $data_ctv = new CskhCtvUsers();
        $data_input = array(
            'msisdn' => $msisdn,
            'service_code' => $service_code,
        );
        //call api add alowlist
        $data_output = $data_ctv->addPhoneToList($data_input);
        if ($data_output['code'] == 1) {
            $phonelist = new CskhPhoneList();
            $phonelist->phone = $msisdn;
            $phonelist->service = $service_code;
            $phonelist->tool = 'ADD';
            $phonelist->user = Yii::app()->user->name;

            $phonelist->save();

        }
        echo CJSON::encode($data_output);
        Yii::app()->end();
    }

    /*
    * Xử lý xóa số điện thoại khỏi api alowlist
    */
    public function actionRemovePhoneList()
    {
        $msisdn = Yii::app()->request->getParam('msisdn');
        $service_code = Yii::app()->request->getParam('service_code');
        $data_ctv = new CskhCtvUsers();
        $data_input = array(
            'msisdn' => $msisdn,
            'service_code' => $service_code,
        );
        //call api add alowlist
        $data_output = $data_ctv->removePhoneToList($data_input);
        if ($data_output['code'] == 1) {
            $phonelist = new CskhPhoneList();
            $phonelist->phone = $msisdn;
            $phonelist->service = $service_code;
            $phonelist->tool = 'DELETE';
            $phonelist->user = Yii::app()->user->name;
            $phonelist->save();

        }
        echo CJSON::encode($data_output);
        Yii::app()->end();
    }

}
