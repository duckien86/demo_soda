<?php

    /**
     * My mobile detech class extend from Mobile_detect class
     * I use for some purposes.
     *
     * @author  thienhaxanh2405 <nguyenductoan2405@gmail.com>
     *
     * @license follow Mobile_detect library <http://mobiledetect.net/>
     *
     * @link    thienhaxanh <http://thienhaxanh.info>
     *
     * @version 1.0.0
     *
     */
    class MyMobileDetect extends Mobile_Detect
    {
        const OS_ANDROID   = "android";
        const OS_IOS       = "ios";
        const OS_SYMBIANOS = "symbian_os";
        const OS_DESKTOP   = "desktop";

        /**
         * Is iphone device
         *
         * @return bool
         */
        public function IsiPhoneDevice()
        {
            if ($this->isMobile() && $this->isiPhone() && $this->isiOS()) {
                return TRUE;
            } else {
                return FALSE;
            }
        }// end function is device android


        /**
         * Is Android device
         *
         * @return bool
         */
        public function IsAndroidDevice()
        {
            if ($this->isMobile() && $this->isAndroidOS() && $this->isSafari()) {
                return TRUE;
            } else {
                return FALSE;
            }
        } //end function is android device


        /**
         * Is windows phone device
         *
         * @return bool
         */
        public function IsWindowsPhoneDevice()
        {
            if ($this->isMobile() && $this->isWindowsPhoneOS() && $this->isIE()) {
                return TRUE;
            } else {
                return FALSE;
            }
        } // end functoin is window phone device

        /**
         * code in the future
         */
        public function IsBlackberryMobile()
        {

        }

        /*
         * Function Get Current mobile Os Manh.tv
         */
        public function getOs()
        {
            $all_os = self::getOperatingSystems();
            $detect = new self();
            if ($detect->isMobile()) {
                return Yii::app()->session['device_os'];
            }

            return '';
        }

        /*
       * Function Get Current mobile Os version. Manh.tv
       */
        public function getOsVersion()
        {
            $all_os = self::getOperatingSystems();
            $detect = new self();
            if ($detect->isMobile()) {
                return Yii::app()->session['os_version'];
            }

            return '';
        }
    }


?>