<?php
/**
 * @var $this OrderCtvController
 * @var $model TOrders
 * @var $province array
 * @var $district array
 * @var $ward array
 * @var $list_packages array
 * @var $form TbActiveForm
 */

$this->step = OrderCtvController::STEP_FILL_ORDER;
?>

<?php $this->renderPartial('/orderCtv/_block_form_wizard');?>

<div class="row">
<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id'=>'torder-form',
    'method' => 'post',
//    'enableAjaxValidation' => true,
//    'enableClientValidation' => true,
    'htmlOptions' => array('enctype' => 'multipart/form-data', ),
)); ?>
    <div class="col-sm-12 order-form">
        <div class="order-panel">

            <!-- File Sim -->
            <div class="form-group">
                <?php echo CHtml::label(Yii::t('tourist/label','file_sim'), '', array('class'=>'form-title')) ?>
                <?php echo CHtml::fileField(CHtml::activeName($model,'file_sim'), $model->getFileSimUrl(), array(
                    'class'     => 'form-item hidden',
                    'accept'    => 'text/plain',
                    'runat'     => 'server',
                    'onchange'  => "showFileName(this.id, '#TOrders_file_sim_name'); getFileSimQuantity();",
                )) ?>
                <a onclick="$('#TOrders_file_sim').trigger('click');" id="btnFile" class="btn btn-xs">Chọn tệp</a>
                <span id="TOrders_file_sim_name">
                    <?php if($model->id && $file = TFiles::getFile(TOrders::OBJECT_FILE_SIM, $model->id)){
                        echo CHtml::encode($file->file_name . '.' .$file->file_ext);
                    } ?>
                </span>
                <i class="fa fa-upload" aria-hidden="true"></i>
                <?php echo $form->error($model, 'file_sim'); ?>
            </div>
            <!-- File Sim Quantity-->
            <div class="form-group">


                <?php echo CHtml::label(Yii::t('tourist/label','quantity'), '', array('class'=>'form-title')) ?>
