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

            <!-- Chọn Kit -->
            <div class="form-group">
                <label class="form-title"><?php echo CHtml::encode(Yii::t('tourist/label', 'select_kit')) ?> <span>*</span></label>
                <div id="block-contract-item">
                    <?php
                    $this->renderPartial('/orderCtv/_block_order_item', array(
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
                                        $("#TOrders_ward_code").select2("val", "");
                                        ', //reset value selected
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
                            'class'    => "form-control form-item",
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
                    'class' => "form-control form-item",
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

function loadContractRemainAmount(item){
    var item_container = $(item).closest('.order-item');

    var quantity;

    var min = 0;
    var price = parseInt(item_container.find('td.item-price').attr('data-value').trim());

    if($(item).val().trim()){
        quantity = parseInt($(item).val().trim());
    }else{
        quantity = 0;
    }

    if(quantity < 0){
        quantity = 0;
        $(item).val(quantity);
    }

    var total = price*quantity;

    var value = formatNumber(total);
    value+= ' đ';

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
