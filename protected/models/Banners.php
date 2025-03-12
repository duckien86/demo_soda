<?php

    /**
     * This is the model class for table "{{banners}}".
     *
     * The followings are the available columns in table '{{banners}}':
     *
     * @property string  $id
     * @property string  $title
     * @property string  $file_name
     * @property string  $file_ext
     * @property string  $img_desktop
     * @property string  $img_mobile
     * @property string  $target_link
     * @property string  $content_html
     * @property integer $sort_order
     * @property integer $status
     * @property string  $type
     */
    class Banners extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{banners}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('title, file_name, file_ext, img_desktop, img_mobile, status', 'required'),
                array('sort_order, status', 'numerical', 'integerOnly' => TRUE),
                array('title, type', 'length', 'max' => 255),
                array('file_name', 'length', 'max' => 500),
                array('file_ext', 'length', 'max' => 10),
                array('img_desktop, img_mobile, target_link', 'length', 'max' => 1000),
                array('content_html', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, title, file_name, file_ext, img_desktop, img_mobile, target_link, content_html, sort_order, status, type', 'safe', 'on' => 'search'),
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
                'id'           => 'ID',
                'title'        => 'Title',
                'file_name'    => 'File Name',
                'file_ext'     => 'File Ext',
                'img_desktop'  => 'Img Desktop',
                'img_mobile'   => 'Img Mobile',
                'target_link'  => 'Target Link',
                'content_html' => 'Content Html',
                'sort_order'   => 'Sort Order',
                'status'       => 'Status',
                'type'         => 'Type',
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
        public function search()
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria = new CDbCriteria;

            $criteria->compare('id', $this->id, TRUE);
            $criteria->compare('title', $this->title, TRUE);
            $criteria->compare('file_name', $this->file_name, TRUE);
            $criteria->compare('file_ext', $this->file_ext, TRUE);
            $criteria->compare('img_desktop', $this->img_desktop, TRUE);
            $criteria->compare('img_mobile', $this->img_mobile, TRUE);
            $criteria->compare('target_link', $this->target_link, TRUE);
            $criteria->compare('content_html', $this->content_html, TRUE);
            $criteria->compare('sort_order', $this->sort_order);
            $criteria->compare('status', $this->status);
            $criteria->compare('type', $this->type);

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
            ));
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return Banners the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
