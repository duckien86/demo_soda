<?php

/**
 * This is the model class for table "{{survey_answer}}".
 *
 * The followings are the available columns in table '{{survey_answer}}':
 * @property integer $id
 * @property integer $question_id
 * @property string $content
 * @property integer $type
 * @property integer $is_right
 * @property integer $sort_order
 * @property integer $status
 */
class WSurveyAnswer extends SurveyAnswer
{

	CONST ANSWER_ACTIVE = 1;
	CONST ANSWER_INACTIVE = 0;

	CONST TYPE_AVAILABLE = 1;
	CONST TYPE_CUSTOMIZE = 2;

	CONST RIGHT_ANSWER = 1;
	CONST WRONG_ANSWER = 0;

	CONST ANSWER_USER_INPUT_ID = -1;

	/**
	 * @param $question_id int
	 * @return static[]
	 */
	public static function getListAnswersByQuestionId($question_id)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 't.question_id = :question_id AND t.status = :status';
		$criteria->params = array(
			':question_id' => $question_id,
			':status' => WSurveyAnswer::ANSWER_ACTIVE,
		);
		$criteria->order = 't.sort_order IS NULL ASC, t.sort_order ASC';

		$cache_key = 'getListAnswersByQuestionId_' . $question_id;
		$results   = Yii::app()->cache->get($cache_key);
		if (!$results) {
			$results = WSurveyAnswer::model()->findAll($criteria);
			Yii::app()->cache->set($cache_key, $results, 24*60*60);
		}
		
		return $results;
	}
}
