<?php
    return array(
        'hashkey'      => 'centech',
        'project_name' => 'VNPT Online',
        'brand_name'   => 'Admin CP',
        'password_key' => 'centech24072013',

        'pagination'                => array(
            'defaultPageSize' => 30,
            'arrPageSize'     => array(10 => 10, 20 => 20, 30 => 30, 50 => 50, 100 => 100),
        ),
        'sendEmail'                 => array(
            'host'     => 'smtp.vnpt.vn',
            'username' => 'freedoo@vnpt.vn',
            'password' => 'S@p6rkm6',
            'port'     => 25,
            'type'     => '',
        ),

        //live
        /*
        'socket_api_url'            => 'http://192.168.100.9:8287',
        'socket_api_app'            => 'http://192.168.100.9:8387',
        */

        //test
        /*
        'socket_api_url'            => 'http://118.70.177.77:8288/',
        'socket_api_app'            => 'http://118.70.177.77:8288/',
        */
        'socket_api_url'            => 'http://10.2.0.107:8285/',
        'socket_api_app'            => 'http://10.2.0.107:8286/',

        'api_notify_assign_shipper' => 'https://fcm.googleapis.com/fcm/send',
        'api_get_url_image'         => 'https://uploads.freedoo.centech.com.vn/imageuploader.php',

        'sessionTimeout' => 1200, // 20 minute
        'card_value'     => array(
            '10000'  => '10.000đ',
            '20000'  => '20.000đ',
            '30000'  => '30.000đ',
            '50000'  => '50.000đ',
            '100000' => '100.000đ',
            '200000' => '200.000đ',
            '300000' => '300.000đ',
            '500000' => '500.000đ',
        ),

        'sessionTimeout'   => 1200, // 20 minute
        //list package checkout page (sim prepaid)
        'checkout_prepaid' => array(
            '9HZBFBYt47KhGpk2Etp1SkiIgHzDDViD',//happy
            'Hb8PqMohTCoyUQLtMr2Y8aCElSnNo5pV',//club
            'pDa489iAWzn01zrSQOPuPlshjfT2hYBx',//vd89
        ),
        'student_package' => array(
            'SPS_PRODUCT_HEYZB30' // heyz
        ),
        'CellPhoneS'       => 99,
    );
?>
