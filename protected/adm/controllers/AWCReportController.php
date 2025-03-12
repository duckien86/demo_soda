<?php

class AWCReportController extends AController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
    public $defaultAction = 'admin';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
            'rights',
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('admin'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new AWCMatch();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AWCMatch']))
		{
			$model->attributes=$_POST['AWCMatch'];
			$model->team_name_1 = AWCTeam::getTeamName($model->team_code_1);
			$model->team_name_2 = AWCTeam::getTeamName($model->team_code_2);

			if(!empty($model->start_time)){
				if(empty($model->hour)){
					$model->hour = "00";
				}else{
					$model->hour = intval($model->hour);
					if(sizeof($model->hour) == 1){
						$model->hour = '0'.$model->hour;
					}
				}
				if(empty($model->minute)){
					$model->minute = "00";
				}else{
					$model->minute = intval($model->minute);
					if(sizeof($model->minute) == 1){
						$model->minute = '0'.$model->minute;
					}
				}
				$model->start_time = date('Y-m-d', strtotime($model->start_time));
				$model->start_time.= " $model->hour:$model->minute:00";
			}

			if($model->validate()){
				if($model->save()){
					$this->redirect(array('admin'));
				}
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		$model->hour = date('H', strtotime($model->start_time));
		$model->minute = date('i', strtotime($model->start_time));
		$model->start_time = date('d/m/Y', strtotime($model->start_time));
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AWCMatch']))
		{
			if(isset($_POST['AWCMatch']))
			{
				$model->attributes=$_POST['AWCMatch'];
				$model->team_name_1 = AWCTeam::getTeamName($model->team_code_1);
				$model->team_name_2 = AWCTeam::getTeamName($model->team_code_2);

				if(!empty($model->start_time)){
					if(empty($model->hour)){
						$model->hour = "00";
					}else{
						$model->hour = intval($model->hour);
						if(sizeof($model->hour) == 1){
							$model->hour = '0'.$model->hour;
						}
					}
					if(empty($model->minute)){
						$model->minute = "00";
					}else{
						$model->minute = intval($model->minute);
						if(sizeof($model->minute) == 1){
							$model->minute = '0'.$model->minute;
						}
					}
					$model->start_time = date('Y-m-d', strtotime($model->start_time));
					$model->start_time.= " $model->hour:$model->minute:00";
				}

				if($model->validate()){
					if($model->save()){
						$this->redirect(array('admin'));
					}
				}
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('AWCMatch');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new AWCReport('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_REQUEST['AWCReport'])){
			$model->attributes=$_REQUEST['AWCReport'];
		}


		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionGetListMatchByType()
	{
		$data = '';
		if(isset($_POST['type'])){
			$matches = AWCMatch::getAllMatch($_POST['type']);
			if(!empty($matches)){
				foreach ($matches as $match_id => $match_name){
					$data.= "<option value='$match_id'>$match_name</option>";
				}
			}
		}
		echo $data;
		Yii::app()->end();
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return AWCMatch the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=AWCMatch::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param AWCMatch $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='awcmatch-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * Action change status
	 */
	public function actionChangeStatus()
	{
		$result = FALSE;
		$id     = Yii::app()->request->getParam('id');
		$status = Yii::app()->request->getParam('status');
		$model  = AWCReport::model()->findByPk($id);
		if ($model) {
			$model->status = $status;
			if ($model->update()) {
				$result = TRUE;
			}
		}

		echo CJSON::encode($result);
		exit();
	}


	public function actionReward()
	{
		$result = array(
			'msg' => '',
			'error' => '',
		);
		$id     = Yii::app()->request->getParam('id');
		$status = Yii::app()->request->getParam('status');
		$model  = AWCReport::model()->findByPk($id);
		if ($model) {
			$match = AWCMatch::model()->findByPk($model->match_id);
			if($match){
				$limit = $match->getRewardLimit();
				$used = AWCMatch::getRewardUsed($match->id);
				if($used >= $limit){
					$result['error'] = 'Số lượng phần thưởng đã hết cho trận đấu này!';
				}
				if($model->isUserRewarded($match->type)){
					$result['error'] = $model->name . ' đã được trao một phần thưởng dự đoán Worldcup vòng ' . AWCMatch::getTypeLabel($match->type);
				}
				if(empty($result['error'])){
					$model->status = $status;
					if ($model->update()) {
						$result['msg'] = 'Trao thưởng thành công';
					}
				}
			}
		}
		echo CJSON::encode($result);
		exit();
	}
}
