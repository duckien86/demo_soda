<?php

    class OrdersController extends Controller
    {
        public $layout = '/layouts/main';

        private $isMobile = FALSE;

        public function init()
        {
            parent::init();
            $detect         = new MyMobileDetect();
            $this->isMobile = $detect->isMobile();
            if ($detect->isMobile()) {
                $this->layout = '/layouts/mobile_main';
            }
        }

        public function actionIndex()
        {
            $this->pageTitle = 'Dịch vụ của tôi - Danh sách đơn hàng';
            if (!Yii::app()->user->isGuest) {
                $customer = WCustomers::model()->find('sso_id=:sso_id', array(':sso_id' => Yii::app()->user->sso_id));
                if ($customer) {
                    $modelSearch = new SearchOrderForm();
                    $orders_data = new OrdersData();
                    //get list order
                    $data_input = array(
                        'sso_id'    => $customer->sso_id,
                        'order_id'  => NULL,
                        'delivered' => NULL,
                        'from_date' => NULL,
                        'to_date'   => NULL,
                    );
                    //call api
                    $data_output = $orders_data->getListOrder($data_input);
                    $orders      = array();
                    if ($data_output) {
                        $orders = new CArrayDataProvider($data_output, array(
                            'keyField'   => FALSE,
                            'pagination' => array(
                                'pageSize' => 20,
                            ),
                        ));
                    }
                    //end get list order

                    //get list package
                    $data_input_pack = array(
                        'so_tb' => $customer->phone,
                    );
                    //call api
                    $data_output_pack = $orders_data->getListPackage($data_input_pack);

                    $packages = array();
                    if ($data_output_pack) {
                        $packages = new CArrayDataProvider($data_output_pack, array(
                            'keyField'   => FALSE,
                            'pagination' => array(
                                'pageSize' => 20,
                            ),
                        ));
                    }
                    //end get list package

                    $this->render('index', array(
                        'modelSearch' => $modelSearch,
                        'orders'      => $orders,
                        'packages'    => $packages,
                    ));
                } else {
                    //redirect to message
                    $this->redirect($this->createUrl('orders/message', array('t' => 4)));
                }
            } else {
                //redirect to message
                $this->redirect($this->createUrl('orders/message', array('t' => 4)));
            }
        }

        /**
         * search by sso_id
         *
         * @throws CException
         */
        public function actionSearchAjax()
        {
            if (!Yii::app()->user->isGuest) {
                $customer = WCustomers::model()->find('sso_id=:sso_id', array(':sso_id' => Yii::app()->user->sso_id));
                if ($customer) {
                    $orders_data = new OrdersData();
                    $msg         = $status = $from_date = $to_date = '';

                    $orders = array();
                    if (isset($_POST['SearchOrderForm'])) {
                        $status    = $_POST['SearchOrderForm']['status'];
                        $from_date = $_POST['SearchOrderForm']['from_date'];
                        $to_date   = $_POST['SearchOrderForm']['to_date'];
                    }

                    if ($from_date == '' && $to_date == '' && $status == '') {
                        $msg = Yii::t('web/portal', 'filter_order_empty');
                    } else {
                        $data_input = array(
                            'sso_id'    => $customer->sso_id,
                            'order_id'  => '',
                            'delivered' => $status,
                            'from_date' => $from_date,
                            'to_date'   => $to_date,
                        );

                        //call api
                        $data_output = $orders_data->getListOrder($data_input);
                        $orders      = new CArrayDataProvider($data_output, array(
                            'keyField'   => FALSE,
                            'pagination' => array(
                                'pageSize' => 20,
                            ),
                        ));
                    }
                    echo CJSON::encode($this->renderPartial('_list_order', array(
                        'orders' => $orders,
                        'msg'    => $msg,
                    ), TRUE));

                    Yii::app()->end();
                } else {
                    //redirect to message
                    $this->redirect($this->createUrl('orders/message', array('t' => 4)));
                }
            } else {
                //redirect to message
                $this->redirect($this->createUrl('orders/message', array('t' => 4)));
            }
        }

        /**
         * search order: order_id, phone_contact
         *
         * @throws CException
         */
        public function actionSearchOrder()
        {
            $this->pageTitle       = 'Đơn hàng - Tra cứu đơn hàng';
            $modelSearch           = new SearchOrderForm();
            $modelSearch->scenario = 'search_order';

            $order_info    = array();
            $customer_info = array();
            $order_detail  = array();
            $order_state   = array();

            //validate ajax
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'filter_order') {
                echo CActiveForm::validate($modelSearch);
                Yii::app()->end();
            }

            if (isset($_POST['SearchOrderForm'])) {
                $modelSearch->attributes = $_POST['SearchOrderForm'];

                if ($modelSearch->validate()) {
                    $orders_data = new OrdersData();
                    $data_input  = array(
                        'order_id'      => $modelSearch->id,
                        'phone_contact' => $modelSearch->phone_contact,
                    );
                    //call api
                    $data_output = $orders_data->getOrderDetail($data_input);

                    if ($data_output) {
                        $order_info    = isset($data_output['order_info']) ? $data_output['order_info'] : array();
                        $customer_info = isset($data_output['customer_info']) ? $data_output['customer_info'] : array();
                        $order_detail  = isset($data_output['order_detail']) ? $data_output['order_detail'] : array();
                        $order_state   = isset($data_output['order_states']) ? $data_output['order_states'] : array();
                    }
                    $order_detail = new CArrayDataProvider($order_detail, array(
                        'keyField' => FALSE,
                    ));

                    $order_state = new CArrayDataProvider($order_state, array(
                        'keyField' => FALSE,
                    ));
                }
            }

            $this->render('search', array(
                'modelSearch'   => $modelSearch,
                'order_info'    => $order_info,
                'customer_info' => $customer_info,
                'order_detail'  => $order_detail,
                'order_state'   => $order_state,
            ));
        }

        public function actionDetail($id)
        {
            $this->pageTitle = 'Đơn hàng - Chi tiết đơn hàng';
            if (!Yii::app()->user->isGuest && !empty($id)) {
                $orders_data   = new OrdersData();
                $data_input    = array(
                    'order_id'      => $id,
                    'phone_contact' => '',
                );
                $order_info    = array();
                $customer_info = array();
                $order_detail  = array();
                $order_state   = array();
                //call api
                $data_output = $orders_data->getOrderDetail($data_input);
                if ($data_output) {
                    $order_info    = isset($data_output['order_info']) ? $data_output['order_info'] : array();
                    $customer_info = isset($data_output['customer_info']) ? $data_output['customer_info'] : array();
                    $order_detail  = isset($data_output['order_detail']) ? $data_output['order_detail'] : array();
                    $order_state   = isset($data_output['order_states']) ? $data_output['order_states'] : array();
                }
                $order_detail = new CArrayDataProvider($order_detail, array(
                    'keyField' => FALSE,
                ));

                $order_state = new CArrayDataProvider($order_state, array(
                    'keyField' => FALSE,
                ));
                $this->render('detail', array(
                    'order_info'    => $order_info,
                    'customer_info' => $customer_info,
                    'order_detail'  => $order_detail,
                    'order_state'   => $order_state,
                ));
            } else {
                //redirect to message
                $this->redirect($this->createUrl('orders/message', array('t' => 4)));
            }
        }

        public function actionMessage($t)
        {
            $this->render('message', array(
                'msg' => Yii::t('web/portal', 'not_logged')
            ));
        }
    } //end class