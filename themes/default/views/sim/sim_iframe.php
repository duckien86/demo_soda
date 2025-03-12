<?php
    /* @var $this SimController */
    /* @var $searchForm SearchForm */
    /* @var $data */
    /* @var $msg */
?>
<div class="page_detail">
    <section class="ss-bg">
        <section id="ss-box1" class="ss-box1">
            <div class="container no_pad_xs">
                <div class="ss-box1-right-all">
                    <div class="ss-box1-right-title">
                        <div class="ss-box1-left-top-tit">
                            <img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_sim_1.png" alt="image">
                            <span class="uppercase">Chọn số</span>
                        </div>
                    </div>
                    <div class="space_10"></div>
                    <?php $this->renderPartial('_filter_area_iframe', array('searchForm' => $searchForm)); ?>
                    <div id="list_msisdn">
                        <?php $this->renderPartial('_list_msisdn_iframe', array('data' => $data)); ?>
                    </div>
                </div>

                <div class="space_10"></div>
            </div>
        </section>
        <!-- #ss-box1 -->
    </section>
    <?php $this->renderPartial('_modal_confirm'); ?>
</div>