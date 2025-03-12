<?php

class WSurvey extends Survey
{

	CONST SURVEY_ACTIVE = 1;
	CONST SURVEY_INACTIVE = 0;

	CONST UN_LIMIT = -1;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 *
	 * @param string $className active record class name.
	 *
	 * @return WSurvey the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return WSurvey
	 */
	public static function getNewestSurvey()
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 't.status = :status';
		$criteria->params = array(
			':status' => WSurvey::SURVEY_ACTIVE
		);
		$criteria->order = 'id DESC';
		return WSurvey::model()->find($criteria);
	}

}
