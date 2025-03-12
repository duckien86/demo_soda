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

        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                   => 'forget-password-form',
            'enableAjaxValidation' => FALSE,
            'htmlOptions'          => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left avatar-form')
        )); ?>
        <div class="main-login main-center">
            <?php if (isset($limit)) { ?>
                <h5 class="title-error">Bạn đã yêu cầu lấy mật khẩu quá 3 lần trong 1 ngày</h5>
            <?php } else if (isset($otp_result)) {
                ?>
                <h5 class="title-error">Thay đổi mật khẩu thành công!</h5>
                <?php
            } else {
                ?>
                <h5 class="title-error">Vui lòng kiểm tra email <a href=""><?= isset($email) ? $email : '' ?></a> để lấy
                    lại
                    mật khẩu</h5>
            <?php } ?>

            <div class="form-group ">
                <div class="login-register" style="width:100%; ">
                    <a href="../login/<?= $pid ?>"
                       class="btn btn-primary btn-lg btn-block login-button button-sso-login">Về trang chủ
                    </a>
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

