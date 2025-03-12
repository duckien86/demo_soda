<?php

class Result{
    /**
     * Trạng thái của kết quả service: đúng hay sai
     * @var bool
     */
    public $status;
    /**
     * Message của kết quả service service
     * @var string
     */
    public $message;
    /**
     * Dữ liệu kết quả service
     * @var object
     */
    public $data;

    public function Result($status = true, $message = null, $data = null){
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }
}