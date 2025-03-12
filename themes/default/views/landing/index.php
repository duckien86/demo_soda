<?php
    /* @var $this LandingController */
    /* @var $form CActiveForm */
    $detect = new MyMobileDetect();
?>
<script src='https://www.google.com/recaptcha/api.js?hl=vi'></script>
<div class="bg_landing">
    <div>
        <?php
            if ($detect->isMobile()) {
                $src = Yii::app()->theme->baseUrl . '/images/bg_landing_m.png';
            } else {
                $src = Yii::app()->theme->baseUrl . '/images/bg_landing.png';
            }
        ?>
        <img class="img" src="<?= $src; ?>">
    </div>
    <div class="form_verify">
        <div class="col-md-4 col-xs-12"></div>
        <div class="col-md-4 no_pad">
            <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                'id'                   => 'verify_form',
                'method'               => 'POST',
                'action'               => Yii::app()->controller->createUrl('landing/index'),
                'enableAjaxValidation' => TRUE,
            )); ?>
            <div class="space_15_xs"></div>
            <div class="text-center">
                <div class="g-recaptcha" data-sitekey="6LdnWS4UAAAAAAyy0Odc6bAuWs8wEm6BD9A6h66t"></div>
            </div>
            <div class="space_10"></div>
            <div>
                <?php echo CHtml::submitButton('Trải nghiệm', array('class' => 'btn btn_continue', 'name' => 'btn_verify')); ?>
            </div>
            <?php $this->endWidget(); ?>
            <div class="space_1"></div>
        </div>
        <div class="col-md-4 col-xs-12"></div>
    </div>
</div>
