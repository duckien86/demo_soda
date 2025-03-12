<?php
    return array(
        'facebook_sdk_path'   => dirname(__DIR__) . '/../../vendors/facebook-sdk/autoload.php',
        'facebook'            => array(
            'app_id'     => '1102943073067888',
            'app_secret' => 'a9d5b9059484c4559cf3d55d5a6677a8',
            'cookie'     => TRUE
        ),
        'facebookPermissions' => array('email', 'public_profile', 'user_birthday', 'user_friends'),

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
        'card_value'  => array(
            '10000'  => '10.000đ',
            '20000'  => '20.000đ',
            '30000'  => '30.000đ',
            '50000'  => '50.000đ',
            '100000' => '100.000đ',
            '200000' => '200.000đ',
            '300000' => '300.000đ',
            '500000' => '500.000đ',
        ),

        'url_payment_centech'    => 'http://pay.centech.com.vn',
        'cp_id'                  => '015',
        'upload_dir'             => 'uploads/',
        'ftp_vtb'                => 'ftp/vietinbank/',
        'vtb_out'                => 'VIETINBANK_OUT/',
        'vtb_in'                 => 'VIETINBANK_IN/',
        'verify_config'          => array(
            'verify_number'      => 2,
            'times_reset'        => 3,//3 minute
            'apisgw_times_reset' => 5,//5 minute
            'send_otp_number'    => 3,
        ),
        'sessionTimeout'         => 1200, // 20 minute
        'sessionTimeoutApi'      => 600, // 5 minute
        'sessionTimeout_package' => 180, // 3 minute
        'url_oneid'              => 'http://sso.dev/',

        'aes_key'        => '0123456789abcdefghik',

//        'url_domain'     => 'http://vnptonline.portal.dev',
        'url_domain'     => 'http://10.2.0.107:8694/vnpt_online/portal/source/',
        //'socket_api_url'      => 'ws://10.2.0.159:20979/api',
//        'socket_api_url'      => 'ws://10.2.0.240:20979/api',
        'socket_api_url' => 'ws://10.2.0.107:20979/api',
//        'socket_api_url'      => 'ws://127.0.0.1:20993', //server config

        'min_price_cod'          => 300000,//neu lon hon gia tri thi cod khong hien
        'min_free_price_term'    => 300000,//nếu là trả sau && price_term <= 300k + mua gói cước
        'prepaid_postpaid_price' => 10000,//chênh lệch sim trả trước và trả sau
        'qrcode_key_aes'         => 'vSkzaJwJy1kZG1pch01CjkzOYG069HlU',
        'intent_appid'           => 'vietinbankmobile',
        'intent_packid'          => 'com.vietinbank.ipay',
        'msg_aes_key'            => 'freedoo07122017',
        //list package checkout page (sim prepaid)
        'checkout_prepaid'       => array(
            '9HZBFBYt47KhGpk2Etp1SkiIgHzDDViD',//happy
            'Hb8PqMohTCoyUQLtMr2Y8aCElSnNo5pV',//club
            'pDa489iAWzn01zrSQOPuPlshjfT2hYBx',//vd89
            'Hi1OQ1DmZuOXNP5GrfO7dCKuHbn7sCDZ',//heyz
        ),
        'apisgw_secret_key'      => 'secretkey',

//        'sendEmail'                     => array(
//            'host'     => 'ssl://smtp.gmail.com',
//            'username' => 'thanh.nx@centech.com.vn',
//            'password' => '56213509',
//            'port'     => 465,
//            'type'     => 'ssl',
//        ),
        'sendEmail'           => array(
            'host'     => 'smtp.vnpt.vn',
            'username' => 'freedoo@vnpt.vn',
            'password' => 'S@p6rkm6',
            'port'     => 25,
            'type'     => '',
        ),
    );