<?php

/**
 * This is the model class for table "{{brcd_restricted_location}}".
 *
 * The followings are the available columns in table '{{brcd_restricted_location}}':
 * @property integer $id
 * @property string $name
 * @property string $province_code
 * @property string $distrist_code
 * @property string $ward_code
 * @property integer $status
 * @property string $param_1
 * @property string $param_2
 */
class WBrcdRestrictedLocation extends BrcdRestrictedLocation
{
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WBrcdRestrictedLocation the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getList($distrist_code, $ward_code = null)
	{
		$criteria = new CDbCriteria();
		// $criteria->compare('province_code', $province_code);
		$criteria->compare('distrist_code', $distrist_code);
		// // $criteria->compare('status', 1);
		// if ($ward_code) {
		// 	$criteria->compare('ward_code', $ward_code);
		// }

		$restrict = self::model()->findAll($criteria);

		return CHtml::listData($restrict, 'id', 'name');
	}
}
