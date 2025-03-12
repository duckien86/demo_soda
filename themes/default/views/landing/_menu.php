<?php
    $controller = Yii::app()->controller->id;
    $action     = strtolower(Yii::app()->controller->action->id);
?>
<div class="main_menu menu-landing">
    <div class="container">
        <div class="row">
            <div id="menu" class="col-md-7 col-xs-5 no_pad_left">
                <ul class="topnav">
                    <li>
                        <a href="<?= Yii::app()->createUrl("site/index") ?>" title="" class="parent">
                            Tất cả
                        </a>
                    </li>
                    <li>
                        <a href="" target="_blank" title=""
                           class="parent">
                            Phút gọi
                        </a>
                    </li>
                    <li class="">
                        <a href="#" title="" class="parent">
                            SMS
                        </a>
                    </li>
                    <li>
                        <a href="#" title="" class="parent">
                            Data
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>