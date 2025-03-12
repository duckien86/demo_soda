<?php
    /* @var $this CardController */
    /* @var $url */
?>
<script src="https://merchant.vban.vn/freedoo/Resources/js/vbaniframe.js"></script>
<div class="page_detail">
    <?php $this->renderPartial('/layouts/_block_service'); ?>
    <section class="ss-bg">
        <div class="container no_pad_xs">
            <div class="checkout-process">
                <iframe id="vnpt_iframe" name="vnpt_iframe" src="<?= $url; ?>" scrolling="no"
                        style="border: none; padding: 0px !important;" width="100%" height="1000px">
                </iframe>
            </div>
        </div>
    </section>
</div>