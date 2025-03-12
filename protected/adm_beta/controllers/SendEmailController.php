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
            $model = new Report();

            $model->end_date    = date("Y-m-d", strtotime(date("Y-m-d") . ' -1 day'));//Lấy ngày hiện tại
            $model->start_date  = date('Y-m-d', strtotime($model->end_date . ' -3 day'));
            $list_default_email = array(
                'duong.h@centech.com.vn',
//                'long.lt@centech.com.vn',
//                'kienbt@vnpt.vn',
//                'giangpq@vnpt.vn',
//                'phuong.ctm@centech.com.vn',
//                'chi.dtk@centech.com.vn',
//                'hanh.nt@centech.com.vn',
//                'hieu.bq@centech.com.vn',
//                'van.lt@centech.com.vn',
//                'dung.t@centech.com.vn'
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

            $data_order        = $model->getEmailOrderRenueve();
            $data_order_create = $model->getTotalOrderCreate();

            $email_content = $this->renderPartial('_sell',
                array(
                    'data_renueve'      => $data_renueve,
                    'data_accumulated'  => $data_accumulated,
                    'data_order'        => $data_order,
                    'data_order_create' => $data_order_create,
                    'model'             => $model
                ), TRUE
            );

            //Gui Email den Admin
            $list_email = explode(',', $mailto);
            $mail_title = 'Báo cáo bán hàng ngày ' . date("d/m/Y", strtotime(date("Y-m-d") . ' -1 day'));
            set_time_limit(100000);


            $logMsg[] = array($mail_title, 'Email_title: ' . __LINE__, 'T', time());


            foreach ((array)$list_email as $email) {
                $error = '';
                if (Utils::sendEmail('Freedoo - Báo cáo bán hàng ngày ' . date("d/m/Y", strtotime(date("Y-m-d") . ' -1 day')), $email, '', '', $email_content, 'application.adm.config')) {
//                    echo 'Email was sent to ' . $email;
                    $logMsg[] = array($email, ' Success ' . __LINE__, 'T', time());
                } else {
                    if ($model->sendEmailDaily('Freedoo - Báo cáo cộng tác viên ngày ' . date("d/m/Y", strtotime(date("Y-m-d") . ' -1 day')), $email, '', '', $email_content, 'application.adm.config')) {
                        $logMsg[] = array($email, ' Success_1 ' . __LINE__, 'T', time());
                    } else {
                        if ($model->sendEmailDaily('Freedoo - Báo cáo cộng tác viên ngày ' . date("d/m/Y", strtotime(date("Y-m-d") . ' -1 day')), $email, '', '', $email_content, 'application.adm.config')) {
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
                'long.lt@centech.com.vn',
                'kienbt@vnpt.vn',
                'giangpq@vnpt.vn',
                'phuong.ctm@centech.com.vn',
                'chi.dtk@centech.com.vn',
                'hanh.nt@centech.com.vn',
                'hieu.bq@centech.com.vn',
                'van.lt@centech.com.vn',
                'dung.t@centech.com.vn'

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
//            Lấy dữ liệu doanh thu ngày
            $data_total_user    = $model->getTotalUserCTV(); // Tổng số ctv
            $data_total_renueve = $model->getTotalCtvRenueve(); // Tổng số ctv phát sinh doanh thu
            $data_total         = $model->getTotalCtvByType(); // Dữ liệu tổng quan theo trạng thái
            $total_renueve      = $model->getTotalRenueveCtv(); // Tổng doanh thu từ dầu.
            if ($data_total_user && $data_total_renueve && $total_renueve) {
                $data_total['total_user']     = $data_total_user;
                $data_total['create_renueve'] = $data_total_renueve;
                $data_total['total_renueve']  = $total_renueve;
            }
            $data_accumulated = $model->getEmailAccumulated();
            $data_agency      = $model->getAgencyRenueve();
            $email_content    = $this->renderPartial('_ctv',
                array(
                    'data_total'       => $data_total,
                    'model'            => $model,
                    'date'             => $date,
                    'data_agency'      => $data_agency,
                    'data_accumulated' => $data_accumulated
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
                if ($model->sendEmailDaily('Freedoo - Báo cáo cộng tác viên ngày ' . date("d/m/Y", strtotime(date("Y-m-d") . ' -1 day')), $email, '', '', $email_content, 'application.adm.config')) {
                    $logMsg[] = array($email, ' Success ' . __LINE__, 'T', time());
                } else {
                    if ($model->sendEmailDaily('Freedoo - Báo cáo cộng tác viên ngày ' . date("d/m/Y", strtotime(date("Y-m-d") . ' -1 day')), $email, '', '', $email_content, 'application.adm.config')) {
                        $logMsg[] = array($email, ' Success_1 ' . __LINE__, 'T', time());
                    } else {
                        if ($model->sendEmailDaily('Freedoo - Báo cáo cộng tác viên ngày ' . date("d/m/Y", strtotime(date("Y-m-d") . ' -1 day')), $email, '', '', $email_content, 'application.adm.config')) {
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
    }

?>