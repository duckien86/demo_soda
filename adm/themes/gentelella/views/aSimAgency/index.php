<?php
    /* @var $this SimController */
    /* @var $searchForm SearchForm */
    /* @var $data */
    /* @var $msg */
?>
<div id="main_container" class="container">
    <div class="ss-box1-right-all">
        <div class="ss-box1-right-title">
            <div class="ss-box1-left-top-tit">
                <img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_sim_1.png" alt="image">
                <span class="uppercase">Chọn số</span>
            </div>
        </div>
        <div class="space_10"></div>
        <?php $this->renderPartial('_filter_area', array('searchForm' => $searchForm)); ?>
        <div id="list_msisdn">
            <?php $this->renderPartial('_list_msisdn', array('data' => $data, 'data_output' => $data_output)); ?>
        </div>
    </div>

    <div class="space_10"></div>
    <?php $this->renderPartial('_modal_confirm'); ?>
</div>
