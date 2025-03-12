<?php

    /**
     * LoginForm class.
     * LoginForm is the data structure for keeping
     * user login form data. It is used by the 'login' action of 'SiteController'.
     */
    class AUploadForm extends CFormModel
    {
        public $subscription_permanent_address; // Địa chỉ thường trú tương ứng với address trong bảng sim.
        public $photo_face_url; // Địa chỉ thường trú tương ứng với address trong bảng sim.
        public $photo_personal1_url; // Địa chỉ thường trú tương ứng với address trong bảng sim.
        public $photo_personal2_url; // Địa chỉ thường trú tương ứng với address trong bảng sim.
        public $photo_order_board_url; // Địa chỉ thường trú tương ứng với address trong bảng sim.


        /**
         * Declares the validation rules.
         * The rules state that username and password are required,
         * and password needs to be authenticated.
         */
        public function rules()
        {
            return array(
                array('photo_face_url,photo_personal1_url, photo_personal2_url, photo_order_board_url', 'file', 'types' => 'jpg,jpeg gif, png', 'allowEmpty' => FALSE),
                array('photo_face_url,photo_personal1_url, photo_personal2_url, photo_order_board_url', 'safe'),
            );
        }

        /**
         * Declares attribute labels.
         */
        public function attributeLabels()
        {
            return array(
                'photo_face_url'        => 'Ảnh chân dung',
                'photo_personal1_url'   => 'Ảnh mặt trước',
                'photo_personal2_url'   => 'Ảnh mặt sau',
                'photo_order_board_url' => 'Ảnh chụp phiếu đăng ký hợp đồng',
            );
        }

        /**
         * @param $complete_form
         * @param $order_id
         * Upload ảnh, crop ảnh, call api lấy url ảnh.
         */
        public static function upLoadImgOrderComplete($complete_form, $order_id, $attribute)
        {
            $upload = CUploadedFile::getInstance($complete_form, $attribute);

//            $photo_personal1_url   = CUploadedFile::getInstance($complete_form, 'photo_personal1_url');
//            $photo_personal2_url   = CUploadedFile::getInstance($complete_form, 'photo_personal2_url');
//            $photo_order_board_url = CUploadedFile::getInstance($complete_form, 'photo_order_board_url');
            $dir_upload = "complete_order";
            $sourceFile = Yii::app()->params->upload_dir_path . $dir_upload . '/' . $order_id;
            if (!is_dir($sourceFile)) {
                mkdir($sourceFile, 0777, TRUE);
            }

            $upload->saveAs($sourceFile . "/" . $upload);
//            $photo_personal1_url->saveAs($sourceFile . "/" . $photo_personal1_url);
//            $photo_personal2_url->saveAs($sourceFile . "/" . $photo_personal2_url);
//            $photo_order_board_url->saveAs($sourceFile . "/" . $photo_order_board_url);

            // Crop ảnh về định dạng quy định.
            $control_img1 = Utils::resizeImage($sourceFile . '/' . $upload);
            if ($control_img1) {
                $control_img1 = urlencode(base64_encode(file_get_contents($sourceFile . "/" . $upload)));
                //Call api lấy đường dẫn ảnh .
                $data_header = array(
                    'Content-Type: application/x-www-form-urlencoded',
                );

                $image_response = '&image= ' . $control_img1;
                // Gọi api để lấy link ảnh.
                $complete_form->$attribute = Utils::cUrlPostJson(Yii::app()->params['api_get_url_image'], $image_response, TRUE, 15, $http_status, $data_header);
            }
        }
    }
