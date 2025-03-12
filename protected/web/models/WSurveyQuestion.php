<?php

/**
 * This is the model class for table "{{survey_question}}".
 *
 * The followings are the available columns in table '{{survey_question}}':
 * @property integer $id
 * @property string $content
 * @property string $first_label
 * @property string $last_label
 * @property integer $type
 * @property integer $survey_id
 * @property integer $sort_order
 * @property integer $point
 * @property integer $status
 */
class WSurveyQuestion extends SurveyQuestion
{
	CONST QUESTION_ACTIVE = 1;
	CONST QUESTION_INACTIVE = 0;

	CONST TYPE_CHOOSE_ONE 	= 1;
	CONST TYPE_CHOOSE_MANY 	= 2;
	CONST TYPE_CUSTOMIZE 	= 3;

	/**
	 * @param $survey_id int
	 * @return static[]
	 */
	public static function getListQuestionBySurveyId($survey_id)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 't.survey_id = :survey_id AND t.status = :status';
		$criteria->params = array(
			':survey_id' => $survey_id,
			':status' => WSurveyQuestion::QUESTION_ACTIVE,
		);
		$criteria->order = 't.sort_order IS NULL ASC, t.sort_order ASC';

		$cache_key = 'getListQuestionBySurveyId_' . $survey_id;
		$results   = Yii::app()->cache->get($cache_key);
		if (!$results) {
			$results = WSurveyQuestion::model()->findAll($criteria);
			Yii::app()->cache->set($cache_key, $results, 24*60*60);
		}

		return $results;
	}
}
