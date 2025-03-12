<?php
/**
 * @var $this WorldcupController
 * @var $model WCMatch
 */
?>

<div id="worldcup_topmenu">
    <div class="banner_worldcup">
        <img src="<?php echo Yii::app()->theme->baseUrl?>/images/banner_worldcup.jpg">
    </div>

    <div class="top_menu">
        <div class="container">
            <div class="row">

                <div class="col-xs-5">
                    <div class="row">
                        <div class="col-xs-4 text-right">
                            <img class="logo_freedoo" src="<?php echo Yii::app()->theme->baseUrl?>/images/icon_login_title.png">
                        </div>
                        <div class="col-xs-4 text-right">
                            <a href="<?php echo Yii::app()->createUrl('site/index')?>">
                                Trang chủ
                            </a>
                        </div>
                        <div class="col-xs-4 text-right">
                            <a href="<?php echo Yii::app()->createUrl('worldcup/index')?>">
                                Dự đoán
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xs-2 no_pad text-center">
                    <img id="logo_worldcup" src="<?php echo Yii::app()->theme->baseUrl?>/images/logo_worldcup-min.png">
                </div>

                <div class="col-xs-5">
                    <div class="row">
                        <div class="col-xs-6">
                            <a href="<?php echo Yii::app()->createUrl('worldcup/reward')?>">
                                Thể lệ & Giải thưởng
                            </a>
                        </div>
                        <div class="col-xs-6">
                            <a href="<?php echo Yii::app()->createUrl('worldcup/winners')?>">
                                Danh sách trúng thưởng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>