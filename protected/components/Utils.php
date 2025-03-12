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
                $res            = CJSON::decode(self::do_post_request($google_url, '', $recaptcha_data));

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
                'Content-Length: ' . strlen($post_string),
            );
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


    public static function cUrlPostJsonFiber($url, $token, $post_string, $https = FALSE, $time_out = 15, &$http_status = '', $opt_header_arr = TRUE)
    {

        $ch = curl_init();
        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        }
        if (!is_array($opt_header_arr)) {
            $opt_header_arr = array(
                'erp-token :' . $token,
                'erp-acc :' . 'freedoo',
                'erp-pwd :' . 'apidhsx@1857474',
            );
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
     * @param $follow_location
     *
     * @return mixed (array|bool)
     */
    public static function cUrlGet($url, $timeout = 15, &$http_code, $follow_location = FALSE, $https = FALSE)
    {
        $ch = curl_init();
        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $follow_location);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $rs        = curl_exec($ch);
        curl_close($ch);

        return $rs;
    }

    public static function cUrlGetFiber($url, $token, $timeout = 15, &$http_code, $follow_location = FALSE, $https = FALSE, $opt_header_arr = TRUE)
    {
        $ch = curl_init();
        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        }
        if (!is_array($opt_header_arr)) {
            $opt_header_arr = array(
                'erp-token :' . $token,
                'erp-acc :' . 'freedoo',
                'erp-pwd :' . 'apidhsx@1857474',
            );
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $follow_location);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $opt_header_arr);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $rs        = curl_exec($ch);
        curl_close($ch);

        return $rs;
    }

    public static function sendEmail($from, $to, $subject, $short_desc, $content = '', $views_layout_path = 'web.views.layouts', $attachment = '')
    {
        $mail = new YiiMailer();
        $mail->setLayoutPath($views_layout_path);
        $mail->setData(array('message' => $content, 'name' => $from, 'description' => $short_desc));
        $mail->setFrom(Yii::app()->params->sendEmail['username'], $from);
        $mail->setTo($to);
        $mail->setSubject($from . ' | ' . $subject);
        if ($attachment) {
            $mail->addStringAttachment($attachment, 'hoa_don_dien_tu.pdf');
        }
        $mail->setSmtp(Yii::app()->params->sendEmail['host'], Yii::app()->params->sendEmail['port'], Yii::app()->params->sendEmail['type'], TRUE, Yii::app()->params->sendEmail['username'], Yii::app()->params->sendEmail['password']);
        if ($mail->send()) { // echo 'Email was sent';

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

    public static function safe_b64encode($string, $replace = TRUE)
    {
        $data = base64_encode($string);
        if ($replace) {
            $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        }

        return $data;
    }

    public static function safe_b64decode($string, $replace = TRUE)
    {
        if ($replace) {
            $string = str_replace(array('-', '_'), array('+', '/'), $string);
        }
        $mod4 = strlen($string) % 4;
        if ($mod4) {
            $string .= substr('====', $mod4);
        }

        return base64_decode($string);
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
            'VINAPHONE'    => array('91', '94', '123', '124', '125', '127', '129', '88', '81', '82', '83', '84', '85'),
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
        //            if(substr($msisdn, 0, 6) == '841290'){
        //                $now = date('Y-m-d H:i:s');
        //                $accept_date = date('Y-m-d H:i:s', strtotime('2018-08-17 23:00:00'));
        //                if($now >= $accept_date){
        //                    $msisdn = '84820' . substr($msisdn, 6);
        //                }
        //            }
        // đổi đầu số
        if (substr($msisdn, 0, 7) == '8416966') {
            $now = date('Y-m-d H:i:s');
            $accept_date = date('Y-m-d H:i:s', strtotime('2018-09-14 00:00:00'));
            if ($now >= $accept_date) {
                $msisdn = '843966' . substr($msisdn, 7);
            }
        } else {
            $msisdn = CFunction::convertNewMsisdn($msisdn, true, false);
        }
        //./END đổi đầu số
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

    public static function getHours($mode_24 = FALSE)
    {
        if ($mode_24) {
            return array(
                0   => '00',
                1   => '01',
                2   => '02',
                3   => '03',
                4   => '04',
                5   => '05',
                6   => '06',
                7   => '07',
                8   => '08',
                9   => '09',
                10  => '10',
                11  => '11',
                12  => '12',
                13  => '13',
                14  => '14',
                15  => '15',
                16  => '16',
                17  => '17',
                18  => '18',
                19  => '19',
                20  => '20',
                21  => '21',
                22  => '22',
                23  => '23',
            );
        } else {
            return array(
                1   => '01 AM',
                2   => '02 AM',
                3   => '03 AM',
                4   => '04 AM',
                5   => '05 AM',
                6   => '06 AM',
                7   => '07 AM',
                8   => '08 AM',
                9   => '09 AM',
                10  => '10 AM',
                11  => '11 AM',
                12  => '12 AM',
                13  => '01 PM',
                14  => '02 PM',
                15  => '03 PM',
                16  => '04 PM',
                17  => '05 PM',
                18  => '06 PM',
                19  => '07 PM',
                20  => '08 PM',
                21  => '09 PM',
                22  => '10 PM',
                23  => '11 PM',
                0   => '12 PM',
            );
        }
    }

    public static function getMinutes()
    {
        $minutes = array();
        for ($i = 0; $i < 60; $i++) {
            $minute = $i;
            if ($i < 10) {
                $minute = '0' . $minute;
            }
            $minutes[] = $minute;
        }
        return $minutes;
    }

    public static  function exportCSV($file_name, $data)
    {
        header("Content-Type:text/csv, charset=utf-8"); // Config header utf-8.
        header("Content-Disposition:attachment;filename=$file_name.csv");
        $output = fopen("php://output", 'w') or die("Can't open php://output");
        fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); // Config input utf-8

        foreach ($data as $line) {
            //Input each row in csv.
            fputcsv($output, $line);
        }
        fclose($output) or die("Can't close php://output");
    }

    /**
     * @param $prefix string | array
     * @param $phone string
     * @return bool
     * Ex: $prefix: '01290' | array('01290', '841290') ; $phone: '01290123456' => return TRUE;
     */
    public static function checkPrefix($prefix, $phone)
    {
        if (is_array($prefix)) {
            foreach ($prefix as $pre) {
                if (substr($phone, 0, strlen($pre)) == $pre) {
                    return TRUE;
                }
            }
            return FALSE;
        } else
            return (substr($phone, 0, strlen($prefix)) == $prefix) ? TRUE : FALSE;
    }

    /**
     * @param $prefix string
     * @param $phone string
     * @return string
     * Ex: $prefix: '0820' ; $phone: '01290123456' => return 082123456;
     */
    public static function changePrefix($prefix, $phone)
    {
        return $prefix . substr($phone, strlen($prefix));
    }

    public static function getTokenPass(UserLogin $model)
    {

        $password_md5 = md5($model->password);
        $password_sub = substr($password_md5, 0, 16);
        Yii::app()->user->setState('password_sub', $password_sub);
        $token = $model->apiLogin($model->username, $password_sub);

        return $token;
    }
    /*
         * Check số điện thoại có phải của VINA không thông qua API java
         */

    public static function getInfoPhone($data)
    {
        $type = 'web_get_msisdn_info';
        $id   = Yii::app()->request->csrfToken;
        $arr_param = array(
            'type' => $type,
            'id'   => $id,
            'data' => CJSON::encode($data),
        );
        $str_json  = CJSON::encode($arr_param);
        //call api
        $response = Utils::cUrlPostJson($GLOBALS['config_common']['api']['hostname'], $str_json, FALSE, 45, $http_code);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {
            $status = $arr_response['status'];

            return $status;
        }

        return FALSE;
    }

    public static function verifyPayment($price_term, $paymentmethod)
    {
        $payment_method = 0;
        if (isset($price_term) && $price_term > 0 && $payment_method == 4) {
            if (!Yii::app()->user->hasFlash('danger')) {
                Yii::app()->user->setFlash('danger', Yii::t('web/portal', 'payment_method_cod'));
            }
        }
        return true;
    }


    /**
     *  Lấy tất cả các ngày nằm trong khoảng thời gian
     *
     * @param $start_date
     * @param $end_date
     * @param $optional : return array(date, day_of_week, day_num)
     * @return array
     */
    public static function getListDate($start_date, $end_date, $optional = false)
    {
        $arr_date = array();
        $period = new DatePeriod(
            new DateTime($start_date),
            new DateInterval('P1D'),
            new DateTime($end_date)
        );
        foreach ($period as $key => $value) {
            if ($optional) {
                $val = $value->format('Y-m-d');
                $arr_date[] = array(
                    'date' => $val,
                    'day_of_week' => date('w', strtotime($val)) + 1,
                    'day_num' => strtolower(date("d", strtotime($val)))
                );
            } else {
                $arr_date[] = $value->format('Y-m-d');
            }
        }
        return $arr_date;
    }

    /**
     * @param $number
     * chuyển đổi số sang chu
     * @return bool|mixed|null|string
     */
    public static function convert_number_to_words($number)
    {
        $hyphen = ' ';
        $conjunction = '  ';
        $separator = ' ';
        $negative = 'âm ';
        $decimal = ' phẩy ';
        $dictionary = array(
            0 => 'Không',
            1 => 'Một',
            2 => 'Hai',
            3 => 'Ba',
            4 => 'Bốn',
            5 => 'Năm',
            6 => 'Sáu',
            7 => 'Bảy',
            8 => 'Tám',
            9 => 'Chín',
            10 => 'Mười',
            11 => 'Mười một',
            12 => 'Mười hai',
            13 => 'Mười ba',
            14 => 'Mười bốn',
            15 => 'Mười năm',
            16 => 'Mười sáu',
            17 => 'Mười bảy',
            18 => 'Mười tám',
            19 => 'Mười chín',
            20 => 'Hai mươi',
            30 => 'Ba mươi',
            40 => 'Bốn mươi',
            50 => 'Năm mươi',
            60 => 'Sáu mươi',
            70 => 'Bảy mươi',
            80 => 'Tám mươi',
            90 => 'Chín mươi',
            100 => 'trăm',
            1000 => 'ngàn',
            1000000 => 'triệu',
            1000000000 => 'tỷ',
            1000000000000 => 'nghìn tỷ',
            1000000000000000 => 'ngàn triệu triệu',
            1000000000000000000 => 'tỷ tỷ'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error('convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING);
            return false;
        }

        if ($number < 0) {
            return $negative . Utils::convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int)($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . Utils::convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = Utils::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= Utils::convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
        return $string;
    }

    /**
     * Convert array to standart object
     */
    public static function array_to_obj($array, &$obj)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $obj->$key = new stdClass();
                self::array_to_obj($value, $obj->$key);
            } else {
                $obj->$key = $value;
            }
        }
        return $obj;
    }

    public static function aryToObj($array)
    {
        $obj = new stdClass();
        return self::array_to_obj($array, $obj);
    }
}
