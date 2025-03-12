<?php

    class CskhPackage extends Package
    {
        const  PACKAGE_ACTIVE   = 1;
        const  PACKAGE_INACTIVE = 0;

        const PACKAGE_PREPAID   = 1;
        const PACKAGE_POSTPAID  = 2;
        const PACKAGE_DATA      = 3;
        const PACKAGE_VAS       = 4;
        const PACKAGE_SIMKIT    = 5;
        const PACKAGE_REDEEM    = 6;
        const FLEXIBLE_CALL_INT = 7;
        const FLEXIBLE_CALL_EXT = 8;
        const FLEXIBLE_SMS_INT  = 9;
        const FLEXIBLE_SMS_EXT  = 10;
        const FLEXIBLE_DATA     = 11;

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return WPackage the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * @param bool|TRUE $dataProvider
         * @param int       $limit
         * @param int       $offset
         *
         * @return array|CActiveDataProvider|mixed|null
         */
        public static function getListPackage($dataProvider = TRUE, $limit = 10, $offset = 0)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 'status=:status';
            $criteria->params    = array(':status' => self::PACKAGE_ACTIVE);
            $criteria->limit     = $limit;
            $criteria->offset    = $offset;
            $criteria->order     = 'id DESC';
            if ($dataProvider) {
                return new CActiveDataProvider(self::model(), array(
                    'criteria'   => $criteria,
                    'sort'       => array(
                        'defaultOrder' => 'id DESC',
                    ),
                    'pagination' => array(
                        'pageSize' => $limit,
                    )
                ));
            } else {
                return self::model()->findAll($criteria);
            }
        }
        public static function getAllPackageType()
        {
            return array(
                self::PACKAGE_PREPAID   => Yii::t('adm/label', 'package_prepaid'),
                self::PACKAGE_POSTPAID  => Yii::t('adm/label', 'package_postpaid'),
                self::PACKAGE_DATA      => Yii::t('adm/label', 'package_data'),
                self::PACKAGE_VAS       => Yii::t('adm/label', 'package_vas'),
                self::PACKAGE_SIMKIT    => Yii::t('adm/label', 'package_simkit'),
                self::PACKAGE_REDEEM    => Yii::t('adm/label', 'package_redeem'),
                self::FLEXIBLE_CALL_INT => Yii::t('adm/label', 'flexible_call_int'),
                self::FLEXIBLE_CALL_EXT => Yii::t('adm/label', 'flexible_call_ext'),
                self::FLEXIBLE_SMS_INT  => Yii::t('adm/label', 'flexible_sms_int'),
                self::FLEXIBLE_SMS_EXT  => Yii::t('adm/label', 'flexible_sms_ext'),
                self::FLEXIBLE_DATA     => Yii::t('adm/label', 'flexible_data'),
//                self::PACKAGE_ROAMING   => Yii::t('adm/label', 'package_roaming'),
            );
        }

        /**
         * @param $type
         *
         * @return mixed
         */
        public static function getPackageType($type)
        {
            $array_type = self::getAllPackageType();

            return (isset($array_type[$type])) ? $array_type[$type] : $type;
        }

        public static function getPackageByCode($code)
        {
            if ($code) {
                $package = CskhPackage::model()->findByAttributes(array('code' => $code));
                if ($package) {
                    return $package->name;
                }
            }

            return "";
        }
    }
