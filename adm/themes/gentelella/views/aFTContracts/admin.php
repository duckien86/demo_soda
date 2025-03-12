<?php
    /* @var $this AFTContractsController */
    /* @var $model AFTContracts */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'contracts_manage') => array('admin'),
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'contracts_manage'); ?></h2>

        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive">
            <?php
                $this->widget('booster.widgets.TbGridView', array(
                    'id'              => 'aftcontracts-grid',
                    'dataProvider'    => $model->search(),
                    'filter'          => $model,
                    'afterAjaxUpdate' => 'reinstallDatePicker',
                    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'columns'         => array(
//                        array(
//                            'name'        => 'id',
//                            'htmlOptions' => array('style' => 'width:100px;vertical-align:middle;'),
//                        ),
                        array(
                            'name'        => 'company',
                            'type'        => 'raw',
                            'value'       => function($data){
                                return CHtml::encode($data->company);
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'address',
                            'type'        => 'raw',
                            'value'       => function($data){
                                return CHtml::encode($data->address);
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'fullname',
                            'type'        => 'raw',
                            'value'       => function($data){
                                return CHtml::encode($data->fullname);
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'username',
                            'type'        => 'raw',
                            'value'       => function ($data) {
                                $value = CHtml::link(CHtml::encode($data->username), array('aFTUsers/view', 'id' => $data->user_id), array('target' => '_blank'));
                                return $value;
                            },
                            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'code',
                            'type'        => 'raw',
                            'value'       => function($data){
                                return CHtml::encode($data->code);
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
//                        array(
//                            'name'        => 'start_date',
//                            'value'       => 'date("d/m/Y",strtotime($data->start_date))',
//                            'filter'      => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
//                                'model'          => $model,
//                                'attribute'      => 'start_date',
//                                'language'       => 'vi',
//                                'htmlOptions'    => array(
//                                    'class' => 'form-control',
//                                    'size'  => '10',
//                                ),
//                                'defaultOptions' => array(
//                                    'showOn'            => 'focus',
//                                    'dateFormat'        => 'mm/dd/yy',
//                                    'showOtherMonths'   => TRUE,
//                                    'selectOtherMonths' => TRUE,
//                                    'changeMonth'       => TRUE,
//                                    'changeYear'        => TRUE,
//                                    'showButtonPanel'   => TRUE,
//                                )
//                            ), TRUE),
//                            'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
//                        ),
//                        array(
//                            'name'        => 'finish_date',
//                            'value'       => 'date("d/m/Y",strtotime($data->finish_date))',
//                            'filter'      => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
//                                'model'          => $model,
//                                'attribute'      => 'finish_date',
//                                'language'       => 'vi',
//                                'htmlOptions'    => array(
//                                    'class' => 'form-control',
//                                    'size'  => '10',
//                                ),
//                                'defaultOptions' => array(
//                                    'showOn'            => 'focus',
//                                    'dateFormat'        => 'mm/dd/yy',
//                                    'showOtherMonths'   => TRUE,
//                                    'selectOtherMonths' => TRUE,
//                                    'changeMonth'       => TRUE,
//                                    'changeYear'        => TRUE,
//                                    'showButtonPanel'   => TRUE,
//                                )
//                            ), TRUE),
//                            'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
//                        ),
                        array(
                            'name'        => 'status',
                            'filter'      => CHtml::activeDropDownList(
                                $model,
                                'status',
                                $model->getAllStatus(),
                                array('empty' => Yii::t('adm/label', 'all'), 'class' => 'form-control')
                            ),
                            'type'        => 'raw',
                            'value'       => function ($data) {
                                return CHtml::activeDropDownList($data, 'status',
                                    $data->getAllStatus(),
                                    array('class'    => 'dropdownlist',
                                          'onChange' => "js:changeStatus($data->id, this.value)",
                                    )
                                );
                            },
                            'htmlOptions' => array('width' => '130px', 'style' => 'vertical-align:middle;'),
                        ),
//                        array(
//                            'header'      => Yii::t('adm/label', 'folder_path_contract'),
//                            'type'        => 'raw',
//                            'value'       => function ($data) {
//                                return $data->getFileUrl($data->id);
//                            },
//                            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;text-align: center;'),
//                        ),
                        array(
                            'header'      => Yii::t('adm/actions', 'action'),
                            'class'       => 'booster.widgets.TbButtonColumn',
                            'template'    => '{view} {update}',
                            'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '100px', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                            'buttons'     => array(
                                'update' => array(
                                    'visible' => '$data->getBtnUpdate()',
                                )
                            ),
                        ),
                    ),
                ));

                //reinstall datePicker after update ajax
                Yii::app()->clientScript->registerScript('re-install-date-picker', "
                    function reinstallDatePicker(id, data) {
                        $('#AFTContracts_start_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['vi'],{'dateFormat':'dd/mm/yy'}));
                        $('#AFTContracts_finish_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['vi'],{'dateFormat':'dd/mm/yy'}));
                    }
                ");
            ?>

        </div>
    </div>
</div>

<script language="javascript">
    function changeStatus(id, status) {
        if (confirm('Bạn muốn thay đổi trạng thái?')) {
            $.ajax({
                type: "POST",
                url: '<?=Yii::app()->controller->createUrl('aFTContracts/changeStatus')?>',
                crossDomain: true,
                dataType: 'json',
                data: {id: id, status: status},
                success: function (result) {
                    $('#aftcontracts-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            });
        }
    }
</script>