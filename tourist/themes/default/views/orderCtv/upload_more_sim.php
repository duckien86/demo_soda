<?php
/**
 * @var $this OrderCtvController
 * @var $model TOrders
 * @var $form TbActiveForm
 */
?>

<div id="order" class="order-form">
<?php $this->widget('booster.widgets.TbDetailView', array(
    'data'       => $model,
    'type'       => '',
    'htmlOptions'=> array(
        'class' => 'table table-bordered table-striped table-responsive table-hover td-width-50',
        'style' => 'margin-top: 20px',
    ),
    'attributes' => array(
        array(
            'name'        => Yii::t('tourist/label','order_code'),
            'value'       => function ($data) {
                return Chtml::encode($data->code);
            },
            'htmlOptions' => array('style' => 'vertical-align:middle;'),
        ),
        array(
            'name'        => Yii::t('tourist/label','quantity'),
            'value'       => function($data) {
                $value = TOrders::getTotalQuantity($data);
                return number_format($value,0,',','.');
            },
            'htmlOptions' => array('style' => 'vertical-align:middle;'),
        ),
        array(
            'name'        => Yii::t('tourist/label','total_success'),
            'value'       => function($data) {
                return number_format($data->total_success,0,',','.');
            },
            'htmlOptions' => array('style' => 'vertical-align:middle;'),
        ),
        array(
            'name'        => Yii::t('tourist/label','total_fails'),
            'value'       => function($data) {
                return number_format($data->total_fails,0,',','.');
            },
            'htmlOptions' => array('style' => 'vertical-align:middle;'),
        ),
    ),
));
?>

<div class="space_20"></div>

<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id'=>'torder-form',
    'method' => 'post',
//    'enableAjaxValidation' => true,
//    'enableClientValidation' => true,
//    'action'=> Yii::app()->controller->createUrl('order/create'),
    'htmlOptions' => array('enctype' => 'multipart/form-data', ),
)); ?>

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
            </span>
        <i class="fa fa-upload" aria-hidden="true"></i>
        <?php echo $form->error($model, 'file_sim'); ?>
    </div>

    <!-- File Sim Quantity-->
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2">
            <?php echo CHtml::label(Yii::t('tourist/label','quantity'), '', array('class'=>'form-title')) ?>
            <?php echo $form->numberField($model, 'quantity', array(
                'class' => 'form-control form-item',
                'readonly'  => true,
            ))?>
            <i class="fa fa-number" aria-hidden="true"></i>
            </div>
            <?php echo $form->error($model, 'quantity'); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo CHtml::submitButton("Bổ sung Sim", array('id' => 'btn-submit', 'class' => 'btn btn-lg')); ?>
    </div>

<?php $this->endWidget(); ?>

</div>

<script>
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
            }
        });
    }
</script>