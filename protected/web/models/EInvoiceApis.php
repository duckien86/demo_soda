<?php

class EInvoiceApis
{
    public $url;
    public $Account;
    public $ACpass;
    public $username;
    public $pass;
    public $pattern;
    public $serial;
    public $array_api_url;

    public function __construct()
    {
        $this->url      = $GLOBALS['config_common']['eInvoice']['url'];
        $this->Account  = $GLOBALS['config_common']['eInvoice']['Account'];
        $this->ACpass   = $GLOBALS['config_common']['eInvoice']['ACpass'];
        $this->username = $GLOBALS['config_common']['eInvoice']['username'];
        $this->pass     = $GLOBALS['config_common']['eInvoice']['pass'];
        $this->pattern  = $GLOBALS['config_common']['eInvoice']['pattern'];
        $this->serial   = $GLOBALS['config_common']['eInvoice']['serial'];
        $this->array_api_url = array(
            'importAndPublishInv' => $this->url.'PublishService.asmx?op=ImportAndPublishInv',
            'getInvViewFkey'      => $this->url.'PortalService.asmx?op=getInvViewFkeyNoPay',
            'downloadInvFkey'     => $this->url.'PortalService.asmx?op=downloadInvFkeyAction',
            'cancelInv'           => $this->url.'BusinessService.asmx?op=cancelInv',
            'adjustInv'           => $this->url.'BusinessService.asmx?op=AdjustInvoie',
        );
    }


    public function importAndPublishInv($xmlInvData, $convert = 0){
        $type = 'importAndPublishInv';
        $id = Yii::app()->request->csrfToken;

        $arr_params = array(
            'Account' => $this->Account,
            'ACpass' => $this->ACpass,
            'xmlInvData' => $xmlInvData,
            'username' => $this->username,
            'pass' => $this->pass,
            'pattern' => $this->pattern,
            'serial' => $this->serial,
            'convert' => $convert,
        );
        $xml_body = $this->setBodyXml('ImportAndPublishInv', $arr_params);
        $opt_header_arr = array(
            "content-type: text/xml; charset=utf-8",
        );
        $url = $this->array_api_url['importAndPublishInv'];
        //call api
        $response = Utils::cUrlPostJson($url, $xml_body, FALSE, 45, $http_code, $opt_header_arr);

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());
        $logMsg[] = array($xml_body, 'Input: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        //decode output
        $doc = new DOMDocument();
        $doc->loadXML($response);
        $response = $doc->getElementsByTagName('ImportAndPublishInvResponse')->item(0)->nodeValue;
        
        $arr_response = explode(':', $response);
        
        $return = array(
            'success' => TRUE,
            'msg' => '',
            'data' => '',
        );

        if(count($arr_response) == 2){
            switch ($arr_response[0]){
                case 'ERR':
                    $return['success'] = false;
                    switch ($arr_response[1]){
                        case 1:
                            $return['msg'] = 'Tài khoản đăng nhập sai hoặc không có quyền thêm khách hàng';
                            break;
                        case 3:
                            $return['msg'] = 'Dữ liệu xml đầu vào không đúng quy định';
                            break;
                        case 7:
                            $return['msg'] = 'User name không phù hợp, không tìm thấy company tương ứng cho user';
                            break;
                        case 20:
                            $return['msg'] = 'Pattern và serial không phù hợp, hoặc không tồn tại hóa đơn đã đăng kí có sử dụng pattern và serial truyền vào';
                            break;
                        case 5:
                            $return['msg'] = 'Không phát hành được hóa đơn';
                            break;
                        case 10:
                            $return['msg'] = 'Lô có số hóa đơn vượt quá max cho phép';
                            break;
                    }
                    break;
                case 'OK':
                    $return['msg'] = 'Đã phát hành hóa đơn thành công';
                    $return['data'] = $arr_response[1];
                    break;
                default:
                    $return['success'] = false;
                    $return['msg'] = "Dữ liệu trả về sai định dạng";
            }
        }else{
            $return['success'] = false;
            $return['msg'] = "Dữ liệu trả về sai định dạng";
        }


        return $return;
    }

