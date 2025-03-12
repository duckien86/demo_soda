<?php

    class ACompleteOrdersController extends AController
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
         * Displays a particular model.
         *
         * @param integer $id the ID of the model to be displayed
         */
        public function actionView($id)
        {
            $order_state   = AOrderState::getListOrderState($id, TRUE, 30);//order history
            $order_detail  = AOrderState::getDetailOrder($id);
            $order_shipper = AOrders::getShipperDetail($id);
            $logs_sim      = ALogsSim::getLogs($id);
            $this->render('view', array(
                'model'         => $this->loadModel($id),
                'order_state'   => $order_state,
                'order_detail'  => $order_detail,
                'order_shipper' => $order_shipper,
                'logs_sim'      => $logs_sim,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionAdmin()
        {
            $model           = new AOrders('search');
            $model_search    = new AOrders('search');
            $model->scenario = 'admin_complete';
            $model->unsetAttributes();  // clear any default values
            $post                     = FALSE;
            $model_search->start_date = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
            $model_search->end_date   = date('d/m/Y');
            $model->start_date        = date('d/m/Y', strtotime(date('Y-m-d') . ' -7 days'));
            $model->end_date          = date('d/m/Y');
            if (isset($_GET['AOrders'])) {
                if (isset($_GET['AOrders']['sim']) && $_GET['AOrders']['sim'] != '') {
                    $post = TRUE;
                }
                $model->attributes = $_GET['AOrders'];
                if (isset($_GET['AOrders']['province_code']) && $_GET['AOrders']['province_code'] != '') {
                    $post = TRUE;

                    $model->province_code = $_GET['AOrders']['province_code'];
                }
                if (isset($_GET['AOrders']['sale_office_code']) && $_GET['AOrders']['sale_office_code'] != '') {
                    $model->sale_office_code = $_GET['AOrders']['sale_office_code'];

                    $post = TRUE;
                }
                if (isset($_GET['AOrders']['brand_offices_id']) && $_GET['AOrders']['brand_offices_id'] != '') {
                    $model->brand_offices_id = $_GET['AOrders']['brand_offices_id'];

                    $post = TRUE;
                }
                if (isset($_GET['AOrders']['start_date']) && $_GET['AOrders']['start_date'] != '') {
                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['AOrders']['start_date'])));
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $_GET['AOrders']['end_date'])));
                }
            }

            if (isset($_POST['AOrders'])) {
                $post = TRUE;
                if ($_POST['AOrders']['start_date'] != '' && $_POST['AOrders']['end_date'] != '') {
                    $model->attributes        = $_POST['AOrders'];
                    $model_search->attributes = $_POST['AOrders'];
                    $model_search->start_date = $_POST['AOrders']['start_date'];
                    $model_search->end_date   = $_POST['AOrders']['end_date'];
                    $model->start_date        = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['AOrders']['start_date'])));
                    $model->end_date          = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['AOrders']['end_date'])));
                }
                if (isset($_POST['AOrders']['sale_office_code']) && $_POST['AOrders']['sale_office_code'] != '') {
                    $model->sale_office_code        = $_POST['AOrders']['sale_office_code'];
                    $model_search->sale_office_code = $_POST['AOrders']['sale_office_code'];
                }
                if (isset($_POST['AOrders']['brand_offices_id']) && $_POST['AOrders']['brand_offices_id'] != '') {
                    $model->brand_offices_id        = $_POST['AOrders']['brand_offices_id'];
                    $model_search->brand_offices_id = $_POST['AOrders']['brand_offices_id'];
                }
                if (isset($_POST['AOrders']['province_code']) && $_POST['AOrders']['province_code'] != '') {
                    $post = TRUE;

                    $model->province_code        = $_POST['AOrders']['province_code'];
                    $model_search->province_code = $_POST['AOrders']['province_code'];
                }
                if (!$model->validate()) {
                    $model->getErrors();
                }
            }

            $this->render('admin', array(
                'model'        => $model,
                'model_search' => $model_search,
                'post'         => $post,
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
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }

        /**
         * Hiện popup tra mã xác thực.
         */
        public function actionCheckOtp()
        {
            $otp_form = new AOtpForm();
            $return   = array();
            $order_id = Yii::app()->request->getParam('order_id', FALSE);
            if ($order_id) {
                $otp_form->order_id = $order_id;
                $sim                = ASim::model()->findByAttributes(array('order_id' => $order_id));
                if (!Yii::app()->cache->get("check_topup_" . $order_id)) {

                    if ($sim) {
                        if ($sim->type == ASim::TYPE_POSTPAID) {
                            if ($sim->status == ASim::SIM_ACTIVE) { // Nếu đã khai báo rồi.Chuyển sang form kiểm tra hòa mạng.

                                $return = $this->renderPartial('_popup_check_roaming',
                                    array('order_id' => $order_id));

                            } else { // Nếu chưa hòa mạng. Chuyển sang form check OTP
                                $return = $this->renderPartial('_popup_check_otp',
                                    array('otp_form' => $otp_form)
                                );
                            }
                        } else { //Trả trước
                            if ($sim->status != ASim::SIM_ACTIVE) {
                                $return = $this->renderPartial('_popup_check_otp',
                                    array('otp_form' => $otp_form)
                                );
                            } else {
                                $return = TRUE;
                            }
                        }
                    }
                } else { // Pass đến bước gán gói cước.

                    if (Yii::app()->cache->get("check_topup_" . $order_id) == 1) {

                        $data_resquest = array(
                            'order_id' => $order_id,
                            'user_id'  => Yii::app()->user->id,
                        );

                        $data_resquest_complete = CJSON::encode($data_resquest);
                        $security               = self::getSecurity(Yii::app()->user->password_sub, Yii::app()->user->token, $data_resquest_complete);

                        $check = AOrders::checkPrePackage($data_resquest_complete, $security, Yii::app()->user->username);

                        $return = $this->renderPartial('_popup_result_register_package', array('order_id' => $order_id, 'msg_end' => $check['status']['msg'], 'response' => $check['status']['code']));
                        echo $return;
                        exit();
                    }
                }

            }
            echo $return;
            exit();
        }

        /**
         * Kiểm tra mã xác thực.
         */
        public function actionCheckOtpExist()
        {
            $data     = 0;
            $order_id = Yii::app()->request->getParam('order_id', FALSE);
            $otp      = Yii::app()->request->getParam('otp', FALSE);
            if ($order_id && $otp) {
                $order = AOrders::model()->findByAttributes(array('id' => $order_id, 'otp' => $otp));
                if ($order) {
                    $data = 1;
                }
            }
            echo $data;
            exit();
        }

        /**
         * Gửi lại mã xác thực cho khách hàng.
         */
        public function actionResendOtp()
        {
            $result   = array(
                'success' => FALSE,
                'msg'     => ''
            );
            $order_id = $id = Yii::app()->request->getParam('order_id', FALSE);
            if ($order_id) {

                $orders        = AOrders::model()->findByAttributes(array('id' => $order_id));
                $order_details = AOrderDetails::model()->findByAttributes(array('order_id' => $order_id, 'type' => 'sim'));
                if (!empty($orders->phone_contact) && $order_details->item_name) {
                    $msisdn_send = $orders->phone_contact;
                    $msisdn      = $order_details->item_name;
                    if ($orders->otp) {
                        // Send MT.
                        //Lưu log gọi api.
                        $mt_content = Yii::t('adm/mt_content', 'message_resend_otp', array(
                            '{msisdn}'    => $msisdn,
                            '{token_key}' => $orders->otp,
                        ));
                        $logFolder  = "Resend_otp_complete_orders";
                        if (AOrders::sentMtVNP($msisdn_send, $mt_content, $logFolder)) {
                            $result['success'] = TRUE;
                            $result['msg']     = "Gửi mã xác thực thành công!";
                        } else {
                            $result['success'] = TRUE;
                            $result['msg']     = "Lỗi gửi mã xác thực!";
                        }
                    }
                } else {
                    $result['success'] = FALSE;
                    $result['msg']     = "Không có số điện thoại khách hàng hoặc số thuê bao!";
                }
            }
            echo CJSON::encode($result);
        }


        /**
         * Hiện popup số serial number.
         */
        public function actionCheckSerialSim()
        {
            $data           = 0;
            $phone_customer = 0;
            $order_id       = $id = Yii::app()->request->getParam('order_id', FALSE);
            if ($order_id) {
                $order          = AOrders::model()->findByAttributes(array('id' => $order_id));
                $phone_customer = $order->phone_contact;
            }
            $data = $this->renderPartial('_popup_serial_number',
                array('order_id' => $order_id, 'phone_customer' => $phone_customer)
            );
            echo $data;
            exit();
        }

        /**
         * Xác thực số serial và call api khai báo sim.
         */
        public function actionCallApiRegisterSim()
        {
            $result = array(
                'success'  => FALSE,
                'msg'      => '',
                'continue' => FALSE,
                'data'     => array(),
            );

            $customer_type = $personal_id_type = $national = array();

            $serial_number  = Yii::app()->request->getParam('serial_number', FALSE);
            $phone_customer = Yii::app()->request->getParam('phone_customer', FALSE);
            $order_id       = Yii::app()->request->getParam('order_id', FALSE);

            // B1: Check số serial phải là 10 số.
            if ($serial_number && $phone_customer && $order_id) {
                $pattern = '/^([0-9]{10}|[0-9]{10})$/';
                if (!preg_match($pattern, $serial_number)) {
                    $result['success'] = FALSE;
                    $result['msg']     = 'Số serial phải có 10 chữ số! Vui lòng thử lại!';
                } else {

                    $order_details = AOrderDetails::model()->findByAttributes(array('order_id' => $order_id, 'type' => 'sim'));
                    if ($order_details->item_name) {
                        $sim = ASim::model()->findByAttributes(array('msisdn' => $order_details->item_name));
                        if ($sim) {
                            if ($sim->type == ASim::TYPE_PREPAID) {
                                $result['continue'] = TRUE;
                            }
                        }
                    }
                    // Gọi api đăng ký sim.
                    $data_resquest = array(
                        'user_id'           => -1,
                        'sim_serial_number' => $serial_number,
                        'order_id'          => $order_id,
                    );
                    $data_resquest = CJSON::encode($data_resquest);
                    $security      = self::getSecurity(Yii::app()->user->password_sub, Yii::app()->user->token, $data_resquest);
                    $register      = ASim::registerSim($data_resquest, $security, Yii::app()->user->username);

                    if (isset($register['status']['code']) && ($register['status']['code'] == 1)) {
                        $result['success'] = TRUE;
                        $result['msg']     = '';
                        Yii::app()->cache->set("serial_10_" . $order_id, $serial_number, 9999999999);


                    } else {
                        $result['success'] = FALSE;
                        $result['msg']     = $register['status']['msg'];
                    }
                }
            } else {
                if (!$serial_number) {
                    $result = array(
                        'success' => FALSE,
                        'msg'     => 'Bạn phải nhập mã xác thực!'
                    );
                }
            }
            echo CJSON::encode($result);
            exit();
        }

        public function actionShowRoaming()
        {
            $order_id = Yii::app()->request->getParam('order_id', FALSE);

            $data = $this->renderPartial('_popup_check_roaming',
                array('order_id' => $order_id));
            echo($data);
            exit();
        }

        public function actionCheckRoaming()
        {
            $order_id = Yii::app()->request->getParam('order_id', FALSE);
            $result   = array(
                'success' => FALSE,
                'msg'     => '',
            );
            if ($order_id) {
                $model        = new AOrders();
                $data_request = array(
                    'user_id'  => -1,
                    'order_id' => $order_id,
                );
                $response     = $model->checkRoaming($data_request);
                if ($response['code'] == -1) {
                    $result['msg'] = $response['msg'];
                } else {
                    $result['msg'] = "Hoàn tất đơn hàng!";
                }
            }
            echo CJSON::encode($result);
            exit();
        }

        /**
         * @param string $order_id
         * Đăng ký thông tin thuê bao.
         */
        public function actionRegisterInfo($order_id = '', $serial_number = '')
        {
            $user_id = -1;
            // B2.1: Data request.
            $data_resquest          = array(
                'user_id' => -1,
                'phone'   => '0912135250',
            );
            $complete_form_validate = new ACustomerForm();
            $upload_form            = $upload_form_validate = new AUploadForm();
            $customer_type          = $personal_id_type = $national = $provinces = array();
            $complete_form          = new ACustomerForm();
            $post                   = 0;

            if (!isset($_REQUEST['ACustomerForm']) || isset($_POST['ACustomerForm'])) {

                $data_res_get_info = CJSON::encode($data_resquest);
                $security          = self::getSecurity(Yii::app()->user->password_sub, Yii::app()->user->token, $data_res_get_info);
                $data_response     = ACustomers::getCustomerCompleteInfo($data_res_get_info, $security, Yii::app()->user->username);

                if (!empty($data_response)) {
                    if ($data_response['provinces']) {
                        $provinces = CHtml::listData($data_response['provinces'], 'id', 'name');
                    }
                    if ($data_response['customer_type']) {
                        $customer_type = CHtml::listData($data_response['customer_type'], 'id', 'name');
                    }
                    if ($data_response['personal_id_type']) {
                        $personal_id_type = CHtml::listData($data_response['personal_id_type'], 'id', 'name');
                    }
                    if ($data_response['national']) {
                        $national = CHtml::listData($data_response['national'], 'id', 'name');
                    }

                    $order_detail         = AOrderDetails::model()->findByAttributes(array('order_id' => $order_id, 'type' => 'sim'));
                    $order_detail_package = AOrderDetails::model()->findByAttributes(array('order_id' => $order_id, 'type' => 'package'));
                    if ($order_detail_package->item_name) {
                        $package = APackage::model()->findByAttributes(array('code' => $order_detail_package->item_id));
                    }
                    $order = AOrders::model()->findByAttributes(array('id' => $order_id));

                    if ($order_detail->item_name) {
                        $sim                                           = ASim::model()->findByAttributes(array('msisdn' => $order_detail->item_name));
                        $complete_form->full_name                      = $order->full_name;
                        $complete_form->gender                         = 1;
                        $complete_form->number_page                    = $sim->personal_id;
                        $complete_form->personal_id_type               = $sim->personal_id_type;
                        $complete_form->phone_number                   = $sim->msisdn;
                        $complete_form->register_for                   = $sim->register_for;
                        $complete_form->customer_type                  = $sim->customer_type_vnpt_net;
                        $complete_form->package_code                   = isset($package->name) ? $package->name : '';
                        $complete_form->nation                         = 232;
                        $complete_form->subscription_permanent_address = $sim->address;
                    }
                }
            }

            self::performAjaxValidation($complete_form_validate);
            $response = $msg = '';

            //Step 1: Hoàn thành form đăng ký.
            if (isset($_POST['ACustomerForm'])) {

                $complete_form->attributes = $complete_form_validate->attributes = $_POST['ACustomerForm'];

                if ($_POST['ACustomerForm']['birth_day']) {
                    $complete_form->birth_day = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['ACustomerForm']['birth_day'])));
                }
                if ($_POST['ACustomerForm']['personal_id_create_date']) {
                    $complete_form->personal_id_create_date = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['ACustomerForm']['personal_id_create_date'])));
                }
                if ($complete_form_validate->validate()) {

                    // Dữ liệu gửi đăng ký thông tin thuê bao.
                    $data_request_info = array(
                        'order_id'                       => $order_id,
                        'user_id'                        => -1,
                        'subscription_name'              => $complete_form->full_name,
                        'subscription_birth_date'        => $complete_form_validate->birth_day,
                        'subscription_sex'               => $complete_form->gender,
                        'subscription_country'           => $complete_form->nation,
                        'subscription_personal_id'       => $complete_form->number_page,
                        'subscription_personal_date'     => $complete_form_validate->personal_id_create_date,
                        'subscription_personal_office'   => $complete_form->personal_id_create_place,
                        'subscription_personal_type'     => $complete_form->personal_id_type,
                        'subscription_permanent_address' => $complete_form->subscription_permanent_address,
                        'subscription_type'              => 'Bản thân',
                        'subscription_customer_type'     => $complete_form->customer_type,
                        'subscription_sim_number'        => $complete_form->phone_number,
                        'subscription_sim_seri_number'   => $complete_form->sim,
                        'subscription_pacakge'           => '',
                        'exchange_addresss'              => '',
                    );
                    // Gọi api đăng ký thông tin thuê bao(Anh Thắng).
                    $data_request_info      = CJSON::encode($data_request_info);
                    $security               = self::getSecurity(Yii::app()->user->password_sub, Yii::app()->user->token, $data_request_info);
                    $data_response_register = ACustomers::getCustomerRegisterInfo($data_request_info, $security, Yii::app()->user->username);

                    $data_check = CJSON::decode($data_response_register['data']);

                    if ($data_response_register['status']['code'] == 1) {
                        $msg_success = "Đăng ký thành công! ";
                        if ($data_check['number_register'] < 3) {
                            $msg = "Thực hiện đăng ký thông tin thuê bao trên phiếu đăng ký";
                        } else {
                            $msg = "Thực hiện đăng ký thông tin thuê bao trên hợp đồng";
                        }
                        $post = 1;
                        Yii::app()->user->setFlash('success', $msg_success);

                        // Call api hoàn tất đơn hàng
                        return $this->render('complete_info', array(
                            'upload_form'            => $upload_form,
                            'upload_form_validate'   => $upload_form_validate,
                            'customer_type'          => $customer_type,
                            'personal_id_type'       => $personal_id_type,
                            'national'               => $national,
                            'order_id'               => $order_id,
                            'provinces'              => $provinces,
                            'complete_form'          => $complete_form,
                            'complete_form_validate' => $complete_form_validate,
                            'tab'                    => '_form_upload',
                            'post'                   => $post,
                            'msg'                    => $msg
                        ));
                    }
                }

            }
