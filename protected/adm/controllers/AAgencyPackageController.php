<?php

class AAgencyPackageController extends AController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout        = '//layouts/column1';
	public $defaultAction = 'admin';
	public $dir_contracts = 'agency/contract';

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
		$model=new AAgencyPackage;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AAgencyPackage']))
		{
			$model->attributes=$_POST['AAgencyPackage'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AAgencyPackage']))
		{
			$model->attributes=$_POST['AAgencyPackage'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new AAgencyPackage('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['AAgencyPackage']))
			$model->attributes=$_GET['AAgencyPackage'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}


	public function actionGetPackageByDisplayType(){
		$display_type = Yii::app()->getRequest()->getParam("display_type", FALSE);
		echo "<option value=''>".Yii::t('adm/label', 'display_type')."</option>";
		if ($display_type == AAgencyPackage::DISPLAY_IN_BUY_SIM) {
			$return = APackage::getListPackageByDisplayCheckout(Sim::TYPE_PREPAID);
			$return = CHtml::listData($return, 'code', 'name') ;
		}else{
			$return = APackage::getPackageCodes();
		}
		foreach ($return as $k => $v) {
			echo CHtml::tag('option', array('value' => $k), CHtml::encode($v), TRUE);
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return AgencyPackage the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=AAgencyPackage::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param AgencyPackage $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='agency-package-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
