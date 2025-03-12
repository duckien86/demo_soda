<?php
    return array(
        'facebook_sdk_path'     => dirname(__DIR__) . '/../../vendors/facebook-sdk/autoload.php',
        'facebook'              => array(
            'app_id'     => '1102943073067888',
            'app_secret' => 'a9d5b9059484c4559cf3d55d5a6677a8',
            'cookie'     => TRUE
        ),
        'facebookPermissions'   => array('email', 'public_profile', 'user_birthday', 'user_friends'),
        'collection_categories' => array(
            'uncategory' => 0,
        ),

        'telco_list' => array(
            'VIETTEL'      => 'Viettel',
            'MOBIFONE'     => 'Mobifone',
            'VINAPHONE'    => 'Vinaphone',
            'VIETNAMOBILE' => 'Vietnam Mobile',
            'ZING'         => 'Zing',
            'FPT'          => 'FPT Gate',
            'ONCASH'       => 'On Cash',
            'MEGACARD'     => 'Megacard',
        ),

        'telco_rules' => array(
            'VIETTEL'      => array('serial' => array(11, 15), 'pin' => array(13, 15)),
            'MOBIFONE'     => array('serial' => 3, 'pin' => 14),
            'VINAPHONE'    => array('serial' => 3, 'pin' => 12),
            'VIETNAMOBILE' => array('serial' => 3, 'pin' => 12),
            'ZING'         => array('serial' => 3, 'pin' => 12),
            'FPT'          => array('serial' => 3, 'pin' => 10),
            'ONCASH'       => array('serial' => 3, 'pin' => 12),
            'MEGACARD'     => array('serial' => 0, 'pin' => 12),
        ),

        'url_payment_centech' => 'http://pay.centech.com.vn',
        'cp_id'               => '015',
        'upload_dir'          => 'uploads/',
        'verify_config'       => array(
            'verify_number' => 2,
            'times_reset'   => 3,
        ),
        'sendEmail'           => array(
            'host'     => 'smtp.vnpt.vn',
            'username' => 'freedoo@vnpt.vn',
            'password' => 'Kdol@123',
            'port'     => 25,
            'type'     => '',
        ),
        //'socket_api_url'      => 'ws://10.2.0.159:20979/api',
//        'socket_api_url'      => 'ws://10.2.0.240:20979/api',
        'socket_api_url'      => 'ws://10.2.0.107:20979/api',
//        'socket_api_url'      => 'ws://127.0.0.1:20993', //server config

    );
