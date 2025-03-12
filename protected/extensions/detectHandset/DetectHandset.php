<?php
    $name = preg_match("/host=([^;]*)/", Yii::app()->db->connectionString, $matches);
    define("DB_HOST", $matches[1]);
    define("DB_USER", Yii::app()->db->username);
    define("DB_PASS", Yii::app()->db->password);
    define("DB_PREFIX", 'tw');
    $name = preg_match("/dbname=([^;]*)/", Yii::app()->db->connectionString, $matches);
    define("DB_SCHEMA", $matches[1]);
    require_once realpath(dirname(__FILE__).'/TeraWurfl.php');
    require_once realpath(dirname(__FILE__).'/TeraWurflUtils/TeraWurflDeviceImage.php');

    class DetectHandset
    {
        const TABLET      = "tablet";
        const SMART_PHONE = "smartphone";
        const WEBSITE     = "website";
        const WAPSITE     = "wapsite";
        const OS_ANDROID  = "Android";
        const OS_IOS      = "IOS";
        public $user_agent;
        public $model;
        public $branch;
        public $capabilities;
        public $device_type;
        public $image_html;
        public $image_src;
        public $is_android    = false;
        public $is_ios        = false;
        public $os_version    = "";
        public $device_os;
        public $maketing_name = "";

        public $width;
        public $height;


        public static $userAgentHeaders = array(
            'HTTP_X_DEVICE_USER_AGENT',
            'HTTP_X_ORIGINAL_USER_AGENT',
            'HTTP_X_OPERAMINI_PHONE_UA',
            'HTTP_X_SKYFIRE_PHONE',
            'HTTP_X_BOLT_PHONE_UA',
            'HTTP_USER_AGENT',
        );

        public function detect($ua = null)
        {
            $this->user_agent = self::getUserAgent($ua);
            $wurflObj         = new TeraWurfl();
            $matched          = $wurflObj->GetDeviceCapabilitiesFromAgent($this->user_agent);

            $this->capabilities  = $wurflObj->capabilities['product_info'];
            $this->width         = $wurflObj->capabilities['display']['resolution_width'];
            $this->height        = $wurflObj->capabilities['display']['resolution_height'];
            $this->model         = $this->capabilities['model_name'];
            $this->maketing_name = $this->capabilities['marketing_name'];
            $this->branch        = $this->capabilities['brand_name'];
            if ($this->capabilities["is_tablet"]) {
                $this->device_type = self::TABLET;
            } elseif ($this->capabilities["is_wireless_device"]) {
                if ($this->capabilities["pointing_method"]=='touchscreen') {
                    $this->device_type = self::SMART_PHONE;
                } else {
                    $this->device_type = self::WAPSITE;
                }
            } else {
                $this->device_type = self::WEBSITE;
            }
            $this->device_os = $this->capabilities['device_os'];
            if ($this->capabilities['device_os']==self::OS_ANDROID) {
                $this->is_android = true;
                $this->os_version = $this->capabilities['device_os_version'];
            }

            if (trim(strtoupper($this->capabilities['device_os']))==self::OS_IOS) {
                $this->is_ios     = true;
                $this->os_version = $this->capabilities['device_os_version'];
            }

            $image = new TeraWurflDeviceImage($wurflObj);
            if (!is_dir(realpath(Yii::app()->getBasePath().'/../').'/uploads/device_picture/')) {
                mkdir(realpath(Yii::app()->getBasePath().'/../').'/uploads/device_picture/', 0777, true);
            }
            $image->setBaseURL('/uploads/device_picture/');
            $image->setImagesDirectory(realpath(Yii::app()->getBasePath().'/../').'/uploads/device_picture/');
            $image_src = $image->getImage();
            if ($image_src) {
                $this->image_src  = $image_src;
                $this->image_html = sprintf('<img src="%s" border="0"/>', $image_src);
            } else {
                $this->image_src  = "";
                $this->image_html = "No image available";
            }
        }

        public static function getUserAgent($source = null)
        {
            if (is_null($source)) {
                $source = $_SERVER;
            } else {
                return $source;
            }
            $userAgent = '';
            foreach (self::$userAgentHeaders as $header) {
                if (array_key_exists($header, $source) && $source[$header]) {
                    $userAgent = $source[$header];
                    break;
                }
            }

            return $userAgent;
        }

    }

?>