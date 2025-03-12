<div id="footer">
    <div class="copy_right">
        <div class="space_5"></div>
        <div class="container">
            <div class="uppercase copy_right_tit text-center">
                freedoo.vnpt.vn - website bán hàng online chính thức của vnpt vinaphone
            </div>
            <div class="copy_right_txt text-center">
                Copyright VNPT VINAPHONE <?= date('Y') ?>. All rights reserved.
            </div>
        </div>
        <div class="space_5"></div>
    </div>
</div>
<?php
    echo Utils::genGA(Yii::app()->params->google_analytics_code);
?>
