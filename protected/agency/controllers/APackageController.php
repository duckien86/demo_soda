<?php

    class APackageController extends AController
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
                'rights',
            );
        }

        /**
         * list package
         */
        public function actionIndex()
        {
            $model = new APackage();
            $this->pageTitle = 'Sản phẩm - Gói cước';
            if(isset($_GET['APackage']))
                $model->attributes= $_GET['APackage'];
            $this->render('index', array(
                'model' => $model,
            ));
        }
        /**
         * register package
         *
         * @param $package
         */
        public function actionRegister($package)
        {
            $modelPackage = APackage::model()->find('id=:id', array(':id' => $package));
            if ($modelPackage) {
                $this->pageTitle = 'Đăng ký gói cước - ' . $modelPackage->name;
                //check price_discount
                if ($modelPackage->price_discount > 0) {
                    $modelPackage->price = $modelPackage->price_discount;
                } elseif ($modelPackage->price_discount == -1) {
                    $modelPackage->price = 0;
                }
                $modelOrder = new AOrders();
                $orderDetails = new AOrderDetails();

                $modelOrder->scenario = 'register_package';

                $modelOrder->id = $modelOrder->generateOrderId();
                $orderDetails->setOrderDetailsPackage($modelPackage, $modelOrder, $orderDetails);

                //validate ajax
                if (isset($_POST['ajax']) && $_POST['ajax'] === 'register_package') {
                    echo CActiveForm::validate($modelOrder);
                    Yii::app()->end();
                }
                
                if (isset($_POST['APackage']) && isset($_POST['AOrders'])) {
                    $modelOrder->attributes = $_POST['AOrders'];
                    if($modelOrder->validate()){
                        if(!isset($_POST['yt1'])){
                            $order = AOrders::checkPhoneContactOfAgency($modelOrder->phone_contact);
                            if(empty($order)){
                                echo CJSON::encode(0);
                                Yii::app()->end();
                            }
                        }
                        $orders_data = new AOrdersData();
                        // package
                         $orders_data->package = $modelPackage;

                        // order_details
                        $order_details_pack = new AOrderDetails();
                        $order_details_pack->setOrderDetailsPackage($modelPackage, $modelOrder, $order_details_pack);
                        $orders_data->order_details['packages'] = $order_details_pack->attributes;

                        //order state
                        $order_state = new AOrderState();
                        $order_state->setOrderState($modelOrder, $order_state, AOrderState::CONFIRMED);
                        $orders_data->order_state = $order_state;

                        //orders
                        $modelOrder->payment_method = 5;
                        $modelOrder->promo_code = '';
                        $modelOrder->affiliate_source = !empty(Yii::app()->user->agency)? Yii::app()->user->agency : '' ;
                        $modelOrder->user_id = Yii::app()->user->id;
                        $orders_data->orders = $modelOrder;

                        $data                       = array(
                            'orders'        => $modelOrder->attributes,
                            'order_details' => $orders_data->order_details,
                            'order_state'   => $order_state->attributes,
                        );
                        $data['orders']['agency_contract_id'] = AAgencyContract::getCurrentAgencyContractActive();
                        //call api
                        $response_arr = $orders_data->registerPackage($data);
                        // TESTING
//                        $response_arr = ["code"=> 1,"msg"=> "dang ky thanh cong"];
                        $response_code = isset($response_arr['code']) ? $response_arr['code'] : '';
                        //redirect to message
                        $modelOrder->phone_contact = '';
                        $modelOrder->promo_code = '';
                        if(isset($_POST['yt1'])) {
                            $this->redirect($this->createUrl('aPackage/message', array('t' => $response_code, 'order_id' => $modelOrder->id)));
                        }else{
                            $response_arr['order_id'] = $modelOrder->id;
                            echo CJSON::encode($response_arr);
                            Yii::app()->end();
                        }

                    }else{
                        echo CActiveForm::validate($modelOrder);
                        Yii::app()->end();
                    }
                }

                $this->render('register', array(
                    'modelPackage' => $modelPackage,
                    'modelOrder' => $modelOrder,
                    'orderDetails' => $orderDetails,
                ));
            }
        }

        public function actionMessage($t, $order_id)
        {
            $this->pageTitle = 'Gói cước - Thông báo';
            $msg = ($t ==1 ) ? 'Đăng ký gói thành công' : 'Đăng ký gói thất bại';
            $class_name = ($t ==1 ) ? 'success' : 'danger';
            $this->render('message', array(
                'msg' => $msg,
                'class_name' => $class_name
            ));
        }
        public static function EncryptMsg($msg, $key, $alg)
        {
            return Utils::encrypt('freedoo*_' . $msg, md5($key), $alg);
        }
    }
