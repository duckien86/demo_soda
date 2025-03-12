<?php

    class AReportSocialController extends AController
    {

        public function init()
        {
            parent::init();
            $this->defaultAction = 'index';
//            $this->pageTitle     = 'Query Builder';
        }

        public function accessRules()
        {
            return array(
                array('allow',  // allow all users to perform 'index' and 'view' actions
                    'actions' => array('index', 'sim', 'package', 'card', 'getDistrictByProvice', 'getWardByDistrict', 'getBrandOfficeByDistrict'),
                    'users'   => array('@'),
                ),
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                    'actions' => array('index', 'sim', 'package', 'card', 'getDistrictByProvice', 'getWardByDistrict', 'getBrandOfficeByDistrict'),
                    'users'   => array('@'),
                ),
                array('allow', // allow admin user to perform 'admin' and 'delete' actions
                    'actions' => array('index', 'sim', 'package', 'card', 'getDistrictByProvice', 'getWardByDistrict', 'getBrandOfficeByDistrict'),
                    'users'   => array('admin'),
                ),
                array('deny',  // deny all users
                    'users' => array('*'),
                ),
            );
        }

        public function filters()
        {
            return array(
                'rights', // perform access control for CRUD operations
            );
        }

        /**
         * Doanh thu tổng quan.
         */
        public function actionIndex()
        {
            $form                    = new AReportSocialForm();
            $form_validate           = new AReportSocialForm();
            $form_validate->scenario = 'index';
            $data                    = array();

            $start = $end = '';
            $model = new AReportSocial();
//            CVarDumper::dump($_REQUEST,10,true);die();
            if (isset($_POST['AReportSocialForm']) || isset($_REQUEST['AReportSocialForm'])) {
                if (!isset($_POST['AReportSocialForm'])) {
                    $_POST['AReportSocialForm'] = $_REQUEST['AReportSocialForm'];
                }

                $form->end_date     = $form_validate->start_date = $_POST['AReportSocialForm']['end_date'];
                $form->start_date   = $form_validate->end_date = $_POST['AReportSocialForm']['start_date'];
                $model->customer_id = $form->customer_id = $form_validate->customer_id = $_POST['AReportSocialForm']['customer_id'];


                if (isset($_POST['AReportSocialForm']['start_date']) && $_POST['AReportSocialForm']['start_date'] != '') {
                    $start = $model->start_date = $form_validate->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['AReportSocialForm']['start_date'])));
                }

                if (isset($_POST['AReportSocialForm']['end_date']) && $_POST['AReportSocialForm']['end_date'] != '') {
                    $end = $model->end_date = $form_validate->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['AReportSocialForm']['end_date'])));
                }

                if ($form_validate->validate()) { // Validate form
                    $data = $model->getListCustomer();
                } else {
                    $form_validate->getErrors();
                }
            }

            return $this->render('index',
                array('form'          => $form,
                      'form_validate' => $form_validate,
                      'data'          => $data,
                      'start'         => $start,
                      'end'           => $end,
                ));
        }

        /**
         * Doanh thu thành viên.
         */
        public function actionDetailUser()
        {

            $form          = new AReportSocialForm();
            $form_validate = new AReportSocialForm();
            $model         = new AReportSocial();

            $form_validate->scenario = 'detail_user';

            $data = $data_post_list = $data_point_list = $data_comment_list = $data_likes_list = $data_sub_point_list = $data_redeem_list = array();
            // Nhận post.

            if (isset($_POST['AReportSocialForm']) || isset($_REQUEST['AReportSocialForm'])) {

                if (!isset($_POST['AReportSocialForm'])) {
                    $_POST['AReportSocialForm'] = $_REQUEST['AReportSocialForm'];
                }
                $form->end_date    = $form_validate->start_date = $_POST['AReportSocialForm']['end_date'];
                $form->start_date  = $form_validate->end_date = $_POST['AReportSocialForm']['start_date'];
                $form->customer_id = $form_validate->customer_id = $_POST['AReportSocialForm']['customer_id'];

                if (isset($_POST['AReportSocialForm']['start_date']) && $_POST['AReportSocialForm']['start_date'] != '') {
                    $model->start_date = $form_validate->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['AReportSocialForm']['start_date'])));
                }

                if (isset($_POST['AReportSocialForm']['end_date']) && $_POST['AReportSocialForm']['end_date'] != '') {
                    $model->end_date = $form_validate->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['AReportSocialForm']['end_date'])));
                }

                if ($form_validate->validate()) { // Validate form
                    // Xử lý dữ liệu.
                    $model->customer_id = $form->customer_id = $_POST['AReportSocialForm']['customer_id'];

                    //Lấy dữ liệu tổng quan.
                    $data_likes     = $model->getCustomerLikes();
                    $data_comment   = $model->getCustomerComment();
                    $data_post      = $model->getCustomerPost();
                    $data_sub_point = $model->getCustomerTotalSubPoint();
                    $data_redeem    = $model->getCustomerTotalRedeem();

                    $data = array_merge_recursive($data_likes, $data_post);
                    $data = array_merge_recursive($data, $data_comment);
                    $data = array_merge_recursive($data, $data_sub_point);
                    $data = array_merge_recursive($data, $data_redeem);

                    $data = $model->controllDataCustomer($data, $form, 1);
                    $data = new CArrayDataProvider($data, array(
                        'keyField' => FALSE,
                    ));

                    //Lấy dữ liệu danh sách bài đăng.
                    $data_post_list = $model->getListPost();

                    //Lấy dữ liệu danh sách bình luận.
                    $data_comment_list = $model->getListComment();

                    //Lấy dữ liệu danh sách bình luận.
                    $data_likes_list = $model->getListLikes();

                    //Lấy dữ liệu danh sách số lần bị trừ điểm.
                    $data_sub_point_list = $model->getListSubPoint();

                    //Lấy dữ liệu lịch sử thăng hạng.
                    $data_point_list = $model->getListPoint();

                    //Lấy dữ liệu danh sách lịch sử đổi quà.
                    $data_redeem_list = $model->getListRedeem();
                } else {
                    $form_validate->getErrors();
                }

            }

            return $this->render('detail_user',
                array('form'                => $form,
                      'model'               => $model,
                      'form_validate'       => $form_validate,
                      'data'                => $data,
                      'data_post_list'      => $data_post_list,
                      'data_comment_list'   => $data_comment_list,
                      'data_likes_list'     => $data_likes_list,
                      'data_sub_point_list' => $data_sub_point_list,
                      'data_redeem_list'    => $data_redeem_list,
                      'data_point_list'     => $data_point_list,
                ));
        }

        /**
         * Báo cáo người dùng.
         */
        public function actionReportUser()
        {
            $form          = new AReportSocialForm();
            $form_validate = new AReportSocialForm();
            $model         = new AReportSocial();

            $form_validate->scenario = 'report_user';
            $data                    = $data_post_list = $data_point_list = $data_comment_list = $data_likes_list = $data_sub_point_list = $data_redeem_list = array();
            // Nhận post.
            if (isset($_POST['AReportSocialForm']) || isset($_REQUEST['AReportSocialForm'])) {

                if (!isset($_POST['AReportSocialForm'])) {
                    $_POST['AReportSocialForm'] = $_REQUEST['AReportSocialForm'];
                }
                $form->end_date    = $form_validate->start_date = $_POST['AReportSocialForm']['end_date'];
                $form->start_date  = $form_validate->end_date = $_POST['AReportSocialForm']['start_date'];
                $form->customer_id = $form_validate->customer_id = $model->customer_id = $_POST['AReportSocialForm']['customer_id'];
                $form->status      = $form_validate->status = $model->status = $_POST['AReportSocialForm']['status'];


                if (isset($_POST['AReportSocialForm']['start_date']) && $_POST['AReportSocialForm']['start_date'] != '') {
                    $model->start_date = $form_validate->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['AReportSocialForm']['start_date'])));
                }

                if (isset($_POST['AReportSocialForm']['end_date']) && $_POST['AReportSocialForm']['end_date'] != '') {
                    $model->end_date = $form_validate->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['AReportSocialForm']['end_date'])));
                }


                if ($form_validate->validate()) { // Validate form
                    // Xử lý dữ liệu.
                    //Lấy dữ liệu tổng quan.
                    $data_likes     = $model->getCustomerLikes();
                    $data_comment   = $model->getCustomerComment();
                    $data_post      = $model->getCustomerPost();
                    $data_sub_point = $model->getCustomerTotalSubPoint();
                    $data_redeem    = $model->getCustomerTotalRedeem();

                    $data = array_merge_recursive($data_likes, $data_post);
                    $data = array_merge_recursive($data, $data_comment);
                    $data = array_merge_recursive($data, $data_sub_point);
                    $data = array_merge_recursive($data, $data_redeem);
                    $data = $model->controllDataCustomer($data, $form);

                    $data = new CArrayDataProvider($data, array(
                        'pagination' => array(
                            'pageSize' => 30,
                            'params'   => array(
                                'AReportSocialForm[start_date]' => $model->start_date,
                                'AReportSocialForm[end_date]'   => $model->end_date,
                                'AReportSocialForm[status]'     => $model->status,
                            ),
                        ),
                        'keyField'   => FALSE,

                    ));
                } else {
                    $form_validate->getErrors();
                }
            }


            return $this->render('report_user', array(
                'form'          => $form,
                'model'         => $model,
                'form_validate' => $form_validate,
                'data'          => $data,
            ));
        }

        /**
         * Lấy thông tin chi tiết user.
         */
        public function actionGetDetail()
        {
            $sso_id = Yii::app()->getRequest()->getParam("sso_id", FALSE);

            $customer = ACustomers::model()->findByAttributes(array('sso_id' => $sso_id));

            $data = $this->renderPartial('_customer_info', array('model' => $customer, 'id' => $sso_id));

            echo $data;
        }
    }

?>