//            self::performUploadAjaxValidation($upload_form);
            //Step 2: Upload ảnh
            $tab = '';
            if (isset($_POST['AUploadForm'])) {

                // Upload ảnh.
                $upload_form->attributes = $_POST['AUploadForm'];
                if ($upload_form->photo_face_url != '' && $upload_form->photo_personal1_url && $upload_form->photo_personal2_url && $upload_form->photo_order_board_url) {
                    //Dữ liệu gửi api hoàn tất đơn hàng.
                    $users    = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                    $diachigd = array();
                    if ($users->province_code != '') {
                        $diachigd = AProvince::model()->findByAttributes(array('code' => $users->province_code));
                    }
                    $data_resquest_complete = array(
                        'order_id'                => $order_id,
                        'user_id'                 => -1,
                        'subscription_sim_number' => $complete_form->phone_number,
                        'photo_face_url'          => $upload_form->photo_face_url,
                        'photo_personal1_url'     => $upload_form->photo_personal1_url,
                        'photo_personal2_url'     => $upload_form->photo_personal2_url,
                        'photo_order_board_url'   => $upload_form->photo_order_board_url,
                        'agent_code'              => $users->phone,
                        'diachigd'                => $diachigd->vnp_province_id,
                    );
                    // Goi api hoàn tất đơn hàng.
                    $data_resquest_complete = CJSON::encode($data_resquest_complete);
                    $security               = self::getSecurity(Yii::app()->user->password_sub, Yii::app()->user->token, $data_resquest_complete);
                    $data_response          = ACustomers::getCustomerCompleteOrders($data_resquest_complete, $security, Yii::app()->user->username);

                    $msg      = $data_response['msg'];
                    $response = $data_response['code'];
                    if ($response != 1) {
                        $tab = '_form_register_info';
                    } else {
                        $tab = '_form_upload';
                    }
                } else {
                    Yii::app()->user->setFlash('error', 'Ảnh upload thiếu!');
                }

                return $this->render('complete_info', array(
                    'upload_form'            => $upload_form,
                    'upload_form_validate'   => $upload_form_validate,
                    'customer_type'          => $customer_type,
                    'personal_id_type'       => $personal_id_type,
                    'national'               => $national,
                    'provinces'              => $provinces,
                    'order_id'               => $order_id,
                    'complete_form'          => $complete_form,
                    'complete_form_validate' => $complete_form_validate,
                    'tab'                    => $tab,
                    'post'                   => $post,
                    'msg'                    => $msg,
                    'response'               => $response,
                ));

            }

            return $this->render('complete_info', array(
                'complete_form'          => $complete_form,
                'complete_form_validate' => $complete_form_validate,
                'customer_type'          => $customer_type,
                'personal_id_type'       => $personal_id_type,
                'national'               => $national,
                'order_id'               => $order_id,
                'provinces'              => $provinces,
                'upload_form'            => $upload_form,
                'upload_form_validate'   => $upload_form_validate,
                'tab'                    => '_form_register_info',
                'post'                   => $post,
            ));
        }

        /**
         * Kiểm tra gói cước tồn tại.
         */
        public function actionCheckExistPackage()
        {
            $check         = array();
            $order_id      = Yii::app()->request->getParam('order_id', FALSE);
            $order_details = AOrderDetails::model()->findByAttributes(array('order_id' => $order_id, 'type' => 'package'));
//            if ($order_details) {

            $data_resquest = array(
                'order_id' => $order_id,
                'user_id'  => Yii::app()->user->id,
            );

            $data_resquest_complete = CJSON::encode($data_resquest);
            $security               = self::getSecurity(Yii::app()->user->password_sub, Yii::app()->user->token, $data_resquest_complete);

            $check = AOrders::checkPrePackage($data_resquest_complete, $security, Yii::app()->user->username);
            if ($check['status']['code'] == 2) {
                Yii::app()->cache->set("check_topup_" . $order_id, 1, 9999999);
            }
//            } else {
//                $check['status']['msg']  = 'Đơn hàng đã được hoàn tất!';
//                $check['status']['code'] = 1;
//            }

            $data = $this->renderPartial('_popup_result_register_package', array('order_id' => $order_id, 'msg_end' => $check['status']['msg'], 'response' => $check['status']['code']));
            echo $data;
            exit();
        }

        /**
         * Performs the AJAX validation.
         * $provinces
         *
         * @param AOrders $model the model to be validated
         */
        protected function performAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'register-info-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }

        /**
         * Performs the AJAX validation upload form.
         * $provinces
         *
         * @param AOrders $model the model to be validated
         */
        protected function performUploadAjaxValidation($model)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'upload-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }

        /**
         * action upload image
         */
        public function actionThumbnail1()
        {
            $dir_upload = Yii::app()->params->upload_dir_path . 'complete_order';
            $time       = date("Ymdhis");
            $DS         = DIRECTORY_SEPARATOR;
            if (isset($_POST['tempFileName1']) && $_POST['tempFileName1'] != '') {
                // file temporary
                $fileTemporary = $_POST['tempFileName1'];
                // temporary folder
                $temporaryFolder = $dir_upload . '/temp/';

                if (!file_exists($temporaryFolder)) {
                    mkdir($temporaryFolder, 0777, TRUE);
                }
                // get upload file info
                $fileUploadInfo = pathinfo($fileTemporary);


                $fileUploadNewName = Utils::unsign_string($fileUploadInfo['filename']) . '-' . time();

                // init folder contain file
                $destinationFolder = $dir_upload . $DS . $time . $DS;

                // check and create folder;
                if (!file_exists($destinationFolder)) {
                    mkdir($destinationFolder, 0777, TRUE);
                    mkdir($destinationFolder . 'images/', 0777, TRUE);
                }

                // folder destination
                $destinationFolder .= 'images/';

                $control_img = Utils::resizeImage($temporaryFolder . $fileTemporary);
                // copy temporary file to image file folder and delete in temporary folder
                if ($control_img == TRUE) {

                    copy($temporaryFolder . $fileTemporary, $destinationFolder . $fileUploadNewName . '.' . $fileUploadInfo['extension']);
                    unlink($temporaryFolder . $fileTemporary);
                    // Log api
                    $type = 'upload_thumb1';
                    $id   = Yii::app()->request->csrfToken;

                    $logMsg   = array();
                    $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
                    $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

                    $control_img = urlencode(base64_encode(file_get_contents($destinationFolder . $fileUploadNewName . '.' . $fileUploadInfo['extension'])));
                    //Call api lấy đường dẫn ảnh.

                    $data_header    = array(
                        'Content-Type: application/x-www-form-urlencoded',
                    );
                    $image_response = '&image= ' . $control_img;
                    $url_get_image  = Yii::app()->params['api_get_url_image'] . "?token=" . Yii::app()->user->token;
                    // Gọi api để lấy link ảnh.
                    $stt = 0;
                    do {
                        $stt++;
                        $url = Utils::cUrlPostJson($url_get_image, $image_response, TRUE, 15, $http_status, $data_header);
                    } while ($http_status != 200 && $stt <= 3);

                    $logMsg[]  = array($url, 'Output: ' . __LINE__, 'T', time());
                    $logFolder = "Log_call_api/" . date("Y/m/d");
                    $logObj    = ATraceLog::getInstance($logFolder);
                    $logObj->setLogFile('upload_thumb1.log');
                    $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
                    $logObj->processWriteLogs($logMsg);

                    $url_source = $destinationFolder . $fileUploadNewName . '.' . $fileUploadInfo['extension'];

                    if ($url == "error") {
                        echo CJSON::encode(array(
                            'status'    => FALSE,
                            'file_name' => '',
                            'msg'       => 'Có lỗi trong quá trình tải ảnh vui lòng thử lại!',
                        ));
                    } else {
                        echo CJSON::encode(array(
                            'status'       => TRUE,
                            'file_name'    => $url,
                            'file_preview' => $url_source,
                            'msg'          => '',
                        ));
                    }
                }
            } else {
                echo CJSON::encode(array(
                    'status'    => FALSE,
                    'file_name' => '',
                    'msg'       => 'Vui lòng chọn file để upload',
                ));
            }

            exit();
        }

        /**
         * action upload image
         */
        public function actionThumbnail2()
        {
            $dir_upload = Yii::app()->params->upload_dir_path . 'complete_order';
            $time       = date("Ymdhis");
            $DS         = DIRECTORY_SEPARATOR;
            if (isset($_POST['tempFileName2']) && $_POST['tempFileName2'] != '') {
                // file temporary
                $fileTemporary = $_POST['tempFileName2'];
                // temporary folder
                $temporaryFolder = $dir_upload . '/temp/';

                if (!file_exists($temporaryFolder)) {
                    mkdir($temporaryFolder, 0777, TRUE);
                }
                // get upload file info
                $fileUploadInfo = pathinfo($fileTemporary);


                $fileUploadNewName = Utils::unsign_string($fileUploadInfo['filename']) . '-' . time();

                // init folder contain file
                $destinationFolder = $dir_upload . $DS . $time . $DS;

                // check and create folder;
                if (!file_exists($destinationFolder)) {
                    mkdir($destinationFolder, 0777, TRUE);
                    mkdir($destinationFolder . 'images/', 0777, TRUE);
                }

                // folder destination
                $destinationFolder .= 'images/';
                $control_img = Utils::resizeImage($temporaryFolder . $fileTemporary);
                // copy temporary file to image file folder and delete in temporary folder
                if ($control_img == TRUE) {

                    copy($temporaryFolder . $fileTemporary, $destinationFolder . $fileUploadNewName . '.' . $fileUploadInfo['extension']);
                    unlink($temporaryFolder . $fileTemporary);

                    // Log api
                    $type = 'upload_thumb2';
                    $id   = Yii::app()->request->csrfToken;

                    $logMsg   = array();
                    $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
                    $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

                    $control_img = urlencode(base64_encode(file_get_contents($destinationFolder . $fileUploadNewName . '.' . $fileUploadInfo['extension'])));
                    //Call api lấy đường dẫn ảnh .
                    $data_header    = array(
                        'Content-Type: application/x-www-form-urlencoded',
                    );
                    $url_get_image  = Yii::app()->params['api_get_url_image'] . "?token=" . Yii::app()->user->token;
                    $image_response = '&image= ' . $control_img;
                    // Gọi api để lấy link ảnh.
                    $stt = 0;
                    do {
                        $stt++;
                        $url = Utils::cUrlPostJson($url_get_image, $image_response, TRUE, 15, $http_status, $data_header);
                    } while ($http_status != 200 && $stt <= 3);

                    $logMsg[]  = array($url, 'Output: ' . __LINE__, 'T', time());
                    $logFolder = "Log_call_api/" . date("Y/m/d");
                    $logObj    = ATraceLog::getInstance($logFolder);
                    $logObj->setLogFile('upload_thumb2.log');
                    $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
                    $logObj->processWriteLogs($logMsg);

                    $url_source = $destinationFolder . $fileUploadNewName . '.' . $fileUploadInfo['extension'];
                    if ($url == "error") {
                        echo CJSON::encode(array(
                            'status'    => FALSE,
                            'file_name' => '',
                            'msg'       => 'Có lỗi trong quá trình tải ảnh vui lòng thử lại!',
                        ));
                    } else {
                        echo CJSON::encode(array(
                            'status'       => TRUE,
                            'file_name'    => $url,
                            'file_preview' => $url_source,
                            'msg'          => '',
                        ));
                    }

                }
            } else {
                echo CJSON::encode(array(
                    'status'    => FALSE,
                    'file_name' => '',
                    'msg'       => 'Vui lòng chọn file để upload',
                ));
            }

            exit();
        }

        /**
         * action upload image
         */
        public function actionThumbnail3()
        {
            $dir_upload = Yii::app()->params->upload_dir_path . 'complete_order';
            $time       = date("Ymdhis");
            $DS         = DIRECTORY_SEPARATOR;
            if (isset($_POST['tempFileName3']) && $_POST['tempFileName3'] != '') {
                // file temporary
                $fileTemporary = $_POST['tempFileName3'];
                // temporary folder
                $temporaryFolder = $dir_upload . '/temp/';
                if (!file_exists($temporaryFolder)) {
                    mkdir($temporaryFolder, 0777, TRUE);
                }
                // get upload file info
                $fileUploadInfo = pathinfo($fileTemporary);


                $fileUploadNewName = Utils::unsign_string($fileUploadInfo['filename']) . '-' . time();

                // init folder contain file
                $destinationFolder = $dir_upload . $DS . $time . $DS;

                // check and create folder;
                if (!file_exists($destinationFolder)) {
                    mkdir($destinationFolder, 0777, TRUE);
                    mkdir($destinationFolder . 'images/', 0777, TRUE);
                }

                // folder destination
                $destinationFolder .= 'images/';
                $control_img = Utils::resizeImage($temporaryFolder . $fileTemporary);
                // copy temporary file to image file folder and delete in temporary folder
                if ($control_img == TRUE) {

                    copy($temporaryFolder . $fileTemporary, $destinationFolder . $fileUploadNewName . '.' . $fileUploadInfo['extension']);
                    unlink($temporaryFolder . $fileTemporary);

                    // Log api
                    $type = 'upload_thumb3';
                    $id   = Yii::app()->request->csrfToken;

                    $logMsg   = array();
                    $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
                    $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

                    $control_img = urlencode(base64_encode(file_get_contents($destinationFolder . $fileUploadNewName . '.' . $fileUploadInfo['extension'])));
                    //Call api lấy đường dẫn ảnh .
                    $data_header    = array(
                        'Content-Type: application/x-www-form-urlencoded',
                    );
                    $url_get_image  = Yii::app()->params['api_get_url_image'] . "?token=" . Yii::app()->user->token;
                    $image_response = '&image= ' . $control_img;
                    // Gọi api để lấy link ảnh.
                    $stt = 0;
                    do {
                        $stt++;
                        $url = Utils::cUrlPostJson($url_get_image, $image_response, TRUE, 15, $http_status, $data_header);
                    } while ($http_status != 200 && $stt <= 3);

                    $logMsg[]  = array($url, 'Output: ' . __LINE__, 'T', time());
                    $logFolder = "Log_call_api/" . date("Y/m/d");
                    $logObj    = ATraceLog::getInstance($logFolder);
                    $logObj->setLogFile('upload_thumb3.log');
                    $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
                    $logObj->processWriteLogs($logMsg);

                    $url_source = $destinationFolder . $fileUploadNewName . '.' . $fileUploadInfo['extension'];
                    if ($url == "error") {
                        echo CJSON::encode(array(
                            'status'    => FALSE,
                            'file_name' => '',
                            'msg'       => 'Có lỗi trong quá trình tải ảnh vui lòng thử lại!',
                        ));
                    } else {
                        echo CJSON::encode(array(
                            'status'       => TRUE,
                            'file_name'    => $url,
                            'file_preview' => $url_source,
                            'msg'          => '',
                        ));
                    }

                }
            } else {
                echo CJSON::encode(array(
                    'status'    => FALSE,
                    'file_name' => '',
                    'msg'       => 'Vui lòng chọn file để upload',
                ));
            }

            exit();
        }


        /**
         * action upload image
         */
        public function actionThumbnail4()
        {
            $dir_upload = Yii::app()->params->upload_dir_path . 'complete_order';
            $time       = date("Ymdhis");
            $DS         = DIRECTORY_SEPARATOR;
            if (isset($_POST['tempFileName4']) && $_POST['tempFileName4'] != '') {
                // file temporary
                $fileTemporary = $_POST['tempFileName4'];
                // temporary folder
                $temporaryFolder = $dir_upload . '/temp/';
                if (!file_exists($temporaryFolder)) {
                    mkdir($temporaryFolder, 0777, TRUE);
                }
                // get upload file info
                $fileUploadInfo = pathinfo($fileTemporary);


                $fileUploadNewName = Utils::unsign_string($fileUploadInfo['filename']) . '-' . time();

                // init folder contain file
                $destinationFolder = $dir_upload . $DS . $time . $DS;

                // check and create folder;
                if (!file_exists($destinationFolder)) {
                    mkdir($destinationFolder, 0777, TRUE);
                    mkdir($destinationFolder . 'images/', 0777, TRUE);
                }

                // folder destination
                $destinationFolder .= 'images/';
                $control_img = Utils::resizeImage($temporaryFolder . $fileTemporary);
                // copy temporary file to image file folder and delete in temporary folder
                if ($control_img == TRUE) {

                    // Log api
                    $type = 'upload_thumb4';
                    $id   = Yii::app()->request->csrfToken;

                    $logMsg   = array();
                    $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
                    $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

                    copy($temporaryFolder . $fileTemporary, $destinationFolder . $fileUploadNewName . '.' . $fileUploadInfo['extension']);
                    unlink($temporaryFolder . $fileTemporary);

                    $control_img = urlencode(base64_encode(file_get_contents($destinationFolder . $fileUploadNewName . '.' . $fileUploadInfo['extension'])));
                    //Call api lấy đường dẫn ảnh .
                    $data_header    = array(
                        'Content-Type: application/x-www-form-urlencoded',
                    );
                    $url_get_image  = Yii::app()->params['api_get_url_image'] . "?token=" . Yii::app()->user->token;
                    $image_response = '&image= ' . $control_img;
                    // Gọi api để lấy link ảnh.
                    $stt = 0;
                    do {
                        $stt++;
                        $url = Utils::cUrlPostJson($url_get_image, $image_response, TRUE, 15, $http_status, $data_header);
                    } while ($http_status != 200 && $stt <= 3);

                    $logMsg[]  = array($url, 'Output: ' . __LINE__, 'T', time());
                    $logFolder = "Log_call_api/" . date("Y/m/d");
                    $logObj    = ATraceLog::getInstance($logFolder);
                    $logObj->setLogFile('upload_thumb4.log');
                    $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
                    $logObj->processWriteLogs($logMsg);

                    $url_source = $destinationFolder . $fileUploadNewName . '.' . $fileUploadInfo['extension'];
                    if ($url == "error") {
                        echo CJSON::encode(array(
                            'status'    => FALSE,
                            'file_name' => '',
                            'msg'       => 'Có lỗi trong quá trình tải ảnh vui lòng thử lại!',
                        ));
                    } else {
                        echo CJSON::encode(array(
                            'status'       => TRUE,
                            'file_name'    => $url,
                            'file_preview' => $url_source,
                            'msg'          => '',
                        ));
                    }

                }
            } else {
                echo CJSON::encode(array(
                    'status'    => FALSE,
                    'file_name' => '',
                    'msg'       => 'Vui lòng chọn file để upload',
                ));
            }

            exit();
        }

        /**
         * Receive book file, upload via ajax
         *
         * @throws CException if uploading is failure
         */
        public function actionUpload()
        {
            $dir_upload = 'complete_order';
            Yii::import('ext.UploadHandler.UploadHandler');

            $dir_root = dirname(Yii::app()->request->scriptFile);
            $dir_root = str_replace('adm_beta', '', $dir_root);
            $DS       = DIRECTORY_SEPARATOR;

            $upload_dir = $dir_root . $DS . 'uploads' . $DS . $dir_upload . $DS . 'temp' . $DS;
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, TRUE);
            }

            $max_upload_size   = 999 * 1024 * 1024;
            $accept_file_types = 'jpg|jpeg|png|gif';
            $options_arr       = array(
                'script_url'        => Yii::app()->createUrl('aPackage/deleteFile'),
                'upload_dir'        => $upload_dir,
                'upload_url'        => $dir_root . $DS . 'uploads' . $DS . $dir_upload . $DS . 'temp' . $DS,
                'max_file_size'     => $max_upload_size,
                'accept_file_types' => '/\.(' . $accept_file_types . ')$/i',
            );

            $upload_handler = new UploadHandler($options_arr);
        }

        public function getSecurity($password, $token, $data)
        {
            $milliseconds  = round(microtime(TRUE) * 1000);
            $array_encrypt = array(
                'token'         => $token,
                'time'          => $milliseconds,
                'pass'          => $password,
                'data_checksum' => md5($data),
            );
            $array_encrypt = CJSON::encode($array_encrypt);
            $security      = self::encrypt_noreplace($array_encrypt, $password, MCRYPT_RIJNDAEL_128);

            return $security;
        }


        public function safe_b64encode_noreplace($string)
        {
            $data = base64_encode($string);

//            $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);

            return $data;
        }

        public function safe_b64decode_noreplace($data)
        {
//            $data = str_replace(array('-', '_'), array('+', '/'), $string);
            $mod4 = strlen($data) % 4;
            if ($mod4) {
                $data .= substr('====', $mod4);
            }

            return base64_decode($data);
        }

        public function encrypt_noreplace($encrypt, $key, $algorithm)
        {
            $iv        = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, MCRYPT_MODE_ECB), MCRYPT_RAND);
            $encrypted = $this->safe_b64encode_noreplace(mcrypt_encrypt($algorithm, $key, $encrypt, MCRYPT_MODE_ECB, $iv));

            return $encrypted;
        }


        public function decrypt_noreplace($decrypt, $key, $algorithm)
        {
            $iv        = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, MCRYPT_MODE_ECB), MCRYPT_RAND);
            $decrypted = mcrypt_decrypt($algorithm, $key, $this->safe_b64decode_noreplace($decrypt), MCRYPT_MODE_ECB, $iv);

            return $decrypted;
        }

        /**
         * Láy danh sách người đại diện
         */
        public function actionProxy()
        {
            $value    = Yii::app()->getRequest()->getParam("value", FALSE);
            $type     = Yii::app()->getRequest()->getParam("type", FALSE);
            $order_id = Yii::app()->getRequest()->getParam("order_id", FALSE);

            if ($value && $type && $order_id) {

                $model = new User();
                if ($type == AOrders::SALEOFFICE_PERSION) {
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


    }