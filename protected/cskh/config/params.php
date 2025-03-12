<?php
    return array(
        'hashkey'                     => 'centech',
        'project_name'                => '',
        'brand_name'                  => 'Admin CP',
        'password_key'                => 'centech24072013',

        /********Videos******/
//        'ftp_upload_config'           => array(
//            'base_folder' => '../uploads/videos/',
//        ),
        'video_upload_config'         => array(
            'uploads_dir'     => '../videos/uploads/',
            'video_files_dir' => 'video_files/',
        ),
        'media_upload_config'         => array(
            'uploads_dir'     => '../uploads/media/',
            'video_files_dir' => 'files/',
        ),
        'ip_streaming_server'         => '183.182.100.172:1935',
        'video_price'                 => array(
            0    => '0 KIP',
            500  => '500 KIP',
            1000 => '1000 KIP',
            1500 => '1500 KIP',
            2000 => '2000 KIP',
            2500 => '2500 KIP',
            3000 => '3000 KIP',
            4000 => '4000 KIP',
            5000 => '5000 KIP',
        ),
        'video_quality'               => array(
            '240'  => '240p',
            '360'  => '360p',
            '720'  => '720p',
            '1080' => '1080p',
        ),
        'pagination'                  => array(
            'defaultPageSize' => 30,
            'arrPageSize'     => array(10 => 10, 20 => 20, 30 => 30, 50 => 50, 100 => 100),
        ),
        /********Videos******/
        'categories_type'             => array(
            'GAME_APPS'  => 'GAME_APPS',
            'VIDEOS'     => 'VIDEOS',
            'CHARACTERS' => 'CHARACTERS',
            'EBOOKS'     => 'EBOOKS',
            'WALLPAPERS' => 'WALLPAPERS',
        ),
        'sendEmail'                 => array(
            'host'     => 'smtp.vnpt.vn',
            'username' => 'freedoo@vnpt.vn',
            'password' => 'S@p6rkm6',
            'port'     => 25,
            'type'     => '',
        ),
        'order_shipper_price'         => 20000,
        'socket_api_url'              => 'http://10.2.0.107:8285/',
//        'socket_api_url'              => 'http://192.168.100.9:8287',
//        'socket_api_url'              => 'http://10.2.0.212:8288/',
        'api_notify_assign_shipper'   => 'https://fcm.googleapis.com/fcm/send',
//        'socket_api_url'              => 'http://10.2.0.247:8288/',
        'upload_banners_dir'          => 'banners/',
        'sessionTimeout'              => 20 * 60,
        'book_upload_size_limit'      => 999 * 1024 * 1024,
//        'book_upload_extensions'      => array("pdf", "doc", "txt", "apk", "jar"),
        'book_upload_extensions'      => 'pdf|doc|txt|apk|jar|epub',
        'wallpaper_upload_size_limit' => 20 * 1024 * 1024,
        'wallpaper_upload_extensions' => array("png", "jpeg", "jpg", "gif", "bmp"),
    );
?>
