<?php
/* @var $this PackageController */
/* @var $searchPackageForm SearchPackageForm */
/* @var $form TbActiveForm */
/* @var $package_search_filter boolean */
?>
<!--<div class="package_filter_area">-->
<!--    <div class="row">-->
<!--        <div class="col-sm-12">-->
<!--            <div class="msisdn_search_filter">-->
<!--                --><?php //$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
//                    'id' => 'search_package_form',
////                    'enableAjaxValidation' => true,
////                    'enableClientValidation' => true,
////                    'action'=> Yii::app()->controller->createUrl('sim/search'),
//                    'htmlOptions' => array(
//                        'onsubmit' => 'return false;',
//                    ),
//                )); ?>
<!--                <div class="row">-->
<!--                    <div class="col-lg-12">-->
<!--                        <div class="title" style="float:left">-->
<!--                            Bạn muốn lắp đặt Dịch vụ MyTV tại:-->
<!--                        </div>-->
<!--                        <div class="" style="float:left; margin-top: 14px">-->
<!--                            <select  class="form-control" style="margin-top: -8px; border: #ccc 1px solid" id="province_code" name="province_code" onchange="getlistpackagefiber();">-->
<!--                                <option value="1000">--Toàn quốc--</option>-->
<!--                                --><?php //foreach ($list_province as $key => $value) { ?>
<!--                                    <option value="--><?php //echo $value['code'] ?><!--">--><?php //echo $value['name'] ?><!--</option>-->
<!--                                --><?php //} ?>
<!--                            </select>-->
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!---->
<!--                </div>-->
<!--                --><?php //$this->endWidget() ?>
<!--                --><?php //$this->renderPartial('_modal_confirm_search', array('searchPackageForm' => $searchPackageForm)); ?>
<!--            </div>-->
<!--        </div>-->
<!--        --><?php //if ($package_search_filter) { ?>
<!---->
<!--        --><?php //} ?>
<!--    </div>-->
<!--</div>-->
<script>
    function getlistpackagefiber() {
        var province_code = document.getElementById('province_code').value;
        $.ajax({
            url: '<?=Yii::app()->controller->createUrl("package/listmytv");?>',
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

<div class="chose-used">
    <div class="container">
<!--        <div class="row">-->
<!--            <div class="col- col-sm-12">-->
<!--                <div class="title-chose-used">-->
<!--                    Bạn đang sử dụng dịch vụ internet của VNPT không?-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="btn-chose-used">-->
<!--                <div class="row">-->
<!--                    <div class="col-sm-4"></div>-->
<!--                    <div class="col-sm-2 text-center">-->
<!--                        <a onclick="getListPackageMyTV()" class="btn btn-custom-chose">Đang sử dụng</a>-->
<!--                    </div>-->
<!--                    <div class="col-sm-2 text-center">-->
<!--                        <a data-toggle="modal" data-target="#CHOSE_COMBO" class="btn btn-custom-chose">Chưa sử dụng</a>-->
<!--                    </div>-->
<!--                    <div class="col-sm-4"></div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</div>
<div id="list-item-package_mytv">

</div>
<!-- Modal -->
<!--<div id="TYPE_TV" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">-->
<!--    <div class="modal-dialog">-->
<!---->
<!--        <div class="modal-content">-->
<!--            <div class="modal-header">-->
<!--                <button type="button" class="close" data-dismiss="modal">&times;</button>-->
<!--                <h4 class="modal-title">Bạn đang sử dụng loại TV nào?</h4>-->
<!--            </div>-->
<!--            <div class="modal-body" id="content_modal_custom">-->
<!--                <div class="row">-->
<!--                    <div class="col-lg-2 text-center"></div>-->
<!--                    <div class="col-lg-4 text-center">-->
<!--                        <a class="btn btn-custom-chose" onclick="getListPackageSmartTV()">-->
<!--                            Smart TV-->
<!--                        </a>-->
<!--                    </div>-->
<!--                    <div class="col-lg-4 text-center">-->
<!--                        <a class="btn btn-custom-chose" onclick="getListPackageNormalTV()">TV cơ bản-->
<!--                        </a>-->
<!--                    </div>-->
<!--                    <div class="col-lg-2 text-center"></div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--    </div>-->
<!--</div>-->

<div id="CHOSE_COMBO" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="margin-top: -12px !important;">&times;</button>
                <h4 class="modal-title"> </h4>
            </div>
            <div class="modal-body" id="content_modal_custom">
                <div class="row">
                    <div class="col-lg-12 text-center" style="color: #2C79C1; margin-bottom: 20px">
                        Quý khách chưa lắp đặt dịch vụ Internet VNPT, xin vui lòng chọn 1 gói cước Internet cáp quang trước khi sử dụng dịch vụ MyTV.
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2 text-center"></div>
                    <div class="col-lg-8 text-center">
                        <a class="btn btn-custom-chose" href="<?php echo Yii::app()->controller->createUrl('package/indexfiber', array('m' => 'mytv')); ?>">
                            Tiếp tục
                        </a>
                    </div>
                    <div class="col-lg-2 text-center"></div>
                </div>
            </div>
        </div>

    </div>
</div>
<style>
    .chose-used {
        width: 100%;
        float: left;
    }

    .title-chose-used {
        width: 100%;
        float: left;
        text-align: center;
        font-size: 18px;
    }

    .btn-chose-used {
        width: 100%;
        float: left;
        margin-top: 20px;

    }
    .btn-custom-chose{
        background: #f92b80;
        color: #fff;
        margin-bottom: 10px;
    }
    .close{
        color: #f92b80;
    }
    .close:hover{
        color: #f92b80;
    }
    #list-item-package_mytv{
        float: left;
        width: 100%;
        text-align: center;
        margin-top: 10px;
    }
    #content_modal_custom{
        text-align: center;
    }
    #content_modal_custom img{
        width: 170px;
    }
    #list-item-package_mytv img.loading_img{
        width: 170px;
    }
    .package_freedoo, .package_other{
        padding: 0px !important;
    }
