<?php
    $web  = dirname(dirname(__FILE__));
    $base = dirname($web);
    Yii::setPathOfAlias('web', $web);
    $baseArray = require($base . '/config/main.php');
    $webArray  = array(
        'basePath'          => $base,
        'preload'           => array(
            'log',
            'yiibooster',
        ),
        'controllerPath'    => $web . '/controllers',
        'viewPath'          => $web . '/views',
        'runtimePath'       => $web . '/runtime',
        'defaultController' => 'site',
        'import'            => array(
            'web.models.*',
            'web.components.*',
            'application.models.*',
            'application.components.*',
            'application.extensions.yiibooster.*',
            'application.extensions.YiiMailer.*',
            'application.extensions.validators.*',
        ),
        'language'          => 'vi',
        'params'            => require(dirname(__FILE__) . '/params.php'),
        'components'        => array(
            'errorHandler' => array(
                'errorAction' => 'site/error',
            ),
            'request'      => array(
                'enableCsrfValidation'   => TRUE,
                'enableCookieValidation' => TRUE,
                'class'                  => 'HttpRequest',
                'noCsrfValidationRoutes' => array( // config mang nhung action ko can check csrf
                    'receiver/confirmPayment',
                    'receiver/result',
                    'receiver/confirmPaymentVtbDomestic',
                    'receiver/confirmPaymentVnptpay',
                    'checkout/receipt',
                    'apisgw/regpackage',
                    'apisgw/confirmotp',
                    'apisgw/queryDrTransaction',
                    'apisgw/checkout',
                    'receiver/updateOrderStatus',
                    // new api for zalo, icom
                    'apisgw/searchMsisdn',
                    'apisgw/keepMsisdn',
                    'apisgw/checkoutOrder',
                    'apisgw/SendOtpRx',
                    'apisgw/RegisterRx',
                    'apisgw/GetPackages',
                ),
            ), //en

            'yiibooster' => array(
                'class' => 'ext.yiibooster.components.Booster', // assuming you extracted bootstrap under extensions
            ),
            'user'       => array(
                'class'          => 'WebUser',
                // enable cookie-based authentication
                'allowAutoLogin' => TRUE,
                'identityCookie' => array(
                    'httpOnly' => TRUE,
                )
            ),
            // uncomment the following to enable URLs in path-format
            'urlManager' => array(
                'urlFormat'      => 'path', // 'path' or 'get'
                'showScriptName' => FALSE, // show index.php
                'caseSensitive'  => FALSE, // case sensitive
                'urlSuffix'      => '.html',
                'rules'          => array(
                    ''                                            => 'site/index',
                    'survey'                                      => 'survey/index',
                    'sitemap'                                     => 'site/sitemap',
                    'dang-ky-fiber'                               => 'package/fibervnn',
                    'dang-ky-goi-cuoc-internet-cap-quang'                           => 'package/registerfibervnn',
                    'dang-ky-goi-cuoc-internet-cap-quang-tq/<package:\w+>'          => 'package/registerfiberallprovince',
                    'dang-ky-goi-cuoc-internet-truyen-hinh/<package:\w+>'           => 'package/registercombo',
                    'dang-ky-goi-cuoc-internet-truyen-hinh-tq/<package:\w+>'        => 'package/registercomboallprovince',
                    'dang-ky-goi-cuoc-combo/<package:\w+>'        => 'package/registerHomeBundle',
                    'thoat'                                       => 'site/logout',
                    'thong-tin-ca-nhan'                           => 'site/profile',
                    'get-point/<usr:\w+>'                         => 'site/getPoint',
                    'gioi-thieu'                                  => 'site/about',
                    'cac-kenh-ho-tro'                             => 'site/supportChannel',
                    '9-dieu-khoan-va-dieu-kien-giao-dich'         => 'site/termCondition',
                    'chinh-sach-giao-nhan'                        => 'site/deliveryPolicy',
                    'quy-dinh-ve-hinh-thuc-thanh-toan'            => 'site/regulationsPayment',
                    'chinh-sach-doi-tra-hang-va-hoan-tien'        => 'site/returnPolicy',
                    'chinh-sach-bao-mat-thanh-toan'               => 'site/paymentSecurity',
                    'chinh-sach-bao-mat-thong-tin-ca-nhan'        => 'site/profileSecurity',
                    'chinh-sach-thanh-toan'                       => 'site/paymentPolicy',
                    'chinh-sach-doi-tra-hang-va-hoan-tien'        => 'site/changeProduct',
                    'tro-giup-app'                                => 'site/helpapp',
                    '<id:\d+>-<title:.*>'                         => 'site/news',
                    'sim-so'                                      => 'sim/index',
                    'them-vao-gio-hang'                           => 'sim/addtocart',
                    'response'                                    => 'checkout/response',
                    'checkout'                                    => 'checkout/checkout',
                    'checkout-step2'                              => 'checkout/checkout2',
                    'checkout-step3'                              => 'checkout/checkout3',
                    'xac-nhan'                                    => 'checkout/verifyTokenKey',
                    'xac-nhan-otp'                                => 'checkoutapi/verifyTokenKey',
                    'huong-dan-thanh-toan-qr-code'                => 'checkout/guideQrCode',
                    'vtb-directpay-result'                        => 'checkout/confirm',
                    'xac-nhan-thanh-toan'                         => 'checkout/confirmVietinbank',
                    'goi-cuoc'                                    => 'package/index',
                    'goi-cuoc-internet-cap-quang'                 => 'package/indexfiber',
                    'goi-cuoc-truyen-hinh-mytv'                   => 'package/indexmytv',
                    'goi-cuoc-internet-truyen-hinh'               => 'package/indexCombo',
                    'goi-cuoc-combo'                        => 'package/indexHomeBundle',
                    'danh-muc-goi-cuoc/<id:\d+>-<title:.*>'       => 'package/category',
                    'chi-tiet-goi/<slug:.+>'                      => 'package/detail',
                    'dang-ky-goi/<package:\w+>'                   => 'package/register',
                    'dang-ky-goi-cuoc-internet-cap-quang/<package:\w+>'             => 'package/registerfibers',
                    'dang-ky-goi-cuoc-truyen-hinh-mytv/<package:\w+>'              => 'package/registermytv',
                    'goi-linh-hoat'                               => 'package/packageFlexible',
                    'danh-sach-goi-cuoc-chuyen-doi/<package:\w+>' => 'package/listChangePackage',
                    'kit-tra-sua'                                 => 'package/kitTraSua',
                    'kit-banh-my'                                 => 'package/kitBanhMy',
                    'mua-ma-the'                                  => 'card/buycard',
                    'nap-the'                                     => 'card/topup',
                    'orderresult'                                 => 'card/orderresult',
                    'accesscard'                                  => 'card/accesscard',
                    'card-checkout-step2'                         => 'card/checkout2',
                    'ho-tro'                                      => 'help/index',
                    'huong-dan-mua-hang'                          => 'help/supportSell',
                    'huong-dan-san-pham'                          => 'help/supportProduct',
                    'buy-card-step2'                              => 'card/payment',
                    'dich-vu-cua-toi'                             => 'orders/index',
                    'tra-cuu-don-hang'                            => 'orders/searchOrder',
                    'chi-tiet-don-hang/<id:\d+>'                  => 'orders/detail',
                    'vtb-confirm-payment'                         => 'receiver/confirmPayment',
                    'vtb-confirm-payment-test'                    => 'receiver/confirmPayment',
                    'link/<token:\w+>'                            => 'receiver/customerLink',
                    'update-order-status'                         => 'receiver/updateOrderStatus',
                    'landing-page'                                => 'landing/index',
                    'goi-cuoc-roaming'                            => 'roaming/index',
                    'tin-tuc'                                     => 'news/index',
                    'tin-tuc/<slug:([A-Za-z0-9-_]*)>-<id:\d+>'    => 'news/view',
                    'bo-kit'                                      => 'simkit/index',
                    'goi-kit/<id:\w+>'                            => 'simkit/detail',
                    '<controller:\w+>/<action:\w+>'               => '<controller>/<action>',


                ),
            ),
            // uncomment the following to enable the Gii tool
            'gii'    => array(
                'class'          => 'system.gii.GiiModule',
                'password'       => '123456',
                'ipFilters'      => array('127.0.0.1', '::1', '10.2.0.*', '192.168.6.*'),
                'generatorPaths' => array('bootstrap.gii'),
            ),
        ),

        'theme' => 'default',
    );
    if (!function_exists('w3_array_union_recursive')) {
        /**
         * This function does similar work to $array1+$array2,
         * except that this union is applied recursively.
         *
         * @param array $array1 - more important array
         * @param array $array2 - values of this array get overwritten
         *
         * @return array
         */
        function w3_array_union_recursive($array1, $array2)
        {
            $retval = $array1 + $array2;
            foreach ($array1 as $key => $value) {
                if (isset($array2[$key]) && isset($array2[$key]) && is_array($array1[$key]) && is_array($array2[$key])) {
                    $retval[$key] = w3_array_union_recursive($array1[$key], $array2[$key]);
                }
            }

            return $retval;
        }

    }

    return w3_array_union_recursive($webArray, $baseArray);
?>