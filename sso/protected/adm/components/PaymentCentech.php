<?php
    /**
     * Author: KienND
     * Date: 04/09/2015
     * Time: 9:00 AM
     */

    /**
     * Basic technical informations
     *
     * 1. Standard HTTP response
     *     HTTP 200 - Success.
     *     HTTP 400 - Parameter error.
     *     HTTP 401 - Authentication failed.
     *     HTTP 403 - No permission to access the resource.
     *     HTTP 405 - HTTP method not allowed, POST while expecting GET or vice versa.
     *     HTTP 500 - Resource error.
     *
     */
    class PaymentCentech
    {

        const DETECTION       = 'DETECTION';
        const REGISTER        = 'REGISTER';
        const DOWNLOAD        = 'DOWNLOAD';
        const CANCEL          = 'CANCEL';
        const PAYMENT_SUCCESS = '0';

        const SMS_CHANNEL = 'SMS';
        const WAP_CHANNEL = 'WAP';

        const VIETTEL_TELCO   = 'VIETTEL';
        const VINAPHONE_TELCO = 'VINAPHONE';
        const MOBIFONE_TELCO  = 'MOBIFONE';
        const UNKNOWN_TELCO   = 'UNKNOWN_TELCO';


        /**
         * Generate PayCentech Url
         *
         * @param int    $cp_id
         * @param string $command      (DOWNLOAD | REGISTER | CANCEL | DETECTION)
         * @param array  $arrayParams
         * @param string $serviceid
         * @param string $content_id
         * @param string $mode         (PROD | API | TEST)
         * @param int    $verify_param (1:Yes | 0: No)
         *
         * @return string
         */
        public static function genPayCentechUrl($cp_id, $command, $arrayParams, $serviceid = '', $content_id = '', $mode = 'WAP', $verify_param = 1)
        {
            // Load các thông tin từ file XML
            $xml = self::loadXMLContent(Yii::app()->params->xml_folder, $cp_id);

            // Lấy các tham số cần sử dụng khi thực hiện giao dịch
            $keyAES      = self::genKeyAES(); // Key AES sinh theo từng phiên giao dịch, bao gồm 32 ký tự bất kỳ
            $publicKey1  = $xml->publish_key_1;
            $privateKey2 = $xml->private_key_2;

            // Khởi tạo các tham số cần sử dụng
            $converter = new Encryption();
            $converter->setSkey($keyAES);
            $converter->setPublicKey($publicKey1);
            $converter->setPrivateKey($privateKey2);

            // Convert $arrayParams về dạng string
            $urlEncode = http_build_query($arrayParams);

            // Mã hóa dữ liệu bằng key AES
            $dataEncode = $converter->encode($urlEncode);

            // Dữ liệu mã hóa + key AES
            $dataAES = 'data=' . $dataEncode . '&key=' . $keyAES . '';

            // Mã hóa dữ liệu bằng puclicKey1
            $dataEncrypted = urlencode($converter->encryptData($dataAES));

            // Tạo chữ ký bằng privateKey2
            $dataSignature = $converter->createSignature($dataEncrypted);

            if ($command == self::DOWNLOAD || $command == self::REGISTER || $command == self::CANCEL) {
                $requestCMD = $command;
                if ($command == self::DOWNLOAD && $content_id == '') {
                    return FALSE;
                }
            } else if ($command == self::DETECTION) {
                $requestCMD = self::DETECTION;
            } else {
                echo 'CMD parameter invalid';
                exit();
            }
            // Dữ liệu redirect sang PayCentech
            $arrayRedirect = array(
                'cmd'        => $requestCMD,
                'cpid'       => $cp_id,
                'data'       => $dataEncrypted,
                'signature'  => $dataSignature,
                'serviceid'  => $serviceid,
                'mode'       => $mode,
                'campaignid' => Yii::app()->session['campaignid'],
                'itemid'     => $content_id,
                'pv'         => $verify_param,
            );

            $urlRedirect = http_build_query($arrayRedirect);

            // Url redirect sang PayCentech
            $urlRedirect = Yii::app()->params['url_payment_centech'] . '/charge.html?' . $urlRedirect;

            return $urlRedirect;
        }

        /**
         * Decryption data from payment Centech
         *
         * @param $cp_id
         * @param $data_encrypted
         * @param $signature
         *
         * @return bool
         */
        public static function decryptDetection($cp_id, $data_encrypted, $signature)
        {
            // Load các thông tin từ file XML
            $xml = self::loadXMLContent(Yii::app()->params->xml_folder, $cp_id);
            // Lấy các tham số cần sử dụng khi thực hiện giao dịch
            $publicKey1  = $xml->publish_key_1;
            $privateKey2 = $xml->private_key_2;

            // Khởi tạo các tham số cần sử dụng
            $converter = new Encryption();
            $converter->setPublicKey($publicKey1);
            $converter->setPrivateKey($privateKey2);
            $converter->setSignature($signature);

            $verifySignature = $converter->verifySignature($data_encrypted);

            if ($verifySignature) {
                $dataDecrypted = $converter->decryptData($data_encrypted);
                if ($dataDecrypted) {
                    parse_str($dataDecrypted, $outputDecrypted);
                    $dataAES = isset($outputDecrypted['data']) ? trim($outputDecrypted['data']) : FALSE;
                    $keyAES  = isset($outputDecrypted['key']) ? trim($outputDecrypted['key']) : FALSE;

                    if ($dataAES && $keyAES) {
                        // Khởi tạo key AES
                        $converter->setSkey($keyAES);
                        // Giải mã dữ liệu ($dataAES) nhận được
                        $dataDecoded = $converter->decode($dataAES);
                        if ($dataDecoded) {
                            parse_str($dataDecoded, $outputDecode);
                            $msisdn = $outputDecode['mobile'];
//                            CVarDumper::dump($outputDecode,10,true);die;
                            if ($msisdn != '') {
                                Yii::app()->session['msisdn'] = PaymentCentech::makePhoneNumberStandard($msisdn);
                            }

                            return $outputDecode;
                        }
                    }
                }
            }

            return FALSE;
        }

        /**
         * Load XML Content
         *
         * @param $filePath
         * @param $fileName
         *
         * @return bool|SimpleXMLElement
         */
        public function loadXMLContent($filePath, $fileName)
        {
            $xmlFilePath = $filePath . $fileName . '.xml';
            if (file_exists($xmlFilePath)) {
                $xmlContent = simplexml_load_file($xmlFilePath);
                $ary_return = array();
                foreach ($xmlContent as $key => $value) {
                    $ary_return[$key] = (string)$value;
                }

                return $xmlContent;
            }

            echo 'xml file is not exists';
            exit();
        }

        /**
         * $params: $lengthChars độ dài của chuỗi ký tự cần tạo
         *
         * @return: Tạo random key AES. Hàm này sẽ trả về 32 ký tự bất kỳ
         */

        private function genKeyAES($lengthChars = 32)
        {
            if ($lengthChars <= 0) {
                return FALSE;
            } else {
                $alphaString  = 'abcdefghijklmnopqrstuvwxyz';
                $numberString = '1234567890';

                $shuffleString = $alphaString . $numberString;
                $randomString  = substr(str_shuffle($shuffleString), 0, $lengthChars);

                return $randomString;
            }
        }

        public static function is3G()
        {
            $IP_3G_RANGE = "/^183\.182\.126\.([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-4])$/###
    					 /^183\.182\.127\.([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-4])$/###
                         /^103\.1\.30\.([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-4])$/###
                         /^172\.(70|71|72|79)\.0\.0$/###
                         /^172\.(84|85|86|87|88|89|90)\.0\.0$/###";

            $ip          = trim($_SERVER['REMOTE_ADDR']);
            $str_partern = $IP_3G_RANGE;
            $arrPatern   = explode('###', $str_partern);

            foreach ($arrPatern as $partern) {
                $partern = trim($partern);
                if (!empty($partern) && preg_match($partern, $ip)) {
                    return TRUE;
                }
            }

            return FALSE;
        }


        public static function detectMSISDN()
        {
            $msisdn = '';
//            if (self::is3G()) {
            if ($msisdn == '')
                $msisdn = (isset($_SERVER['HTTP_X_WAP_MSISDN']) && $_SERVER['HTTP_X_WAP_MSISDN']) ? $_SERVER['HTTP_X_WAP_MSISDN'] : $msisdn;
            if ($msisdn == '')
                $msisdn = (isset($_SERVER['X_WAP_MSISDN']) && $_SERVER['X_WAP_MSISDN']) ? $_SERVER['X_WAP_MSISDN'] : $msisdn;
            if ($msisdn == '')
                $msisdn = (isset($_SERVER['X-Wap-MSISDN']) && $_SERVER['X-Wap-MSISDN']) ? $_SERVER['X-Wap-MSISDN'] : $msisdn;
            if ($msisdn == '')
                $msisdn = (isset($_SERVER['MSISDN']) && $_SERVER['MSISDN']) ? $_SERVER['MSISDN'] : $msisdn;
            if ($msisdn == '')
                $msisdn = (isset($_SERVER['msisdn']) && $_SERVER['msisdn']) ? $_SERVER['msisdn'] : $msisdn;
            if ($msisdn == '')
                $msisdn = (isset($_SERVER['HTTP-X-WAP-MSISDN']) && $_SERVER['HTTP-X-WAP-MSISDN']) ? $_SERVER['HTTP-X-WAP-MSISDN'] : $msisdn;
            if ($msisdn == '')
                $msisdn = (isset($_SERVER['HTTP_X_WAP_MSISDN']) && $_SERVER['HTTP_X_WAP_MSISDN']) ? $_SERVER['HTTP_X_WAP_MSISDN'] : $msisdn;
            if ($msisdn == '')
                $msisdn = (isset($_SERVER['HTTP-MSISDN']) && $_SERVER['HTTP-MSISDN']) ? $_SERVER['HTTP-MSISDN'] : $msisdn;
            if ($msisdn == '')
                $msisdn = (isset($_SERVER['HTTP_MSISDN']) && $_SERVER['HTTP_MSISDN']) ? $_SERVER['HTTP_MSISDN'] : $msisdn;
            if ($msisdn == '')
                $msisdn = (isset($_SERVER['X-MSISDN']) && $_SERVER['X-MSISDN']) ? $_SERVER['X-MSISDN'] : $msisdn;
            if ($msisdn == '')
                $msisdn = (isset($_SERVER['X_MSISDN']) && $_SERVER['X_MSISDN']) ? $_SERVER['X_MSISDN'] : $msisdn;

//            }

            return $msisdn;
        }

        public function callPPD($phone_number, $content_id, $price)
        {
            $unitel_charging = array(
                'charging_url'      => 'http://10.58.56.58:9010/api/charging/',
                'charging_cpcode'   => '003',
                'charging_username' => 'sdpcharging',
                'charging_password' => 'sDpChar123ng',
                'charging_type'     => 'download',
                'charging_channel'  => 'WAP',
                'charging_src'      => '930'
            );

            $charging_url      = $unitel_charging['charging_url'];
            $charging_username = $unitel_charging['charging_username'];
            $charging_password = $unitel_charging['charging_password'];
            $charging_type     = $unitel_charging['charging_type'];
            $charging_channel  = $unitel_charging['charging_channel'];
            $charging_src      = $unitel_charging['charging_src'];
            $msgtype           = $unitel_charging['msgtype'];
            $cpid              = $unitel_charging['charging_cpcode'];
            $serviceid         = $unitel_charging['serviceid'];
            $mtseq             = $cpid . '' . date('ymdHis') . rand(100, 999);       //ppp yy MM dd HH mm ss XXX
            $msgtitle          = '';
            $msgbody           = urlencode(CHtml::encode('test charging'));

            if (Yii::app()->session['campaign_id'] != '') {
                $campaign_id = Yii::app()->session['campaign_id'];
                $charging_channel .= '-' . $campaign_id;
            }

            $url_charging = $charging_url . '?type=' . $charging_type . '&channel=' . $charging_channel . '&src=' . $charging_src . '&dest=' . $phone_number . '';
            $url_charging .= '&msgtype=' . $msgtype . '&msgtitle=' . $msgtitle . '&msgbody=' . $msgbody . '&procresult=1&cpid=' . $cpid . '&serviceid=' . $serviceid . '';
            $url_charging .= '&username=' . $charging_username . '&password=' . $charging_password . '&contentid=' . $content_id . '&price=' . $price . '&mtseq=' . $mtseq . '';
            $url_charging .= '&cpname=' . $cpid . '';

            $statusCode = CFunction_MPS::getHeaderCodeUrl($url_charging, 30);

            // return demo array
            return array('UrlCharging' => $url_charging, 'Status' => $statusCode);
            //return array ('Status' => 1001, 'Message' => 'Some error when charge your account, please contact admin for help.', 'ServerOutput' => $server_output);
        }

        /**
         * @params     : $cpCode là mã CP do Telco cung cấp (0 -> 999)
         *
         * @return     : Hàm này trả về chuỗi số định danh 1 giao dịch CPRequestionID
         * @description: Chuỗi số này gồm 18 ký tự theo theo quy tắc pppyyMMddHHmmssXXX
         */
        public static function setREQ($cpCode)
        {
            if (!$cpCode) {
                return '';
            } else {
                $timeREQ = date('ymdHis', time());
                $endREQ  = rand(000, 999);
                if (strlen($endREQ) == 1) {
                    $endREQ = '00' . $endREQ;
                } else if (strlen($endREQ) == 2) {
                    $endREQ = '0' . $endREQ;
                }
                $randomREQ = $cpCode . $timeREQ . $endREQ;

                return $randomREQ;
            }
        }

        /**
         * @params: $phoneNumber Số điện thoại chưa đúng dạng chuẩn (0xxx)
         *
         * @return: Trả về số điện thoại đúng dạng chuẩn (84xxx)
         */
        public static function makePhoneNumberStandard($phoneNumber)
        {
            $phoneNumberStandard = '';
            if ($phoneNumber != '') {
                if (substr($phoneNumber, 0, 1) == '0') {
                    $phoneNumberStandard = substr($phoneNumber, 1, strlen($phoneNumber));
                } else if (substr($phoneNumber, 0, 2) == '84') {
                    $phoneNumberStandard = substr($phoneNumber, 2, strlen($phoneNumber));
                } else {
                    $phoneNumberStandard = $phoneNumber;
                }
                $phoneNumberStandard = '84' . $phoneNumberStandard;
            }

            return $phoneNumberStandard;
        }

        /**
         * @param $msisdn
         * @param $msgBody
         * @param $api_url
         *
         * @return bool
         */
        public static function sentMtVNP($msisdn, $msgBody, &$api_url)
        {
            $msisdn       = CFunction_MPS::makePhoneNumberStandard($msisdn);
            $mtseq        = time() . rand(1000, 9999);
            $smsMtRequest = array(
                'src'        => '9307',
                'mtseq'      => $mtseq,
                'moseq'      => $mtseq,
                'procresult' => 0,
                'username'   => 'vovtt',
                'password'   => '12345',
                'dest'       => $msisdn,
                'msgtype'    => 'Text',
                'msgbody'    => $msgBody,
            );

            $api_url = 'http://42.117.7.61:8787/smsmt/vovtt?' . http_build_query($smsMtRequest);

            $rs = self::cUrl($api_url, $http_code, 10);
            if ($http_code == '200') {
                if ($rs) {
                    return TRUE;
                }
            }

            echo "url: $api_url <br>";
            echo "http_code: $http_code <br>";

            return FALSE;
        }

        /**
         * @param $msisdn
         * @param $msgBody
         * @param $api_url
         *
         * @return bool
         */
        public static function sentMtMobifone($msisdn, $msgBody, &$api_url, &$http_code = '')
        {
            $msisdn = CFunction_MPS::makePhoneNumberStandard($msisdn);
            $mtseq  = time() . rand(1000, 9999);

            $smsMtRequest = array(
                'msgtype'    => 'text',
                'msgservice' => 'mtdisneyvms',
                'src'        => '9065',
                'dest'       => $msisdn,
                'mtseq'      => $mtseq,
                'msgtitle'   => 'DISNEY',
                'msgbody'    => $msgBody,
                'cpid'       => '010',
                'username'   => 'disney9065',
                'password'   => 'd720161ney9o65',
            );

            $api_url = 'http://10.11.11.9:9302/api/forwarder/?' . http_build_query($smsMtRequest);

            $rs = self::cUrl($api_url, $http_code, 10);
            if ($http_code == '200') {
                return TRUE;
            }

            //echo "url: $api_url <br>";
            //echo "http_code: $http_code <br>";

            return FALSE;
        }

        /**
         * @param        $msisdn
         * @param        $price
         * @param        $item_code
         * @param string $http_code
         *
         * @return bool|mixed
         */
        public static function callDirectCharging($msisdn, $price, $content_id, &$logMsg)
        {
            $msisdn = CFunction_MPS::makePhoneNumberStandard($msisdn);
            $mtseq  = time() . rand(1000, 9999);
            $cp_id  = '010';

            $charging_params = array(
                'msgservice'    => 'cgdisneyvms',
                'cpid'          => $cp_id,
                'contenttype'   => 'GAME',
                'type'          => self::DOWNLOAD,
                'password'      => 'd1n203y2016',
                'mtseq'         => $mtseq,
                'serviceid'     => '151',
                'username'      => 'disney',
                'dest'          => $msisdn,
                'price'         => $price,
                'msgtype'       => 'MTChargingRequest',
                'originalprice' => $price,
                'src'           => '9065',
                'procresult'    => 1,
                'cpname'        => $cp_id,
                'msgbody'       => 'mtcharging',
                'channel'       => 'WAP',
                'note'          => '',
                'msgtitle'      => 'Disney',
                'contentname'   => $content_id,
                'contentid'     => '90001',
            );

            $api_url  = 'http://10.11.11.9:9202/api/forwarder/?' . http_build_query($charging_params);
            $logMsg[] = array($api_url, 'api url:', 'T', time());

            $rs       = self::cUrl($api_url, $http_code, 10);
            $rs       = trim($rs);
            $logMsg[] = array("http_code:$http_code | output:$rs", 'Response:', 'T', time());

            return $http_code;
        }


        /**
         * convertStrLength
         *
         * @param $str
         * @param $maxLength
         *
         * @return mixed
         */
        public static function convertStrLength($str, $maxLength, $preStr = '')
        {
            $strLength    = strlen($str);
            $preStrLength = strlen($preStr);
            if ($strLength <= $maxLength) {
                $str_result = str_repeat('0', $maxLength - ($strLength + $preStrLength)) . $str;
            } else {
                $str_result = substr($str, 0, $maxLength);
            }

            return $preStr . $str_result;
        }

        /**
         * Build http query by params
         *
         * @param $arrayParams
         *
         * @return string
         */
        public static function httpBuildQuery($arrayParams)
        {
            return urldecode(http_build_query($arrayParams));
        }

        /**
         * Get Content from url (CURL)
         *
         * @param $url (api url)
         *
         * @return mixed (array|bool)
         */
        public static function cUrl($url, &$http_status, $timeout)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $rs          = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (!empty($rs)) {

                return $rs;
            }

            return FALSE;

        }

        /**
         * Get Content from url (CURL)
         *
         * @param $url
         * @param $raw_output
         * @param $http_status
         *
         * @return bool|DOMDocument
         * @throws Exception
         */
        public static function callAPI($url, &$raw_output, &$http_status)
        {
            if (!empty($url)) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $rs          = curl_exec($ch);
                $raw_output  = $rs;
                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                // Make xml string standard
                $rs = self::get_string_between($rs, '<response>', '</response>');

                if (!empty($rs)) {
                    return XML2Array::createArray($rs);
                }
            }

            return FALSE;
        }

        /**
         * Get substring between 2 string node
         *
         * @param $string
         * @param $start
         * @param $end
         *
         * @return bool|string
         */
        public static function get_string_between($string, $start, $end)
        {
            $string = " " . $string;
            $ini    = strpos($string, $start);
            if ($ini == 0) return FALSE;
            $ini += strlen($start);
            $len = strpos($string, $end, $ini) - $ini;

            return $start . substr($string, $ini, $len) . $end;
        }
        
        /**
         * @return bool
         */
        public static function isMobifone($msisdn)
        {

            if ($msisdn) {
                $msisdn = self::makePhoneNumberStandard($msisdn);

                if (preg_match("/^84[0-9]{9,11}$/i", $msisdn) == TRUE) {
                    //lấy 3 số sau 84
                    $pre_code = preg_replace('/^84(\d\d\d).*/', '$1', $msisdn);

                    //ktra chính xác sđt đầu 08,09 = 10 số
                    if ((substr($pre_code, 0, 1) == 8 || substr($pre_code, 0, 1) == 9) && strlen($msisdn) >= 10) {
                        $pre_code = substr($pre_code, 0, 2);
                    }

                    //check đầu số MobiFone
                    $MobiFone = array(
                        '90',   //1
                        '93',   //2
                        '120',  //3
                        '121',  //4
                        '122',  //5
                        '126',  //6
                        '128',  //7
                        '89',   //8
                    );
                    //ktra 3 số và 2 số đầu có thuộc $MobiFone ko
                    if (in_array($pre_code, $MobiFone))
                        return TRUE;
                }
            }

            return FALSE;
        }

    }

?>