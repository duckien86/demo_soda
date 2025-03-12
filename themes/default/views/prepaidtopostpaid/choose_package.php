<?php
/**
 * @var $this PrepaidtopostpaidController
 * @var $model WPrepaidToPostpaid
 * @var $list_package array
 */
?>

<div id="prepaidtopostpaid">
    <section class="ss-bg">
        <section class="ss-box1">
            <div class="container no_pad_xs">
                <div class="ss-box1-right-all">
                    <?php echo $this->renderPartial('/prepaidtopostpaid/_choose_package', array(
                        'model' => $model,
                        'list_package' => $list_package,
                    ))?>
                </div>
            </div>
        </section>
    </section>
</div>