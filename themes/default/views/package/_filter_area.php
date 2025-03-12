<?php
/* @var $this PackageController */
/* @var $searchPackageForm SearchPackageForm */
/* @var $form TbActiveForm */
/* @var $package_search_filter boolean */
?>
<div class="package_filter_area">
    <div class="row">
        <div class="col-sm-6">
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
                    <div class="col-sm-12">
                        <div class="title">
                            <?php echo CHtml::label(CHtml::encode(Yii::t('web/portal','your_package')),'')?>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="search">
                            <?php echo $form->textField($searchPackageForm, 'msisdn', array(
                                'id'          => 'input_msisdn',
                                'class'       => 'form-control input-search',
                                'maxlength'   => 20,
                                'placeholder' => Yii::t('web/portal', 'package_search_msisdn')
                            )); ?>
                            <!--    --><?php //echo CHtml::submitButton(Yii::t('web/portal','search'), array('class' => 'hidden'));?>

                            <button type="button" class="btn btn-search" id="btn_search_msisdn">
                                <img src="<?php echo Yii::app()->theme->baseUrl ?>/images/package_search_icon.png">
                            </button>
                        </div>
                    </div>
                </div>


                <?php $this->endWidget() ?>

                <?php $this->renderPartial('_modal_confirm_search', array('searchPackageForm' => $searchPackageForm)); ?>
            </div>
        </div>

        <?php if($package_search_filter){?>
            <div class="col-sm-6 package_search_filter_separator">
                <div class="package_search_filter">
                    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                        'id'                   => 'searchpackage-form',
                        'method'               => 'POST',
//                    'enableAjaxValidation' => TRUE,
                        'htmlOptions'   => array(
                            'onsubmit'  => 'return false',
                        ),
                    )); ?>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="title">
                                <?php echo CHtml::label(CHtml::encode(Yii::t('web/portal','sort_with')),'')?>
                            </div>
                            <div class="sortGroup">
                                <div class="sortType">
                                    <?php echo $form->dropDownList($searchPackageForm, 'sortType', CHtml::listData(SearchPackageForm::getListSortType(),'id','name'))?>
                                </div>
                                <div class="sortOrder">
                                    <?php echo $form->dropDownList($searchPackageForm, 'sortOrder', CHtml::listData(SearchPackageForm::getListSortOrder(),'id','name'))?>
                                </div>

                                <div id="loadingPackageImg" class="spinner hidden"></div>
                            </div>


                        </div>

                        <div class="col-sm-12">
                            <div class="search">
                                <?php echo $form->textField($searchPackageForm,'key', array(
                                    'class' => 'form-control input-search',
                                    'placeholder' => Yii::t('web/portal','search_package')
                                ))?>

                                <input type="hidden" name="activeId" id="activeId" value="<?php echo WPackage::PACKAGE_HOT?>"/>

                                <button type="button" class="btn btn-search" onclick="filteringPackages()">
                                    <img src="<?php echo Yii::app()->theme->baseUrl?>/images/package_search_icon.png">
                                </button>

                            </div>
                        </div>

                    </div>

                    <?php $this->endWidget(); ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<script>

    $(document).ready(function () {
        $('#input_msisdn').enterKey(function () {
            $('#btn_search_msisdn').click();
        });

        $('#btn_search_msisdn').on('click', function (e) {
            var modal = $('#confirm_search_package');
            var msisdn = $('#input_msisdn').val();
            if (msisdn) {
                $('#SearchPackageForm_msisdn').val(msisdn);
                console.log(msisdn);
                modal.modal('show');
            } else {
                alert('Quý khách vui lòng nhập vào số điện thoại');
            }
        });


        $('#SearchPackageForm_key').enterKey(function () {
            filteringPackages();
        });
        $('#SearchPackageForm_sortType').on('change',function () {
            filteringPackages();
        });
        $('#SearchPackageForm_sortOrder').on('change',function () {
            filteringPackages();
        });
        $('#list_category_package').on('click','a[role=tab]',function () {
            var activeId = $(this).attr('data-type');
            $('#searchpackage-form').find('#activeId').first().val(activeId);
        });
    });


    function filteringPackages() {
        var form = $('#searchpackage-form');
        var container = $('#packages_tabs');
        var activeId = container.find('.active[role=tabpanel]').first().attr('data-type');
        var imgLoading = $('#loadingPackageImg');
        imgLoading.removeClass('hidden');
        $.ajax({
            url: '<?php echo Yii::app()->controller->createUrl('package/ajaxSearchPackage')?>',
            type: 'post',
            dataType: 'html',
            data: form.serialize(),
            success: function (result) {
                container.html(result);
                imgLoading.addClass('hidden');
            }
        });
    }
</script>
