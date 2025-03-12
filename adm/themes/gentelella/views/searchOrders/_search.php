<?php
    /* @var $this CskhCustomersController */
    /* @var $model CskhCustomers */
    /* @var $form CActiveForm */
?>
<div class="col-md-12">
    <div class="wide form">

        <?php $form = $this->beginWidget('CActiveForm', array(
            'action' => Yii::app()->createUrl($this->route),
            'method' => 'post',
        )); ?>

        <div class="row">

            <div class="col-md-3">
                <?php echo $form->label($model, 'input_type'); ?>
            </div>
            <div class="col-md-3">
                <?php echo $form->label($model, 'order_search'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo $form->dropDownList($model, 'input_type', $model->getSearchOrderBox(), array(
                            'class' => 'form-control',
                        )
                    ); ?>
                    <?php echo $form->error($model, 'input_type'); ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo $form->textField($model, 'order_search', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control', 'placeholder' => 'Nhập thông tin ...')); ?>
                    <?php echo $form->error($model, 'order_search'); ?>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <?php echo CHtml::submitButton('Tìm kiếm', array('class' => 'btn btn-default')); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 order-states">
                <div class="form-group">
                    <?php echo $form->label($model, 'status_state'); ?>
                    <?php echo $form->dropDownList($model, 'status_state', $model->getAllStatus(), array(
                            'class' => 'form-control',
                            'empty' => 'Chọn tất cả',
                        )
                    ); ?>
                    <?php echo $form->error($model, 'status_state'); ?>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<script type="text/javascript">
    if (($('#AOrders_input_type :selected').val() == 'order_id') || ($('#AOrders_input_type :selected').val() == 'sim')) {
        $('.order-states').css("display", "none");
        $('.label-states').css("display", "none");
    } else {
        $('.order-states').css("display", "inline");
        $('.label-states').css("display", "inline");
    }
    $('#AOrders_input_type').change(function () {
        var selected_check = $('#AOrders_input_type :selected').val();
        if (selected_check == 'order_id' || selected_check == 'sim') {
            $('.order-states').css("display", "none");
            $('.label-states').css("display", "none");
        } else {
            $('.order-states').css("display", "inline");
            $('.label-states').css("display", "inline");
        }
    });
</script>