<?php
/**
 * @var $this OrderController
 * @var $model TOrders
 * @var $province array
 * @var $district array
 * @var $ward array
 * @var $list_packages array
 * @var $form TbActiveForm
 */

$this->step = OrderController::STEP_FILL_ORDER;
?>

<?php $this->renderPartial('/order/_block_form_wizard');?>
<?php $this->renderPartial('/order/_modal_contract')?>

<div class="row">
<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id'=>'torder-form',
    'method' => 'post',
//    'enableAjaxValidation' => true,
//    'enableClientValidation' => true,
//    'action'=> Yii::app()->controller->createUrl('order/create'),
    'htmlOptions' => array('enctype' => 'multipart/form-data', ),
)); ?>
    <div class="col-sm-12 order-form">
        <div class="order-panel">
            <!-- Chọn hợp đồng -->
            <div class="form-group">
            <?php
            if(Yii::app()->controller->action->id == 'create'){
                echo $form->labelEx($model, 'contract_id', array('class' => 'form-title'));
                $this->widget(
                    'booster.widgets.TbSelect2',
                    array(
                        'model'       => $model,
                        'attribute'   => 'contract_id',
                        'data'        => CHtml::listData(TContracts::getListContractsByUser(Yii::app()->user->id),'id','code'),
                        'htmlOptions' => array(
                            'class'    => 'form-control form-item',
                            'multiple' => FALSE,
                            'prompt'   => Yii::t('tourist/label', 'contract_id'),
                            'onchange' => 'loadOrderPackages();loadContractModal();',
                        ),
                    )
                );
            }elseif(Yii::app()->controller->action->id == 'update'){
                echo '<label class="form-title" style="width: auto">'. Yii::t('tourist/label','contract_id') . ': <b class="text-primary">' . TContracts::getContractCodeByOrder($model) .'</b></label>';
                echo $form->textField($model,'contract_id', array('class' => 'hidden'));
            }

            echo CHtml::link(Yii::t('tourist/label','view_contract'), '#', array(
                'id'          => 'btn-modal-contract',
                'class'       => 'hidden',
                'data-toggle' => 'modal',
                'data-target' => '#modal_contract',
                'style'       => 'margin-left: 10px;',
            ));

            echo $form->error($model, 'contract_id');
            ?>
            </div>

            
            <div class="form-group">
                <label class="form-title">
                    <?php echo CHtml::encode(Yii::t('tourist/label','promo_code_2'))?>
                </label>
                <?php echo $form->checkBox($model, 'use_promo_code', array(
                    'value' => 1,
                    'onchange'   => 'loadOrderPackages();',
                ));?>
                Sử dụng mã CTV/ mã giới thiệu (tính hoa hồng, không được tính chiết khấu)
            </div>
            <div class="form-group">
                <?php echo $form->textField($model, 'promo_code', array(
                    'class' => 'form-control form-item',
                    'readonly' => ($model->use_promo_code) ? false : true,
                    'required' => ($model->use_promo_code) ? true : false,
                ));?>
                <?php echo $form->error($model,'promo_code');?>
            </div>

            <!-- Chọn Kit -->
            <div class="form-group">
                <label class="form-title"><?php echo CHtml::encode(Yii::t('tourist/label', 'select_kit')) ?> <span>*</span></label>
                <div id="block-contract-item">
                    <?php
                    $this->renderPartial('/order/_block_order_item', array(
                        'list_packages' => $list_packages,
                        'order' => $model,
                    ));
                    ?>
                </div>
                <?php echo $form->error($model, 'packages'); ?>
            </div>

            <div class="order-form-separator"></div>

            <!-- Ủy nhiệm chi -->
            <div class="form-group">
                <?php echo CHtml::label(Yii::t('tourist/label','accepted_payment_files'), '', array('class'=>'form-title')) ?>
                <?php echo CHtml::fileField(CHtml::activeName($model,'accepted_payment_files'), $model->getAcceptedPaymentFileUrl(), array(
                    'class'     => 'form-item hidden',
                    'accept'    => 'image/*,.pdf',
                    'runat'     => 'server',
                    'onchange'  => "showFileName(this.id, '#TOrders_accepted_payment_files_name')",
                )) ?>
                <a onclick="$('#TOrders_accepted_payment_files').trigger('click');" id="btnFile" class="btn btn-xs">Chọn tệp</a>
                <span id="TOrders_accepted_payment_files_name">
                    <?php if($model->id && $file = TFiles::getFile(TOrders::OBJECT_FILE_ACCEPT_PAYMENT, $model->id)){
                        echo CHtml::encode($file->file_name . '.' .$file->file_ext);
                    } ?>
                </span>
                <i class="fa fa-upload" aria-hidden="true"></i>
                <?php echo $form->error($model, 'accepted_payment_files'); ?>
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
                                'url'    => Yii::app()->controller->createUrl('order/getDistrictByProvince'), //or $this->createUrl('loadcities') if '$this' extends CController
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
                                'url'      => Yii::app()->controller->createUrl('order/getWardBrandOfficesByDistrict'), //or $this->createUrl('loadcities') if '$this' extends CController
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

    loadContractModal();

    $('.form-item').on('change', function () {
        removeError(this);
    });

    $('#TOrders_use_promo_code').on('change', function (){
        var input = $('#TOrders_promo_code');
        if(this.checked){
            input.attr('readonly',false);
            input.attr('required',true);
        }else{
            input.attr('readonly',true);
            input.attr('required',false);
            input.val('');
        }
    });
});

