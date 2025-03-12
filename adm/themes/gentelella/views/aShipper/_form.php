<?php
    /* @var $this CskhShipperController */
    /* @var $model CskhShipper */
    /* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'                   => 'cskh-shipper-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => TRUE,
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>


    <div class="row">
        <div class="col-md-8">
            <div class="col-md-6 col-xs-12">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'username'); ?>
                    <?php echo $form->textField($model, 'username', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'username'); ?>
                </div>
                <?php if ($model->isNewRecord): ?>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'password'); ?>
                        <?php echo $form->passwordField($model, 'password', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                        <?php echo $form->error($model, 'password'); ?>
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'full_name'); ?>
                    <?php echo $form->textField($model, 'full_name', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'full_name'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'phone_1'); ?>
                    <?php echo $form->textField($model, 'phone_1', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control', 'placeholder' => 'Số ELoad để ĐKTTTB')); ?>
                    <?php echo $form->error($model, 'phone_1'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'phone_2'); ?>
                    <?php echo $form->textField($model, 'phone_2', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control', 'placeholder' => 'Nhận thông báo đơn hàng mới')); ?>
                    <?php echo $form->error($model, 'phone_2'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'address_detail'); ?>
                    <?php echo $form->textField($model, 'address_detail', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'address_detail'); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->labelEx($model, 'birthday'); ?>
                    <div class="input-prepend input-group">
                                        <span class="add-on input-group-addon">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </span>
                        <?php
                            $model->birthday = ($model->isNewRecord) ? date('d/m/Y') : date('d/m/Y', strtotime($model->birthday));
                            echo $form->textField($model, 'birthday', array('class' => 'form-control', 'size' => 25, 'maxlength' => 50));
                        ?>
                    </div>
                    <?php echo $form->error($model, 'birthday'); ?>
                </div>

                <div class="form-group">
                    <div class="checkbox-nopad">
                        <label>
                            <?php echo $form->labelEx($model, 'gender'); ?>
                            <?php
                                $accountStatus = array(0 => 'Nam', 1 => 'Nữ');
                                echo $form->radioButtonList($model, 'gender', $accountStatus, array('separator' => ' '));
                            ?>
                            &nbsp;&nbsp;&nbsp;</label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="checkbox-nopad">
                        <label>
                            <?php
                                if ($model->isNewRecord) {
                                    echo $form->checkBox($model, 'status', array('checked' => 'checked', 'class' => 'flat')) . ' ' . Yii::t('adm/app', 'Active');
                                } else {
                                    echo $form->checkBox($model, 'status', array('class' => 'flat')) . ' ' . Yii::t('adm/app', 'Active');
                                }
                            ?>
                            &nbsp;&nbsp;&nbsp;</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xs-12">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'province_code'); ?>
                    <?php
                        $this->widget(
                            'booster.widgets.TbSelect2',
                            array(
                                'model'       => $model,
                                'attribute'   => 'province_code',
                                'data'        => AProvince::model()->getAllProvince(),
                                'htmlOptions' => array(
                                    'multiple' => FALSE,
                                    'prompt'   => (!ADMIN && !SUPER_ADMIN) ? NULL : Yii::t('report/menu', 'province_code'),
                                    'ajax'     => array(
                                        'type'   => 'POST',
                                        'url'    => Yii::app()->createUrl('aShipper/getSaleOfficeByProvince'), //or $this->createUrl('loadcities') if '$this' extends CController
                                        'update' => '#AShipper_sale_offices_code',
                                        'data'   => array('province_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                    ),
                                    //reset value selected
                                    'style'    => 'width:100%'
                                ),
                            )
                        );
                    ?>
                    <?php echo $form->error($model, 'province_code'); ?>
                </div>
                <div class="form-group">

                    <?php echo $form->labelEx($model, 'sale_offices_code'); ?>
                    <?php
                        $this->widget(
                            'booster.widgets.TbSelect2',
                            array(
                                'model'       => $model,
                                'attribute'   => 'sale_offices_code',
                                'data'        => ($model->province_code != '') ? ASaleOffices::model()->getSaleOfficesByProvince($model->province_code) : (!ADMIN && !SUPER_ADMIN) ? ASaleOffices::model()->getAllSaleOffices() : array(),
                                'htmlOptions' => array(
                                    'multiple' => FALSE,
                                    'prompt'   => (!ADMIN && !SUPER_ADMIN) ? NULL : Yii::t('report/menu', 'sale_offices_id'),
                                    'ajax'     => array(
                                        'type'   => 'POST',
                                        'url'    => Yii::app()->createUrl('aTraffic/getBrandOfficeBySaleCode'), //or $this->createUrl('loadcities') if '$this' extends CController
                                        'update' => '#User_brand_offices_id',
                                        'data'   => array('sale_offices_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                    ),
                                    'onchange' => '$("#User_brand_offices_id").select2("val", "");
                                    ',
                                    //reset value selected
                                    'style'    => 'width:100%'
                                ),
                            )
                        );
                    ?>
                    <?php echo $form->error($model, 'sale_offices_id'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'email'); ?>
                    <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'email'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'personal_id'); ?>
                    <?php echo $form->textField($model, 'personal_id', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'personal_id'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'personal_id_create_place'); ?>
                    <?php echo $form->textField($model, 'personal_id_create_place', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'personal_id_create_place'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'personal_id_create_date'); ?>
                    <div class="input-prepend input-group">
                                        <span class="add-on input-group-addon">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </span>
                        <?php
                            $model->personal_id_create_date = ($model->isNewRecord) ? date('d/m/Y') : date('d/m/Y', strtotime($model->personal_id_create_date));
                            echo $form->textField($model, 'personal_id_create_date', array('class' => 'form-control', 'size' => 25, 'maxlength' => 50));
                        ?>
                    </div>
                    <?php echo $form->error($model, 'personal_id_create_date'); ?>
                </div>

            </div>
        </div>
    </div>
    <div class="row buttons">
        <div class="form-group" style="margin-left:30px;">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-warning')); ?>
        </div>
    </div>


    <?php $this->endWidget(); ?>

</div><!-- form -->
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/moment.min2.js"></script>
<script type="text/javascript"
        src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datepicker/daterangepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        $('#AShipper_birthday').daterangepicker({
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

        $('#AShipper_personal_id_create_date').daterangepicker({
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

</script>
<style>
    .errorMessage {
        position: absolute;
        color: red;
    }

    .form-group {
        margin-bottom: 20px;
    }

    #AShipper_gender {
        float: right;
    }
</style>