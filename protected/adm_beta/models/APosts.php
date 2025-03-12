<?php

    class APosts extends Posts
    {
        const ACTIVE    = 'active';
        const INACTIVE  = 'inactive';
        const PENDING   = 'pending';
        const NOCOMMENT = 'no_comment';

        const TYPE_SUB = 0;
        const TYPE_ADD = 1;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('get_award, sort_order', 'numerical', 'integerOnly' => TRUE),
                array('title, image, media_url, sso_id', 'length', 'max' => 255),
                array('total_comment, total_like, post_category_id', 'length', 'max' => 10),
                array('status', 'length', 'max' => 20),
                array('note, tags', 'length', 'max' => 500),
                array('content, create_date, last_update', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, title, content, image, media_url, total_comment, total_like, sso_id, status, note, create_date, last_update, post_category_id, get_award, sort_order, tags', 'safe', 'on' => 'search'),
            );
        }

        /**
         * @return array relational rules.
         */
        public function relations()
        {
            // NOTE: you may need to adjust the relation name and the related
            // class name for the relations automatically generated below.
            return array();
        }

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'               => 'ID',
                'title'            => 'Tiêu đề',
                'content'          => 'Nội dung',
                'image'            => 'Ảnh',
                'media_url'        => 'Media Url',
                'total_comment'    => 'Bình luận',
                'total_like'       => 'Lượt thích',
                'sso_id'           => 'Người đăng',
                'status'           => 'Trạng thái',
                'note'             => 'Lý do',
                'create_date'      => 'Ngày đăng',
                'last_update'      => 'Cập nhật cuối',
                'post_category_id' => 'Thể loại bài đăng',
                'get_award'        => 'Điểm bài viết',
                'sort_order'       => 'Ghim bài',
                'tags'             => 'Tags',
            );
        }

        /**
         * Retrieves a list of models based on the current search/filter conditions.
         *
         * Typical usecase:
         * - Initialize the model fields with values from filter form.
         * - Execute this method to get CActiveDataProvider instance which will filter
         * models according to data in model fields.
         * - Pass data provider to CGridView, CListView or any similar widget.
         *
         * @return CActiveDataProvider the data provider that can return the models
         * based on the search/filter conditions.
         */
        public function search($type = '')
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria = new CDbCriteria;

            $criteria->compare('id', $this->id, TRUE);
            $criteria->compare('title', $this->title, TRUE);
            $criteria->compare('content', $this->content, TRUE);
            $criteria->compare('image', $this->image, TRUE);
            $criteria->compare('media_url', $this->media_url, TRUE);
            $criteria->compare('total_comment', $this->total_comment, TRUE);
            $criteria->compare('total_like', $this->total_like, TRUE);
            $criteria->compare('sso_id', $this->sso_id, TRUE);
            if ($type != '') {
                $criteria->addCondition("status='" . $type . "'");
            }
            $criteria->compare('note', $this->note, TRUE);
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('last_update', $this->last_update, TRUE);
            $criteria->compare('post_category_id', $this->post_category_id, TRUE);
            $criteria->compare('get_award', $this->get_award);
            $criteria->compare('sort_order', $this->sort_order);
            $criteria->compare('tags', $this->tags);

            return new CActiveDataProvider($this, array(
                'criteria'   => $criteria,
                'sort'       => array(
                    'defaultOrder' => 'id DESC',
                ),
                'pagination' => array(
                    'pageSize' => 20,
                )
            ));
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return Posts the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * @param $images
         *
         * @return string
         */
        public function getImageUrl($images)
        {
            $dir_root = Yii::app()->params->upload_dir_path;

            return CHtml::image($dir_root . $images, '', array("width" => "60px", "height" => "60px", "title" => $this->title));
        }

        /**
         * @param $id
         * Lấy tiêu đề thể loại bài đăng.
         *
         * @return string
         */
        public function getPostCate($id)
        {
            $data = PostCategory::model()->findByAttributes(array('id' => $id));

            return isset($data) ? $data->name : '';

        }

        /**
         * Lấy danh sách thể loại bài đăng
         */
        public function getAllPostCate()
        {
            $data = PostCategory::model()->findAll();

            return CHtml::listData($data, 'id', 'name');
        }

        /**
         * Lấy danh sách trạng thái.
         */
        public static function getAllStatus()
        {
            return array(
                APosts::ACTIVE    => Yii::t('adm/label', 'active'),
                APosts::INACTIVE  => Yii::t('adm/label', 'inactive'),
                APosts::PENDING   => Yii::t('adm/label', 'pending'),
                APosts::NOCOMMENT => Yii::t('adm/label', 'no_comment')
            );
        }

        public function getStatusLabel($status)
        {
            $array_status = $this->getAllStatus();

            return (isset($array_status[$status])) ? $array_status[$status] : $status;
        }

        public function getPostTitle($id)
        {
            $post = APosts::model()->findByAttributes(array('id' => $id));

            return ($post) ? $post->content : '';
        }

        public function checkAward($award, $id)
        {

            $return = 0;
            if ($award && $id) {

                $post = APosts::model()->findByAttributes(array('id' => $id));

                if ($post) {

                    if ($post->status == APosts::INACTIVE) {
                        $return = "-3";
                    } else {
                        if ($award == 1) {
                            $return = "+3";
                        } else {
                            $return = 0;
                        }
                    }
                }

            }

            return $return;
        }
    }
