<?php

    class AFunction
    {
        public static function number_format($string, $decimals = 0, $dec_sep = ",", $thous_sep = ".")
        {
            $ret = '0';
            if ($string != '')
                $ret = number_format($string, $decimals, $dec_sep, $thous_sep);

            return $ret;
        }

        public static function generate_file_name($type = 'file', $baseUploadPath = '/uploads/')
        {
            $file_info = array();
            $curr_info = date("Y,m,d,H,i,s");
            list($year, $month, $day, $hour, $minute, $second) = @split(",", $curr_info);
            $filename                   = $year . $month . $day . $hour . $minute . $second . AFunction::random_generator(5);
            $file_info['name']          = $filename;
            $file_info['host_path']     = $baseUploadPath . $type . "/$year/$month";
            $file_info['physical_path'] = $type . '/' . $year . "/$month";

            return $file_info;
        }
        public static function generate_file_name_ftp($type = 'file')
        {
            $file_info = array();
            $curr_info = date("Y,m,d,H,i,s");
            list($year, $month, $day, $hour, $minute, $second) = @split(",", $curr_info);
            $filename                   = $year . $month . $day . $hour . $minute . $second . AFunction::random_generator(5);
            $file_info['name']          = $filename;
            $file_info['host_path']     = $type . "/$year/$month/";
            $file_info['physical_path'] = $type . "/$year/$month/$day/";
            return $file_info;
        }

        public static function random_generator($digits)
        {
            srand((double)microtime() * 10000000);
            $input = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
            $temp  = "";
            for ($i = 1; $i < $digits + 1; $i++) {
                if (rand(1, 2) == 1) {
                    $rand_index = array_rand($input);
                    $temp .= $input[$rand_index];
                } else {
                    $temp .= rand(0, 9);
                }

            }

            return $temp;
        }

        public static function getPagerInfo($count, $cpage, $rowsperpage = FALSE)
        {
            $pInfo               = array();
            $pInfo['cpage']      = $cpage;
            $pInfo['count']      = $count;
            $pInfo['limit']      = ($rowsperpage === FALSE) ? Yii::app()->params['m_rows_per_page'] : $rowsperpage;
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

        public static function truncate($string, $length = 80, $etc = '...',
                                        $break_words = FALSE, $middle = FALSE)
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

        public static function get_subfolders_name($path, $file = FALSE)
        {

            $list    = array();
            $results = scandir($path); //var_dump($results);exit();
            foreach ($results as $result) {
                if ($result === '.' or $result === '..' or $result === '.svn') continue;
                if (!$file) {
                    if (is_dir($path . '/' . $result)) {
                        $list[] = trim($result);
                    }
                } else {
                    if (is_file($path . '/' . $result)) {
                        $list[] = trim($result);
                    }
                }
            }

            return $list;
        }

        public static function recursive_remove_directory($directory, $empty = FALSE)
        {
            // if the path has a slash at the end we remove it here
            if (substr($directory, -1) == '/') {
                $directory = substr($directory, 0, -1);
            }

            // if the path is not valid or is not a directory ...
            if (!file_exists($directory) || !is_dir($directory)) {
                // ... we return false and exit the function
                return FALSE;

                // ... if the path is not readable
            } elseif (!is_readable($directory)) {
                // ... we return false and exit the function
                return FALSE;

                // ... else if the path is readable
            } else {

                // we open the directory
                $handle = opendir($directory);

                // and scan through the items inside
                while (FALSE !== ($item = readdir($handle))) {
                    // if the filepointer is not the current directory
                    // or the parent directory
                    if ($item != '.' && $item != '..') {
                        // we build the new path to delete
                        $path = $directory . '/' . $item;

                        // if the new path is a directory
                        if (is_dir($path)) {
                            // we call this function with the new path
                            self::recursive_remove_directory($path);

                            // if the new path is a file
                        } else {
                            // we remove the file
                            unlink($path);
                        }
                    }
                }
                // close the directory
                closedir($handle);

                // if the option to empty is not set to true
                if ($empty == FALSE) {
                    // try to delete the now empty directory
                    if (!rmdir($directory)) {
                        // return false if not possible
                        return FALSE;
                    }
                }

                // return success
                return TRUE;
            }
        }

        public static function getDatesFromRange($startDate, $endDate)
        {
            $return = array($startDate);
            $start  = $startDate;
            $i      = 1;
            if (strtotime($startDate) < strtotime($endDate)) {
                while (strtotime($start) < strtotime($endDate)) {
                    $start    = date('Y-m-d', strtotime($startDate . '+' . $i . ' days'));
                    $return[] = $start;
                    $i++;
                }
            }

            return $return;
        }

        /**
         *  unserialize string data to readable string
         *  return string
         */
        public static function serializeToStr($data)
        {
            $ary_str = '';
            if ($data != '' && $data != NULL) {
                $ary_data = unserialize($data);
                if (is_array($ary_data)) {
                    $ary_data = unserialize($data);
                    $glue     = ', ';
                    $ary_str  = implode($glue, $ary_data);
                }
            }

            return $ary_str;
        }

        /**
         * generate public key and private key
         */
        public static function generatePairKeys()
        {
            // generate 2048-bit RSA key
            $pkGenerate = openssl_pkey_new(array(
                'private_key_bits' => 2048,
                'private_key_type' => OPENSSL_KEYTYPE_RSA
            ));
            $key_ary    = array();
            // get the private key
            openssl_pkey_export($pkGenerate, $pkGeneratePrivate); // NOTE: second argument is passed by reference
            if ($pkGeneratePrivate != '') {
                $key_ary['private_key'] = $pkGeneratePrivate;
            }

            // get the public key
            $pkGenerateDetails = openssl_pkey_get_details($pkGenerate);
            $pkGeneratePublic  = $pkGenerateDetails['key'];
            if ($pkGeneratePublic != '') {
                $key_ary['public_key'] = $pkGeneratePublic;
            }

            return $key_ary;
        }

        /*
         * Encrypt password
         * Return encrypted data string
         */
        public static function encryptPassword($password, $hashKey)
        {
            return md5($hashKey . $password);
        }

        /**
         * method to encrypt a plain text string
         * initialization vector has to be the same when encrypting and decrypting
         * you can also choose to append the IV to the encrypted text and get it when decrypting
         *
         * @param string $action : can be 'encrypt' or 'decrypt'
         * @param string $string : string to encrypt or decrypt
         *
         * @return bool|string
         */
        public static function encryptPasswordKey($string)
        {
            $output = FALSE;

            $key = Yii::app()->params->password_key;

            // initialization vector
            $iv = md5(md5($key));


            $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, $iv);
            $output = base64_encode($output);

            return $output;
        }

        /**
         * method to decrypt a plain text string
         * initialization vector has to be the same when encrypting and decrypting
         * you can also choose to append the IV to the encrypted text and get it when decrypting
         *
         * @param string $action : can be 'encrypt' or 'decrypt'
         * @param string $string : string to encrypt or decrypt
         *
         * @return bool|string
         */

        public static function decryptPasswordKey($string)
        {
            $output = FALSE;

            $key = Yii::app()->params->password_key;

            // initialization vector
            $iv = md5(md5($key));

            $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
            $output = rtrim($output, "");

            return $output;
        }

        public static function genListData($model, $value, $label)
        {
            if ($model != NULL) {
                $list = CHtml::listData($model, $value, $label);
            }

        }
        public static function is_not_number($input)
        {
            if (is_numeric($input)) {
                return false;
            }
            return true;
        }
        public static function formatSizeUnits($bytes)
        {
            if ($bytes >= 1073741824)
            {
                $bytes = number_format($bytes / 1073741824, 1) . ' GB';
            }
            elseif ($bytes >= 1048576)
            {
                $bytes = number_format($bytes / 1048576, 1) . ' MB';
            }
            elseif ($bytes >= 1024)
            {
                $bytes = number_format($bytes / 1024, 1) . ' KB';
            }
            elseif ($bytes > 1)
            {
                $bytes = $bytes . ' bytes';
            }
            elseif ($bytes == 1)
            {
                $bytes = $bytes . ' byte';
            }
            else
            {
                $bytes = '0 bytes';
            }

            return $bytes;
        }
        public static function secondsToTime($seconds)
        {
            // extract hours
            $hours = floor($seconds / (60 * 60));

            // extract minutes
            $divisor_for_minutes = $seconds % (60 * 60);
            $minutes = floor($divisor_for_minutes / 60);

            // extract the remaining seconds
            $divisor_for_seconds = $divisor_for_minutes % 60;
            $seconds = ceil($divisor_for_seconds);
            if($hours<10){$hours='0'.$hours;}
            if($minutes<10){$minutes='0'.$minutes;}
            if($seconds<10){$seconds='0'.$seconds;}
            // return the final array
            if($hours>0){
              return $hours.":".$minutes.":".$seconds."";
            }elseif($minutes>0){
                return $minutes.":".$seconds."";
            }else{
                return$seconds."";
            }
        }
        public  static function get_file_ext($file){
            return strtolower( str_replace( ".", "", substr( $file, strrpos( $file, '.' ) ) ) );
        }
        public static function add_session($session_key,$arr_key,$session_value){
            $session = Yii::app()->session;
            if (!isset($session[$session_key]) || count($session[$session_key])==0)
            {
                $session[$session_key] = array($arr_key=>$session_value);
            }
            else {
                $myarr = $session[$session_key];
                $myarr[$arr_key] = $session_value;
                $session[$session_key] = $myarr;
            }
        }
        public static function usset_session($session_key){
            unset(Yii::app()->session[$session_key]);
        }

    }

?>