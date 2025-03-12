<?php
/**
 * @var $this PackageController
 * @var $list_package array
 * @var $list_package_other array
 * @var $type int
 * @var $activeId int
 */

?>
<div id="list-item-package">
    <?php if (!empty($list_package)) { ?>
        <div class="package_freedoo">
            <div class="container">
                <div class="title text-center">
                    <h3>KIT TRÀ SỮA</h3>
                </div>
                <div class="content">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="row">
                                <?php foreach ($list_package as $package) {
                                    $this->renderPartial('/package/_item_package_tra_sua', array(
                                        'model' => $package
                                    ));
                                }
                                if ($isOpen) {
                                    echo $close;
                                }
                                if (count($list_package) > ($limit * $rowLimit)) {
                                    ?>
                                <?php } ?>

                            </div>
                        </div>
                        <div class="col-md-2"></div>
                    </div>

                </div>
            </div>
        </div>
    <?php } ?>
</div>

<style>
    .more_package_fiber {
        width: 100%;
        float: left;
        text-align: center;
    }

    .more_down {
        font-size: 50px;
    }

    #package_fiber_all_province_more .img_more {
        width: 22%;
    }
</style>
<!--<script>
    function getlistpackagefiberallprovince() {
        $.ajax({
            url: '<? /*=Yii::app()->controller->createUrl("package/listfiberallprovince");*/ ?>',
            method: 'GET',
            data: {
                'YII_CSRF_TOKEN': '<?php /*echo Yii::app()->request->csrfToken*/ ?>',
            },
            dataType: 'json',
            beforeSend: function() {
                $('#package_fiber_all_province_more').html("<img class='img_more' src='https://merchant.vban.vn/freedoo/Resources/images/preload.svg' />");
            },
            success: function (result) {
                $('#package_fiber_all_province_more').html(result.content);
            }
        });
    }
</script>-->

