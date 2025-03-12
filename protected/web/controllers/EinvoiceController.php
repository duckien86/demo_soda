<?php
    use Dompdf\Dompdf;

    class EinvoiceController extends Controller
{
    private $isMobile = FALSE;
    public $layout = '/layouts/main';
    public $defaultAction = 'create';
    public function filters()
    {
        return array(
            'accessControl',
            'postOnly + delete', // we only allow deletion via POST request
        );
    }
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('importAndPublishInv'),
                'ips' => array($GLOBALS['config_common']['project']['host']),
            ),
            array('deny',
                'actions' => array('importAndPublishInv'),
                'ips' => array('*'),
            ),
        );
    }
        public function init()
    {
        parent::init();
        $detect = new MyMobileDetect();
        $this->isMobile = $detect->isMobile();
        if ($detect->isMobile()) {
            $this->layout = '/layouts/mobile_main';
        }
    }

    /**
     * Default action
     */
    public function actionCreate()
    {
        $this->pageTitle = 'Đăng ký nhân hóa đơn điện tử';

        $order_id = $_GET['order_id'];
        $key = $_GET['key'];

        if(empty($order_id) || empty($key) || (md5($order_id.'E_INVOICE') != $key)){
            throw new CHttpException(404, 'Yêu cầu của bạn không hợp lệ');
        }

        $order = WOrders::model()->findByPk($order_id);

        $model = new WOrderEinvoice('create');
        $model->order_id = $order_id;
        $model->c_name = $order->full_name;
        $model->c_phone = $order->phone_contact;
        $model->c_email = $order->email;

        if(isset($_POST['WOrderEinvoice'])){
            $model->attributes = $_POST['WOrderEinvoice'];

            if($model->validate()){

                if($model->save()){

                    $invoice = new EBInvoices();
                    $invoice->cus_name = $model->c_name;
                    $invoice->cus_phone = $model->c_phone;
                    $invoice->cus_tax_code = $model->c_tax_code;
                    $invoice->cus_address = $model->c_address;
                    $invoice->cus_code = $order->sso_id;
                    $invoice->products = array();
                    
                    $order_details = WOrderDetails::model()->findAllByAttributes(array('order_id' => $order_id));
                    foreach ($order_details as $detail){
                        $product = new EBProducts();
                        $product->prod_name = $detail->item_id;
                        $product->prod_unit = '';
                        $product->prod_price = $detail->price;
                        $product->prod_quantity = $detail->quantity;
                        $product->amount = $detail->price*$detail->quantity;

                        $invoice->products[] = $product;
                    }


                    $xmlData = EBInvoices::parserToXml(array($invoice));

                    $e_invoice_api = new EInvoiceApis();
//                    $e_invoice_api->importAndPublishInv($xmlData);

                }

            }
        }


        $this->render('create', array(
            'model'     => $model,
            'order_id'  => $order_id,
            'key'       => $key
        ));

    } //end index
    
    public function actionImportAndPublishInv(){
        $order = WOrders::getExportedInvOrder('one');
        if($order && $order->c_email){
            $xmlData = WOrders::setXmlInvData($order);
            $e_invoice_api = new EInvoiceApis();
            // phát hành hóa đơn
            $result = $e_invoice_api->importAndPublishInv($xmlData);
            if(isset($result['success']) && $result['success'] == TRUE){
                // lưu trạng thái phát hành hóa đơn
                $order_einvoice = WOrderEinvoice::model()->findByPk($order->order_einvoice_id);
                $order_einvoice->status = WOrderEinvoice::INVOICED;
                $order_einvoice->key = $result['data'];
                $order_einvoice->save();

                // in và gửi hóa đơn
                $fkey = WOrderEinvoice::getFkey($order_einvoice->key);
                $this->downloadInvFkey($fkey);
            }
            CVarDumper::dump($result, 10, true); die;
        }else{
            echo "Không có hóa đơn cần phát hành"; die;
        }
    } //end xuất hóa đơn điện tử

    public function downloadInvFkey($fkey){
//
        $e_invoice_api = new EInvoiceApis();
        $result = $e_invoice_api->getInvViewFkey($fkey);

        // export pdf
        if($result && $result['success'] == TRUE){
            // render PDF
            $html = $this->renderPartial('download',array('html'=> $result['data']), true);

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);

            $dompdf->setPaper('A4');
            $dompdf->render();
//            $dompdf->stream("dompdf_out.pdf", array("Attachment" => false)); die;
            $output = $dompdf->output();

            // sendEmail
            $from = 'Freedoo';
            $to = 'chinh.nd@centech.com.vn';
            $subject = $short_desc = '';
            $content = 'Hóa đơn điện tử';
            $views_layout_path = 'web.views.layouts';
            $sendEmail = Utils::sendEmail($from, $to, $subject, $short_desc, $content, $views_layout_path, $output);
            if($sendEmail){
                $result = array(
                      'success' => true,
                      'msg' => 'Gửi mail thành công',
                      'data' => ''
                );
            }else{
                $result = array(
                    'success' => false,
                    'msg' => 'Gửi mail thất bại',
                    'data' => ''
                );
            }
        }
        CVarDumper::dump($result, 10, true); die;

    }// download hóa đơn

//    public function actionCancelInv(){
//        $oe_id = 1;
//        $order_einvoice = WOrderEinvoice::model()->findByPk($oe_id);
//        $fkey = WOrderEinvoice::getFkey($order_einvoice->key);
//        $e_invoice_api = new EInvoiceApis();
//        $result = $e_invoice_api->cancelInv($fkey);
//        if(isset($result['success']) && $result['success'] == TRUE){
//            $order_einvoice->status = WOrderEinvoice::INVOICING;
//            $order_einvoice->key = '';
//            $order_einvoice->save();
//            $result['msg'] = 'DONE' ;
//        }
//        CVarDumper::dump($result, 10, true); die;
//    }// end hủy hóa đơn điện tử

//    public function actionAdjustInv(){
//        $oe_id = 1;
//        $order_einvoice = WOrderEinvoice::model()->findByPk($oe_id);
//        $fkey = WOrderEinvoice::getFkey($order_einvoice->key);
//        $order = WOrders::getExportedInvOrder('one', $order_einvoice->order_id);
//        $xmlInvData = WOrders::setXmlInvData($order, TRUE, $fkey);
//        
//        $e_invoice_api = new EInvoiceApis();
//        $result = $e_invoice_api->adjustInv($xmlInvData, $fkey);
//
//        CVarDumper::dump($result, 10, true); die;
//    }

}