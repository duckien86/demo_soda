<?php
/* @var $this PackageController */
/* @var $searchPackageForm SearchPackageForm */
/* @var $form TbActiveForm */
/* @var $package_search_filter boolean */
?>
<div class="package_filter_area">
    <div class="row">
        <div class="col-sm-12">
            <div class="msisdn_search_filter">
                <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'search_package_form',
//                    'enableAjaxValidation' => true,
//                    'enableClientValidation' => true,
//                    'action'=> Yii::app()->controller->createUrl('sim/search'),
                    'htmlOptions' => array(
                        'onsubmit' => 'return false;',
                    ),
                )); ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="title" style="float:left">
                            Bạn muốn lắp đặt Dịch vụ Internet & Truyền hình tại:
                        </div>
                        <div class="" style="float:left; margin-top: 14px">
                            <select  class="form-control" style="margin-top: -8px; border: #ccc 1px solid" id="province_code" name="province_code" onchange="getlistpackagefiber();">
                                <option value="1000">--Toàn quốc--</option>
                                <?php foreach ($list_province as $key => $value) { ?>
                                    <option value="<?php echo $value['code'] ?>"><?php echo $value['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>


                </div>
                <?php $this->endWidget() ?>
                <?php $this->renderPartial('_modal_confirm_search', array('searchPackageForm' => $searchPackageForm)); ?>
            </div>
        </div>
        <?php if ($package_search_filter) { ?>

        <?php } ?>
    </div>
</div>
<script>
    function getlistpackagefiber() {
        var province_code = document.getElementById('province_code').value;
        $.ajax({
            url: '<?=Yii::app()->controller->createUrl("package/listcombo");?>',
            method: 'POST',
            data: {
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
                province_code: province_code,
            },
            dataType: 'json',
            success: function (result) {
                $('#list-item-package').html(result.content);
            }
        });
    }
</script>
