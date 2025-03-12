<?php

    class Utils
    {

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

        /**
         * Do post request
         *
         * @param string $url           This is full, qualified, web service or web page URL, it must contain with
         *                              http:// or https://
         * @param string $function_name string, optional, default empty string - Remote functio name, no need for
         *                              access web pages
         * @param array  $ary_param     associative array - post values
         * @param bool   $auth_flag     It makes a request with authentication or not, if it chagne to true, next 2
         *                              params(username and pasword) should not be empty
         * @param string $username      username for authentication
         * @param string $password      password for authentication
         *
         * @return string or FALSE on failure.
         */
        public static function do_post_request($url, $function_name = '', $param = '', $timeout = 3, $auth_flag = FALSE, $username = '', $password = '')
        {
            $auth_param = ""; // check authentication enable or not
            if ($auth_flag) {
                if ($username == "") {
                    return FALSE;
                }
                if ($password == "") {
                    return FALSE;
                }
                $auth_param = "Authorization: Basic " . base64_encode($username . ':' . $password) . "\r\n";
            }

            // construct web service URL
            $ws_req_url = $url . ($function_name ? '/' . $function_name : '');
//            $ws_req_url .= ($function_name ? '/' . $function_name : '');// check whether function name available or not

            // construct params array to query string format
            $query_param = is_array($param) ? http_build_query($param) : $param;

            $params = array(
                'http' => array(
                    'ignore_errors' => TRUE,
                    'method'        => 'POST',
                    'header'        => "Content-type: application/x-www-form-urlencoded\r\n" . $auth_param,
                    'content'       => $query_param,
                ),
            );

            $context = stream_context_create($params);
            stream_set_timeout($context, $timeout);
            $stream   = fopen($ws_req_url, 'r', FALSE, $context); //check to make sure that allow_url_fopen is enabled
            $response = stream_get_contents($stream);

            return $response;
        }

        /**
         * Do get request
         *
         * @param string $url           This is full, qualified, web service or web page URL, it must contain with
         *                              http:// or https://
         * @param string $function_name string, optional, default empty string - Remote functio name, no need for
         *                              access web pages
         * @param array  $ary_param     associative array - post values
         * @param bool   $auth_flag     It makes a request with authentication or not, if it chagne to true, next 2
         *                              params(username and pasword) should not be empty
         * @param string $username      username for authentication
         * @param string $password      password for authentication
         *
         * @return string
         */
        public static function do_get_request($url, $function_name = '', $ary_param = '', $auth_flag = FALSE, $username = '', $password = '')
        {
            $auth_param = ""; // check authentication enable or not
            if ($auth_flag) {
                if ($username == "") {
                    return FALSE;
                }
                if ($password == "") {
                    return FALSE;
                }
                $auth_param = "Authorization: Basic " . base64_encode($username . ':' . $password) . "\r\n";
            }

            // construct web service URL
            $ws_req_url = $url . ($function_name ? '/' . $function_name : '');

            // construct params array to query string format
            $query_param = is_array($ary_param) ? http_build_query($ary_param) : '';
//            $query_param = http_build_query($ary_param);

            $ws_req_url = $ws_req_url . '?' . $query_param;

            $params = array(
                'http' => array(
                    'ignore_errors' => TRUE,
                    'method'        => 'GET',
                    'header'        => "Content-type: application/x-www-form-urlencoded\r\n" . $auth_param,
                ),
            );

            $context  = stream_context_create($params);
            $stream   = fopen($ws_req_url, 'r', FALSE, $context);
            $response = stream_get_contents($stream);

            return $response;
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

        public static function unsign_string_origin($str, $separator = '-', $keep_special_chars = FALSE)
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

            return trim(strtolower($str));
        }

        /**
         * Google Recaptcha
         *
         * @return string
         */
        public static function googleVerify($secret_key)
        {
            if (YII_DEBUG == TRUE) {
                return TRUE;
            } else {
                $recaptcha = Yii::app()->request->getParam('g-recaptcha-response', '');
                if (!empty($recaptcha)) {
                    $google_url     = 'https://www.google.com/recaptcha/api/siteverify';
                    $recaptcha_data = array(
                        'secret'   => $secret_key,
                        'remoteip' => $_SERVER['REMOTE_ADDR'],
                        'response' => $recaptcha,
                    );
                    $url            = $google_url . http_build_query($recaptcha_data);
                    $res            = CJSON::decode(Utils::do_post_request($google_url, '', $recaptcha_data));

                    //reCaptcha success check
                    return $res['success'];
                }

                return FALSE;
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
            if ($ini == 0) {
                return FALSE;
            }
            $ini += strlen($start);
            $len = strpos($string, $end, $ini) - $ini;

            return $start . substr($string, $ini, $len) . $end;
        }


        /**
         * @param     $url
         * @param     $post_string
         * @param int $time_out
         * @param     $http_status
         *
         * @return mixed
         */
        public static function cUrlPost($url, $post_string, $https, $time_out = 15, &$http_status)
        {
            $ch = curl_init();
            if ($https) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            }
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
            curl_setopt($ch, CURLOPT_TIMEOUT, $time_out);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $data        = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $data;
        }

        /**
         * @param     $url
         * @param     $post_string
         * @param int $time_out
         * @param     $http_status
         *
         * @return mixed
         */
        public static function cUrlPostJson($url, $post_string, $https = FALSE, $time_out = 15, &$http_status = '', $opt_header_arr = FALSE)
        {
            $ch = curl_init();
            if ($https) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            }
            if (!is_array($opt_header_arr)) {
                $opt_header_arr = array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($post_string));
            }

            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $opt_header_arr);
            curl_setopt($ch, CURLOPT_TIMEOUT, $time_out);
            $data        = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $data;
        }

        /**
         * Get Content from url (CURL)
         *
         * @param $url (api url)
         * @param $timeout
         * @param $http_code
         *
         * @return mixed (array|bool)
         */
        public static function cUrlGet($url, $timeout = 15, &$http_code)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $rs        = curl_exec($ch);
            curl_close($ch);

            return $rs;
        }

        public static function sendEmail($from, $to, $subject, $short_desc, $content = '', $views_layout_path = 'web.views.layouts')
        {
            $mail = new YiiMailer();
            $mail->setLayoutPath($views_layout_path);
            $mail->setData(array('message' => $content, 'name' => $from, 'description' => $short_desc));

            $mail->setFrom(Yii::app()->params->sendEmail['username'], $from);
            $mail->setTo($to);
            $mail->setSubject($from . ' | ' . $subject);
            $mail->setSmtp(Yii::app()->params->sendEmail['host'], Yii::app()->params->sendEmail['port'], Yii::app()->params->sendEmail['type'], TRUE, Yii::app()->params->sendEmail['username'], Yii::app()->params->sendEmail['password']);
            if ($mail->send()) {// echo 'Email was sent';

                return TRUE;
            } else {
                CVarDumper::dump($mail->getError(), 10, TRUE);
            }
        }

        public static function cUrlWithHttps($url, $time_out = 10, $https = TRUE)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            if ($https) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            }
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
                return '0:' . $seconds . "";
            }
        }

        public static function formatSizeUnits($bytes)
        {
            if ($bytes >= 1073741824) {
                $bytes = number_format($bytes / 1073741824, 1) . ' GB';
            } elseif ($bytes >= 1048576) {
                $bytes = number_format($bytes / 1048576, 1) . ' MB';
            } elseif ($bytes >= 1024) {
                $bytes = number_format($bytes / 1024, 1) . ' KB';
            } elseif ($bytes > 1) {
                $bytes = $bytes . ' bytes';
            } elseif ($bytes == 1) {
                $bytes = $bytes . ' byte';
            } else {
                $bytes = '0 bytes';
            }

            return $bytes;
        }

        public static function check_os_content($os, $content_name, $arr_os_content)
        {
            $os     = self::unsign_string($os);
            $detect = new MyMobileDetect();
            if ($os && ($os == MyMobileDetect::OS_ANDROID) || ($os == MyMobileDetect::OS_IOS) || ($os == MyMobileDetect::OS_SYMBIANOS) || ($os == MyMobileDetect::OS_DESKTOP)) {
                if ($content_name && $arr_os_content) {
                    if (isset($arr_os_content[$os][$content_name])) {
                        return $arr_os_content[$os][$content_name];
                    }
                }
            }

            return TRUE;
        }

        public static function getEventGA($category, $action, $label, $value = '')
        {
            $function = "ga('send', 'event', '$category', '$action', '$label', '$value');";

            return $function;
        }

        /**
         * Generate google analytic
         *
         * @param $key
         *
         * @return string
         */
        public static function genGA($key)
        {
            return "
                <script>
                    $(document).ready(function () {
                        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
                        ga('create', '$key', 'auto');
                        ga('send', 'pageview');
                    });
                </script>
            ";
        }

        public static function redirectJS($url)
        {
            echo "<script> window.location.href = \"$url\";</script>";
        }

        /**
         * @param int        $length
         * @param bool|FALSE $is_number
         *
         * @return string
         */
        public static function generateRandomString($length = 10, $is_number = FALSE)
        {
            if ($is_number) {
                $characters = '0123456789';
            } else {
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            }
            $charactersLength = strlen($characters);
            $randomString     = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            return $randomString;
        }

        public static function secondsToTimeStamp($seconds)
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
                return '00:' . $minutes . ":" . $seconds . "";
            } else {
                return '00:00:' . $seconds . "";
            }
        }


        public function safe_b64encode($string)
        {
            $data = base64_encode($string);

//            $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);

            return $data;
        }

        public function safe_b64decode($string)
        {
            $data = str_replace(array('-', '_'), array('+', '/'), $string);
            $mod4 = strlen($data) % 4;
            if ($mod4) {
                $data .= substr('====', $mod4);
            }

            return base64_decode($data);
        }

        public static function encrypt($encrypt, $key, $algorithm)
        {
            $iv        = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, MCRYPT_MODE_ECB), MCRYPT_RAND);
            $encrypted = self::safe_b64encode(mcrypt_encrypt($algorithm, $key, $encrypt, MCRYPT_MODE_ECB, $iv));

            return $encrypted;
        }


        public static function decrypt($decrypt, $key, $algorithm)
        {
            $iv        = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, MCRYPT_MODE_ECB), MCRYPT_RAND);
            $decrypted = mcrypt_decrypt($algorithm, $key, self::safe_b64decode($decrypt), MCRYPT_MODE_ECB, $iv);

            return $decrypted;
        }

        /*
         * Function Detect Telco by msisdn
         * @return boo
        */
        public static function detectTelcoByMsisdn($msisdn)
        {
            $shortcode_telco = array(
                'VIETTEL'      => array('96', '97', '98', '162', '163', '164', '165', '166', '167', '168', '169',),
                'MOBIFONE'     => array('90', '93', '120', '121', '122', '126', '128',),
                'VINAPHONE'    => array('91', '94', '123', '124', '125', '127', '129', '88'),
                'VIETNAMOBILE' => array('92', '188',),
                'BEELINE'      => array('993', '994', '995', '996', '99',),
                'SFONE'        => array('95',),
            );
            $return          = 'UNKNOW_TELCO';
            if ($msisdn) {
                $msisdn = CFunction::makePhoneNumberStandard($msisdn);

                if (preg_match("/^84[0-9]{9,11}$/i", $msisdn) == TRUE) {
                    //lấy 3 số sau 84
                    $pre_code = preg_replace('/^84(\d\d\d).*/', '$1', $msisdn);

                    //ktra chính xác sđt đầu 08,09 = 10 số
                    if ((substr($pre_code, 0, 1) == 8 || substr($pre_code, 0, 1) == 9) && strlen($msisdn) >= 10) {
                        $pre_code = substr($pre_code, 0, 2);
                    }

                    $arr_by_short_code = array();
                    foreach ($shortcode_telco as $telco => $row) {
                        foreach ((array)$row as $srow) {
                            $arr_by_short_code[$srow] = $telco;
                        }
                    }
                    $return = isset($arr_by_short_code[$pre_code]) ? $arr_by_short_code[$pre_code] : $return;
                }
            }

            return $return;
        }

        /**
         * @param     $content
         * @param int $min_len
         * @param     $black_list_arr
         * @param     $err_msg
         * @param int $word_char
         *
         * @return bool
         */
        public static function validateContent($content, $min_len = 0, $black_list_arr, &$err_msg, $word_char = 0)
        {
            $content = mb_strtolower($content, 'UTF-8');
            if ($min_len > 0) {
                if ($word_char == 0 && str_word_count($content) < $min_len) {
                    $err_msg = "Độ dài tối thiểu là $min_len từ";

                    return FALSE;
                }
                if ($word_char == 1 && strlen($content) < $min_len) {
                    $err_msg = "Độ dài tối thiểu là $min_len ký tự";

                    return FALSE;
                }
            }

            if (is_array($black_list_arr)) {
                foreach ($black_list_arr as $word) {
                    if (preg_match("/\b$word\b/i", $content)) {
                        $err_msg = "Nội dung không hợp lệ ";

                        return FALSE;
                    }
                }
            }

            return TRUE;
        }


        public static function sentMtVNP($msisdn, $msgBody, &$api_url, &$http_code = '', &$rs = '')
        {
            $msisdn = CFunction_MPS::makePhoneNumberStandard($msisdn);
            $mtseq  = time() . rand(1000, 9999);

            $smsMtRequest = array(
                'username'   => 'freedoo01',
                'password'   => 'CentEch2o17FREEdoo',
                'dest'       => $msisdn,
                'msgtype'    => 'Text',
                'cpid'       => '',
                'src'        => 'FREEDOO',
                'procresult' => 0,
                'mtseq'      => $mtseq,
                'msgbody'    => $msgBody,
                'serviceid'  => '',
            );

            $api_url = str_replace('?', '', $GLOBALS['config_common']['api']['sms_gw']) . '?' . http_build_query($smsMtRequest);

            $rs = self::cUrlGet($api_url, 10, $http_code);
            if ($http_code == '200' || $rs == '200') {
                return TRUE;
            }

            echo "url: $api_url <br>";
            echo "http_code: $http_code <br>";

            return FALSE;
        }

        /**
         * @param $source_image
         *
         * Xử lý chiều cao và chiều rộng ảnh.
         * jpeg, jpg, png, gif.
         *
         * @return bool
         */
        public static function resizeImage($source_image)
        {
            list($width, $height) = getimagesize($source_image);

            $newwidth  = 400;
            $newheight = 300;
            $thumb     = imagecreatetruecolor($newwidth, $newheight);
            $info      = getimagesize($source_image);
            $extension = image_type_to_extension($info[2]);

            if ($extension == '.jpeg' || $extension == '.jpg') {
                $source = imagecreatefromjpeg($source_image);
                imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            } else if ($extension == '.png') {
                $source = imagecreatefrompng($source_image);
                imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            } else if ($extension == '.gif') {
                $source = imagecreatefromgif($source_image);
                imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            }

            if ($extension == '.jpeg' || $extension == '.jpg') {
                if (imagejpeg($thumb, $source_image)) {
                    return TRUE;
                }
            } else if ($extension == '.png') {
                if (imagepng($thumb, $source_image)) {
                    return TRUE;
                }
            } else if ($extension == '.gif') {
                if (imagegif($thumb, $source_image)) {
                    return TRUE;
                }
            }

            return FALSE;

        }
    }

?>