<!--                <span>--><?php //echo ($model->quantity) ? $model->quantity : 0?><!--</span>-->
<!--                <label class="form-title">--><?php //echo Yii::t('tourist/label','quantity') . ':'?><!--</label>-->
                <?php echo $form->numberField($model, 'quantity', array(
                    'class' => 'form-control form-item',
                    'readonly'  => true,
                ))?>
                <i class="fa fa-number" aria-hidden="true"></i>
                <?php echo $form->error($model, 'quantity'); ?>
            </div>

            <!-- Chọn Kit -->
            <div class="form-group">
                <label class="form-title"><?php echo CHtml::encode(Yii::t('tourist/label', 'select_kit')) ?> <span>*</span></label>
                <div id="block-contract-item">
                    <?php
                    $this->renderPartial('/orderCtv/_block_order_item2', array(
                        'list_packages' => $list_packages,
                        'order' => $model,
                    ));
                    ?>
                </div>
                <?php echo $form->error($model, 'packages'); ?>
            </div>
            <div class="order-form-separator"></div>

            <!-- Address -->
            <div class="form-group">
                <label for="" class="form-title"><?php echo CHtml::encode(Yii::t('tourist/label', 'address_receive')) ?> <span>*</span></label>
                <div class="">
                <?php
                $this->widget(
                    'booster.widgets.TbSelect2',
                    array(
                        'model'       => $model,
                        'attribute'   => 'province_code',
                        'data'        => $province,
                        'htmlOptions' => array(
                            'class'    => 'form-control form-item',
                            'multiple' => FALSE,
                            'prompt'   => Yii::t('tourist/label', 'province_code'),
                            'ajax'     => array(
                                'type'   => 'POST',
                                'url'    => Yii::app()->controller->createUrl('orderCtv/getDistrictByProvince'), //or $this->createUrl('loadcities') if '$this' extends CController
                                'update' => '#TOrders_district_code', //or 'success' => 'function(data){...handle the data in the way you want...}',
                                'data'   => array('province_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                            ),
                            'onchange' => ' $("#TOrders_district_code").select2("val", "");
                                        $("#TOrders_ward_code").select2("val", "");', //reset value selected
                            'style'=>'margin-top:2px',
                        ),
                    )
                );
                ?>

                <?php
                $this->widget(
                    'booster.widgets.TbSelect2',
                    array(
                        'model'       => $model,
                        'attribute'   => 'district_code',
                        'data'        => $district,
                        'htmlOptions' => array(
                            'class'    => 'form-control form-item',
                            'multiple' => FALSE,
                            'prompt'   => Yii::t('tourist/label', 'district_code'),
                            'ajax'     => array(
                                'type'     => 'POST',
                                'dataType' => 'json',
                                'url'      => Yii::app()->controller->createUrl('orderCtv/getWardBrandOfficesByDistrict'), //or $this->createUrl('loadcities') if '$this' extends CController
//                            'update' => '#TOrders_ward_code', //or 'success' => 'function(data){...handle the data in the way you want...}',
                                'success'  => 'function(data){
                                                $("#TOrders_ward_code").html(data.html_ward);
                                            }',
                                'data'     => array('district_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                            ),
                            'onchange' => '$("#TOrders_ward_code").select2("val", "");', //reset value selected
                            'style'=>'margin-top:2px',
                        ),
                    )
                );
                ?>

                <?php
                $this->widget(
                    'booster.widgets.TbSelect2',
                    array(
                        'model'       => $model,
                        'attribute'   => 'ward_code',
                        'data'        => $ward,
                        'htmlOptions' => array(
                            'class'    => 'form-control form-item',
                            'multiple' => FALSE,
                            'prompt'   => Yii::t('tourist/label', 'ward_code'),
//                            'ajax'     => array(
//                                'type'   => 'POST',
//                                'url'    => Yii::app()->controller->createUrl('checkout/getListBrandOffices'), //or $this->createUrl('loadcities') if '$this' extends CController
//                                'update' => '#TOrders_brand_offices', //or 'success' => 'function(data){...handle the data in the way you want...}',
//                                'data'   => array('ward_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
//                            ),
//                            'onchange' => '$(\'#TOrders_address_detail\').val($(\'#TOrders_ward_code\').find(\'option:selected\').text() + \', \' +$(\'#TOrders_district_code\').find(\'option:selected\').text() + \', \' + $(\'#TOrders_province_code\').find(\'option:selected\').text());',
                            'style'=>'margin-top:2px',
                        ),
                    )
                );
                ?>

                <?php echo $form->textArea($model, 'address_detail', array(
                    'class' => 'form-control form-item',
                    'placeholder' => Yii::t('tourist/label', 'address_detail'),
                    'style' => 'resize: vertical;',
                )) ?>
                </div>

                <?php echo $form->error($model, 'province_code'); ?>
                <?php echo $form->error($model, 'district_code'); ?>
                <?php echo $form->error($model, 'ward_code'); ?>
                <?php echo $form->error($model, 'address_detail'); ?>
            </div>

            <div class="order-form-separator"></div>

            <div class="row">
                <div class="col-sm-6">
                    <!-- Delivery date -->
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'delivery_date', array('class' => 'form-title'))?>
                        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'model'          => $model,
                            'attribute'      => 'delivery_date',
                            'language'       => 'vi',
                            'htmlOptions'    => array(
                                'class' => 'form-control',
                                'autocomplete' => 'off'
                            ),
                            'defaultOptions' => array(
                                'showOn'            => 'focus',
                                'dateFormat'        => 'dd/mm/yy',
                                'showOtherMonths'   => TRUE,
                                'selectOtherMonths' => TRUE,
                                'changeMonth'       => TRUE,
                                'changeYear'        => TRUE,
                                'showButtonPanel'   => TRUE,
                                'minDate'           => 0,
                            )
                        ));
                        ?>

                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        <?php echo $form->error($model, 'delivery_date'); ?>

                    </div>
                    <!-- Receiver name -->
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'receiver_name', array('class' => 'form-title'))?>
                        <?php echo $form->textField($model, 'receiver_name', array(
                            'class' => 'form-control form-item'
                        ))?>
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <?php echo $form->error($model, 'receiver_name'); ?>

                    </div>

                </div>
                <div class="col-sm-6">
                    <!-- Orderer name -->
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'orderer_name', array('class' => 'form-title'))?>
                        <?php echo $form->textField($model, 'orderer_name', array(
                            'class' => 'form-control form-item'
                        ))?>
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <?php echo $form->error($model, 'orderer_name'); ?>

                    </div>
                    <!-- Orderer phone -->
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'orderer_phone', array('class' => 'form-title'))?>
                        <?php echo $form->telField($model, 'orderer_phone', array(
                            'class' => 'form-control form-item',
                            'onchange'  => 'changeMsisdnPrefix(this, null);',
                        ))?>
                        <i class="fa fa-phone" aria-hidden="true"></i>
                        <?php echo $form->error($model, 'orderer_phone'); ?>
                    </div>
                </div>
            </div>

            <div class="order-form-separator"></div>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'note', array('class' => 'form-title'))?>
                <?php echo $form->textArea($model, 'note', array(
                    'class' => 'form-control',
                    'placeholder' => '',
                    'style' => 'resize: vertical',
                )) ?>
                <?php echo $form->error($model, 'note'); ?>
            </div>
        </div>
    </div>

    <div class="col-sm-12 order-form">
        <div class="form-group">
            <?php echo CHtml::submitButton(Yii::t('tourist/label', 'order_create'), array('id' => 'btn-submit', 'class' => 'btn btn-lg')); ?>
        </div>
    </div>

