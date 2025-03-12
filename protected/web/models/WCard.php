<?php

    class WCard extends Card
    {
        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'             => Yii::t('web/portal', 'id'),
                'serial_number'  => Yii::t('web/portal', 'serial_number'),
                'pin_number'     => Yii::t('web/portal', 'pin_number'),
                'price'          => Yii::t('web/portal', 'price'),
                'price_discount' => Yii::t('web/portal', 'price_discount'),
                'exp_date'       => Yii::t('web/portal', 'exp_date'),
            );
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return WCard the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public static function getListCard()
        {
            $today               = date('Y-m-d 23:59:59', time());
            $criteria            = new CDbCriteria();
            $criteria->condition = 'exp_date >= :exp_date';
            $criteria->params    = array(':exp_date' => $today);

            return self::model()->findAll($criteria);
        }
    }
