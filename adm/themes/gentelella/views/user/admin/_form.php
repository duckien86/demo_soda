<div class="form">
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                   => 'user-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => TRUE,
    )); ?>
    <!--    --><?php //echo CHtml::beginForm('', 'post', array('class' => 'form-horizontal form-label-left', 'enctype' => 'multipart/form-data')); ?>
    <div class="col-md-12" style="text-align: left;margin-bottom: 20px;">
        <?php echo "Trường có dấu <span class='required'>*</span> là bắt buộc"; ?>
    </div>
    <?php echo CHtml::errorSummary(array($model, $profile)); ?>
    <?php
        $sale  = FALSE;
        $brand = FALSE;
        if (!ADMIN && !SUPER_ADMIN) {
            if (isset(Yii::app()->user->province_code)) {
                if (!empty(Yii::app()->user->province_code)) {
                    $model->province_code = Yii::app()->user->province_code;
                }
            }
            if (isset(Yii::app()->user->sale_offices_id)) {
                if (!empty(Yii::app()->user->sale_offices_id)) {

                    $sale = TRUE;

                    $model->sale_offices_id = Yii::app()->user->sale_offices_id;
                }
            }
            if (isset(Yii::app()->user->brand_offices_id)) {
                if (!empty(Yii::app()->user->brand_offices_id)) {

                    $brand = TRUE;

                    $model->brand_offices_id = Yii::app()->user->brand_offices_id;
                }
            }
        }

    ?>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <?php if ($model->isNewRecord): ?>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'username'); ?>
                    <?php echo $form->textField($model, 'username', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'username'); ?>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'password'); ?>
                    <?php echo $form->passwordField($model, 'password', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'password'); ?>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 're_password'); ?>
                    <?php echo $form->passwordField($model, 're_password', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 're_password'); ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'email'); ?>
                <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'email'); ?>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'phone'); ?>
                <?php echo $form->textField($model, 'phone', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control', 'placeholder' => 'Số ELOAD để ĐKTTTB')); ?>
                <?php echo $form->error($model, 'phone'); ?>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'phone_2'); ?>
                <?php echo $form->textField($model, 'phone_2', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control', 'placeholder' => 'Nhận thông báo đơn hàng mới')); ?>
                <?php echo $form->error($model, 'phone_2'); ?>
            </div>
        </div>
        <?php
            $profileFields = $profile->getFields();
            if ($profileFields) {
                foreach ($profileFields as $field) {
                    if ($field->varname == 'birthday') {
                        ?>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <?php echo $form->labelEx($profile, 'birthday'); ?>
                                <div class="input-prepend input-group">
                                        <span class="add-on input-group-addon">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </span>
                                    <?php
                                        if ($profile->birthday == '') {
                                            $profile->birthday = date('Y-m-d');
                                        }
                                        echo $form->textField($profile, 'birthday', array('class' => 'form-control', 'size' => 25, 'maxlength' => 50));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    } elseif ($field->range) {

                        ?>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <?php
                                    echo CHtml::activeLabelEx($profile, $field->varname);
                                    echo CHtml::activeDropDownList($profile, $field->varname, Profile::range($field->range));
                                ?>
                            </div>
                        </div>
                        <?php
                    } elseif ($field->field_type == "TEXT") {
                        ?>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <?php
                                    echo CHtml::activeLabelEx($profile, $field->varname);
                                    echo CHtml::activeTextArea($profile, $field->varname, array('class' => 'form-control', 'rows' => 6, 'cols' => 50));
                                ?>
                            </div>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <?php
                                    if ($field->title == 'First Name') {

                                        ?>
                                        <label for="Profile_firstname" class="required">Họ <span
                                                    class="required">*</span></label>
                                        <?php
                                    } else if ($field->title == 'Last Name') {
                                        ?>
                                        <label for="Profile_lastname" class="required">Tên <span
                                                    class="required">*</span></label>
                                        <?php
                                    }

                                    echo CHtml::activeTextField($profile, $field->varname, array('class' => 'form-control', 'maxlength' => (($field->field_size) ? $field->field_size : 255)));
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                    <?php
                }
            }
        ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'status'); ?>
                <?php echo $form->dropDownList($model, 'status', User::itemAlias('UserStatus'), array('class' => 'form-control', 'options' => array(($model->isNewRecord) ? 1 : $model->status => array('selected' => TRUE)))); ?>
                <?php echo $form->error($model, 'status'); ?>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 23px;">
            <div class="form-group">
                <?php echo CHtml::submitButton($model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save'), array('class' => 'btn btn-primary')); ?>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="col-md-12 col-sm-12 col-xs-12">
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
                                    'url'    => Yii::app()->createUrl('user/admin/getSaleOfficeByProvince'), //or $this->createUrl('loadcities') if '$this' extends CController
                                    'update' => '#User_sale_offices_id',
                                    'data'   => array('province_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                ),
                                'onchange' => ' $("#User_district_code").select2("val", "");
                                        $("#User_ward_code").select2("val", "");
                                        $("#User_brand_offices_id").select2("val", "");
                                        $("#User_sale_offices_id").select2("val", "");
                                    ',
                                //reset value selected
                                'style'    => 'width:100%'
                            ),
                        )
                    );
                ?>
                <?php echo $form->error($model, 'province_code'); ?>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'sale_offices_id'); ?>
                <?php
                    $this->widget(
                        'booster.widgets.TbSelect2',
                        array(
                            'model'       => $model,
                            'attribute'   => 'sale_offices_id',
                            'data'        => ($model->province_code != '') ? ASaleOffices::model()->getSaleOfficesByProvince($model->province_code) : (!ADMIN && !SUPER_ADMIN) ? ASaleOffices::model()->getAllSaleOffices() : array(),
                            'htmlOptions' => array(
                                'multiple' => FALSE,
                                'prompt'   => (!ADMIN && !SUPER_ADMIN && $sale == TRUE) ? NULL : Yii::t('report/menu', 'sale_offices_id'),
                                'ajax'     => array(
                                    'type'   => 'POST',
                                    'url'    => Yii::app()->createUrl('user/admin/getBrandOfficeBySaleCode'), //or $this->createUrl('loadcities') if '$this' extends CController
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
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'brand_offices_id'); ?>
                <?php
                    $this->widget(
                        'booster.widgets.TbSelect2',
                        array(
                            'model'       => $model,
                            'attribute'   => 'brand_offices_id',
                            'data'        => ($model->sale_offices_id != '') ? ABrandOffices::model()->getBrandOfficesBySaleCode($model->sale_offices_id) : array(),
                            'htmlOptions' => array(
                                'multiple' => FALSE,
                                'prompt'   => (!ADMIN && !SUPER_ADMIN && $brand == TRUE) ? NULL : Yii::t('report/menu', 'brand_offices_id'),
                                'ajax'     => array(
                                    'type'   => 'POST',
                                    'url'    => Yii::app()->createUrl('user/admin/getRegency'), //or $this->createUrl('loadcities') if '$this' extends CController
                                    'update' => '#User_regency',
                                    'data'   => array('brand_offices_id' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                ),
                                //reset value selected
                                'style'    => 'width:100%'
                            ),
                        )
                    );
                ?>
                <?php echo $form->error($model, 'brand_offices_id'); ?>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'regency'); ?>
                <?php echo $form->dropDownList($model, 'regency', $model->getRegency(), array(
                        'class'  => 'form-control',
                        'prompt' => (!ADMIN && !SUPER_ADMIN) ? NULL : 'Chọn chức vụ',
                    )
                ); ?>
                <?php echo $form->error($model, 'regency'); ?>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'agency_id'); ?>
                <?php echo $form->dropDownList($model, 'agency_id', User::getListAgency(), array(
                    'class'  => 'form-control',
                )); ?>
                <?php echo $form->error($model, 'agency_id'); ?>
            </div>
        </div>

    </div>
    <?php $this->endWidget(); ?>
</div>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/moment.min2.js"></script>
<script type="text/javascript"
        src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datepicker/daterangepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#Profile_birthday').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
//            timePicker: true,
            timePickerIncrement: 5,
            format: 'YYYY-MM-DD',
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





