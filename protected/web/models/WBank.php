<?php

    class WBank extends Bank
    {
        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'              => Yii::t('web/portal', 'bank_id'),
                'name'            => Yii::t('web/portal', 'bank_name'),
                'code'            => Yii::t('web/portal', 'bank_code'),
                'logo'            => Yii::t('web/portal', 'bank_logo'),
                'description'     => Yii::t('web/portal', 'bank_description'),
                'payment_gateway' => Yii::t('web/portal', 'bank_payment_gateway'),
                'status'          => Yii::t('web/portal', 'status'),
            );
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return WBank the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * @param $payment_gateway
         *
         * @return array
         */
        public static function getListBankByPaymentGateway($payment_gateway)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 'payment_gateway=:payment_gateway';
            $criteria->params    = array(':payment_gateway' => $payment_gateway);
            $results             = self::model()->findAll($criteria);

            return CHtml::listData($results, 'code', 'name');
        }
    }
