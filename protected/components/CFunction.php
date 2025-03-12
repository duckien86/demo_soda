<?php

    class CFunction
    {
        public static function generateFileNameByDate()
        {
            $today = date("U") + (7 * 3600); //GMT+7

            return date("Ymd");
        }

        public static function encrypt($value, $hashKey)
        {
            return md5($hashKey . $value);
        }

        public static function genRandIdGame($game_id)
        {
            $numberId = Yii::app()->params->number_id;
            $countstr = strlen($game_id);
            $add      = $numberId - $countstr;
            $i        = 1;
            for ($i = 1; $i <= $add; $i++) {
                $game_id = '0' . $game_id;
            }

            return $game_id;
        }

        public static function GUID()
        {
            return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
        }

        public static function getRandIdCat($cat_id)
        {
            $numberId = Yii::app()->params->number_catid;
            $countstr = strlen($cat_id);
            $add      = $numberId - $countstr;
            $i        = 1;
            for ($i = 1; $i <= $add; $i++) {
                $cat_id = '0' . $cat_id;
            }

            return $cat_id;
        }


        public static function makePhoneNumberStandard($phonenumber)
        {
            $newnumber = $phonenumber;
            if ($phonenumber != '') {
                if (substr($phonenumber, 0, 1) == '0') {
                    $newnumber = substr($phonenumber, 1, strlen($phonenumber));
                } else
                    if (substr($phonenumber, 0, 2) == '84') {
                        $newnumber = substr($phonenumber, 2, strlen($phonenumber));
                    }
                $newnumber = "84" . $newnumber;
            }

            return $newnumber;
        }

        public static function makePhoneNumberBasic($phonenumber, $zero = '0')
        {
            $newnumber = $phonenumber;
            if ($phonenumber != '') {
                if (substr($phonenumber, 0, 1) == '0') {
                    $newnumber = substr($phonenumber, 1, strlen($phonenumber));
                } else if (substr($phonenumber, 0, 2) == '84') {
                    $newnumber = substr($phonenumber, 2, strlen($phonenumber));
                }
                $newnumber = $zero . $newnumber;
            }

            return $newnumber;
        }

        public static function random_generator($digits)
        {
            srand((double)microtime() * 10000000);
            $input = array(
                'a',
                'b',
                'c',
                'd',
                'e',
                'f',
                'g',
                'h',
                'i',
                'j',
                'k',
                'l',
                'm',
                'n',
                'o',
                'p',
                'q',
                'r',
                's',
                't',
                'u',
                'v',
                'w',
                'x',
                'y',
                'z');
            $temp  = "";
            for ($i = 1; $i < $digits + 1; $i++) {
                if (rand(1, 2) == 1) {
                    $rand_index = array_rand($input);
                    $temp       .= $input[$rand_index];
                } else {
                    $temp .= rand(0, 9);
                }

            }

            return $temp;
        }

        public static function sendMail($email, $subject, $message)
        {
            $adminEmail = Yii::app()->params['adminEmail'];
            $headers    = "MIME-Version: 1.0\r\nFrom: $adminEmail\r\nReply-To: $adminEmail\r\nContent-Type: text/html; charset=utf-8";
            $message    = wordwrap($message, 70);
            $message    = str_replace("\n.", "\n..", $message);

            return mail($email, '=?UTF-8?B?' . base64_encode($subject) . '?=', $message, $headers);
        }

        public function SendMailReplly($subject, $body, $mail_cc, $mail_to)
        {
            if ($mail_to != '') {
                $mail_to = $mail_to . ";";
            }
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
//        $headers .= 'From: gqkn@centech.com.vn' . "\r\n" ;
//        'Reply-To: gqkn@centech.com.vn' . "\r\n" .
            $headers .= 'From: admin@viettelads.vn' . "\r\n";
            'Reply-To: admin@viettelads.vn' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

            if ($mail_cc != '') {
                $headers .= 'Cc: ' . $mail_cc . "\r\n";
            }
            $rsmail = mail($mail_to, $subject, $body, $headers);

            return $rsmail;
        }

        public static function gennerateOrderId()
        {
            return sprintf('%04x%04x%04x%04x%04x%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
        }

        public static function highlight_keywords($text, $keyword)
        {
            $color     = Yii::app()->params->color;
            $tag_start = "<span style='background-color:" . $color . "'>";
            $tag_end   = "</span>";
            if ($text != '' && $keyword != '') {
                $original = $text;
                $text     = CFunction::vn_str_filter(strtolower($text));
                $tagLen   = (strlen($tag_start) + strlen($tag_end));
                $keyword  = CFunction::vn_str_filter(strtolower($keyword));
                $current  = $offset = $delta = 0;
                $len      = mb_strlen($keyword, "utf-8");
                $total    = mb_strlen($text, "utf-8");
                while ((FALSE !== ($pos = strpos($text, $keyword, $offset)))) {
                    $original = mb_substr($original, 0, ($pos + $delta), "utf-8") . $tag_start .
                        mb_substr($original, ($pos + $delta), $len, "utf-8") . $tag_end . mb_substr($original,
                            ($pos + $delta + $len), $total, "utf-8");
                    $delta    += $tagLen;
                    $offset   = $pos + 1;

                }

                return $original;
            } else {
                return $text;
            }

        }

        public static function vn_str_filter($str)
        {
            $unicode = array(
                'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
                'd' => 'đ',
                'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
                'i' => 'í|ì|ỉ|ĩ|ị',
                'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
                'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
                'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
                'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
                'D' => 'Đ',
                'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
                'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
                'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
                'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
                'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
            );

            foreach ($unicode as $nonUnicode => $uni) {
                $str = preg_replace("/($uni)/i", $nonUnicode, $str);
            }

            return $str;
        }

        public static function getPriceGame()
        {
            $arrPrice = Yii::app()->params->price_list;
            $arrPrice = explode(",", $arrPrice);
            $arrData  = array();
            if (is_array($arrPrice)) {
                foreach ($arrPrice as $key => $value) {
                    $arrData[$value] = $value;
                }

                return $arrData;
            } else {
                return FALSE;
            }
        }

        public static function valid_sso_header($msisdn, $sessionid)
        {
            $url     = Yii::app()->params['mobifone_link_verify'];
            $url     .= '?sessionid=' . $sessionid . '&msisdn=' . $msisdn;
            $content = file_get_contents($url);
            if (trim($content) === '0:OK') {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        public static function getTypeGame()
        {
            $arrPrice = Yii::app()->params->game_type;
            $arrPrice = explode(",", $arrPrice);
            $arrData  = array();
            if (is_array($arrPrice)) {
                foreach ($arrPrice as $key => $value) {
                    $arrData[$value] = $value;
                }

                return $arrData;
            } else {
                return FALSE;
            }
        }

        public static function output_file($file, $name, $mime_type = '')
        {
            if (!is_readable($file))
                die('File not found or inaccessible!');
            $size             = filesize($file);
            $name             = rawurlencode($name);
            $known_mime_types = array(
                "application/rar",
                "application/x-rar-compressed",
                "application/arj",
                "application/excel",
                "application/gnutar",
                "application/octet-stream",
                "application/pdf",
                "application/powerpoint",
                "application/postscript",
                "application/plain",
                "application/rtf",
                "application/vocaltec-media-file",
                "application/wordperfect",
                "application/x-zip",
                "application/x-bzip",
                "application/x-bzip2",
                "application/x-compressed",
                "application/x-excel",
                "application/x-gzip",
                "application/x-latex",
                "application/x-midi",
                "application/x-msexcel",
                "application/x-rtf",
                "application/x-sit",
                "application/x-stuffit",
                "application/x-shockwave-flash",
                "application/x-troff-msvideo",
                "application/x-zip-compressed",
                "application/xml",
                "application/zip",
                "application/msword",
                "application/mspowerpoint",
                "application/vnd.ms-excel",
                "application/vnd.ms-powerpoint",
                "application/vnd.ms-word",
                "application/vnd.ms-word.document.macroEnabled.12",
                "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                "application/vnd.ms-word.template.macroEnabled.12",
                "application/vnd.openxmlformats-officedocument.wordprocessingml.template",
                "application/vnd.ms-powerpoint.template.macroEnabled.12",
                "application/vnd.openxmlformats-officedocument.presentationml.template",
                "application/vnd.ms-powerpoint.addin.macroEnabled.12",
                "application/vnd.ms-powerpoint.slideshow.macroEnabled.12",
                "application/vnd.openxmlformats-officedocument.presentationml.slideshow",
                "application/vnd.ms-powerpoint.presentation.macroEnabled.12",
                "application/vnd.openxmlformats-officedocument.presentationml.presentation",
                "application/vnd.ms-excel.addin.macroEnabled.12",
                "application/vnd.ms-excel.sheet.binary.macroEnabled.12",
                "application/vnd.ms-excel.sheet.macroEnabled.12",
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                "application/vnd.ms-excel.template.macroEnabled.12",
                "application/vnd.openxmlformats-officedocument.spreadsheetml.template",
                "text/vnd.sun.j2me.app-descriptor",
                "application/java-archive",
                "audio/*",
                "image/*",
                "video/*",
                "multipart/x-zip",
                "multipart/x-gzip",
                "text/richtext",
                "text/plain",
                "text/xml");
            if (!in_array($mime_type, $known_mime_types)) {
                $mime_type = 'application/force-download';
            }
            @ob_end_clean();
            if (ini_get('zlib.output_compression'))
                ini_set('zlib.output_compression', 'Off');
            header('Content-Type: ' . $mime_type);
            header('Content-Disposition: attachment; filename="' . $name . '"');
            header("Content-Transfer-Encoding: binary");
            header('Accept-Ranges: bytes');
            /* The three lines below basically make the
            download non-cacheable */
            header("Cache-control: private");
            header('Pragma: private');
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            // multipart-download and download resuming support
            if (isset($_SERVER['HTTP_RANGE'])) {
                list($a, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
                list($range) = explode(",", $range, 2);
                list($range, $range_end) = explode("-", $range);
                $range = intval($range);
                if (!$range_end) {
                    $range_end = $size - 1;
                } else {
                    $range_end = intval($range_end);
                }

                $new_length = $range_end - $range + 1;
                header("HTTP/1.1 206 Partial Content");
                header("Content-Length: $new_length");
                header("Content-Range: bytes $range-$range_end/$size");
            } else {
                $new_length = $size;
                header("Content-Length: " . $size);
            }
            /* output the file itself */
            $chunksize  = 1 * (1024 * 1024); //you may want to change this
            $bytes_send = 0;
            if ($file = fopen($file, 'r')) {
                if (isset($_SERVER['HTTP_RANGE']))
                    fseek($file, $range);

                while (!feof($file) && (!connection_aborted()) && ($bytes_send < $new_length)) {
                    $buffer = fread($file, $chunksize);
                    print ($buffer); //echo($buffer); // is also possible
                    flush();
                    if (connection_status() != 0) {
                    } else {
                        $statusDownloadFile = '1';
                        //$this->updateDownloadFileStatus2DB($statusDownloadFile);
                    }
                    $bytes_send += strlen($buffer);
                }
                fclose($file);
            } else
                die('Error - can not open file.');
        }

        public static function games_lead_zero($s, $maxlength)
        {

            if (strlen($s) >= $maxlength) {
                return $s;
            } else {
                return self::games_lead_zero("0" . $s, $maxlength);
            }
        }

        public static function getUrlReport()
        {
            $clearPass = (isset($_SESSION['clearPass'])) ? self::tripleDesEncrypt(Yii::app()->params['trip_des_key'], $_SESSION['clearPass']) : "";
            if (isset($_SESSION['cp_code']) && $_SESSION['cp_code'] != "") $url = 'http://mgame.vn:8072/new_report/src/index.php?r=site/loginAPI&username=' . $_SESSION['username'] . '&password=' . urlencode($clearPass);
            else $url = 'http://mgame.vn:8072/';

            return $url;
        }

        public static function number_format($string, $decimals = "", $dec_sep = ",", $thous_sep = ".")
        {
            $ret = '0';
            if ($string != '')
                $ret = number_format($string, $decimals, $dec_sep, $thous_sep);

            return $ret;
        }

        public static function aes_encrypt($key, $str)
        {
            $aes = new AES($key);

            return $aes->encrypt($str);
        }

        public static function aes_encrypt_2_base64($key, $str)
        {
            $aes = new AES($key);

            return base64_encode($aes->encrypt($str));
        }

        public static function tripleDesEncrypt($key, $value)
        {
            $key   = base64_decode($key);
            $iv    = substr($key, -8);
            $key   = substr($key, 0, 24);
            $value = mcrypt_encrypt(MCRYPT_3DES, $key, $value, MCRYPT_MODE_CBC, $iv);

            return bin2hex($value);
        }

        public static function tripleDesDecrypt($key, $value)
        {
            $key   = base64_decode($key);
            $iv    = substr($key, -8);
            $key   = substr($key, 0, 24);
            $value = pack("H*", $value);
            $value = mcrypt_decrypt(MCRYPT_3DES, $key, ($value), MCRYPT_MODE_CBC, $iv);

            return trim($value);
        }

        public static function getSignature($value, $pri_key_cp)
        {
            $signature = '';
            openssl_sign($value, $signature, $pri_key_cp, OPENSSL_ALGO_SHA1);
            // Base64 Encode
            $signature = base64_encode($signature);
            // URL Encode
            $signature = urlencode($signature);

            return $signature;
        }

        public static function verifySignature($value, $signature, $public_key)
        {
            //$signature = urldecode($signature);
            $signature = base64_decode($signature);
            $res       = openssl_verify($value, $signature, $public_key, OPENSSL_ALGO_SHA1);
            if ($res == 1) {
                return TRUE;
            } else {
                return FALSE;
            }
        }


        /**
         * @param $moseq
         *
         * @return array
         */


        public static function getMsisdnViettelByMoseq($moseq)
        { //get msisdn viettel by moseq(mo sequence)
            /*log params send------------------------------------------------*/
            $time_stamp = time();
            $time       = date('d/m/Y H:i:s', $time_stamp);
            $logMsg[]   = array('---------------------#START---------------------------------------------------------', 'Log Viettel get MSISDN by moseq', 'I');
            $logMsg[]   = array('Time: ' . $time, 'Log Viettel get MSISDN by moseq: ' . $time . '', 'I');
            $logFolder  = "VIETTEL_GET_MSISDN_BY_MOSEQ/" . date("Y") . "/" . date("m");
            $logRequest = new SystemLog($logFolder);
            $logRequest->setLogFile('log_viettel_get_msisdn_by_moseq_' . date("Ymd") . '.log');
            $logMsg[] = array('--------------------------', 'I');
            $logMsg[] = array('moseq : ' . $moseq, 'I');

            //get config
            $viettel_rsa = Yii::app()->params['VIETTEL_RSA'];
            $viettel_mo  = Yii::app()->params['VIETTEL_MO'];


            $cpCode      = $viettel_rsa['cpCode'];
            $private_key = $viettel_rsa['private_key_2'];
            $deskey      = $viettel_rsa['deskey'];
            $url_call    = $viettel_mo['url'];
            $reqtype     = 'getmsisdn';
            $type        = 'mo';
            $str         = 'sequence=' . $moseq . '&type=' . $type . '';

            //echo $str.'<br>';
            $value = self::tripleDesEncrypt($deskey, $str);
            //echo $value.'<br>';
            $signature = CFunction::getSignature($value, $private_key);
            //echo $signature.'<br>';
            $logMsg[] = array('reqtype : ' . $reqtype, 'Params', 'I');
            $logMsg[] = array('cpCode : ' . $cpCode, 'Params', 'I');
            $logMsg[] = array('value : ' . $value, 'Params', 'I');
            $logMsg[] = array('str : ' . $str, 'Params', 'I');
            $logMsg[] = array('signature : ' . $signature, 'Params', 'I');

            $url_call .= 'reqtype=' . $reqtype . '&cpCode=' . $cpCode . '&value=' . $value . '&signature=' . $signature . '';

            $logMsg[] = array('Url call : ' . $url_call, 'Receiver', 'I');

            $server_output = CFunction::callCURL($url_call);

            if ($server_output['header'] == 200) {
                $logMsg[] = array('SUCCESS, get viettel msisdn by moseq , result: ' . $server_output['header'] . ' ', 'I');
                $logMsg[] = array('data response: ' . CJSON::encode($server_output) . '', 'I');
                //decrypt
                $viettel_rsa = Yii::app()->params['VIETTEL_RSA'];
                $private_key = $viettel_rsa['private_key_2'];

                $data_decrypted = $server_output['data'];

                $result = CFunction::privateKeyDecrypt($data_decrypted, $private_key);

                $logMsg[] = array('data_decrypted: ' . $result . '', 'I');

                parse_str($result, $array_str);

                $msisdn = $array_str['msisdn'];
                $msisdn = CFunction::makePhoneNumberStandard($msisdn);

                $logMsg[] = array('msisdn: ' . $msisdn . '', 'I');
                $logMsg[] = array('------------------#END-------------------------------------------', 'Log Viettel get MSISDN by moseq', 'I');
                @$logRequest->processWriteLogs($logMsg);

                return array(
                    'status' => TRUE,
                    'msisdn' => $msisdn,
                );
            } else {

                $logMsg[] = array('FAILED, get viettel msisdn by moseq, result: ' . $server_output['header'] . ' ', 'I');
                $logMsg[] = array('data response: ' . CJSON::encode($server_output) . '', 'I');
                $logMsg[] = array('------------------#END-------------------------------------------', 'Log Viettel get MSISDN by moseq', 'I');
                @$logRequest->processWriteLogs($logMsg);

                return array(
                    'status' => FALSE,
                    'msisdn' => '',
                );
            }
        }


        /**
         * @param $password
         * @param $min
         *
         * @return bool
         */
        public static function checkPasswordStrength($password, $min)
        { //var_dump($repassword); exit();
            if (preg_match("/^.*(?=.{" . $min . ",})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).*$/", $password)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        public static function getWeekOfRangeday($day)
        {
            $start_week = strtotime("last monday midnight", strtotime($day));
            $end_week   = strtotime("+1 week", $start_week);

            $start_week = date("Y-m-d", $start_week);
            $end_week   = date("Y-m-d", $end_week);

            return array(
                'start_week' => $start_week,
                'end_week'   => $end_week
            );
        }

        public static function getURLContent($url, $time_out = 10)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
            curl_setopt($ch, CURLOPT_NOBODY, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_TIMEOUT, $time_out);
            $output = curl_exec($ch);
            $ret    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $ret;
        }

        public static function callCURL($url, $time_out = 10)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_TIMEOUT, $time_out);
            $data   = curl_exec($ch);
            $header = curl_getinfo($ch, CURLINFO_HTTP_CODE);;
            curl_close($ch);

            return array(
                'header' => $header,
                'data'   => $data
            );
        }

        public static function getPagerInfo($count, $cpage, $rowsperpage = FALSE)
        {
            $pInfo               = array();
            $pInfo['cpage']      = $cpage;
            $pInfo['count']      = $count;
            $pInfo['limit']      = ($rowsperpage === FALSE) ? 9 : $rowsperpage;
            $pInfo['totalpages'] = 0;
            $pInfo['start']      = 0;
            if ($pInfo['count'] > 0) {
                $pInfo['totalpages'] = ceil($pInfo['count'] / $pInfo['limit']);
            }
            if ($pInfo['cpage'] > $pInfo['totalpages']) $pInfo['cpage'] = $pInfo['totalpages'];
            $pInfo['start'] = $pInfo['limit'] * $pInfo['cpage'] - $pInfo['limit'];
            if ($pInfo['start'] < 0) $pInfo['start'] = 0;

            return $pInfo;
        }

        public static function checkIPExist($clientIP, $array_ip)
        {
            if (self::isIPv6($clientIP)) { //ip v6
                $result = self::checkIPV6Exist($clientIP, $array_ip);
            } else { //ip v4
                $result = self::checkIPV4Exist($clientIP, $array_ip);
            }

            return $result;
        }


        public static function checkIPV6Exist($clientIP, $array_ip)
        {
            $result     = FALSE;
            $ipNumberic = self::ipv6_to_numeric($clientIP);

            if (sizeof($array_ip > 0)) {
                foreach ($array_ip as $key => $value) {
                    if (self::isIPv6($key)) {
                        $startRange = self::ipv6_to_numeric($key);
                        $endRange   = self::ipv6_to_numeric($value);
                        if ($ipNumberic >= $startRange && $ipNumberic <= $endRange) {
                            $result = TRUE;
                            break;
                        }
                    }
                }
            }

            return $result;
        }

        public static function web_pager($count, $cpage, $limit = 3, $current_uri, $imgPath = NULL)
        {
            $pagerinf             = array();
            $pagerinf['next']     = "";
            $pagerinf['previous'] = "";
            $pagerinf['strPager'] = "";
            if (!isset($cpage) || !is_numeric($cpage)) $cpage = 1;
            $numofpages = ceil($count / $limit);
            $ext_char   = (strpos($current_uri, "?")) ? '&' : '?';

            if ($cpage > $numofpages) $cpage = $numofpages;
            if ($numofpages > 1) {
                if (($cpage > 1) & ($cpage < $numofpages)) {
                    $pagerinf['previous'] = $current_uri . "" . $ext_char . "p=" . ($cpage - 1);
                    $pagerinf['next']     = $current_uri . "" . $ext_char . "p=" . ($cpage + 1);
                } elseif ($cpage == 1) {
                    $pagerinf['next'] = $current_uri . "" . $ext_char . "p=" . ($cpage + 1);
                } elseif ($cpage == $numofpages) {
                    $pagerinf['previous'] = $current_uri . "" . $ext_char . "p=" . ($cpage - 1);
                }
                $pagerinf['numofpages'] = $numofpages;
                $pagerinf['curpage']    = $cpage;
                if ($cpage > 1 && $cpage <= $numofpages) {
                    $pagerinf['strPager'] .= '<span><a href="' . $pagerinf['previous'] . '"> Trước </a></span>&nbsp;';
                    $pagerinf['strPager'] .= '<span><a href="' . $current_uri . "" . $ext_char . "p=" . ($cpage - 1) . '">' . ($cpage - 1) . '</a></span>&nbsp;';
                }
                $pagerinf['strPager'] .= '<span><a style="color: #000; font-size: 15px;" href="' . $current_uri . "" . $ext_char . "p=" . ($cpage) . '">' . ($cpage) . '</a></span>&nbsp;';
                if ($cpage < $numofpages) {
                    $pagerinf['strPager'] .= '<span><a href="' . $current_uri . "" . $ext_char . "p=" . ($cpage + 1) . '">' . ($cpage + 1) . '</a></span>&nbsp;';
                }

                if ($cpage < $numofpages && $numofpages > 2) {
                    $pagerinf['strPager'] .= '<span>...</span>';
                    $pagerinf['strPager'] .= '<span><a href="' . $current_uri . "" . $ext_char . "p=" . ($numofpages) . '">' . ($numofpages) . '</a></span>&nbsp;';
                    $pagerinf['strPager'] .= '<span><a href="' . $pagerinf['next'] . '"> Tiếp </a></span>';
                }

                return $pagerinf;
            }

            return FALSE;
        }

        public static function truncate($string, $length = 80, $etc = '...', $break_words = FALSE, $middle = FALSE)
        {
            if ($length == 0)
                return '';

            if (strlen($string) > $length) {
                $length -= min($length, strlen($etc));
                if (!$break_words && !$middle) {
                    $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length + 1));
                }
                if (!$middle) {
                    return substr($string, 0, $length) . $etc;
                } else {
                    return substr($string, 0, $length / 2) . $etc . substr($string, -$length / 2);
                }
            } else {
                return $string;
            }
        }

        public static function convertSpace($string)
        {
            return self::unsign_string($string);

            $a      = array('Ấ', "ễ", "Á", "À", "Ả", "Ã", "Ạ", "Ó", "Ò", "Ỏ", "Õ", "Ọ", "Ă", "Ắ", "Ằ", "Ẳ", "Ẵ", "Ặ", "Ô", "Ố", "Ồ", "Ổ", "Ỗ", "Ộ", "Â", "Ã", "Á", "À", "Ả", "Ẫ", "Ậ", "Ơ", "Ớ", "Ờ", "Ở", "Ỡ", "Ợ", "É", "È", "Ẻ", "Ẽ", "Ẹ", "Ú", "Ù", "Ủ", "Ũ", "Ụ", "Ê", "Ễ", "Ề", "Ể", "Ệ", "Ư", "Ứ", "Ừ", "Ử", "Ữ", "Ự", "Í", "Ì", "Ỉ", "Ĩ", "Ị", "Ý", "Ỳ", "Ỷ", "Ỹ", "Ỵ", "Đ", "á", "à", "ả", "ã", "ạ", "ó", "ò", "ỏ", "õ", "ọ", "ă", "ắ", "ằ", "ẳ", "ẵ", "ặ", "ô", "ố", "ồ", "ổ", "ỗ", "ộ", "â", "ã", "ấ", "ầ", "ẩ", "ẫ", "ậ", "ơ", "ớ", "ờ", "ở", "ỡ", "ợ", "é", "è", "ẻ", "ê", "ế", "ề", "ệ", "ẽ", "ẹ", "ú", "ù", "ủ", "ũ", "ụ", "ê", "ẽ", "ề", "ể", "ệ", "ư", "ứ", "ừ", "ử", "ữ", "ự", "í", "ì", "ỉ", "ĩ", "ị", "ý", "ỳ", "ỷ", "ỹ", "ỵ", "đ", "!", "@", "?", ".", ":", "à");
            $b      = array('ấ', "e", "a", "a", "a", "a", "a", "o", "o", "o", "o", "o", "a", "a", "a", "a", "a", "a", "o", "o", "o", "o", "o", "o", "a", "a", "a", "a", "a", "a", "a", "o", "o", "o", "o", "o", "o", "e", "e", "e", "e", "e", "u", "u", "u", "u", "u", "e", "e", "e", "e", "e", "u", "u", "u", "u", "u", "u", "i", "i", "i", "i", "i", "y", "y", "y", "y", "y", "d", "a", "a", "a", "a", "a", "o", "o", "o", "o", "o", "a", "a", "a", "a", "a", "a", "o", "o", "o", "o", "o", "o", "a", "a", "a", "a", "a", "a", "a", "o", "o", "o", "o", "o", "o", "e", "e", "e", "e", "e", "e", "e", "e", "e", "u", "u", "u", "u", "u", "e", "e", "e", "e", "e", "u", "u", "u", "u", "u", "u", "i", "i", "i", "i", "i", "y", "y", "y", "y", "y", "d", "", "", "", "", "", "", "a");
            $string = strtolower(str_replace($a, $b, $string));
            $string = str_replace(" ", "-", $string);
            $string = str_replace("--", "", $string);


            return $string;
        }

        public static function unsign_string($str, $separator = '-', $keep_special_chars = FALSE)
        {
            $str = str_replace(array("à", "á", "ạ", "ả", "ã", "ă", "ằ", "ắ", "ặ", "ẳ", "ẵ", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ"), "a", $str);
            $str = str_replace(array("À", "Á", "Ạ", "Ả", "Ã", "Ă", "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ"), "A", $str);
            $str = str_replace(array("è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề", "ế", "ệ", "ể", "ễ"), "e", $str);
            $str = str_replace(array("È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ"), "E", $str);
            $str = str_replace("đ", "d", $str);
            $str = str_replace("Đ", "D", $str);
            $str = str_replace(array("ỳ", "ý", "ỵ", "ỷ", "ỹ", "ỹ"), "y", $str);
            $str = str_replace(array("Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ"), "Y", $str);
            $str = str_replace(array("ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ"), "u", $str);
            $str = str_replace(array("Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ"), "U", $str);
            $str = str_replace(array("ì", "í", "ị", "ỉ", "ĩ"), "i", $str);
            $str = str_replace(array("Ì", "Í", "Ị", "Ỉ", "Ĩ"), "I", $str);
            $str = str_replace(array("ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ", "ờ", "ớ", "ợ", "ở", "ỡ"), "o", $str);
            $str = str_replace(array("Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ", "Ờ", "Ớ", "Ợ", "Ở", "Ỡ"), "O", $str);
            if ($keep_special_chars == FALSE) {
                $str = str_replace(array('–', '…', '“', '”', "~", "!", "@", "#", "$", "%", "^", "&", "*", "/", "\\", "?", "<", ">", "'", "\"", ":", ";", "{", "}", "[", "]", "|", "(", ")", ",", ".", "`", "+", "=", "-"), $separator, $str);
                $str = preg_replace("/[^_A-Za-z0-9- ]/i", '', $str);
            }

            $str = str_replace(' ', $separator, $str);

            return trim(strtolower($str), "-");
        }


        public static function setglobal($key, $value, $group = NULL)
        {
            global $_G;
            $k = explode('/', $group === NULL ? $key : $group . '/' . $key);
            switch (count($k)) {
                case 1:
                    $_G[$k[0]] = $value;
                    break;
                case 2:
                    $_G[$k[0]][$k[1]] = $value;
                    break;
                case 3:
                    $_G[$k[0]][$k[1]][$k[2]] = $value;
                    break;
                case 4:
                    $_G[$k[0]][$k[1]][$k[2]][$k[3]] = $value;
                    break;
                case 5:
                    $_G[$k[0]][$k[1]][$k[2]][$k[3]][$k[4]] = $value;
                    break;
            }

            return TRUE;
        }

        public static function getglobal($key, $group = NULL)
        {
            global $_G;
            $k = explode('/', $group === NULL ? $key : $group . '/' . $key);
            switch (count($k)) {
                case 1:
                    return isset($_G[$k[0]]) ? $_G[$k[0]] : NULL;
                    break;
                case 2:
                    return isset($_G[$k[0]][$k[1]]) ? $_G[$k[0]][$k[1]] : NULL;
                    break;
                case 3:
                    return isset($_G[$k[0]][$k[1]][$k[2]]) ? $_G[$k[0]][$k[1]][$k[2]] : NULL;
                    break;
                case 4:
                    return isset($_G[$k[0]][$k[1]][$k[2]][$k[3]]) ? $_G[$k[0]][$k[1]][$k[2]][$k[3]] : NULL;
                    break;
                case 5:
                    return isset($_G[$k[0]][$k[1]][$k[2]][$k[3]][$k[4]]) ? $_G[$k[0]][$k[1]][$k[2]][$k[3]][$k[4]] : NULL;
                    break;
            }

            return NULL;
        }


        public static function mobile_device_detect($mobileredirect = FALSE, $desktopredirect = FALSE, $ipad = FALSE, $iphone = TRUE, $android = TRUE, $opera = FALSE, $blackberry = TRUE, $palm = TRUE, $windows = TRUE)
        {
            $mobile_browser = FALSE; // set mobile browser as false till we can prove otherwise
            $user_agent     = $_SERVER['HTTP_USER_AGENT']; // get the user agent value - this should be cleaned to ensure no nefarious input gets executed
            $accept         = $_SERVER['HTTP_ACCEPT']; // get the content accept value - this should be cleaned to ensure no nefarious input gets executed
            switch (TRUE) { // using a switch against the following statements which could return true is more efficient than the previous method of using if statements
                case (@eregi('ipod', $user_agent) || @eregi('iphone', $user_agent)); // we find the words iphone or ipod in the user agent
                    $mobile_browser = $iphone; // mobile browser is either true or false depending on the setting of iphone when calling the function
                    if (substr($iphone, 0, 4) == 'http') { // does the value of iphone resemble a url
                        $mobileredirect = $iphone; // set the mobile redirect url to the url value stored in the iphone value
                    } // ends the if for iphone being a url
                    break; // break out and skip the rest if we've had a match on the iphone or ipod

                case (@eregi('ipad', $user_agent)); // we find the words iphone or ipod in the user agent
                    $mobile_browser = $ipad; // mobile browser is either true or false depending on the setting of iphone when calling the function
                    if (substr($ipad, 0, 4) == 'http') { // does the value of iphone resemble a url
                        $mobileredirect = $ipad; // set the mobile redirect url to the url value stored in the iphone value
                    } // ends the if for iphone being a url
                    break; // break out and skip the rest if we've had a match on the iphone or ipod

                case (@eregi('android', $user_agent)); // we find android in the user agent
                    $mobile_browser = $android; // mobile browser is either true or false depending on the setting of android when calling the function
                    if (substr($android, 0, 4) == 'http') { // does the value of android resemble a url
                        $mobileredirect = $android; // set the mobile redirect url to the url value stored in the android value
                    } // ends the if for android being a url
                    break; // break out and skip the rest if we've had a match on android

                case (@eregi('opera mini', $user_agent)); // we find opera mini in the user agent
                    $mobile_browser = $opera; // mobile browser is either true or false depending on the setting of opera when calling the function
                    if (substr($opera, 0, 4) == 'http') { // does the value of opera resemble a rul
                        $mobileredirect = $opera; // set the mobile redirect url to the url value stored in the opera value
                    } // ends the if for opera being a url
                    break; // break out and skip the rest if we've had a match on opera

                case (@eregi('blackberry', $user_agent)); // we find blackberry in the user agent
                    $mobile_browser = $blackberry; // mobile browser is either true or false depending on the setting of blackberry when calling the function
                    if (substr($blackberry, 0, 4) == 'http') { // does the value of blackberry resemble a rul
                        $mobileredirect = $blackberry; // set the mobile redirect url to the url value stored in the blackberry value
                    } // ends the if for blackberry being a url
                    break; // break out and skip the rest if we've had a match on blackberry

                case (preg_match('/(palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i', $user_agent)); // we find palm os in the user agent - the i at the end makes it case insensitive
                    $mobile_browser = $palm; // mobile browser is either true or false depending on the setting of palm when calling the function
                    if (substr($palm, 0, 4) == 'http') { // does the value of palm resemble a rul
                        $mobileredirect = $palm; // set the mobile redirect url to the url value stored in the palm value
                    } // ends the if for palm being a url
                    break; // break out and skip the rest if we've had a match on palm os

                case (preg_match('/(windows ce; ppc;|windows ce; smartphone;|windows ce; iemobile)/i', $user_agent)); // we find windows mobile in the user agent - the i at the end makes it case insensitive
                    $mobile_browser = $windows; // mobile browser is either true or false depending on the setting of windows when calling the function
                    if (substr($windows, 0, 4) == 'http') { // does the value of windows resemble a rul
                        $mobileredirect = $windows; // set the mobile redirect url to the url value stored in the windows value
                    } // ends the if for windows being a url
                    break; // break out and skip the rest if we've had a match on windows

                case (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|pda|psp|treo)/i', $user_agent)); // check if any of the values listed create a match on the user agent - these are some of the most common terms used in agents to identify them as being mobile devices - the i at the end makes it case insensitive
                    $mobile_browser = TRUE; // set mobile browser to true
                    break; // break out and skip the rest if we've preg_match on the user agent returned true

                case ((strpos($accept, 'text/vnd.wap.wml') > 0) || (strpos($accept, 'application/vnd.wap.xhtml+xml') > 0)); // is the device showing signs of support for text/vnd.wap.wml or application/vnd.wap.xhtml+xml
                    $mobile_browser = TRUE; // set mobile browser to true
                    break; // break out and skip the rest if we've had a match on the content accept headers

                case (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])); // is the device giving us a HTTP_X_WAP_PROFILE or HTTP_PROFILE header - only mobile devices would do this
                    $mobile_browser = TRUE; // set mobile browser to true
                    break; // break out and skip the final step if we've had a return true on the mobile specfic headers

                case (in_array(strtolower(substr($user_agent, 0, 4)), array('1207' => '1207', '3gso' => '3gso', '4thp' => '4thp', '501i' => '501i', '502i' => '502i', '503i' => '503i', '504i' => '504i', '505i' => '505i', '506i' => '506i', '6310' => '6310', '6590' => '6590', '770s' => '770s', '802s' => '802s', 'a wa' => 'a wa', 'acer' => 'acer', 'acs-' => 'acs-', 'airn' => 'airn', 'alav' => 'alav', 'asus' => 'asus', 'attw' => 'attw', 'au-m' => 'au-m', 'aur ' => 'aur ', 'aus ' => 'aus ', 'abac' => 'abac', 'acoo' => 'acoo', 'aiko' => 'aiko', 'alco' => 'alco', 'alca' => 'alca', 'amoi' => 'amoi', 'anex' => 'anex', 'anny' => 'anny', 'anyw' => 'anyw', 'aptu' => 'aptu', 'arch' => 'arch', 'argo' => 'argo', 'bell' => 'bell', 'bird' => 'bird', 'bw-n' => 'bw-n', 'bw-u' => 'bw-u', 'beck' => 'beck', 'benq' => 'benq', 'bilb' => 'bilb', 'blac' => 'blac', 'c55/' => 'c55/', 'cdm-' => 'cdm-', 'chtm' => 'chtm', 'capi' => 'capi', 'comp' => 'comp', 'cond' => 'cond', 'craw' => 'craw', 'dall' => 'dall', 'dbte' => 'dbte', 'dc-s' => 'dc-s', 'dica' => 'dica', 'ds-d' => 'ds-d', 'ds12' => 'ds12', 'dait' => 'dait', 'devi' => 'devi', 'dmob' => 'dmob', 'doco' => 'doco', 'dopo' => 'dopo', 'el49' => 'el49', 'erk0' => 'erk0', 'esl8' => 'esl8', 'ez40' => 'ez40', 'ez60' => 'ez60', 'ez70' => 'ez70', 'ezos' => 'ezos', 'ezze' => 'ezze', 'elai' => 'elai', 'emul' => 'emul', 'eric' => 'eric', 'ezwa' => 'ezwa', 'fake' => 'fake', 'fly-' => 'fly-', 'fly_' => 'fly_', 'g-mo' => 'g-mo', 'g1 u' => 'g1 u', 'g560' => 'g560', 'gf-5' => 'gf-5', 'grun' => 'grun', 'gene' => 'gene', 'go.w' => 'go.w', 'good' => 'good', 'grad' => 'grad', 'hcit' => 'hcit', 'hd-m' => 'hd-m', 'hd-p' => 'hd-p', 'hd-t' => 'hd-t', 'hei-' => 'hei-', 'hp i' => 'hp i', 'hpip' => 'hpip', 'hs-c' => 'hs-c', 'htc ' => 'htc ', 'htc-' => 'htc-', 'htca' => 'htca', 'htcg' => 'htcg', 'htcp' => 'htcp', 'htcs' => 'htcs', 'htct' => 'htct', 'htc_' => 'htc_', 'haie' => 'haie', 'hita' => 'hita', 'huaw' => 'huaw', 'hutc' => 'hutc', 'i-20' => 'i-20', 'i-go' => 'i-go', 'i-ma' => 'i-ma', 'i230' => 'i230', 'iac' => 'iac', 'iac-' => 'iac-', 'iac/' => 'iac/', 'ig01' => 'ig01', 'im1k' => 'im1k', 'inno' => 'inno', 'iris' => 'iris', 'jata' => 'jata', 'java' => 'java', 'kddi' => 'kddi', 'kgt' => 'kgt', 'kgt/' => 'kgt/', 'kpt ' => 'kpt ', 'kwc-' => 'kwc-', 'klon' => 'klon', 'lexi' => 'lexi', 'lg g' => 'lg g', 'lg-a' => 'lg-a', 'lg-b' => 'lg-b', 'lg-c' => 'lg-c', 'lg-d' => 'lg-d', 'lg-f' => 'lg-f', 'lg-g' => 'lg-g', 'lg-k' => 'lg-k', 'lg-l' => 'lg-l', 'lg-m' => 'lg-m', 'lg-o' => 'lg-o', 'lg-p' => 'lg-p', 'lg-s' => 'lg-s', 'lg-t' => 'lg-t', 'lg-u' => 'lg-u', 'lg-w' => 'lg-w', 'lg/k' => 'lg/k', 'lg/l' => 'lg/l', 'lg/u' => 'lg/u', 'lg50' => 'lg50', 'lg54' => 'lg54', 'lge-' => 'lge-', 'lge/' => 'lge/', 'lynx' => 'lynx', 'leno' => 'leno', 'm1-w' => 'm1-w', 'm3ga' => 'm3ga', 'm50/' => 'm50/', 'maui' => 'maui', 'mc01' => 'mc01', 'mc21' => 'mc21', 'mcca' => 'mcca', 'medi' => 'medi', 'meri' => 'meri', 'mio8' => 'mio8', 'mioa' => 'mioa', 'mo01' => 'mo01', 'mo02' => 'mo02', 'mode' => 'mode', 'modo' => 'modo', 'mot ' => 'mot ', 'mot-' => 'mot-', 'mt50' => 'mt50', 'mtp1' => 'mtp1', 'mtv ' => 'mtv ', 'mate' => 'mate', 'maxo' => 'maxo', 'merc' => 'merc', 'mits' => 'mits', 'mobi' => 'mobi', 'motv' => 'motv', 'mozz' => 'mozz', 'n100' => 'n100', 'n101' => 'n101', 'n102' => 'n102', 'n202' => 'n202', 'n203' => 'n203', 'n300' => 'n300', 'n302' => 'n302', 'n500' => 'n500', 'n502' => 'n502', 'n505' => 'n505', 'n700' => 'n700', 'n701' => 'n701', 'n710' => 'n710', 'nec-' => 'nec-', 'nem-' => 'nem-', 'newg' => 'newg', 'neon' => 'neon', 'netf' => 'netf', 'noki' => 'noki', 'nzph' => 'nzph', 'o2 x' => 'o2 x', 'o2-x' => 'o2-x', 'opwv' => 'opwv', 'owg1' => 'owg1', 'opti' => 'opti', 'oran' => 'oran', 'p800' => 'p800', 'pand' => 'pand', 'pg-1' => 'pg-1', 'pg-2' => 'pg-2', 'pg-3' => 'pg-3', 'pg-6' => 'pg-6', 'pg-8' => 'pg-8', 'pg-c' => 'pg-c', 'pg13' => 'pg13', 'phil' => 'phil', 'pn-2' => 'pn-2', 'pt-g' => 'pt-g', 'palm' => 'palm', 'pana' => 'pana', 'pire' => 'pire', 'pock' => 'pock', 'pose' => 'pose', 'psio' => 'psio', 'qa-a' => 'qa-a', 'qc-2' => 'qc-2', 'qc-3' => 'qc-3', 'qc-5' => 'qc-5', 'qc-7' => 'qc-7', 'qc07' => 'qc07', 'qc12' => 'qc12', 'qc21' => 'qc21', 'qc32' => 'qc32', 'qc60' => 'qc60', 'qci-' => 'qci-', 'qwap' => 'qwap', 'qtek' => 'qtek', 'r380' => 'r380', 'r600' => 'r600', 'raks' => 'raks', 'rim9' => 'rim9', 'rove' => 'rove', 's55/' => 's55/', 'sage' => 'sage', 'sams' => 'sams', 'sc01' => 'sc01', 'sch-' => 'sch-', 'scp-' => 'scp-', 'sdk/' => 'sdk/', 'se47' => 'se47', 'sec-' => 'sec-', 'sec0' => 'sec0', 'sec1' => 'sec1', 'semc' => 'semc', 'sgh-' => 'sgh-', 'shar' => 'shar', 'sie-' => 'sie-', 'sk-0' => 'sk-0', 'sl45' => 'sl45', 'slid' => 'slid', 'smb3' => 'smb3', 'smt5' => 'smt5', 'sp01' => 'sp01', 'sph-' => 'sph-', 'spv ' => 'spv ', 'spv-' => 'spv-', 'sy01' => 'sy01', 'samm' => 'samm', 'sany' => 'sany', 'sava' => 'sava', 'scoo' => 'scoo', 'send' => 'send', 'siem' => 'siem', 'smar' => 'smar', 'smit' => 'smit', 'soft' => 'soft', 'sony' => 'sony', 't-mo' => 't-mo', 't218' => 't218', 't250' => 't250', 't600' => 't600', 't610' => 't610', 't618' => 't618', 'tcl-' => 'tcl-', 'tdg-' => 'tdg-', 'telm' => 'telm', 'tim-' => 'tim-', 'ts70' => 'ts70', 'tsm-' => 'tsm-', 'tsm3' => 'tsm3', 'tsm5' => 'tsm5', 'tx-9' => 'tx-9', 'tagt' => 'tagt', 'talk' => 'talk', 'teli' => 'teli', 'topl' => 'topl', 'tosh' => 'tosh', 'up.b' => 'up.b', 'upg1' => 'upg1', 'utst' => 'utst', 'v400' => 'v400', 'v750' => 'v750', 'veri' => 'veri', 'vk-v' => 'vk-v', 'vk40' => 'vk40', 'vk50' => 'vk50', 'vk52' => 'vk52', 'vk53' => 'vk53', 'vm40' => 'vm40', 'vx98' => 'vx98', 'virg' => 'virg', 'vite' => 'vite', 'voda' => 'voda', 'vulc' => 'vulc', 'w3c ' => 'w3c ', 'w3c-' => 'w3c-', 'wapj' => 'wapj', 'wapp' => 'wapp', 'wapu' => 'wapu', 'wapm' => 'wapm', 'wig ' => 'wig ', 'wapi' => 'wapi', 'wapr' => 'wapr', 'wapv' => 'wapv', 'wapy' => 'wapy', 'wapa' => 'wapa', 'waps' => 'waps', 'wapt' => 'wapt', 'winc' => 'winc', 'winw' => 'winw', 'wonu' => 'wonu', 'x700' => 'x700', 'xda2' => 'xda2', 'xdag' => 'xdag', 'yas-' => 'yas-', 'your' => 'your', 'zte-' => 'zte-', 'zeto' => 'zeto', 'acs-' => 'acs-', 'alav' => 'alav', 'alca' => 'alca', 'amoi' => 'amoi', 'aste' => 'aste', 'audi' => 'audi', 'avan' => 'avan', 'benq' => 'benq', 'bird' => 'bird', 'blac' => 'blac', 'blaz' => 'blaz', 'brew' => 'brew', 'brvw' => 'brvw', 'bumb' => 'bumb', 'ccwa' => 'ccwa', 'cell' => 'cell', 'cldc' => 'cldc', 'cmd-' => 'cmd-', 'dang' => 'dang', 'doco' => 'doco', 'eml2' => 'eml2', 'eric' => 'eric', 'fetc' => 'fetc', 'hipt' => 'hipt', 'http' => 'http', 'ibro' => 'ibro', 'idea' => 'idea', 'ikom' => 'ikom', 'inno' => 'inno', 'ipaq' => 'ipaq', 'jbro' => 'jbro', 'jemu' => 'jemu', 'java' => 'java', 'jigs' => 'jigs', 'kddi' => 'kddi', 'keji' => 'keji', 'kyoc' => 'kyoc', 'kyok' => 'kyok', 'leno' => 'leno', 'lg-c' => 'lg-c', 'lg-d' => 'lg-d', 'lg-g' => 'lg-g', 'lge-' => 'lge-', 'libw' => 'libw', 'm-cr' => 'm-cr', 'maui' => 'maui', 'maxo' => 'maxo', 'midp' => 'midp', 'mits' => 'mits', 'mmef' => 'mmef', 'mobi' => 'mobi', 'mot-' => 'mot-', 'moto' => 'moto', 'mwbp' => 'mwbp', 'mywa' => 'mywa', 'nec-' => 'nec-', 'newt' => 'newt', 'nok6' => 'nok6', 'noki' => 'noki', 'o2im' => 'o2im', 'opwv' => 'opwv', 'palm' => 'palm', 'pana' => 'pana', 'pant' => 'pant', 'pdxg' => 'pdxg', 'phil' => 'phil', 'play' => 'play', 'pluc' => 'pluc', 'port' => 'port', 'prox' => 'prox', 'qtek' => 'qtek', 'qwap' => 'qwap', 'rozo' => 'rozo', 'sage' => 'sage', 'sama' => 'sama', 'sams' => 'sams', 'sany' => 'sany', 'sch-' => 'sch-', 'sec-' => 'sec-', 'send' => 'send', 'seri' => 'seri', 'sgh-' => 'sgh-', 'shar' => 'shar', 'sie-' => 'sie-', 'siem' => 'siem', 'smal' => 'smal', 'smar' => 'smar', 'sony' => 'sony', 'sph-' => 'sph-', 'symb' => 'symb', 't-mo' => 't-mo', 'teli' => 'teli', 'tim-' => 'tim-', 'tosh' => 'tosh', 'treo' => 'treo', 'tsm-' => 'tsm-', 'upg1' => 'upg1', 'upsi' => 'upsi', 'vk-v' => 'vk-v', 'voda' => 'voda', 'vx52' => 'vx52', 'vx53' => 'vx53', 'vx60' => 'vx60', 'vx61' => 'vx61', 'vx70' => 'vx70', 'vx80' => 'vx80', 'vx81' => 'vx81', 'vx83' => 'vx83', 'vx85' => 'vx85', 'wap-' => 'wap-', 'wapa' => 'wapa', 'wapi' => 'wapi', 'wapp' => 'wapp', 'wapr' => 'wapr', 'webc' => 'webc', 'whit' => 'whit', 'winw' => 'winw', 'wmlb' => 'wmlb', 'xda-' => 'xda-',))); // check against a list of trimmed user agents to see if we find a match
                    $mobile_browser = TRUE; // set mobile browser to true
                    break; // break even though it's the last statement in the switch so there's nothing to break away from but it seems better to include it than exclude it

            } // ends the switch

            // tell adaptation services (transcoders and proxies) to not alter the content based on user agent as it's already being managed by this script
            header('Cache-Control: no-transform'); // http://mobiforge.com/developing/story/setting-http-headers-advise-transcoding-proxies
            header('Vary: User-Agent, Accept'); // http://mobiforge.com/developing/story/setting-http-headers-advise-transcoding-proxies

            // if redirect (either the value of the mobile or desktop redirect depending on the value of $mobile_browser) is true redirect else we return the status of $mobile_browser
//		  if($redirect = ($mobile_browser==true) ? $mobileredirect : $desktopredirect){
//		    header('Location: '.$redirect); // redirect to the right url for this device
//		    exit;

//          if($mobile_browser){
//                header('Location: '.$mobileredirect);
//                exit;
//		  }else{
            return $mobile_browser; // will return either true or false
//		  }

        } // ends function mobile_device_detect


        public static function valid_phone($input, $with84 = TRUE)
        {
            if ($with84) {
                if (preg_match("/^0[0-9]{9,10}$/i", $input) == TRUE || preg_match("/^84[0-9]{9,11}$/i", $input) == TRUE) {
                    return TRUE;
                } else return FALSE;
            } else {
                if (preg_match("/^[0-9]{9,10}$/i", $input) == TRUE || preg_match("/^[0-9]{9,11}$/i", $input) == TRUE) {
                    return TRUE;
                } else return FALSE;
            }
        }

        public static function convert_phone($input)
        {
            if (preg_match("/0[0-9]{9,10}$/i", $input) == TRUE) {
                $return = '84' . substr($input, 1);

                return $return;
            } else if (preg_match("/^[0-9]{9,10}$/i", $input) == TRUE) {
                $return = '84' . $input;

                return $return;
            } else {
                return $input;
            }
        }

        public static function checkIPV4Exist($clientIP, $array_ips)
        {
            $result   = FALSE;
            $clientIP = trim($clientIP);
            if (sizeof($array_ips > 0)) {
                foreach ($array_ips as $key => $value) {
                    $len_of_key = strlen($key);
                    if (strlen($clientIP) > $len_of_key) {
                        $IPprefix = substr($clientIP, 0, $len_of_key);
                        if ($IPprefix == $key) {
                            $key_subfix        = trim($key, ".");
                            $key_subfix_arr    = explode('.', $key_subfix);
                            $len_of_key_subfix = count($key_subfix_arr);
                            $right_client_ip   = substr($clientIP, $len_of_key);
                            $right_client_arr  = explode('.', $right_client_ip);

                            switch ($len_of_key_subfix) {
                                case '1':
                                    if (count($right_client_arr) > 0) {
                                        $section1 = $right_client_arr[0];
//                                        $section2 = $right_client_arr[1];

                                        $arr_ip_section = explode(',', $value); //get ip section

                                        foreach ($arr_ip_section as $k_section => $v_section) {
                                            $arr_range = explode('|', $v_section);
                                            if (count($arr_range) == 2) {
                                                if ($section1 >= $arr_range[0] && $section1 <= $arr_range[1]) {
                                                    $result = TRUE;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                    break;
                                case '2':
                                    if (count($right_client_arr) > 0) {
                                        $section1 = $right_client_arr[0];
//                                        $section2 = $right_client_arr[2];

                                        $arr_ip_section = explode(',', $value); //get ip section
                                        foreach ($arr_ip_section as $k_section => $v_section) {
                                            $arr_range = explode('|', $v_section);
                                            if (count($arr_range) == 2) {
                                                if ($section1 >= $arr_range[0] && $section1 <= $arr_range[1]) {
                                                    $result = TRUE;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                    break;
                                case '3':
                                    $pos            = strripos($clientIP, '.');
                                    $IPpostfix      = substr($clientIP, $pos + 1);
                                    $arr_ip_section = explode(',', $value); //get ip section
                                    foreach ($arr_ip_section as $k_section => $v_section) {
                                        $arr_range = explode('|', $v_section);
                                        if (count($arr_range) == 2) {
                                            if ($IPpostfix >= $arr_range[0] && $IPpostfix <= $arr_range[1]) {
                                                $result = TRUE;
                                                break;
                                            }
                                        }
                                    }
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                }
            }

            return $result;
        }

        public static function ipv6_to_numeric($ip)
        {
            $binNum = '';
            foreach (unpack('C*', inet_pton($ip)) as $byte) {
                $binNum .= str_pad(decbin($byte), 8, "0", STR_PAD_LEFT);
            }

            return (double)base_convert(ltrim($binNum, '0'), 2, 10);
        }


        public static function isIPv6($ip)
        {
            if (strpos($ip, ":") !== FALSE && strpos($ip, ".") === FALSE) {
                return TRUE; //Pure format
            } elseif (strpos($ip, ":") !== FALSE && strpos($ip, ".") !== FALSE) {
                return TRUE; //dual format
            } else {
                return FALSE;
            }
        }

        public static function validateDate($date, $format = 'YmdHis')
        {
            $d = DateTime::createFromFormat($format, $date);

            return $d && $d->format($format) == $date;
        }

        public static function debug($array, $dump = FALSE)
        {
            echo '<xmp>';
            if ($dump) {
                var_dump($array);
            } else {
                print_r($array);
            }
            echo '</xmp>';
        }

        public static function redirect($url, $js = FALSE)
        {
            @header('Location: ' . $url);
            if ($js == TRUE) {
                echo '<script type="text/javascript">window.document.location.href="' . $url . '";</script>';
            }
        }

        public static function decryptData($value, $public_key)
        { //decrypt data from upro.vn
            $value = base64_decode($value);
            openssl_public_decrypt($value, $newsource, $public_key);

            return trim($newsource);
        }

        public static function privateKeyDecrypt($value, $private_key)
        {
            $data_encrypt = base64_decode($value);
            openssl_private_decrypt($data_encrypt, $newsource, $private_key);

            return trim($newsource);
        }


        public static function buildAPIRegisterCancel($cmd, $url, $channel, $from, $params)
        {
            /*
             * cmd = REGISTER, CANCEL, DETECTION,..
             * url = cprequestid='030140323122319123&....'*/

            //get config
            $config = Yii::app()->params['REGISTER_CANCEL_API_VIETTEL'];

            $public_key1  = $config['public_key1'];
            $private_key2 = $config['private_key2'];
            $key          = $config['key'];
            $cpid         = $config['cpid'];
            $serviceid    = $config['serviceid'];
            // Khởi tại class Encryption
            $converter = new Encryption();
            // Khởi tại key AES
            $converter->setSkey($key);
            // Khởi tạo public_key1
            $converter->setPublicKey($public_key1);
            // Khởi tạo private_key2
            $converter->setPrivateKey($private_key2);

            // Mã hóa dữ liệu truyền đi bằng key AES
            $data = $converter->encode($url);
            //echo 'data: '.$data.'<hr>';
            // Dữ liệu mới có dạng
            $newdata = "data=$data&key=$key";

            // Mã hóa dữ liệu $newdata bằng public_key1
            $data_encrypted = $converter->encryptData($newdata);
            //echo 'data_encrypted: '.$data_encrypted.'<hr>';

            // Tạo chữ ký bằng private_key2
            $signature = $converter->createSignature($data_encrypted);
            //echo 'signature: '.$signature.'<hr>';

            $url_call = $config['url'] . '&cmd=' . $cmd . '&cpid=' . $cpid . '&serviceid=' . $serviceid . '';
            $url_call .= '&data=' . $data_encrypted . '&signature=' . $signature . '';
            //echo 'url call: '.$url_call.'<hr>';
            if (is_array($params) && sizeof($params) > 0) {
                foreach ($params as $k => $v) {
                    $url_call .= '&' . $k . '=' . $v . '';
                }
            }

            $url_call .= '&from=' . $from . '';

            return $url_call;
        }

        public static function cv2urltitle($text)
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

        public static function getCurrentUrl()
        {
            $pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
            if ($_SERVER["SERVER_PORT"] != "80") {
                $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
            } else {
                $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
            }

            return $pageURL;
        }

        public static function hiddenPhoneNumber($phone, $textReplace = 'x', $charRemain = 4, $prefix = '84')
        {
            $phone  = self::makePhoneNumberBasic($phone, '');
            $result = '';
            $leng   = strlen($phone);
            if ($leng > 0 && $leng > $charRemain) {
                for ($i = 0; $i < $leng; $i++) {
                    $char = substr($phone, $i, 1);
                    if ($i < ($leng - $charRemain)) {
                        $char = $textReplace;
                    }
                    $result .= $char;
                }
            }

            return $prefix . $result;
        }

        public static function isOperaMini()
        {
            $operaMiniHeadear = array(
                'X-OPERAMINI-PHONE',
                'X-OPERAMINI-PHONE-UA',
            );
            $result           = FALSE;
            foreach (getallheaders() as $name => $value) {
                $msisdn_field_on_header = strtoupper($name);
                if (in_array($msisdn_field_on_header, $operaMiniHeadear)) {
                    $result = TRUE;
                    break;
                }
            }

            return $result;
        }

        public static function aes_base64_encrypt($str, $key = 'eagamevn_centech')
        {
            $aes = new AES($key);

            return base64_encode($aes->encrypt($str));
        }

        public static function aes_base64_decrypt($str, $key = 'eagamevn_centech')
        {
            $aes = new AES($key);

            return $aes->decrypt(base64_decode($str));
        }

        public static function checkTelcoAllow($phonenumber)
        { //hiện tại chỉ hỗ trợ mạng Viettel
            $result         = FALSE;
            $viettel_prefix = Yii::app()->params['VIETTEL_PREFIX'];

            $newnumber = $phonenumber;
            if ($phonenumber != '') {
                if (substr($phonenumber, 0, 1) == '0') {
                    $newnumber = substr($phonenumber, 1, strlen($phonenumber));
                } else
                    if (substr($phonenumber, 0, 2) == '84') {
                        $newnumber = substr($phonenumber, 2, strlen($phonenumber));
                    }
                $newnumber = "0" . $newnumber;
            }

            $three_digit = substr($newnumber, 0, 3);
            $four_digit  = substr($newnumber, 0, 4);

            if (in_array($three_digit, $viettel_prefix) || in_array($four_digit, $viettel_prefix)) {
                $result = TRUE;
                //break;
            }

            return $result;
        }

        public static function createRandomString($lengthChars = 32)
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

        public static function createRandomNumber($length = 4)
        {
            if ($length <= 0) {
                return FALSE;
            } else {
                $alphaString  = '';
                $numberString = '1234567890';

                $shuffleString = $alphaString . $numberString;
                $randomString  = substr(str_shuffle($shuffleString), 0, $length);

                return $randomString;
            }
        }

        public static function checkTelco($phonenumber)
        { //hiện tại chỉ hỗ trợ mạng Viettel
            $result         = FALSE;
            $viettel_prefix = Yii::app()->params['VIETTEL_PREFIX'];
            //array('096', '097', '098', '0163', '0164', '0165', '0166', '0167' ,'0168', '0169');

            $newnumber = $phonenumber;
            if ($phonenumber != '') {
                if (substr($phonenumber, 0, 1) == '0') {
                    $newnumber = substr($phonenumber, 1, strlen($phonenumber));
                } else
                    if (substr($phonenumber, 0, 2) == '84') {
                        $newnumber = substr($phonenumber, 2, strlen($phonenumber));
                    }
                $newnumber = "0" . $newnumber;
            }

            $three_digit = substr($newnumber, 0, 3);
            $four_digit  = substr($newnumber, 0, 4);

            if (in_array($three_digit, $viettel_prefix) || in_array($four_digit, $viettel_prefix)) {
                $result = TRUE;
                //break;
            }

            return $result;
        }


        public static function safe_b64encode($string)
        {
            $data = base64_encode($string);
            $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);

            return $data;
        }

        public static function safe_b64decode($string)
        {
            $data = str_replace(array('-', '_'), array('+', '/'), $string);
            $mod4 = strlen($data) % 4;
            if ($mod4) {
                $data .= substr('====', $mod4);
            }

            return base64_decode($data);
        }


        public static function APIRegisterCancelSub($cmd, $username, $channel, $from = 'wrapper', $params = array())
        {          //đăng ký, hủy sub
            //$cmd = 'REGISTER, CANCEL';
            //from = wap or sms or wrapper
            /*log-------------------------------------------------------------------------*/
            $time       = date('d/m/Y H:i:s', time());
            $logMsg[]   = array('---------------------#START---------------------------------------------------------', '' . $cmd . ' sub', 'I');
            $logMsg[]   = array('Channel: ' . $channel . '', 'I');
            $logMsg[]   = array('Time: ' . $time, '' . $cmd . ' sub: ' . $time . '', 'I');
            $logFolder  = "Register_Cancel_Sub/" . date("Y") . "/" . date("m");
            $logRequest = new SystemLog($logFolder);
            $logRequest->setLogFile('' . $cmd . '_SUB_' . date("Ymd") . '.log');

            if (trim($username == '')) {
                $logMsg[] = array('' . $cmd . ' sub: ' . $time . ', FAILED - MSISDN is null', '' . $cmd . ' sub: ' . $time . '', 'I');
                $logMsg[] = array('------------------#END--------------------------------------------', '' . $cmd . ' sub: ' . $time . '', 'I');
                @$logRequest->processWriteLogs($logMsg);

                return FALSE;
            } else {
                //get config
                $config = Yii::app()->params['REGISTER_CANCEL_API_VIETTEL'];

                $cpid  = $config['cpid'];
                $price = $config['price'];

                $cprequestid = $cpid . '' . date('ymdHis') . rand(100, 999);       //ppp yy MM dd HH mm ss XXX
                $mobile      = CFunction::makePhoneNumberStandard($username);

                $subserviceid = '213';
                $itemname     = 'item';
                $subcpname    = $cpid;
                $content      = 'Game';

                //$price = 1000;
                $url = 'cprequestid=' . $cprequestid . '&price=' . $price . '&mobile=' . $mobile . '';
                $url .= '&subserviceid=' . $subserviceid . '&itemname=' . $itemname . '';
                $url .= '&subcpname=' . $subcpname . '&content=' . $content . '';

                //bulid url
                $url_call = CFunction::buildAPIRegisterCancel($cmd, $url, $channel, $from, $params);

                //log params
                $logParams = array(
                    'mobile'       => $mobile,
                    'cpid'         => $cpid,
                    'price'        => $price,
                    'cprequestid'  => $cprequestid,
                    'subserviceid' => $subserviceid,
                    'itemname'     => $itemname,
                    'subcpname'    => $subcpname,
                    'content'      => $content,
                    'clientIP'     => $_SERVER['REMOTE_ADDR'],
                    'url_call'     => $url_call,

                );
                $logMsg[]  = array('Data send : ' . CJSON::encode($logParams), '' . $cmd . ' sub', 'I');
                /*------------------------------------------------------------------------------*/

                //call api
                $ch      = curl_init();
                $timeout = 10;
                curl_setopt($ch, CURLOPT_URL, $url_call);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                $result = curl_exec($ch);
                curl_close($ch);

                $logMsg[] = array('' . $cmd . ' sub: ' . $time . ', Response result:  ' . $result, '' . $cmd . ' sub: ' . $time . '', 'I');
                parse_str($result, $parser);

                if (array_key_exists('responsecode', $parser) && $parser['responsecode'] == 0) {
                    $logMsg[] = array('' . $cmd . ' sub: ' . $time . ', SUCCESS', '' . $cmd . ' sub: ' . $time . '', 'I');
                    $logMsg[] = array('------------------#END-------------------------------------------', '' . $cmd . ' sub: ', 'I');
                    @$logRequest->processWriteLogs($logMsg);

                    return array(
                        'status'       => TRUE,
                        'responsecode' => 200,   //Success register
                    );
                } else {
                    $logMsg[] = array('' . $cmd . ' sub: ' . $time . ', FAILED', '' . $cmd . ' sub: ' . $time . '', 'I');
                    $logMsg[] = array('------------------#END--------------------------------------------', '' . $cmd . ' sub: ' . $time . '', 'I');
                    @$logRequest->processWriteLogs($logMsg);

                    return array(
                        'status'       => FALSE,
                        'responsecode' => $parser['responsecode']
                    );
                }
            }
        }

        public static function getDownloadLink()
        {
            if (Yii::app()->session['msisdn'] != '') {
                $week_url  = Yii::app()->createUrl('package/' . WCustomers::WEEK_PACKGAGE);
                $month_url = Yii::app()->createUrl('package/' . WCustomers::MONTH_PACKGAGE);
            } else {
                $week_url  = Yii::app()->createUrl('verifyuser/' . strtolower(WCustomers::WEEK_PACKGAGE));
                $month_url = Yii::app()->createUrl('verifyuser/' . strtolower(WCustomers::MONTH_PACKGAGE));
            }

            return array('week_url' => $week_url, 'month_url' => $month_url);
        }

        public static function secondsToTime($seconds)
        {
            // extract hours
            $hours = floor($seconds / (60 * 60));

            // extract minutes
            $divisor_for_minutes = $seconds % (60 * 60);
            $minutes             = floor($divisor_for_minutes / 60);

            // extract the remaining seconds
            $divisor_for_seconds = $divisor_for_minutes % 60;
            $seconds             = ceil($divisor_for_seconds);
            if ($hours < 10) {
                $hours = '0' . $hours;
            }
            if ($minutes < 10) {
                $minutes = '0' . $minutes;
            }
            if ($seconds < 10) {
                $seconds = '0' . $seconds;
            }
            // return the final array
            if ($hours > 0) {
                return $hours . ":" . $minutes . ":" . $seconds . "";
            } elseif ($minutes > 0) {
                return $minutes . ":" . $seconds . "";
            } else {
                return $seconds . "";
            }
        }

        public static function humanTiming($time)
        {

            $time   = time() - $time; // to get the time since that moment
            $tokens = array(
                31536000 => 'năm',
                2592000  => 'tháng',
                604800   => 'tuần',
                86400    => 'ngày',
                3600     => 'giờ',
                60       => 'phút',
                1        => 'giây'
            );

            foreach ($tokens as $unit => $text) {
                if ($time < $unit) continue;
                $numberOfUnits = floor($time / $unit);

                return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
            }
        }

        public static function cutText($string, $setlength)
        {

            $length = $setlength;
            if ($length < strlen($string)) {
                while (($string{$length} != " ") AND ($length > 0)) {
                    $length--;
                }
                if ($length == 0) return substr($string, 0, $setlength);
                else return substr($string, 0, $length);
            } else return $string;
        }

        /*hunghn 2015-09-04*/
        public static function getCurlData($url)
        {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 5);
            curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
            $curlData = curl_exec($curl);
            curl_close($curl);

            return $curlData;
        }

        /*hunghn 2015-09-16 login add point */
        public static function addPoint($customer_id)
        {
            if ($customer_id) {
                $existingCustomer = WCustomers::model()->find('id=:id', array('id' => $customer_id));

                if ($existingCustomer) {
                    // update login count
                    $_lastupdate = date('Y-m-d', strtotime($existingCustomer->last_update)); //check last_update on day
                    $lastupdate  = date('Y-m-d', strtotime($existingCustomer->last_update . '+ 1 day'));

                    if ($_lastupdate == date('Y-m-d')) {
                        $existingCustomer->save();

                        return FALSE;
                    }

                    if ($lastupdate >= date('Y-m-d')) {
                        $existingCustomer->login_count++;
                        $existingCustomer->save();
                    } else {
                        $existingCustomer->login_count = 0;
                        $existingCustomer->save();
                    }
                    // reward by login
                    if ($existingCustomer->login_count == 30) {
                        $existingCustomer->_charge(1000, 0, 'The 30th login');
                        $existingCustomer->_charge(50, 1, 'The 30th login');
                    }
                    if ($existingCustomer->login_count == 100) {
                        $existingCustomer->_charge(1000, 0, 'The 100th login');
                        $existingCustomer->_charge(50, 1, 'The 100th login');
                    }

                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }

        }

        /*hunghn 2015-09-22 url login fb */
        public static function urlLoginFB()
        {

            require Yii::app()->params->facebook_sdk_path;
            $fb = new Facebook\Facebook(Yii::app()->params['facebook']);

            $helper           = $fb->getRedirectLoginHelper();
            $facebookLoginUrl = $helper->getLoginUrl('https://vovthethao.vn/customer/loginFacebookSuccess', Yii::app()->params['facebookPermissions']);

            return $facebookLoginUrl;
        }

        /*Function clean url*/
        public static function clean_url($string)
        {
            $string = str_replace(' ', '-', $string);
            $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

            return preg_replace('/-+/', '-', $string);
        }

        /**
         * Ham convert Sms Link .
         * IOS :
         * Ver <=7.xxx : sms:9307;body=DK
         * Ver 8.xxx : sms:9307&body=DK
         * aNDROID :
         * Ver >= 4.2.3 sms:9307?body=DK
         * Ver <=4.2.2 sms:9307?body=DK  ???
         *
         * @param $link : default : sms:9307&body=DK
         *
         * @return mixed
         */
        public static function convertSmsLink($link)
        {
            $return        = $link;
            $mobile_detect = new MyMobileDetect();
            if ($mobile_detect->isMobile()) {
                if ($mobile_detect->isiOS()) {
                    $version = $mobile_detect->version('iOS');
                    if ($version < '8.0.0') {
                        $return = str_replace('&body', ';body', $link);
                    }
                } elseif ($mobile_detect->isAndroidOS()) {
                    $version = $mobile_detect->version('Android');
                    if ($version >= '4.2.3') {
                        $return = str_replace('&body', '?body', $link);
                    } else {
                        $return = 'sms:' . CFunction::get_string_between($link, 'sms:', '&');
                    }
                }
            }

            return $return;
        }

        /** Lấy link chi tiết của video dựa vào category_id và user đã đăng nhập hay chưa
         *
         * @param int $category_id
         * @param     $item
         *
         * @return string
         */
        public static function getLinkTopUpLogin($category_id = WVideo::CATEGORY_VIDEO_ID, $item)
        {
            $link      = "";
            $device_os = new Mobile_Detect();
            if ($device_os->isMobile() && Yii::app()->session['session_data']->channel_code == 'VIETTEL') { // Nếu là 3G VIETTEL.
                //xet category id truyền vào
                switch ($category_id) {
                    //nếu là video thì gán link video
                    case WVideo::CATEGORY_VIDEO_ID:
                        $link = Yii::app()->createAbsoluteUrl('/video/chi-tiet') . '/' . $item['unsign_name'] . '-' . $item['id'];
                        break;
                    //nếu là video ca nhạc thì gán link video ca nhạc
                    case WVideo::CATEGORY_VIDEO_MUSIC_ID:
                        $link = Yii::app()->createAbsoluteUrl('/video-ca-nhac/chi-tiet') . '/' . $item['unsign_name'] . '-' . $item['id'];
                        break;
                }
            } else if (Yii::app()->user->isGuest) { // Nếu vào bằng wify và web hoặc chưa nhận diện được mà không đăng nhập.
                //Gán link = #
                $link = "#";
//                if ($item['price'] != 0) {
            } else {// Đăng nhập vào bằng wify và web.
                //xet category id truyền vào
                switch ($category_id) {
                    //nếu là video thì gán link video
                    case WVideo::CATEGORY_VIDEO_ID:
                        $link = Yii::app()->createAbsoluteUrl('/video/chi-tiet') . '/' . $item['unsign_name'] . '-' . $item['id'];
                        break;
                    //nếu là video ca nhạc thì gán link video ca nhạc
                    case WVideo::CATEGORY_VIDEO_MUSIC_ID:
                        $link = Yii::app()->createAbsoluteUrl('/video-ca-nhac/chi-tiet') . '/' . $item['unsign_name'] . '-' . $item['id'];
                        break;
                }
            }

            return $link;

        }

        public static function tags_sort($tag_array)
        {
            if (sizeof($tag_array) > 0) {
                $tag_array = array_map("unserialize", array_unique(array_map("serialize", $tag_array)));
            }

            return $tag_array;
        }

        public static function authentication($authenUsername, $authenPassword)
        {
            if (!isset($_SERVER['PHP_AUTH_USER']) || ($_SERVER['PHP_AUTH_USER'] != $authenUsername) || ($_SERVER['PHP_AUTH_PW'] != $authenPassword)) {
                header('WWW-Authenticate: Basic realm="Elastic CiTV Authentication System"');
                header('HTTP/1.0 401 Unauthorized');
                echo "You must enter a valid login ID and password to access this page\n";
                exit;
            }
        }

        /**
         * @param $arrayParams
         *
         * @return string
         */
        public static function implodeParams($arrayParams)
        {
            $dataCover = '';
            $i         = 0;
            foreach ($arrayParams as $key => $value) {
                if ($i == 0) {
                    $dataCover .= $key . '=' . urlencode($value);
                } else {
                    $dataCover .= '&' . $key . '=' . urlencode($value);
                }
                $i++;
            }

            return $dataCover;
        }

        /**
         * @param        $url
         * @param int    $time_out
         * @param string $http_code
         *
         * @return mixed
         */
        public static function getURLContentAPI($url, $time_out = 5, &$http_code = '')
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $time_out);
            $data      = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $data;
        }



        public static function convertMsisdn($msisdn)
        {
            if(strlen($msisdn) == 12){
                $cut = 5;
                $short_code_rules = Yii::app()->params->short_code_rules;
            }else{
                $cut = 4;
                $short_code_rules = array_flip(Yii::app()->params->short_code_rules);
            }
            $msisdn           = self::makePhoneNumberStandard($msisdn);
            $short_code       = substr($msisdn, 0, $cut);
            if (array_key_exists($short_code, $short_code_rules)) {
                $msisdn = str_replace($short_code, $short_code_rules[$short_code], $msisdn);
            }
            return $msisdn;
        }


        /**
         * Convert msisdn
         * @param $msisdn
         * @param $eleven: chuyển từ 11 số sáng 10 sô
         * @param $ten: chuyển từ 10 số sáng 11 số
         */
        public static function convertNewMsisdn($msisdn, $eleven = true, $ten = true)
        {
            $msisdn = self::makePhoneNumberStandard($msisdn);
            $msisdn = trim($msisdn);
            if(CFunction::isConvertedMsisdn($msisdn)){
                $cut = null;
                $short_code_rules = array();
                if(strlen($msisdn) == 12 && $eleven){
                    $cut = 5;
                    $short_code_rules = Yii::app()->params->short_code_rules;
                }else if(strlen($msisdn) == 11 && $ten){
                    $cut = 4;
                    $short_code_rules = array_flip(Yii::app()->params->short_code_rules);
                }
                $short_code       = substr($msisdn, 0, $cut);
                if (array_key_exists($short_code, $short_code_rules)) {
                    $msisdn = str_replace($short_code, $short_code_rules[$short_code], $msisdn);
                }
            }
            return $msisdn;
        }
        /*
         * check converted msisdn
         * 
         * */
        public static function  isConvertedMsisdn($msisdn){
            if(Yii::app()->params->time_convert_msisdn){
                $first_strs  = substr($msisdn, 0, 5);
                $time_convert = Yii::app()->params->time_convert_msisdn[$first_strs];
                date_default_timezone_set("Asia/Bangkok");
                if($time_convert && $time_convert <= date('Y-m-d H:i:s')){
                    return true;
                }
            }
            return false;
        }

    }

?>