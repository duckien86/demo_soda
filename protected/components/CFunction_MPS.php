<?php

    /**
     * @Function: Các hàm chức năng sử dụng trong hệ thống Payment Centech
     * @Author  : thanh.tk@centech.com.vn
     * @Date    : 8/18/14
     * @Time    : 2:09 PM
     */
    class CFunction_MPS
    {
        /*
         * @params: $phoneNumber Số điện thoại chưa đúng dạng chuẩn (0xxx)
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


        /*
         * $params: $lengthChars độ dài của chuỗi ký tự cần tạo
         * $description: Key AES bao gồm 32 ký tự bất ký
         * @return: Tạo random key AES. Hàm này sẽ trả về 32 ký tự bất kỳ
         */

        public static function setKeyAES($lengthChars = 32)
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

        /*
         * $arrayParams: Mảng truyền vào
         * @return: Hàm này trả về 1 chỗ các params đã được implode bằng dấu &
         */
        public static function implodeParams($arrayParams)
        {
            $dataCover = '';
            $i         = 0;
            foreach ($arrayParams as $key => $value) {
                if ($i == 0) {
                    $dataCover .= $key . '=' . $value;
                } else {
                    $dataCover .= '&' . $key . '=' . $value;
                }
                $i++;
            }

            return $dataCover;
        }

        /*
         * @params: $fileName: Tên file XML cần load
         * @return: Hàm này load nội dung của file XML trong SRMS
         */
        /**
         * Load XML Content
         *
         * @param $filePath
         * @param $fileName
         *
         * @return bool|SimpleXMLElement
         */
        public static function loadXMLContent($filePath, $fileName)
        {
            if (!$fileName) {
                return FALSE;
            } else {
                $xmlFile = $filePath . $fileName . '.xml';
                if (file_exists($xmlFile)) {
                    $xml = simplexml_load_file($xmlFile);
                } else {
                    echo 'XML file in srms system does not exists.';
                    exit();
                }
                if (!$xml) {
                    echo 'Could not load content in xml file.';
                    exit();
                }

                return $xml;
            }
        }

        /*
         * @params: $fileName: Tên file XML cần load
         * @return: Hàm này load nội dung của file XML trong SRMS
         */
        public static function loadXMLContentService($id, $serviceId)
        {
            if (!$id || !$serviceId) {
                return FALSE;
            } else {
                $xmlFile = CFunction::getParams('xml_path_srms_service') . $id . '_' . $serviceId . '.xml';
                if (file_exists($xmlFile)) {
                    $getDataXML = @file_get_contents($xmlFile);
                    if (!$getDataXML) {
                        echo 'Could not read content in xml file. Check server security before use function file_get_content.';
                        exit();
                    }
                    $xml = @simplexml_load_string($getDataXML);
                    if (!$xml) {
                        echo 'Could not load content in xml file.';
                        exit();
                    }
                } else {
                    echo 'XML file in srms system does not exists.';
                    exit();
                }

                return $xml;
            }
        }

        /*
         * @function: Hàm này load nội dung từ XML
         */
        public static function simpleXMLLoadString($dataXML)
        {
            if (!$dataXML) {
                return FALSE;
            } else {
                $xml = @simplexml_load_string($dataXML);
                if (!$xml) {
                    echo 'Could not load content in xml file.';
                    exit();
                }

                return $xml;
            }
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
         * Generate random string|number
         *
         * @param int       $length    : $length = 0 is random length
         * @param bool|TRUE $is_number : whether is number or mix text.
         *                             -Number format : yyyymmddhhiissxxx
         */
        public static function genRandKey($is_number = TRUE, $length = 15)
        {
            $randStr = '';
            if ($is_number) {
                $timeREQ = date('YmdHis', time());
                $endREQ  = rand(1000, 9999);
                $randStr = $timeREQ . $endREQ;
            } else {
//                $randStr = CApplication::getSecurityManager()->generateRandomString($length);
                $randStr = substr(md5(rand()), 0, $length);
            }

            return $randStr;
        }

        /*
         * @return: Trả về địa chỉ IP của User khi redirect tới hệ thống Payment
         */
        public static function getUserIP()
        {
            if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') > 0) {
                    $addressIP = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

                    return trim($addressIP[0]);
                } else {
                    return $_SERVER['HTTP_X_FORWARDED_FOR'];
                }
            } else {
                return $_SERVER['REMOTE_ADDR'];
            }
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


        /*
         * $params: $text Nội dung cần filter
         * @function: Hàm này trả sử dụng để filter url khi người dùng nhập tiếng việt có dấu
         */
        public static function converUrlTitle($text)
        {
            $chars   = array("a", "A", "e", "E", "o", "O", "u", "U", "i", "I", "d", "D", "y", "Y");
            $uni[0]  = array("á", "à", "ạ", "ả", "ã", "â", "ấ", "ầ", "ậ", "ẩ", "ẫ", "ă", "ắ", "ằ", "ặ", "ẳ");
            $uni[1]  = array("Á", "À", "Ạ", "Ả", "Ã", "Â", "Ấ", "Ầ", "Ậ", "Ẩ", "Ẫ", "Ă", "Ắ", "Ằ", "Ặ", "Ẳ");
            $uni[2]  = array("é", "è", "ẹ", "ẻ", "ẽ", "ê", "ế", "ề", "ệ", "ể", "ễ");
            $uni[3]  = array("É", "È", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ế", "Ề", "Ệ", "Ể", "Ễ");
            $uni[4]  = array("ó", "ò", "ọ", "ỏ", "õ", "ô", "ố", "ồ", "ộ", "ổ", "ỗ", "ơ", "ớ", "ờ", "ợ", "ở");
            $uni[5]  = array("Ó", "Ò", "Ọ", "Ỏ", "Õ", "Ô", "Ố", "Ồ", "Ộ", "Ổ", "Ỗ", "Ơ", "Ớ", "Ờ", "Ợ", "Ở");
            $uni[6]  = array("ú", "ù", "ụ", "ủ", "ũ", "ư", "ứ", "ừ", "ự", "ử", "ữ");
            $uni[7]  = array("Ú", "Ù", "Ụ", "Ủ", "Ũ", "Ư", "Ứ", "Ừ", "Ự", "Ử", "Ữ");
            $uni[8]  = array("í", "ì", "ị", "ỉ", "ĩ");
            $uni[9]  = array("Í", "Ì", "Ị", "Ỉ", "Ĩ");
            $uni[10] = array("đ");
            $uni[11] = array("Đ");
            $uni[12] = array("ý", "ỳ", "ỵ", "ỷ", "ỹ");
            $uni[13] = array("Ý", "Ỳ", "Ỵ", "Ỷ", "Ỹ");
            for ($i = 0; $i <= 13; $i++) {
                $text = str_replace($uni[$i], $chars[$i], $text);
            }

            return stripslashes($text);
        }

        /*
         * @params: $url Đường dẫn của cần lấy nội dung
         * @function: Hàm này lấy nội dung trả về của 1 link nằm trên server khác
         */
        public static function getContentUrl($url, &$http_code, $timeout = 10)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $data      = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $data;
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

        /*
         * @params: $url Đường dẫn của cần lấy nội dung
         * @function: Hàm này lấy nội dung trả về của 1 link nằm trên server khác
         */
        public static function getHeaderCodeUrl($url, $timeout = 10)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $data      = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $http_code;
        }

        public static function encrypt($encrypt, $key)
        {
            $iv        = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND);
            $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, pack("H*", $key), $encrypt, MCRYPT_MODE_ECB, $iv));

            return $encrypted;
        }

        public static function decrypt($decrypt, $key)
        {
            $iv        = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND);
            $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, pack("H*", $key), base64_decode($decrypt), MCRYPT_MODE_ECB, $iv);

            return $decrypted;
        }

        public static function pkcs5_pad($text, $blocksize)
        {
            $pad = $blocksize - (strlen($text) % $blocksize);

            return $text . str_repeat(chr($pad), $pad);
        }
        /*
         * kiểm tra số cặp đôi
         * */
        public static function isPhoneCouple($msisdn){
            $array_couple = ['8481', '8482','8483', '8485'];
            if($msisdn &&  in_array(substr($msisdn, 0, 4), $array_couple) ){
                return true;
            }
            return false;
        }
        /*
         * convert số cặp đôi
         * */
        public static function convertPhoneCouple($msisdn){
           return '8491' .substr($msisdn,4);
        }
    }