<?php $this->endWidget(); ?>
</div>

<script>
$(document).ready(function () {

    $('.form-item').on('change', function () {
        removeError(this);
    });
});

function removeError(item){
    $(item).closest('.form-group').find('div.help-block.error').each(function () {
        $(this).remove();
    });
}

function loadOrderPackages() {

    var check = $('#TOrders_use_promo_code').val();

    var form = $('#torder-form');
    $('table#contract-package tbody').html('');
    $.ajax({
        url: '<?php echo Yii::app()->controller->createUrl('orderCtv/getOrderFsPackages')?>',
        type: 'post',
        dataType: 'html',
        data: form.serialize(),
        success: function (result) {
            $('table#contract-package tbody').html(result);
            loadOrderTotalPrice();
        }
    });
}

function loadOrderTotalPrice(){

    var total = 0;
    var value = 0;
    var quantity = $('#TOrders_quantity').val();

    if(!quantity) quantity = 0;
    var item_container = $('#contract-package tbody').find('td.action input[type=radio]:checked').first().closest('.order-item');
    if(item_container.length){
        var price = parseInt(item_container.find('td.item-price').attr('data-value').trim());
        $('#contract-package tbody').find('.order-item[data-check="1"]').first().attr('data-check','0');
        item_container.attr('data-check', '1');
        total = price*quantity;
    }
    value = formatNumber(total);
    value+=' đ';
    $('#contract-package tfoot').find('.order-total-price label').html(value);
}

function getFileSimQuantity(){
    var form_data = new FormData(document.getElementById("torder-form"));//id_form
    $.ajax({
        url: '<?php echo Yii::app()->controller->createUrl('orderCtv/getFileSimQuantity')?>',
        type: 'post',
        dataType: 'html',
        data: form_data,
        enctype: 'multipart/form-data',
        processData: false,  // tell jQuery not to process the data
        contentType: false,   // tell jQuery not to set contentType
        crossDomain: true,
        success: function (result) {
            $('#TOrders_quantity').val(result);
            loadOrderPackages();
        }
    });
}

</script>
