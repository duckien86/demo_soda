<?php
    /* @var $this AFTContractsController */
    /* @var $model AFTContracts */
    /* @var $modelDetail AFTContractsDetails */
    /* @var $modelFiles AFTFiles */
    /* @var $form CActiveForm */
    /* @var $packages AFTPackage */
    /* @var $details array */
?>

<div class="form">
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                   => 'aftcontracts-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
//        'enableAjaxValidation' => TRUE,
        'htmlOptions'          => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
    )); ?>

    <p class="note"><?= Yii::t('adm/actions', 'required_field') ?></p>

    <?php echo $form->errorSummary($model); ?>

    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-6">
<!--            <div class="form-group">-->
<!--                --><?php //echo $form->labelEx($model, 'code', array('class' => 'col-md-3')); ?>
<!--                <div class="col-md-9">-->
<!--                    --><?php //echo $form->textField($model, 'code', array('class' => 'textbox', 'size' => 60, 'maxlength' => 255)); ?>
<!--                    --><?php //echo $form->error($model, 'code'); ?>
<!--                </div>-->
<!--            </div>-->
            <div class="form-group">
                <?php echo $form->labelEx($model, 'start_date', array('class' => 'col-md-3')); ?>
                <div class="col-md-9">
                    <div class="input-prepend input-group">
                <span class="add-on input-group-addon">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                </span>
                        <?php
                            echo $form->textField($model, 'start_date', array('class' => 'form-control'));
                        ?>
                    </div>
                    <?php echo $form->error($model, 'start_date'); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'user_id', array('class' => 'col-md-3')); ?>
                <div class="col-md-9">
                    <?php $this->widget(
                        'booster.widgets.TbSelect2',
                        array(
                            'model'       => $model,
                            'attribute'   => 'user_id',
                            'data'        => AFTUsers::getAllUserTourist(),
                            'htmlOptions' => array(
                                'class'     => 'form-control',
                                'multiple'  => FALSE,
                                'prompt'    => Yii::t('adm/label', 'select'),
                                'onchange'  => 'loadAFTContractPackage()',
                            ),
                        )
                    );?>
                    <?php echo $form->error($model, 'user_id'); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo CHtml::label(Yii::t('adm/label', 'folder_path_contract') . ' <span class="required">*</span>', 'AFTFiles_folder_path',
                    array('class' => 'col-md-3 required')) ?>
                <div class="col-md-9">
                    <?php echo $form->fileField($modelFiles, 'folder_path', array('style' => 'height:28px;')); ?>
                    <?php echo $form->error($modelFiles, 'folder_path'); ?>
                    <?php
                        if (file_exists('../' . $modelFiles->folder_path) && is_file('../' . $modelFiles->folder_path)) {
                            echo CHtml::link('<i class="glyphicon glyphicon-download-alt"></i> ' . Yii::t('adm/label', 'file_selected'),
                                '../' . $modelFiles->folder_path,
                                array('title' => '', 'target' => '_blank'));
                        }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'finish_date', array('class' => 'col-md-3')); ?>
                <div class="col-md-9">
                    <div class="input-prepend input-group">
                <span class="add-on input-group-addon">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                </span>
                        <?php
                            echo $form->textField($model, 'finish_date', array('class' => 'form-control'));
                        ?>
                    </div>
                    <?php echo $form->error($model, 'finish_date'); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'note', array('class' => 'col-md-3')); ?>
                <div class="col-md-9">
                    <?php echo $form->textArea($model, 'note', array('class' => 'textbox', 'maxlength' => 255)); ?>
                    <?php echo $form->error($model, 'note'); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <?php echo $form->error($model, 'detail'); ?>
        <div class="space_10"></div>
<!--        --><?php //if ($packages): ?>
            <fieldset class="list_package">
                <legend><?= Yii::t('adm/label', 'list_product') ?></legend>
                <div id="aftcontract-packages">
                    <?php $this->renderPartial('_list_package_form', array(
                        'modelDetail' => $modelDetail,
                        'packages'    => $packages,
                        'details'     => $details,
                        'form'        => $form,
                    )); ?>
                </div>
            </fieldset>
<!--        --><?php //endif; ?>
    </div>
    <div class="space_10"></div>
    <div class="buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('adm/actions', 'create') : Yii::t('adm/actions', 'save'), array('class' => 'btn btn-success')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/moment.min2.js"></script>
<script type="text/javascript"
        src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datepicker/daterangepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#AFTContracts_start_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
//            timePicker: true,
            timePickerIncrement: 5,
            format: 'DD/MM/YYYY ',
            buttonClasses: ['btn btn-default'],
            applyClass: 'btn-small btn-primary',
            cancelClass: 'btn-small',
            locale: {
                applyLabel: 'Áp dụng',
                cancelLabel: 'Đóng',
                daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                firstDay: 1
            }
        }, function () {
        });

        $('#AFTContracts_finish_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
//            timePicker: true,
            timePickerIncrement: 5,
            format: 'DD/MM/YYYY',
            buttonClasses: ['btn btn-default'],
            applyClass: 'btn-small btn-primary',
            cancelClass: 'btn-small',
            locale: {
                applyLabel: 'Áp dụng',
                cancelLabel: 'Đóng',
                daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                firstDay: 1
            }
        }, function () {
        });
    });

    function loadAFTContractPackage(){
        var form = $('#aftcontracts-form');
        var user = $('#AFTContracts_user_id').val();
        $.ajax({
            url: '<?php echo Yii::app()->controller->createUrl('aFTContracts/getAFTContractPackage')?>',
            type: 'post',
            dataType: 'html',
            data: form.serialize(),
            success: function (result) {
                $('#aftcontract-packages').html(result);
            }
        });
    }

</script>
