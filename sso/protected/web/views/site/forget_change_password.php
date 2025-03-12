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
            <?php if (isset($type)) { ?>
                <?php if ($type == 'phone') { ?>
                    <div class="form-group">

                        <label for="new_password" class="cols-sm-2 control-label" id="new_password_title"
                               style="font-size: 15px !important;font-weight: 600 !important;">Nhập mã OTP đã được gửi
                            đến
                            số điện thoại của bạn</label>
                        <div class="cols-sm-10">

                            <?php echo $form->textField($model, 'otp', array('class' => 'form-control form-design', 'placeholder' => 'Mã OTP')); ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
            <?php echo $form->hiddenField($model, 'user_id', array('class' => 'form-control form-design', 'placeholder' => 'user_id')); ?>
            <div class="form-group">

                <label for="new_password" class="cols-sm-2 control-label" id="new_password_title"
                       style="font-size: 15px !important;font-weight: 600 !important;">Mật khẩu mới</label>
                <div class="cols-sm-10">

                    <?php echo $form->passwordField($model, 'new_password', array('class' => 'form-control form-design', 'placeholder' => 'Mật khẩu mới')); ?>
                </div>
            </div>
            <div class="form-group">

                <label for="re_new_password" class="cols-sm-2 control-label" id="re_new_password_title"
                       style="font-size: 15px !important;font-weight: 600 !important;">Xác nhận mật khẩu</label>
                <div class="cols-sm-10">
                    <?php echo $form->passwordField($model, 're_new_password', array('class' => 'form-control form-design', 'placeholder' => 'Nhập lại mật khẩu')); ?>
                </div>
            </div>
            <div class="error_form" style="display: none;margin: 0px 0px 5px 0px;"></div>
            <div class="text-success success_form" style="display: none;margin: 0px 0px 5px 0px;"></div>
            <div class="form-group ">
                <div class="login-register" style="width:100%; ">
                    <button class="btn btn-primary btn-lg btn-block login-button button-sso-login"
                            type="submit">
                        Cập nhật mật khẩu
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

