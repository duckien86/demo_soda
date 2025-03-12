<?php

    class SendEmailController extends AController
    {

        public function init()
        {
//            parent::init();
//            $this->defaultAction = 'index';
//            $this->pageTitle     = 'Query Builder';
        }


//        public function filters()
//        {
//            return array(
//                'rights', // perform access control for CRUD operations
//            );
//        }

        /**
         * Thống kê hoa hồng sim số.
         *
         * @return string
         */
        public function actionSales()
        {
            set_time_limit(100000);
            $model = new Report();

            $model->end_date    = date("Y-m-d", strtotime(date("Y-m-d") . ' -1 day'));//Lấy ngày hiện tại
            $model->start_date  = date('Y-m-d', strtotime($model->end_date . ' -3 day'));
            $list_default_email = array(
//                'duong.h@centech.com.vn',
                'thanh.nx@centech.com.vn',
                'long.lt@centech.com.vn',
                'kienbt@vnpt.vn',
                'giangpq@vnpt.vn',
//                'phuong.ctm@centech.com.vn',
                'chi.dtk@centech.com.vn',
                'hanh.nt@centech.com.vn',
//                'hieu.bq@centech.com.vn',
//                'van.lt@centech.com.vn',
                'dung.t@centech.com.vn',
                'huyenvm@vnpt.vn',
                'tuyen.ph@centech.com.vn',
                'long.gh@centech.com.vn'
            );
            if (isset($_GET['email'])) {
                if (!empty($_GET['email'])) {
                    $list_default_email = array($_GET['email']);
                }
            }

            $type = 'send_mail_sale';
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());


            $mailto = implode(',', $list_default_email);
            //Lấy dữ liệu doanh thu ngày
            $data_renueve = $model->getEmailSellRenueve();


            $data_accumulated = $model->getEmailAccumulated();

            $data_order = $model->getEmailOrderRenueve();

            $data_order_create = $model->getTotalOrderCreate();


            $data_tourist             = $model->getRenueveTourist();
            $data_tourist_accumulated = $model->getEmailAccumulatedTourist();

            $email_content = $this->renderPartial('_sell',
                array(
                    'data_renueve'             => $data_renueve,
                    'data_accumulated'         => $data_accumulated,
                    'data_order'               => $data_order,
                    'data_order_create'        => $data_order_create,
                    'model'                    => $model,
                    'data_tourist'             => $data_tourist,
                    'data_tourist_accumulated' => $data_tourist_accumulated
                ), TRUE
            );

            //Gui Email den Admin
            $list_email = explode(',', $mailto);
            $mail_title = 'Báo cáo bán hàng ngày ' . date("d/m/Y", strtotime(date("Y-m-d") . ' -1 day'));


            $logMsg[] = array($mail_title, 'Email_title: ' . __LINE__, 'T', time());


            foreach ((array)$list_email as $email) {
                $error = '';
                if (Utils::sendEmail('VNPT SHOP - Báo cáo bán hàng ngày ' . date("d/m/Y", strtotime(date("Y-m-d") . ' -1 day')), $email, '', '', $email_content, 'application.adm.config')) {
//                    echo 'Email was sent to ' . $email;
                    $logMsg[] = array($email, ' Success ' . __LINE__, 'T', time());
                } else {
                    if ($model->sendEmailDaily('VNPT SHOP - Báo cáo cộng tác viên ngày ' . date("d/m/Y", strtotime(date("Y-m-d") . ' -1 day')), $email, '', '', $email_content, 'application.adm.config')) {
                        $logMsg[] = array($email, ' Success_1 ' . __LINE__, 'T', time());
                    } else {
                        if ($model->sendEmailDaily('VNPT SHOP - Báo cáo cộng tác viên ngày ' . date("d/m/Y", strtotime(date("Y-m-d") . ' -1 day')), $email, '', '', $email_content, 'application.adm.config')) {
                            $logMsg[] = array($email, ' Success_2 ' . __LINE__, 'T', time());
                        } else {
                            $logMsg[] = array($email, ' Error ' . __LINE__, 'T', time());
                        }
                    }
                }
            }
            $logFolder = "Log_send_mail_daily/" . date("Y/m/d");
            $logObj    = ATraceLog::getInstance($logFolder);
            $logObj->setLogFile($type . '.log');
            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            return FALSE;

        }

        public function actionCtv()
        {
            $model = new Report();

            $model->end_date   = date("Y-m-d", strtotime(date("Y-m-d") . ' -1 day'));//Lấy ngày hiện tại -1
            $model->start_date = '2017-10-01 00:00:00';
            $model->start_date = date('Y-m-d', strtotime($model->start_date));

            $datediff = strtotime($model->end_date) - strtotime($model->start_date);

            $date = floor($datediff / (60 * 60 * 24)) + 1;

            $list_default_email = array(
                'thanh.nx@centech.com.vn',
                'long.lt@centech.com.vn',
                'kienbt@vnpt.vn',
                'giangpq@vnpt.vn',
//                'phuong.ctm@centech.com.vn',
                'chi.dtk@centech.com.vn',
                'hanh.nt@centech.com.vn',
//                'hieu.bq@centech.com.vn',
//                'van.lt@centech.com.vn',
                'dung.t@centech.com.vn',
                'huyenvm@vnpt.vn',
                'tuyen.ph@centech.com.vn',
                'long.gh@centech.com.vn'
            );

            $type = 'send_mail_ctv';
//            $id   = Yii::app()->request->csrfToken;

            if (isset($_GET['email'])) {
                if (!empty($_GET['email'])) {
                    $list_default_email = array($_GET['email']);
                }
            }
            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
//            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $mailto = implode(',', $list_default_email);


//          Báo cáo dăng ký công tác viên.
            $now            = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 days')) . ' 23:59:59';
            $seven_year_ago = date('Y-m-d', strtotime(date('Y-m-d') . ' -7 days')) . ' 23:59:59';

            $data_key = array('finish_profile', 'total', 'rate', 'finish_profile_month', 'total_month', 'rate_month', 'finish_profile_accumulated',
                'total_accumulated', 'rate_accumulated', 'total_create_renueve_month', 'rate_create_month', 'renueve_month', 'renueve_year', 'rate_create_year');
            $allDate  = self::getAllDate($seven_year_ago, $now, $data_key);


            $data_date           = $model->getTotalUserCTV('', $seven_year_ago, $now);
            $data_month          = $model->getTotalUserCTV(1);
            $data_accumulated    = $model->getTotalUserCTV(2);
            $data_month_previous = $model->getTotalUserCTV(3);

//          Báo cáo hoạt đông cộng tác viên.


            $data_action                = $model->getTotalCtvRenueve('', $seven_year_ago, $now);
            $data_action_month          = $model->getTotalCtvRenueve(1, $seven_year_ago, $now);


            $data_action_month_previous = $model->getTotalCtvRenueve(3, $seven_year_ago, $now);


            $data_action_year = $model->getTotalCtvRenueve(2, $seven_year_ago, $now);


//
//
            $data = self::controllDataCtv($allDate, $data_date, $data_month,
                $data_month_previous, $data_accumulated, $data_action, $data_action_month, $data_action_month_previous, $data_action_year);

            $email_content = $this->renderPartial('_ctv',
                array(
                    'model' => $model,
                    'data'  => $data,
                ), TRUE
            );

            //Lấy số liệu đại lý tổ chức.
            //Gui Email den Admin
            $list_email = explode(',', $mailto);
            $mail_title = 'Báo cáo cộng tác viên ngày ' . date("d/m/Y", strtotime(date("Y-m-d") . ' -1 day'));
//            set_time_limit(6000);
            $logMsg[] = array($mail_title, 'Email_title: ' . __LINE__, 'T', time());
            $stt      = 0;

            foreach ($list_email as $email) {
                $error = '';
                if ($model->sendEmailDaily('VNPT SHOP - Báo cáo cộng tác viên ngày ' . date("d/m/Y", strtotime(date("Y-m-d") . ' -1 day')), $email, '', '', $email_content, 'application.adm.config')) {
                    $logMsg[] = array($email, ' Success ' . __LINE__, 'T', time());
                } else {
                    if ($model->sendEmailDaily('VNPT SHOP - Báo cáo cộng tác viên ngày ' . date("d/m/Y", strtotime(date("Y-m-d") . ' -1 day')), $email, '', '', $email_content, 'application.adm.config')) {
                        $logMsg[] = array($email, ' Success_1 ' . __LINE__, 'T', time());
                    } else {
                        if ($model->sendEmailDaily('VNPT SHOP - Báo cáo cộng tác viên ngày ' . date("d/m/Y", strtotime(date("Y-m-d") . ' -1 day')), $email, '', '', $email_content, 'application.adm.config')) {
                            $logMsg[] = array($email, ' Success_2 ' . __LINE__, 'T', time());
                        } else {
                            $logMsg[] = array($email, ' Error ' . __LINE__, 'T', time());
                        }
                    }

                }
            }

            $logFolder = "Log_send_mail_daily/" . date("Y/m/d");
            $logObj    = ATraceLog::getInstance($logFolder);
            $logObj->setLogFile($type . '.log');
            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

//            return FALSE;
        }

        /**
         *
         * Lấy mảng group ngày theo time ngày trước.
         *
         * @param string $start
         * @param string $end
         * @param int    $type = 0|1 : Gener|effect
         *
         * @return array
         */
        public static function getAllDate($start = '', $end = '', $array_key = array())
        {
            $list = array();
            if ($start && $end) {
                $start_time = strtotime(date('Y-m-d', strtotime($start)));
                $end_time   = strtotime(date('Y-m-d', strtotime($end)));
                for ($i = $end_time; $i >= $start_time; $i -= 86400) {
                    foreach ($array_key as $key) {
                        $list[date('Y-m-d', $i)][$key] = 0;
                    }
                }
            }


            return $list;
        }

        public static function controllDataCtv($allDate, $data_date, $data_month, $data_month_previous, $data_accumulated
            , $data_action, $data_action_month, $data_action_month_previous, $data_action_year)
        {
            $first_date = date('Y-m', strtotime(date('Y-m'))) . '-01';

            $first_date = date('Y-m-d', strtotime($first_date . ' -1 days'));

            $finish_profile_month = 0;
            $total_month          = 0;
            foreach ($data_month as $key => $value) {
                if ($value->finish_profile == 1) {
                    $finish_profile_month = $value->total;
                }
                $total_month += $value->total;
            }


            $finish_profile_month_previous = 0;
            $total_month_previous          = 0;
            foreach ($data_month_previous as $key => $value) {
                if ($value->finish_profile == 1) {
                    $finish_profile_month_previous = $value->total;
                }
                $total_month_previous += $value->total;
            }

            $total_create_renueve_month = 0;
            $renueve_month              = 0;
            foreach ($data_action_month as $key => $value) {
                $total_create_renueve_month += $value->total_user_renueve;
                $renueve_month += $value->total_renueve;

            }


            $total_create_renueve_month_previous = 0;
            $renueve_month_previous              = 0;
            foreach ($data_action_month_previous as $key => $value) {
                $total_create_renueve_month_previous += $value->total_user_renueve;
                $renueve_month_previous += $value->total_renueve;

            }


            $total_create_renueve_year = 0;
            $renueve_year              = 0;
            foreach ($data_action_year as $key => $value) {
                $total_create_renueve_year += $value->total_user_renueve;
                $renueve_year += $value->total_renueve;

            }


            $finish_profile_accumulated = 0;
            $total_accumulated          = 0;
            foreach ($data_accumulated as $key => $value) {
                if ($value->finish_profile == 1) {
                    $finish_profile_accumulated = $value->total;
                }
                $total_accumulated += $value->total;
            }


            foreach ($allDate as $key => $date) {

                if ($total_month < 0 || strtotime($key) <= strtotime($first_date)) {
                    $allDate[$key]['total_month'] = $total_month_previous;
                } else {
                    $allDate[$key]['total_month'] = $total_month;
                }
                if ($finish_profile_month < 0 || strtotime($key) <= strtotime($first_date)) {
                    $allDate[$key]['finish_profile_month'] = $finish_profile_month_previous;
                } else {
                    $allDate[$key]['finish_profile_month'] = $finish_profile_month;
                }

                $allDate[$key]['finish_profile_accumulated'] = $finish_profile_accumulated;
                $allDate[$key]['total_accumulated']          = $total_accumulated;

                $allDate[$key]['total_create_renueve_month'] = $total_create_renueve_month;

                $allDate[$key]['renueve_month'] = $renueve_month;

                if ($total_create_renueve_month < 0 || strtotime($key) <= strtotime($first_date)) {

                    $allDate[$key]['total_create_renueve_month'] = $total_create_renueve_month_previous;
                } else {
                    $allDate[$key]['total_create_renueve_month'] = $total_create_renueve_month;
                }
                if ($renueve_month < 0 || strtotime($key) <= strtotime($first_date)) {
                    $allDate[$key]['renueve_month'] = $renueve_month_previous;

                } else {
                    $allDate[$key]['renueve_month'] = $renueve_month;
                }
                $allDate[$key]['total_create_renueve_year'] = $total_create_renueve_year;
                $allDate[$key]['renueve_year']              = $renueve_year;


                foreach ($data_date as $key_date => $value_date) {

                    if ($value_date->date == $key) {

                        $total_month -= $value_date->total;
                        if (strtotime($key) <= strtotime($first_date)) {

                            $total_month_previous -= $value_date->total;
                        }
                        $total_accumulated -= $value_date->total;

                        if ($value_date->finish_profile == 1) {
                            $finish_profile_month -= $value_date->total;
                            if (strtotime($key) <= strtotime($first_date)) {
                                $finish_profile_month_previous -= $value_date->total;
                            }
                            $finish_profile_accumulated -= $value_date->total;
                            $allDate[$key]['finish_profile'] = $value_date->total;
                        }
                        $allDate[$key]['total'] += $value_date->total;


                    }
                }

                foreach ($data_action as $key_action => $value_action) {

                    if ($value_action->date == $key) {
                        $total_create_renueve_month -= $value_action->total_user_renueve;
                        if (strtotime($key) <= strtotime($first_date)) {
                            $total_create_renueve_month_previous -= $value_action->total_user_renueve;
                        }
                        $renueve_month -= $value_action->total_renueve;
                        if (strtotime($key) <= strtotime($first_date)) {
                            $renueve_month_previous -= $value_action->total_renueve;
                        }
                        $total_create_renueve_year -= $value_action->total_user_renueve;
                        $renueve_year -= $value_action->total_renueve;
                    }
                }

            }


            foreach ($allDate as $key => $date) {

                $allDate[$key]['rate']              = ($allDate[$key]['total'] != 0) ? ROUND(($allDate[$key]['finish_profile'] / $allDate[$key]['total']) * 100, 3) . '%' : 0;
                $allDate[$key]['rate_month']        = ($allDate[$key]['total_month'] != 0) ? ROUND(($allDate[$key]['finish_profile_month'] / $allDate[$key]['total_month']) * 100, 3) . '%' : 0;
                $allDate[$key]['rate_accumulated']  = ($allDate[$key]['total_accumulated'] != 0) ? ROUND(($allDate[$key]['finish_profile_accumulated'] / $allDate[$key]['total_accumulated']) * 100, 3) . '%' : 0;
                $allDate[$key]['rate_create_month'] = ($allDate[$key]['total_create_renueve_month'] !=0) ? ROUND(($allDate[$key]['renueve_month'] / $allDate[$key]['total_create_renueve_month']) * 100, 3) : 0;
                $allDate[$key]['rate_create_year']  = ($allDate[$key]['total_create_renueve_year'] !=0) ? ROUND(($allDate[$key]['renueve_year'] / $allDate[$key]['total_create_renueve_year']) * 100, 3) : 0;

            }


            return $allDate;

        }
    }

?>