    public function getInvViewFkey($fkey){
        $type = 'getInvViewFkey';
        $id = Yii::app()->request->csrfToken;

        $arr_params = array(
            'fkey' => $fkey,
            'userName' => $this->username,
            'userPass' => $this->pass
        );
        $xml_body = $this->setBodyXml('getInvViewFkeyNoPay', $arr_params);
        
        $opt_header_arr = array(
            "content-type: text/xml; charset=utf-8",
        );
        $url = $this->array_api_url['getInvViewFkey'];
        //call api
        $response = Utils::cUrlPostJson($url, $xml_body, FALSE, 45, $http_code, $opt_header_arr);

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());
        $logMsg[] = array($xml_body, 'Input: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        //decode output
        //decode output
        $doc = new DOMDocument();
        $doc->loadXML($response);
        $response = $doc->getElementsByTagName('getInvViewFkeyNoPayResponse')->item(0)->nodeValue;

        $arr_response = explode(':', $response);
        $return = array(
            'success' => TRUE,
            'msg' => '',
            'data' => $response,
        );
        if(count($arr_response) == 2){
            switch ($arr_response[0]){
                case 'ERR':
                    $return['success'] = false;
                    switch ($arr_response[1]){
                        case 1:
                            $return['msg'] = 'Tài khoản đăng nhập sai';
                            break;
                        case 6:
                            $return['msg'] = 'Chuỗi Fkey không chính xác';
                            break;
                        case 7:
                            $return['msg'] = 'Công ty không tồn tại';
                            break;
                        case 11:
                            $return['msg'] = 'Hóa đơn chưa được thanh toán';
                            break;
                    }
                    break;
                default:
                    $return['success'] = true;
                    $return['msg'] = 'default success';
            }
        }
        return $return;
    }

    public function downloadInvFkey($fkey){
        $type = 'downloadInvFkey';
        $id = Yii::app()->request->csrfToken;

        $arr_params = array(
            'fkey' => $fkey,
            'userName' => $this->username,
            'userPass' => $this->pass
        );
        $xml_body = $this->setBodyXml('downloadInvFkeyAction', $arr_params);

        $opt_header_arr = array(
            "content-type: text/xml; charset=utf-8",
        );
        $url = $this->array_api_url['downloadInvFkey'];
        //call api
        $response = Utils::cUrlPostJson($url, $xml_body, FALSE, 45, $http_code, $opt_header_arr);

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());
        $logMsg[] = array($xml_body, 'Input: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        //decode output
        $doc = new DOMDocument();
        $doc->loadXML($response);
        $response = $doc->getElementsByTagName('downloadInvFkeyActionResult')->item(0)->nodeValue;

        $arr_response = explode(':', $response);
        $return = array(
            'success' => TRUE,
            'msg' => '',
            'data' => $response,
        );
        if(count($arr_response) == 2){
            switch ($arr_response[0]){
                case 'ERR':
                    $return['success'] = false;
                    switch ($arr_response[1]){
                        case 1:
                            $return['msg'] = 'Tài khoản đăng nhập sai';
                            break;
                        case 6:
                            $return['msg'] = 'Chuỗi Fkey không chính xác';
                            break;
                        case 7:
                            $return['msg'] = 'Công ty không tồn tại';
                            break;
                        case 11:
                            $return['msg'] = 'Hóa đơn chưa được thanh toán';
                            break;
                    }
                    break;
                default:
                    $return['success'] = true;
                    $return['msg'] = 'default success';
            }
        }
        return $return;
    }

    public function cancelInv($fkey){
        $type = 'cancelInv';
        $id = Yii::app()->request->csrfToken;

        $arr_params = array(
            'Account' => $this->Account,
            'ACpass' => $this->ACpass,
            'fkey' => $fkey,
            'userName' => $this->username,
            'userPass' => $this->pass,
        );
        $xml_body = $this->setBodyXml('cancelInv', $arr_params);

        $opt_header_arr = array(
            "content-type: text/xml; charset=utf-8",
        );
        $url = $this->array_api_url['cancelInv'];
        //call api
        $response = Utils::cUrlPostJson($url, $xml_body, FALSE, 45, $http_code, $opt_header_arr);

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());
        $logMsg[] = array($xml_body, 'Input: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        //decode output
        //decode output
        $doc = new DOMDocument();
        $doc->loadXML($response);
        $response = $doc->getElementsByTagName('cancelInvResult')->item(0)->nodeValue;

        $arr_response = explode(':', $response);
        $return = array(
            'success' => TRUE,
            'msg' => '',
            'data' => '',
        );
        if(count($arr_response) == 2){
            switch ($arr_response[0]){
                case 'ERR':
                    $return['success'] = false;
                    switch ($arr_response[1]){
                        case 1:
                            $return['msg'] = 'Tài khoản đăng nhập sai hoặc không có quyền thêm khách hàng';
                            break;
                        case 2:
                            $return['msg'] = 'Không tồn tại hóa đơn cần hủy';
                            break;
                        case 8:
                            $return['msg'] = 'Hóa đơn đã được thay thế rồi, hủy rồi';
                            break;
                        case 9:
                            $return['msg'] = 'Trạng thái hóa đơn ko được hủy';
                            break;
                    }
                    break;
                case 'OK':
                    $return['success'] = TRUE;
                    $return['msg'] = 'Hủy thành công';
                    break;
                default:
                    $return['success'] = TRUE;
                    $return['msg'] = 'default success';
            }
        }
        return $return;
    }

