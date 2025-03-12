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
        <div class="login-heading">
            <div class="login-title text-center">
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
                    .login-flexinss input{
                        background: rgb(10, 183, 117) !important;
                        border: rgb(10, 183, 117) 1px solid;
                    }
                </style>
            </div>
            <div class="login-title text-center">
                <h2 class="title-info">ĐĂNG NHẬP</h2>
            </div>

        </div>
        <div class="login-error text-center">
            <h5 class="title-error"><?= $error ?></h5>
        </div>
        <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'modal-login-form',
//                'action' => Yii::app()->createAbsoluteUrl("site/login"),
                //'enableAjaxValidation' => true,
//                'htmlOptions' => array('onsubmit' => "return false;"),
            )); ?>
        <div class="container">
            <div class="row main-login main-center">
                <div class="form-group">
                    <div class="cols-sm-10">
                        <input type="text" class="form-control form-design" name="WloginForm[username]"
                               id="WloginForm_username"
                               placeholder="Tên đăng nhập" autofocus/>
                        <input type="hidden" class="form-control" name="pid" id="pid" value="<?= $pid ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="cols-sm-10">
                        <input type="password" class="form-control form-design" name="WloginForm[password]"
                               id="WloginForm_pasword"
                               placeholder="Mật khẩu" autocomplete="off"/>
                    </div>
                </div>
                
                <?php if (isset($msg)) { ?>
                    <?php if (($msg != '')): ?>
                        <div class="error_form" style="margin: 0px 0px 5px 0px;"><?= $msg ?></div>
                    <?php else: ?>
                        <div class="error_form" style="display: none;margin: 0px 0px 5px 0px;"><?= $msg ?></div>
                    <?php endif; ?>
                <?php } ?>
                <div class="text-success success_form" style="display: none;margin: 0px 0px 5px 0px;"></div>
                <div class="form-group ">
                    <a href="../forgetpass/<?= $pid ?>" id="forget_password" style="font-size: 14px;">Quên mật khẩu?</a>
                </div>
                <div class="login-element-form " style="margin-top: 40px;">
                    <div class="form-group login-register <?php if($_GET['pid'] == 004){ ?> login-flexinss <?php }?>">
                        <?php echo CHtml::submitButton('Đăng nhập', array('class' => 'btn btn-primary btn-lg btn-block login-button button-sso-login')); ?>
                    </div>
                    <div class="login-register">
                        <?php if ($pid=='002'){ ?>
                            <a href="<?=$GLOBALS['config_common']['register_ctv']['url']?>"
                               class="btn btn-primary btn-lg btn-block login-button button-sso-login a_href">Đăng
                                ký</a>
                        <?php }else{ ?>
                            <?php if($_GET['pid'] == 004){ ?>
                                <a href="http://118.70.177.77:8694/flexmint/ctv/site/finishprofile"
                                   class="btn btn-primary btn-lg btn-block login-button button-sso-login a_href">Đăng
                                    ký</a>
                                <?php }else{?>
                        <a href="../register/<?= $pid ?>"
                           class="btn btn-primary btn-lg btn-block login-button button-sso-login a_href">Đăng
                            ký</a>
                                <?php }?>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
    <?php
        if ($pid == '001') {
            echo Utils::genGA('UA-104621508-3');
        } else if ($pid == '002') {
            echo Utils::genGA('UA-104621508-5');
        }else if ($pid=='003'){
            echo Utils::genGA('UA-104621508-7');
        }
    ?>
</div>
<script>
    //    $(document).ready(function () {
    //        $("#modal-login-form").submit(function (e) {
    //            $('#modal-login-form .error_form').html("<img style='text-align:center' width='20px' src='<?//=$this->theme_url;?>///images/loading.gif'/>").fadeIn();
    //            var formObj = $(this);
    //            var formURL = formObj.attr("action");
    //            var formData = new FormData(this);
    //            $.ajax({
    //                url: formURL,
    //                type: 'POST',
    //                data: formData,
    //                contentType: false,
    //                cache: false,
    //                processData: false,
    //                success: function (data) {
    //                    var return_data = jQuery.parseJSON(data);
    //                    if (return_data.status == 1) {
    //                        window.location.href = return_data.message;
    //                    } else {
    //                        $('#modal-login-form .error_form').html(return_data.message).fadeIn();
    //                    }
    //                },
    //                error: function () {
    //                    alert("Error occured.please try again");
    //                    $('#modal-login-form .error_form').html('').fadeOut();
    //                }
    //            });
    //            e.preventDefault();
    //        });
    //
    //    });
</script>


