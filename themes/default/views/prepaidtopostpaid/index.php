<?php
/**
 * @var $this PrepaidtopostpaidController
 * @var $model WPrepaidToPostpaid
 * @var $province array
 * @var $district array
 * @var $ward array
 */
$this->pageTitle = Yii::t('web/portal','prepaid_to_postpaid');
?>
<div id="prepaidtopostpaid">
    <section class="ss-bg">
        <section class="ss-box1">
            <div class="container no_pad_xs">
                <div class="ss-box1-right-all">
                    <?php echo $this->renderPartial('/prepaidtopostpaid/_form', array(
                        'model'         => $model,
                        'province'      => $province,
                        'district'      => $district,
                        'ward'          => $ward,
                    ));?>
                </div>
            </div>
        </section>
    </section>
</div>