    public function adjustInv($xmlInvData, $fkey, $convert = 0){
        $type = 'adjustInv';
        $id = Yii::app()->request->csrfToken;

        $arr_params = array(
            'Account' => $this->Account,
            'ACpass' => $this->ACpass,
            'xmlInvData' => $xmlInvData,
            'username' => $this->username,
            'pass' => $this->pass,
            'pattern' => $this->pattern,
            'serial' => $this->serial,
            'fkey' => $fkey,
            'convert' => $convert,
        );
        $xml_body = $this->setBodyXml('AdjustInvoie', $arr_params);

        $opt_header_arr = array(
            "content-type: text/xml; charset=utf-8",
        );
        $url = $this->array_api_url['adjustInv'];
        //call api
        $response = Utils::cUrlPostJson($url, $xml_body, FALSE, 45, $http_code, $opt_header_arr);

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());
        $logMsg[] = array($xml_body, 'Input: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        //decode output
        $doc = new DOMDocument();
        $doc->loadXML($response);
        $response = $doc->getElementsByTagName('AdjustInvoieResult')->item(0)->nodeValue;

        $arr_response = explode(':', $response);

        $return = array(
            'success' => TRUE,
            'msg' => '',
            'data' => '',
        );

        if(count($arr_response) == 2){
            switch ($arr_response[0]){
                case 'ERR':
                    $return['success'] = false;
                    switch ($arr_response[1]){
                        case 1:
                            $return['msg'] = 'Tài khoản đăng nhập sai hoặc không có quyền thêm khách hàng';
                            break;
                        case 2:
                            $return['msg'] = 'Hóa đơn cần điều chỉnh không tồn tại';
                            break;
                        case 3:
                            $return['msg'] = 'Dữ liệu xml đầu vào không đúng quy định';
                            break;
                        case 5:
                            $return['msg'] = 'Không phát hành được hóa đơn';
                            break;
                        case 6:
                            $return['msg'] = 'Dải hóa đơn cũ đã hết';
                            break;
                        case 7:
                            $return['msg'] = 'User name không phù hợp, không tìm thấy company tương ứng cho user.';
                            break;
                        case 8:
                            $return['msg'] = 'Hóa đơn cần điều chỉnh đã bị thay thế. Không thể điều chỉnh được nữa.';
                            break;
                        case 9:
                            $return['msg'] = 'Trạng thái hóa đơn không được điều chỉnh';
                            break;
                    }
                    break;
                case 'OK':
                    $return['msg'] = 'Thực hiện điều chỉnh hóa đơn thành công';
                    $return['data'] = $arr_response[1];
                    break;
                default:
                    $return['success'] = false;
                    $return['msg'] = "Dữ liệu trả về sai định dạng";
            }
        }else{
            $return['success'] = false;
            $return['msg'] = "Dữ liệu trả về sai định dạng";
        }
        return $return;
    }
    
    public function setBodyXml($function, $arr_params){
        $body_function = '<'.$function.' xmlns="http://tempuri.org/">';
        $end_body_function = '</' .$function .'>';
        $xml = '<?xml version="1.0" encoding="utf-8"?>';
        $xml .= '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">';
        $xml .= '<soap:Body>';
        $xml .= $body_function;
        foreach ($arr_params as $key => $value){
            if($key == 'xmlInvData'){
                $xml .= '<'.$key.'><![CDATA['.$value.']]></'.$key.'>';
            }else{
                $xml .= '<'.$key.'>'.$value.'</'.$key.'>';
            }
        }
        $xml .= $end_body_function;
        $xml .= '</soap:Body>';
        $xml .= '</soap:Envelope>';

        return $xml;
    }

}