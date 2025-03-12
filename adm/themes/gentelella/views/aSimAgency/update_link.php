<?php
    /* @var $this SimController */
    /* @var $modelOrder AOrders */
    /* @var $model ATokenLinks */
    /* @var $form CActiveForm */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'search_msisdn') => array('index'),
        $model->pre_order_msisdn,
        Yii::t('adm/label', 'update_token_link'),
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/label', 'update_token_link') ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <div class="form">
            <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                'id'                   => 'ktv_update_link',
                // Please note: When you enable ajax validation, make sure the corresponding
                // controller action is handling ajax validation correctly.
                // There is a call to performAjaxValidation() commented in generated controller code.
                // See class documentation of CActiveForm for details on this.
                'enableAjaxValidation' => FALSE,
            )); ?>

            <div class="col-md-12">
                <?php echo $form->radioButtonListGroup(
                    $model,
                    'send_link_method',
                    array(
                        'widgetOptions' => array(
                            'data' => $model->getArrayMethod()
                        )
                    )
                ); ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'link', array('class' => 'label_text')); ?>
                    <?php echo $form->textField($model, 'link', array('class' => 'textbox', 'maxlength' => 255, 'readonly' => TRUE)); ?>
                    <?php echo $form->error($model, 'link'); ?>
                </div>
            </div>
            <div class="space_10"></div>
            <div class="form-group buttons">
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'complete'), array('class' => 'btn btn-success')); ?>
            </div>

            <?php $this->endWidget(); ?>

        </div><!-- form -->
    </div>
</div>