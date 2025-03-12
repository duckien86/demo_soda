<?php
    /* @var $this PackageController */
    /* @var $package WPackage */
    /* @var $category */
?>
<div class="page_detail">
    <?php $this->renderPartial('/layouts/_block_service'); ?>
    <!--    --><?php //$this->renderPartial('/layouts/_block_banner'); ?>
    <section class="ss-bg">
        <section class="ss-box1">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="ss-box1-right-all">
                            <div class="ss-box1-right-title">
                                <div class="ss-box1-left-top-tit border_bottom">
                                    <span class="uppercase"><?= CHtml::encode($category); ?></span>
                                </div>
                            </div>
                            <div class="list_package">
                                <?php
                                    if ($package):
                                        $this->widget(
                                            'booster.widgets.TbThumbnails',
                                            array(
                                                'dataProvider'     => $package,
                                                'template'         => "{items}{pager}",
                                                'enablePagination' => TRUE,
                                                'viewData'         => array('class_col' => TRUE),
                                                'itemView'         => '_block_package',
//                                                        'ajaxType'         => 'POST',
                                                'emptyText'        => '',
                                            )
                                        );
                                    endif;
                                ?>
                            </div>
                        </div>
                        <div class="space_30"></div>
                    </div>
                </div>
            </div>
        </section>
    </section>
    <!--end section #ss-bg -->
</div>

