<script src='https://www.google.com/recaptcha/api.js'></script>
<?php
    $partner_url = '';
    if ($pid) {
        $partner = WPartner::model()->findByAttributes(array('id' => $pid));
        if ($partner) {
            $partner_url = $partner->return_url;
        }
    }
?>
<div class="container">
    <div class="row main">
        <div class="panel-heading">
            <div class="panel-title text-center">
                <?php if($_GET['pid'] != 004){ ?>
                    <h1 class="title"><a href="<?= $partner_url ?>"><img
                                    src="<?= Yii::app()->theme->baseUrl; ?>/images/logo_sso.png"></a></h1>
                <?php }else{?>
                    <div class="flexins" >
                        Flexins
                    </div>
                <?php  }?>
                <style>
                    .flexins{
                        color: rgb(10, 183, 117);
                        font-size: 70px;
                        padding: 12px 10px;
                        font-weight: bold;
                        text-transform: uppercase;
                        font-family: "SanFranciscoDisplay-Bold";
                    }
                    .login-flexinss{
                        background: rgb(10, 183, 117) !important;
                        border: rgb(10, 183, 117) 1px solid;
                    }
                </style>
            </div>
            <div class="login-title text-center">
                <h2 class="title-info">Lấy lại mật khẩu</h2>
            </div>
        </div>
        <div class="login-error text-center">

            <h5 class="title-error"><?= $error ?></h5>
        </div>
        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                   => 'forget-password-form',
            'enableAjaxValidation' => FALSE,
            'htmlOptions'          => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left avatar-form')
        )); ?>
        <div class="main-login main-center">
            <?php echo CHtml::errorSummary($model); ?>
            <div class="radio_form">
                <?php echo $form->radioButton($model, 'select_box', array(
                    'value'        => 'email',
                    'uncheckValue' => NULL,
                    'checked'      => TRUE,
                )); ?>

                <label for="email" class="cols-sm-2 control-label" id="email_title"
                       style="font-size: 14px !important;font-weight: 600 !important;">Email </label><br>

                <?php echo $form->radioButton($model, 'select_box', array(
                    'value'        => 'phone',
                    'uncheckValue' => NULL
                )); ?>
                <label for="phone" class="cols-sm-2 control-label" id="phone_title"
                       style="font-size: 14px !important;font-weight: 600 !important;">Số điện thoại
                </label>
            </div>
            <div class="form-group">
                <div class="cols-sm-10">

                    <?php echo $form->textField($model, 'input_text', array('class' => 'form-control form-design', 'placeholder' => 'Nhập thông tin')); ?>
                </div>
            </div>

            <?php if ($accept_capcha): ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'verifyCode'); ?>
                    <div id="captcha_place_holder"
                         class="g-recaptcha"
                         data-sitekey="6LdnWS4UAAAAAAyy0Odc6bAuWs8wEm6BD9A6h66t">

                    </div>
                    <?php echo $form->error($model, 'verifyCode'); ?>
                </div>
            <?php endif; ?>
            <div class="error_form" style="display: none;margin: 0px 0px 5px 0px;"></div>
            <div class="text-success success_form" style="display: none;margin: 0px 0px 5px 0px;"></div>
            <div class="form-group ">
                <div class="login-register" style="width:100%; ">
                    <button class="btn btn-primary btn-lg btn-block login-button button-sso-login login-flexinss"
                            type="submit">
                        Lấy mật khẩu
                    </button>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>

<style>
    #password_title {
        font-size: 13px !important;
        margin-bottom: 5px;
        font-family: inherit;
        font-weight: 300 !important;
    }

    .help-block {
        margin-left: 5px !important;
        font-size: 12px;
    }

    .form-group {
        margin-bottom: 20px !important;
    }
</style>

