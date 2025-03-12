<?php

    class CskhCtvCareController extends AController
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
         * Manages all models.
         */
        public function actionIndex()
        {
            $data          = array();
            $model         = new CskhCtvUsers('search');
            $form          = new CskhCtvUsers('search');
            $form_validate = new CskhCtvUsers('search');
            $model->unsetAttributes();  // clear any default values
            $model->scenario = 'csCtv';

            $post = FALSE;

            if (isset($_POST['CskhCtvUsers']) || isset($_GET['CskhCtvUsers'])) {
                if (isset($_GET['CskhCtvUsers'])) {
                    $_POST['CskhCtvUsers'] = $_GET['CskhCtvUsers'];
                }
                $model->attributes         = $form->attributes = $form_validate->attributes = $_POST['CskhCtvUsers'];
                $form_validate->start_date = $form->start_date = $model->start_date = isset($_POST['CskhCtvUsers']['start_date']) ? $_POST['CskhCtvUsers']['start_date'] : '';
                $form_validate->end_date   = $form->end_date = $model->end_date = isset($_POST['CskhCtvUsers']['end_date']) ? $_POST['CskhCtvUsers']['end_date'] : '';
                $model->ob_status          = $form->ob_status = isset($_POST['CskhCtvUsers']['ob_status']) ? $_POST['CskhCtvUsers']['ob_status'] : '';
                $model->ob_3day            = $form->ob_3day = isset($_POST['CskhCtvUsers']['ob_3day']) ? $_POST['CskhCtvUsers']['ob_3day'] : '';
                if ($form_validate->validate()) {
                    $post = TRUE;
                } else {
                    $post = FALSE;
                }
            }


            $this->render('index', array(
                'model'         => $model,
                'form'          => $form,
                'form_validate' => $form_validate,
                'post'          => $post,
            ));
        }

        /**
         * Manages all models.
         */
        public function actionReport()
        {
            $data          = array();
            $model         = new CskhObHistory('search');
            $form          = new CskhObHistory('search');
            $form_validate = new CskhObHistory('search');
            $model->unsetAttributes();  // clear any default values
            $model->scenario = 'report';

            $post = FALSE;

            if (isset($_POST['CskhObHistory']) || isset($_GET['CskhObHistory'])) {
                if (isset($_GET['CskhObHistory'])) {
                    $_POST['CskhObHistory'] = $_GET['CskhObHistory'];
                }
                $model->attributes         = $form->attributes = $form_validate->attributes = $_POST['CskhObHistory'];
                $form_validate->start_date = $form->start_date = isset($_POST['CskhObHistory']['start_date']) ? $_POST['CskhObHistory']['start_date'] : '';
                $form_validate->end_date   = $form->end_date = isset($_POST['CskhObHistory']['end_date']) ? $_POST['CskhObHistory']['end_date'] : '';

                if ($form_validate->validate()) {
                    $post = TRUE;
                } else {
                    $post = FALSE;
                }
            }


            $this->render('report', array(
                'model'         => $model,
                'form'          => $form,
                'form_validate' => $form_validate,
                'post'          => $post,
            ));
        }

        /**
         * Kiểm tra mã xác thực.
         */
        public function actionGetInfo()
        {
            $data      = 0;
            $status    = TRUE;
            $user_id   = Yii::app()->request->getParam('user_id', FALSE);
            $ob_status = Yii::app()->request->getParam('ob_status', FALSE);

            if ($user_id) {
                $model      = CskhCtvUsers::model()->findByAttributes(array('user_id' => $user_id));
                $model_logs = new CskhObHistory();

                $model_logs->user_id = $user_id;

                $data = $this->renderPartial('_popup_get_info',
                    array('model' => $model, 'model_logs' => $model_logs, 'user_id' => $user_id, 'ob_status' => $ob_status, 'data' => $this->loadModel($user_id)),
                    TRUE);
            }
            $result = array(
                'content_html' => $data,
                'status'       => $status,
            );
            echo CJSON::encode($result);
            exit();
        }

        /**
         * Kiểm tra mã xác thực.
         */
        public function actionUpdateObStatus()
        {
            $data          = 0;
            $status        = TRUE;
            $user_id       = Yii::app()->request->getParam('user_id', FALSE);
            $ob_status     = Yii::app()->request->getParam('ob_status', 0);
            $old_ob_status = Yii::app()->request->getParam('old_ob_status', 0);
            $note_ob       = Yii::app()->request->getParam('note_ob', '');

            if ($user_id) {

                $cskh_user_id = Yii::app()->user->id;
                $created_on   = date('Y-m-d H:i:s');
                if ($ob_status != $old_ob_status) {
                    $connection    = Yii::app()->db_affiliates;
                    $sql           = "UPDATE tbl_users SET ob_status =:ob_status, note_ob =:note_ob, ob_last_update =:ob_last_update where user_id= :user_id";
                    $command       = $connection->createCommand();
                    $command->text = $sql;
                    $command->bindParam(':user_id', $user_id);
                    $command->bindParam(':ob_status', $ob_status);
                    $command->bindParam(':note_ob', $note_ob);
                    $command->bindParam(':ob_last_update', $created_on);


                    $array_insert[0] = array(

                        'user_id'      => $user_id,
                        'cskh_user_id' => $cskh_user_id,
                        'created_on'   => $created_on,
                        'status_old'   => $old_ob_status,
                        'status_new'   => $ob_status,
                        'note'         => $note_ob,

                    );

                    $connection_insert = Yii::app()->db_affiliates->getSchema()->getCommandBuilder();
                    $command_insert    = $connection_insert->createMultipleInsertCommand('tbl_ob_history', $array_insert);

                    if ($command->execute() && $command_insert->execute()) {
                        $data = $this->renderPartial('_popup_get_info_result',
                            array('user_id' => $user_id),
                            TRUE);
                    }
                } else {
                    $data = $this->renderPartial('_popup_get_info_result',
                        array('user_id' => $user_id),
                        TRUE);
                }

            }
            $result = array(
                'content_html' => $data,
                'status'       => $status,
            );
            echo CJSON::encode($result);
            exit();
        }

        public function actionGetAllOb()
        {
            $status         = TRUE;
            $start_date     = Yii::app()->request->getParam('start_date', '');
            $end_date       = Yii::app()->request->getParam('end_date', '');
            $ob_status      = Yii::app()->request->getParam('ob_status', FALSE);
            $user_name      = Yii::app()->request->getParam('user_name', '');
            $mobile         = Yii::app()->request->getParam('mobile', '');
            $finish_profile = Yii::app()->request->getParam('finish_profile', '');
            $ob_3day        = Yii::app()->request->getParam('ob_3day', '');

            $data   = $this->renderPartial('_popup_get_all_ob',
                array('start_date' => $start_date, 'end_date' => $end_date,
                      'ob_status'  => $ob_status, 'user_name' => $user_name,
                      'mobile'     => $mobile, 'finish_profile' => $finish_profile,
                      'ob_3day'    => $ob_3day),
                TRUE);
            $result = array(
                'content_html' => $data,
                'status'       => $status,
            );
            echo CJSON::encode($result);
            exit();
        }

        public function actionChangeAllOb()
        {
            $status         = TRUE;
            $data           = '';
            $start_date     = Yii::app()->request->getParam('start_date', '');
            $end_date       = Yii::app()->request->getParam('end_date', '');
            $ob_status      = Yii::app()->request->getParam('ob_status', FALSE);
            $user_name      = Yii::app()->request->getParam('user_name', '');
            $mobile         = Yii::app()->request->getParam('mobile', '');
            $finish_profile = Yii::app()->request->getParam('finish_profile', '');
            $ob_3day        = Yii::app()->request->getParam('ob_3day', '');
            $ob_new         = Yii::app()->request->getParam('ob_new', '');
            if (!empty($ob_new)) {
                $model                 = new CskhCtvUsers();
                $model->start_date     = $start_date;
                $model->end_date       = $end_date;
                $model->ob_status      = $ob_status;
                $model->user_name      = $user_name;
                $model->finish_profile = $finish_profile;
                $model->mobile         = $mobile;

                $data        = $model->search(FALSE, TRUE);
                $users       = '';
                $users_array = array();
                $stt         = 0;
                if (!empty($data)) {

                    foreach ($data as $value) {
                        $stt++;
                        if ($stt != count((array)$data)) {
                            $users .= "'" . $value->user_id . "',";
                        } else {
                            $users .= "'" . $value->user_id . "'";
                        }
                        $users_array[] = $value->user_id;
                    }
                }
                $created_on   = date('Y-m-d H:i:s');
                $cskh_user_id = Yii::app()->user->id;
                if (!empty($users)) {
                    $connection    = Yii::app()->db_affiliates;
                    $sql           = "UPDATE tbl_users SET ob_status =:ob_status, ob_last_update =:ob_last_update where user_id IN($users)";
                    $command       = $connection->createCommand();
                    $command->text = $sql;
                    $command->bindParam(':ob_status', $ob_new);
                    $command->bindParam(':ob_last_update', $created_on);


                    $array_insert = array();
                    foreach ($users_array as $value) {
                        $array_insert_key = array(

                            'user_id'      => $value,
                            'cskh_user_id' => $cskh_user_id,
                            'created_on'   => $created_on,
                            'status_old'   => $ob_status,
                            'status_new'   => $ob_new,
                            'note'         => 'All',

                        );
                        $array_insert[]   = $array_insert_key;
                    }

                    $connection_insert = Yii::app()->db_affiliates->getSchema()->getCommandBuilder();
                    $command_insert    = $connection_insert->createMultipleInsertCommand('tbl_ob_history', $array_insert);

                    if ($command->execute() && $command_insert->execute()) {
                        $data = $this->renderPartial('_popup_change_all_ob_result',
                            array('result' => TRUE),
                            TRUE);

                    } ELSE {
                        $data = $this->renderPartial('_popup_change_all_ob_result',
                            array('result' => FALSE),
                            TRUE);
                    }
                }
            }
            $result = array(
                'content_html' => $data,
                'status'       => $status,
            );
            echo CJSON::encode($result);
            exit();

        }

        public function loadModel($id)
        {
            $model = CskhCtvUsers::model()->findByPk($id);
            if ($model === NULL)
                throw new CHttpException(404, 'The requested page does not exist.');

            return $model;
        }


    }
