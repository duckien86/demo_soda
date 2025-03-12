<?php
    /* @var $this ATokenLinksController */
    /* @var $model ATokenLinks */
    $this->breadcrumbs = array(
        Yii::t('adm/label', 'token_link') => array('admin'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'manage_token_link'); ?></h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive">
            <?php
                $this->widget('booster.widgets.TbGridView', array(
                    'id'              => 'token-links-grid',
                    'dataProvider'    => $model->search(),
                    'filter'          => $model,
                    'afterAjaxUpdate' => 'reinstallDatePicker',
                    'type'            => 'bordered condensed striped',
                    'columns'         => array(
                        array(
                            'name'        => 'order_id',
                            'type'        => 'raw',
                            'value'       => 'CHtml::link(CHtml::encode($data->order_id), array(\'view\', \'id\' => $data->id))',
                            'htmlOptions' => array('style' => 'width:110px;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'customer_msisdn',
                            'type'        => 'raw',
                            'value'       => 'CHtml::link(CHtml::encode($data->customer_msisdn), array(\'view\', \'id\' => $data->id))',
                            'htmlOptions' => array('style' => 'width:110px;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'pre_order_msisdn',
                            'type'        => 'raw',
                            'value'       => 'CHtml::link(CHtml::encode($data->pre_order_msisdn), array(\'view\', \'id\' => $data->id))',
                            'htmlOptions' => array('style' => 'width:110px;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'link',
                            'type'        => 'raw',
                            'value'       => 'CHtml::link(CHtml::encode($data->link), array(\'view\', \'id\' => $data->id))',
                            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'create_date',
                            'value'       => 'date("d/m/Y",strtotime($data->create_date))',
                            'filter'      => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                'model'          => $model,
                                'attribute'      => 'create_date',
                                'language'       => 'vi',
                                'htmlOptions'    => array(
                                    'class' => 'form-control',
                                    'size'  => '10',
                                ),
                                'defaultOptions' => array(
                                    'showOn'            => 'focus',
                                    'dateFormat'        => 'mm/dd/yy',
                                    'showOtherMonths'   => TRUE,
                                    'selectOtherMonths' => TRUE,
                                    'changeMonth'       => TRUE,
                                    'changeYear'        => TRUE,
                                    'showButtonPanel'   => TRUE,
                                )
                            ), TRUE),
                            'htmlOptions' => array('style' => 'width:100px;text-align: center;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'last_update',
                            'value'       => 'date("d/m/Y",strtotime($data->last_update))',
                            'filter'      => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                'model'          => $model,
                                'attribute'      => 'last_update',
                                'language'       => 'vi',
                                'htmlOptions'    => array(
                                    'class' => 'form-control',
                                    'size'  => '10',
                                ),
                                'defaultOptions' => array(
                                    'showOn'            => 'focus',
                                    'dateFormat'        => 'mm/dd/yy',
                                    'showOtherMonths'   => TRUE,
                                    'selectOtherMonths' => TRUE,
                                    'changeMonth'       => TRUE,
                                    'changeYear'        => TRUE,
                                    'showButtonPanel'   => TRUE,
                                )
                            ), TRUE),
                            'htmlOptions' => array('style' => 'width:100px;text-align: center;word-break: break-word;vertical-align:middle;'),
                        ),
                    ),
                ));

                //reinstall datePicker after update ajax
                Yii::app()->clientScript->registerScript('re-install-date-picker', "
                    function reinstallDatePicker(id, data) {
                        $('#ATokenLinks_create_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['vi'],{'dateFormat':'dd/mm/yy'}));
                        $('#ATokenLinks_last_update').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['vi'],{'dateFormat':'dd/mm/yy'}));
                    }
                ");
            ?>

        </div>
    </div>
</div>