function loadContractModal() {
    var form = $('#torder-form');
    $('#modal_contract .modal-body').html('');
    if(form.find('#TOrders_contract_id').val()){
        $('#btn-modal-contract').removeClass('hidden');
        $.ajax({
            url: '<?php echo Yii::app()->controller->createUrl('order/getContractInfo')?>',
            type: 'post',
            dataType: 'html',
            data: form.serialize(),
            success: function (result) {
                $('#modal_contract .modal-body').html(result);
            }
        });
    }else{
        $('#btn-modal-contract').addClass('hidden');
    }

}

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
        url: '<?php echo Yii::app()->controller->createUrl('order/getOrderPackages')?>',
        type: 'post',
        dataType: 'html',
        data: form.serialize(),
        success: function (result) {
            $('table#contract-package tbody').html(result);
            loadOrderTotalPrice();
        }
    });
}

function loadContractRemainAmount(item){
    var item_container = $(item).closest('.order-item');

    var quantity;

    var max = parseInt($(item).attr('max'));
    var min = 0;
    var price = parseInt(item_container.find('td.item-price').attr('data-value').trim());
    var remain = parseInt(item_container.find('td.item-quantity-remain').attr('data-value').trim());
    var discount = parseFloat(item_container.find('td.item-discount').attr('data-value').trim());
    var discount_type = item_container.find('td.item-discount').attr('data-type').trim();

    if($(item).val().trim()){
        quantity = parseInt($(item).val().trim());
    }else{
        quantity = 0;
    }

    if(quantity < 0){
        quantity = 0;
        $(item).val(quantity);
    }else if(quantity > max){
        quantity = max;
        $(item).val(quantity);
    }

    var total = price*quantity;
    if(discount > 0){
        if(discount_type === '<?php echo TContractsDetails::DISCOUNT_AMOUNT?>'){
            total -= quantity*discount;
        }else if(discount_type === '<?php echo TContractsDetails::DISCOUNT_PERCENT?>'){
            total -= total*discount/100;
        }
    }

    var value = formatNumber(total);
    value+= ' đ';

    item_container.find('td.item-quantity-remain label').html(max-quantity);
    item_container.find('td.item-total-price label').html(value);
    item_container.find('td.item-total-price input').val(total);

    loadOrderTotalPrice();
}

function loadOrderTotalPrice(){
    var order_total_price = 0;
    var value = 0;
    $('#contract-package tbody').find('td.item-total-price input').each(function () {
        var item_total = $(this).val();
        order_total_price += parseInt(item_total);
    });
    value = formatNumber(order_total_price);
    value+=' đ';
    $('#contract-package tfoot').find('.order-total-price label').html(value);
}
</script>
