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
            'password' => 'kdol1234a@',
            'port'     => 465,
            'type'     => 'tls',
        ),
        'socket_api_url'            => 'http://192.168.100.8:8387',
        'api_notify_assign_shipper' => 'https://fcm.googleapis.com/fcm/send',
        'api_get_url_image'         => 'https://uploads.freedoo.centech.com.vn/imageuploader_beta.php',

        'sessionTimeout' => 1200, // 20 minute
    );
?>