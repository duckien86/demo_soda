<?php
/**
 * @var $this UserController
 * @var $model TUsers
 */

if(Yii::app()->user->user_type == TUsers::USER_TYPE_CTV){
    $title = Yii::t('tourist/label', 'ctv_info');
}else{
    $title = Yii::t('tourist/label', 'enterprises_info');
}

$this->pageTitle = 'Freedoo - ' . Yii::t('tourist/label', 'freedoo_tourist') . ' - ' . $title;
$this->breadcrumbs=array(
    $title
);

?>

<div class="user">
<?php $this->widget('booster.widgets.TbDetailView', array(
    'data'       => $model,
    'type'       => '',
    'htmlOptions'=> array(
        'class' => 'table table-bordered table-striped table-responsive table-hover'
    ),
    'attributes' => array(
//        array(
//            'name'        => Yii::t('tourist/label','user_id'),
//            'value'       => function ($data) {
//                return Chtml::encode($data->id);
//            },
//            'htmlOptions' => array('style' => 'vertical-align:middle;'),
//        ),
        array(
            'name'        => Yii::t('tourist/label','username'),
            'value'       => function ($data) {
                return Chtml::encode($data->username);
            },
            'htmlOptions' => array('style' => 'vertical-align:middle;'),
        ),
        array(
            'name'        => Yii::t('tourist/label', 'email'),
            'value'       => function ($data) {
                return Chtml::encode($data->email);
            },
            'htmlOptions' => array('style' => 'vertical-align:middle;'),
        ),
        array(
            'name'        => Yii::t('tourist/label', 'fullname'),
            'value'       => function ($data) {
                return Chtml::encode($data->fullname);
            },
            'htmlOptions' => array('style' => 'vertical-align:middle;'),
        ),
        array(
            'name'        => Yii::t('tourist/label', 'phone'),
            'value'       => function ($data) {
                return Chtml::encode($data->phone);
            },
            'htmlOptions' => array('style' => 'vertical-align:middle;'),
        ),
        array(
            'name'        => Yii::t('tourist/label', 'address'),
            'value'       => function ($data) {
                return Chtml::encode($data->address);
            },
            'htmlOptions' => array('style' => 'vertical-align:middle;'),
        ),
        array(
            'name'        => Yii::t('tourist/label', 'company'),
            'value'       => function ($data) {
                return Chtml::encode($data->company);
            },
            'htmlOptions' => array('style' => 'vertical-align:middle;'),
            'visible'     => (Yii::app()->user->user_type != TUsers::USER_TYPE_CTV)
        ),
        array(
            'name'        => Yii::t('tourist/label','user_type'),
            'value'       => function($data){
                return TUsers::getTypeLabel($data->user_type);
            },
            'htmlOptions' => array('style' => 'vertical-align:middle;'),
        ),
        array(
            'name'        => Yii::t('tourist/label', 'sale_code'),
            'value'       => function ($data) {
                return Chtml::encode($data->sale_code);
            },
            'htmlOptions' => array('style' => 'vertical-align:middle;'),
            'visible'     => (Yii::app()->user->user_type != TUsers::USER_TYPE_CTV)
        ),
        array(
            'name'        => Yii::t('tourist/label', 'tax_id'),
            'value'       => function ($data) {
                return Chtml::encode($data->tax_id);
            },
            'htmlOptions' => array('style' => 'vertical-align:middle;'),
            'visible'     => (Yii::app()->user->user_type != TUsers::USER_TYPE_CTV)
        ),
        array(
            'name'        => 'Mã CTV',
            'value'       => function ($data) {
                return Chtml::encode($data->invite_code);
            },
            'htmlOptions' => array('style' => 'vertical-align:middle;'),
            'visible'     => (Yii::app()->user->user_type == TUsers::USER_TYPE_CTV)
        )
    ),
)); ?>
<!--    <div style="margin-top: 10px">-->
<!--        --><?php //echo CHtml::link(Yii::t('tourist/label','update_info'),Yii::app()->createUrl('user/update'), array('class' => 'btn btn-success')) ?>
<!--        --><?php //echo CHtml::link(Yii::t('tourist/label','change_password'),Yii::app()->createUrl('user/changePassword'), array('class' => 'btn btn-primary')) ?>
<!--    </div>-->
</div>
