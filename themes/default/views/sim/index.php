<?php
    /* @var $this SimController */
    /* @var $searchForm SearchForm */
    /* @var $data */
    /* @var $msg */
?>
<div class="page_detail">
    <?php $this->renderPartial('/layouts/_block_service'); ?>
    <!--    --><?php //$this->renderPartial('/layouts/_block_banner'); ?>
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
                    <!--title-head-search-->
                    <div class="title-head-search">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-pad-mar">
                                    <span>Khách hàng cá nhân</span>  <span><img src="<?= Yii::app()->theme->baseUrl; ?>/mobile_images/right-new.png" alt=""></span>  <span>Sim số</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end title-head-search-->
                    <div class="space_10"></div>
                    <?php $this->renderPartial('_filter_area', array('searchForm' => $searchForm)); ?>
                    <div id="list_msisdn">
                        <?php
                            $detect   = new MyMobileDetect();
                            $isMobile = $detect->isMobile();
                            if ($isMobile) {
                                $this->renderPartial('_mobile_list_msisdn_tab', array('data' => $data, 'isMobile' => $isMobile,'type_sim'=> $type_sim));
                            } else {
                                $this->renderPartial('_list_msisdn', array('data' => $data,'type_sim' => $type_sim));
                            }
                        ?>
                    </div>
                </div>

                <div class="space_10"></div>
            </div>
        </section>
        <!-- #ss-box1 -->
    </section>
    <?php $this->renderPartial('_modal_confirm'); ?>
</div>
<?php $this->renderPartial('../layouts/_copyright'); ?>
<script>
    isMobile = "<?= !empty($isMobile) ? $isMobile : false ?>";
</script>