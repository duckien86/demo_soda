<?php

    /*Class chứa các hàm lấy dữ liệu , thực thi task qua socket*/

    /*Quy định gọi hàm: Khởi tạo -> set thuộc tính -> gọi hàm
    Với các api gọi lấy chi tiết 1 đối tượng thông qua khóa chính thì truyền mặc định key : primary_key .
    Ví dụ:
     $rs = new DataAdapter();
    //$data = $rs->get_news_category();
    //$data = $rs->list_media_category();
    $rs->primary_key= 1;
    $data = $rs->get_news_detail();
    $rs->media_category_id = 2;
    */

    class DataAdapter extends stdClass
    {
        public $user_id        = FALSE;
        public $primary_key;
        public $debug          = FALSE;
        public $timeout        = 5;
        public $publictime_int = 0;
        public $starttime_int  = 0;

        public $msisdn = '';

        public function search_msisdn()
        {
            $data = array(
                'id'             => $this->primary_key,
                'msisdn'         => $this->page_item,
                'prepaid_price'  => '50000',
                'postpaid_price' => '60000',
            );

            return CJSON::encode($data);
        }

        public function insert_order()
        {
            return TRUE;
        }

        public function payment()
        {
            return TRUE;
        }
    }