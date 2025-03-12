<?php
    $this->pageTitle = Yii::app()->name . Yii::t('adm/common', 'title_site');
    /*$this->breadcrumbs=array(
        'Login',
    );*/
?>

<div class="form login_area">
    <?php $box = $this->beginWidget(
        'booster.widgets.TbPanel',
        array(
            'title'       => Yii::t('common/LoginForm', 'login'),
            'headerIcon'  => 'th-list',
            'padContent'  => FALSE,
            'htmlOptions' => array('class' => 'bootstrap-widget-table')
        )
    ); ?>
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                     => 'login-form',
        'enableClientValidation' => TRUE,
        'clientOptions'          => array(
            'validateOnSubmit' => TRUE,
        ),
        'htmlOptions'            => array('class' => 'well'),
    )); ?>
    <div class="row">
        <?php echo $form->textFieldGroup($model, 'username'); ?>
        <?php echo $form->error($model, 'username'); ?>
    </div>

    <div class="row">
        <?php echo $form->passwordFieldGroup($model, 'password'); ?>
    </div>
    <?php
        if (isset(Yii::app()->session['show_captcha']) && Yii::app()->session['show_captcha']) {
            ?>
            <div class="well">
                <?php echo $form->labelEx($model, 'verifyCode'); ?>
                <div>
                    <?php $this->widget('CCaptcha', array(
                            'captchaAction'  => 'aSite/captcha&YII_CSRF_TOKEN=' . Yii::app()->request->csrfToken,
                            'buttonLabel'    => '<img src="' . Yii::app()->baseUrl . '/images/refresh.png' . '" />',
                            'clickableImage' => TRUE, 'imageOptions' => array('id' => 'captchaimg'
                            )
                        )
                    );
                    ?>
                    <?php echo $form->textField($model, 'verifyCode', array('class' => 'span3')); ?>
                </div>
            </div>

            <?php
        }
    ?>

    <div class="row buttons">
        <?php
            $this->widget(
                'booster.widgets.TbButton',
                array('buttonType' => 'submit', 'label' => Yii::t('common/LoginForm', 'login'), 'context' => 'primary', 'icon' => 'lock')
            );
        ?>
        <?php $this->widget(
            'booster.widgets.TbButton',
            array('buttonType' => 'reset', 'label' => Yii::t('common/LoginForm', 'reset'), 'icon' => 'remove',)
        ); ?>
    </div>
    <?php $this->endWidget(); ?>
    <?php $this->endWidget(); ?>
</div><!-- form -->
