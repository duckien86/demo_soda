<?php
    $partner_url = '';
    if ($pid) {
        $partner = WPartner::model()->findByAttributes(array('id' => $pid));
        if($partner){
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
                <h2 class="title-info">ĐỔI MẬT KHẨU</h2>
            </div>
        </div>
        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                   => 'changePassword-form',
            'enableAjaxValidation' => TRUE,
            'htmlOptions'          => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left avatar-form')
        )); ?>
        <div class="main-login main-center">
            <div class="form-group">
                <label for="password" class="cols-sm-2 control-label">Số điện thoại</label>
                <div class="cols-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-phone fa-lg" aria-hidden="true"></i></span>
                        <?php echo $form->textField($model, 'phone', array('class' => 'form-control', 'placeholder' => 'Nhập số điện thoại')); ?>

                    </div>
                    <?php echo $form->error($model, 'phone'); ?>
                </div>
            </div>

            <?php echo $form->hiddenField($model, 'user_id', array('class' => 'form-control', 'placeholder' => 'Enter your phone number')); ?>
            <div class="form-group">
                <label for="password" class="cols-sm-2 control-label">Mật khẩu cũ</label>
                <div class="cols-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                        <?php echo $form->passwordField($model, 'old_password', array('class' => 'form-control', 'placeholder' => 'Nhập mật khẩu cũ')); ?>

                    </div>
                    <?php echo $form->error($model, 'old_password'); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="cols-sm-2 control-label">Mật khẩu mới</label>
                <div class="cols-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                        <?php echo $form->passwordField($model, 'new_password', array('class' => 'form-control', 'placeholder' => 'Nhập mật khẩu mới')); ?>

                    </div>
                    <?php echo $form->error($model, 'new_password'); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="cols-sm-2 control-label">Xác nhận mật khẩu</label>
                <div class="cols-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                        <?php echo $form->passwordField($model, 're_new_password', array('class' => 'form-control', 'placeholder' => 'Xác nhận mật khẩu mới')); ?>

                    </div>
                    <?php echo $form->error($model, 're_new_password'); ?>
                </div>
            </div>


            <div class="form-group" style="margin-top: 30px;">
                <button class="btn btn-primary btn-lg btn-block login-button"
                        style="font-size: 14px; float: left;" type="submit">
                    Thay đổi mật khẩu.
                </button>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<style>

</style>

