<?php
    return array(
        'manage_menu'                  => 'Menu',
        'manage_customerOrder'         => 'Quản lý thông tin khách hàng',
        'manage_log'                   => 'Quản lý log',
        'manage_unit'                  => 'Quản lý đơn vị',
        'manage_complain'              => (SUPER_ADMIN || ADMIN || Yii::app()->user->checkAccess("LeaderShift")) ? 'Quản lý cuộc gọi' : 'Danh sách cuộc gọi',
        'manage_categories'            => 'Chương trình OB',
        'manage_assignmentShift'       => (SUPER_ADMIN || ADMIN || Yii::app()->user->checkAccess("LeaderShift")) ? 'Phân công ca trực' : 'Lịch trực',
        'create'                       => 'Tạo mới',
        'update'                       => 'Cập nhật',
        'view'                         => 'Chi tiết',
        'delete'                       => 'Xóa',
        'shift_admin'                  => 'Tổng quan',
        'shift_user'                   => 'Chi tiết khai thác viên',
        'complain_pending'             => 'Thống kê, phân công',
        'complain_user'                => 'Quản lý phân công cuộc gọi',
        'categories_name'              => 'Chương trình',
        'total_of_categories_pending'  => 'Chưa xử lý',
        'total_of_categories_called'   => 'Đã xử lý',
        'username'                     => 'Khai thác viên',
        'total'                        => 'Tổng số đang xử lý ',
        'list_complain'                => 'Danh sách cuộc gọi ',
        'order_sim'                    => 'Đặt hàng',
        'search_order'                 => 'Tra cứu đơn hàng',
        'order'                        => 'Đặt mua sim',
        'listcalled'                   => 'Danh sách hẹn gọi lại',
        'profileTable'                 => 'Bảng làm việc cá nhân',
        'online_user'                  => 'Khai thác viên online',
        'total_of_categories_callback' => 'Hẹn gọi lại',
        'chat_sale'                    => 'Tiện ích chat',
        'manage_shipper'               => 'Quản lý NV giao vận',
        'assignment_shipper'           => 'Phân công NV giao vận',
        'manage_trafic'                => 'Quản lý giao vận',
        'manage_msisdn'                => 'Quản lý thuê bao',
        'recanMsisdn'                  => 'Thông tin thuê bao',
        'order_change'                 => 'Điều chuyển phòng bán hàng',
        'manage_ctv_user'              => 'Tra cứu CTV',
        'manage_ctv_commision'         => 'Tra cứu thù lao',
        'manage_ctv_paid'              => 'Tra cứu thanh toán',
        'search_msisdn'                => 'Chọn số',
        'manage_token_link'            => 'Quản lý link mua hàng',
    );
?>