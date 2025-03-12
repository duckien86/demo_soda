<?php

    class AMenu extends Menu
    {
        const MENU_ACTIVE   = 1;
        const MENU_INACTIVE = 0;

        const MENU_TOP    = 'top';
        const MENU_BOTTOM = 'bottom';
        const MENU_LEFT   = 'left';
        const MENU_RIGHT  = 'right';

        public $old_file;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('parent_id, sort_order, status', 'numerical', 'integerOnly' => TRUE),
                array('name, target_link, icon', 'length', 'max' => 255),
                array('positions', 'length', 'max' => 20),
                array('icon', 'file', 'allowEmpty' => TRUE,
                                      'types'      => 'jpg, jpeg, png, gif',
                ),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, parent_id, name, target_link, icon, positions, sort_order, status', 'safe', 'on' => 'search'),
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
                'id'          => Yii::t('adm/label', 'id'),
                'parent_id'   => Yii::t('adm/label', 'parent_id'),
                'name'        => Yii::t('adm/label', 'name'),
                'target_link' => Yii::t('adm/label', 'target_link'),
                'icon'        => Yii::t('adm/label', 'icon'),
                'positions'   => Yii::t('adm/label', 'positions'),
                'sort_order'  => Yii::t('adm/label', 'sort_order'),
                'status'      => Yii::t('adm/label', 'status'),
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
            $criteria->compare('parent_id', $this->parent_id);
            $criteria->compare('name', $this->name, TRUE);
            $criteria->compare('target_link', $this->target_link, TRUE);
            $criteria->compare('icon', $this->icon, TRUE);
            $criteria->compare('positions', $this->positions, TRUE);
            $criteria->compare('sort_order', $this->sort_order);
            $criteria->compare('status', $this->status);

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
         * @return AMenu the static model class
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

            return CHtml::image($dir_root . $images, $this->name, array("style" => "max-width:80px;max-height:50px;", "title" => $this->name));
        }

        /**
         * @param $url_image
         */
        public function cleanup($url_image)
        {
            if ($url_image) {
                unlink(realpath(Yii::app()->getBasePath() . $url_image));
            }
        }

        /**
         * @return array
         */
        public function getListParentId()
        {
            $criteria            = new CDbCriteria;
            $criteria->distinct  = TRUE;
            $criteria->condition = ' status =:status';
            $criteria->params    = array(':status' => self::MENU_ACTIVE);
            $criteria->order     = 'name';
            $results             = self::model()->findAll($criteria);

            return CHtml::listData($results, 'id', 'name');
        }

        public function getListPositions()
        {
            return array(
                self::MENU_TOP    => Yii::t('adm/label', self::MENU_TOP),
                self::MENU_BOTTOM => Yii::t('adm/label', self::MENU_BOTTOM),
                self::MENU_LEFT   => Yii::t('adm/label', self::MENU_LEFT),
                self::MENU_RIGHT  => Yii::t('adm/label', self::MENU_RIGHT),
            );
        }

        /**
         * @return static[]
         */
        public function getListMenu()
        {
            $criteria           = new CDbCriteria;
            $criteria->distinct = TRUE;
            $criteria->order    = 'parent_id';

            return self::model()->findAll($criteria);
        }
    }
