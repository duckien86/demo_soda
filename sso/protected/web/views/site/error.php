
<!--mian-content-->
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
                    .login-flexinss{
                        background: rgb(10, 183, 117) !important;
                        border: rgb(10, 183, 117) 1px solid;
                    }
                </style>
            </div>
        </div>
<div class="main-wthree">
    <?php if ($code !=999){?>
    <h2><?php echo $code; ?></h2>
    <?php }?>
    <p class="sub-agileinfo"><span>Xin lỗi! </span><?php echo CHtml::encode($message); ?></p>
    <!--form-->
    <form class="newsletter" action="#" method="post">


    </form>

    <!--//form-->
</div>

<style>
    body{
        text-align:center;
    }
</style>