<?php
    /* @var $this SiteController */
    /* @var $model WCustomers */
    /* @var $form CActiveForm */
?>
<div class="container page_detail">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Thông tin cá nhân</h2>

                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <?= CHtml::link('Tra cứu đơn hàng', Yii::app()->controller->createUrl('orders/index'), array('class' => 'btn btn_register')) ?>
                    <div class="space_10"></div>
                    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                        'id'                   => 'form-update-info',
                        'action'               => Yii::app()->controller->createUrl('site/profile'),
                        'enableAjaxValidation' => TRUE,
                        'htmlOptions'          => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left avatar-form')
                    )); ?>

                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <fieldset>
                            <legend>Thông tin hệ thống:</legend>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <?php echo $form->labelEx($model, 'sso_id'); ?>
                                    <span class="form-control" readonly="1">
                                        <?= CHtml::encode($model->sso_id) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <?php echo $form->labelEx($model, 'username'); ?>
                                    <span class="form-control" readonly="1">
                                        <?= CHtml::encode($model->username) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <?php echo $form->labelEx($model, 'email'); ?>
                                    <span class="form-control" readonly="1">
                                        <?= CHtml::encode($model->email) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <?php echo $form->labelEx($model, 'phone'); ?>
                                    <span class="form-control" readonly="1">
                                        <?= CHtml::encode($model->phone) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <?php echo $form->labelEx($model, 'birthday'); ?>
                                    <?php
                                        $this->widget(
                                            'booster.widgets.TbDatePicker',
                                            array(
                                                'name'        => 'WCustomers[birthday]',
                                                'id'          => 'WCustomers_birthday',
                                                'value'       => $model->birthday,
                                                'htmlOptions' => array('class' => 'col-md-12 form-control'),
                                            )
                                        );
                                    ?>
                                    <?php echo $form->error($model, 'birthday'); ?>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <div class="form-group ">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#myModal">Cập nhật
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <?php if (Yii::app()->session['session_data']->user_id != '') { ?>
                                        <a href="<?= $sso_change_pass_url ?>">Thay đổi mật
                                            khẩu</a>

                                    <?php } ?>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div id="myModal" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Bạn có chắc chắn cập nhật</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p style="text-align: justify;">Những thông tin liên quan tới thông tin tài
                                                khoản, thông tin định danh
                                                không thể thay đổi vào lần tiếp theo, bạn có chắc chắn muốn thay đổi</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" id='WCustomersForm'
                                                    class="btn btn-primary"
                                                    style="font-size: 14px;">Cập nhật
                                            </button>
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>


                        </fieldset>
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <fieldset>
                            <legend>Thông tin định danh:</legend>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <?php echo $form->labelEx($model, 'personal_id'); ?>
                                    <?php if ($model->personal_id == '') { ?>
                                        <?php echo $form->textField($model, 'personal_id', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control')); ?>
                                    <?php } else { ?>
                                        <span class="form-control" readonly="1">
                                            <?= CHtml::encode($model->personal_id) ?>
                                        </span>
                                    <?php } ?>
                                    <?php echo $form->error($model, 'personal_id'); ?>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <?php echo $form->labelEx($model, 'personal_id_create_date'); ?>
                                    <?php if ($model->personal_id_create_date == '') { ?>
                                        <?php echo $form->textField($model, 'personal_id_create_date', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control')); ?>
                                    <?php } else { ?>
                                        <span class="form-control" readonly="1">
                                            <?= CHtml::encode($model->personal_id_create_date) ?>
                                        </span>
                                    <?php } ?>
                                    <?php echo $form->error($model, 'personal_id_create_date'); ?>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <?php echo $form->labelEx($model, 'personal_id_create_place'); ?>
                                    <?php if ($model->personal_id_create_place == '') { ?>
                                        <?php echo $form->textField($model, 'personal_id_create_place', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control')); ?>
                                    <?php } else { ?>
                                        <?php echo $form->textField($model, 'personal_id_create_place', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control', 'readOnly' => TRUE)); ?>
                                    <?php } ?>
                                    <?php echo $form->error($model, 'personal_id_create_place'); ?>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <?php echo $form->labelEx($model, 'province_code'); ?>
                                    <?php
                                        echo $form->dropDownList($model, 'province_code', WProvince::getListProvince(), array(
                                            'prompt' => 'Chọn tỉnh thành',
                                            'ajax'   => array(
                                                'type'   => 'POST',
                                                'url'    => Yii::app()->controller->createUrl('site/getDistrictByProvince'), //or $this->createUrl('loadcities') if '$this' extends CController
                                                'update' => '#WCustomers_district_code',
                                                'data'   => array('province_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                            ),
                                            'class'  => 'form-control',
                                        ));
                                    ?>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <?php echo $form->labelEx($model, 'district_code'); ?>
                                    <?php
                                        $this->widget(
                                            'booster.widgets.TbSelect2',
                                            array(
                                                'model'       => $model,
                                                'attribute'   => 'district_code',
                                                'data'        => ($model->province_code != '') ? WDistrict::getListDistrictByProvince($model->province_code) : array(),
                                                'value'       => $model->district_code,
                                                'htmlOptions' => array(
                                                    'multiple' => FALSE,
                                                    'style'    => 'width:100%;',
                                                    'prompt'   => Yii::t('adm/label', 'Chọn đơn vị trực thuộc'),
                                                    'class'    => 'form-control',
                                                ),
                                            )
                                        ); ?>

                                </div>
                            </div>


                        </fieldset>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <fieldset>
                            <legend>Thông tin thanh toán:</legend>

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <?php echo $form->labelEx($model, 'bank_account_id'); ?>
                                    <?php if ($model->bank_account_id == '') { ?>
                                        <?php echo $form->textField($model, 'bank_account_id', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control')); ?>
                                    <?php } else { ?>
                                        <?php echo $form->textField($model, 'bank_account_id', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control', 'readOnly' => TRUE)); ?>
                                    <?php } ?>

                                    <?php echo $form->error($model, 'bank_account_id'); ?>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <?php echo $form->labelEx($model, 'bank_account_name'); ?>
                                    <?php if ($model->bank_account_name == '') { ?>
                                        <?php echo $form->textField($model, 'bank_account_name', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control')); ?>
                                    <?php } else { ?>
                                        <?php echo $form->textField($model, 'bank_account_name', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control', 'readOnly' => TRUE)); ?>
                                    <?php } ?>
                                    <?php echo $form->error($model, 'bank_account_name'); ?>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <?php echo $form->labelEx($model, 'bank_name'); ?>
                                    <?php if ($model->bank_name == '') { ?>
                                        <?php echo $form->textField($model, 'bank_name', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control')); ?>
                                    <?php } else { ?>
                                        <?php echo $form->textField($model, 'bank_name', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control', 'readOnly' => TRUE)); ?>
                                    <?php } ?>
                                    <?php echo $form->error($model, 'bank_name'); ?>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <?php echo $form->labelEx($model, 'bank_brandname'); ?>
                                    <?php if ($model->bank_name == '') { ?>
                                        <?php echo $form->textField($model, 'bank_brandname', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control')); ?>
                                    <?php } else { ?>
                                        <?php echo $form->textField($model, 'bank_brandname', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control', 'readOnly' => TRUE)); ?>
                                    <?php } ?>

                                    <?php echo $form->error($model, 'bank_brandname'); ?>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <?php echo $form->labelEx($model, 'job'); ?>
                                    <?php echo $form->textField($model, 'job', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'job'); ?>
                                </div>
                            </div>


                        </fieldset>

                    </div>

                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