</style>
<script>
    function getListPackageMyTV() {
        $.ajax({
            url: '<?=Yii::app()->controller->createUrl("package/listmytv");?>',
            method: 'GET',
            data: {
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
            },
            dataType: 'json',
            beforeSend: function() {
                $("#list-item-package_mytv").html("<img class='loading_img' src='https://merchant.vban.vn/freedoo/Resources/images/preload.svg' />");
            },
            success: function (result) {
                $('#TYPE_TV').modal('toggle');
                $('#list-item-package_mytv').html(result.content);
            }
        });
    }
    function getListPackageNormalTV() {
        $.ajax({
            url: '<?=Yii::app()->controller->createUrl("package/listnomalmytv");?>',
            method: 'GET',
            data: {
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
            },
            dataType: 'json',
            beforeSend: function() {
                $("#list-item-package_mytv").html("<img class='loading_img' src='https://merchant.vban.vn/freedoo/Resources/images/preload.svg' />");
            },
            success: function (result) {
                $('#TYPE_TV').modal('toggle');
                $('#list-item-package_mytv').html(result.content);
            }
        });
    }
    $(window).on('load', function () {
        var fb = location.search.split('fb=')[1]
        if(fb == 2 && fb != ''){
            $("#TYPE_TV").modal()
        }

        $.ajax({
            url: '<?=Yii::app()->controller->createUrl("package/listmytv");?>',
            method: 'GET',
            data: {
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
            },
            dataType: 'json',
            beforeSend: function() {
                $("#list-item-package_mytv").html("<img class='loading_img' src='https://merchant.vban.vn/freedoo/Resources/images/preload.svg' />");
            },
            success: function (result) {
                $('#TYPE_TV').modal('toggle');
                $('#list-item-package_mytv').html(result.content);
            }
        });
    });
</script>
