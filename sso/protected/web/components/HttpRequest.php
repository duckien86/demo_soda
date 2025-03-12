<?php

    class HttpRequest extends CHttpRequest
    {
        public $noCsrfValidationRoutes = array();

        public function validateCsrfToken($event)
        {
            $url = Yii::app()->getUrlManager()->parseUrl($this);
            foreach ($this->noCsrfValidationRoutes as $route) {
                if ($url == $route)
                    return TRUE;
            }

            return parent::validateCsrfToken($event);
        }
    }

?>