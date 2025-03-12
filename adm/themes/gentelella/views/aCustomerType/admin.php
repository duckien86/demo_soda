<?php
    /* @var $this ACustomerTypeController */
    /* @var $model ACustomerType */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'customer_type') => array('admin'),
        Yii::t('adm/actions', 'manage'),
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'customer_type'); ?></h2>

        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_filter_area', array('model' => $model)); ?>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'acustomer-type-grid',
                'dataProvider' => $model->search(),
                'columns'      => array(
                    array(
                        'name'        => 'id',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'name',
                        'type'        => 'raw',
                        'value'       => 'CHtml::link(CHtml::encode($data->name), array(\'update\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'pending_time',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return CHtml::activeDropDownList($data, 'status',
                                array(
                                    ACustomerType::CUSTOMER_TYPE_ACTIVE   => Yii::t('adm/label', 'active'),
                                    ACustomerType::CUSTOMER_TYPE_INACTIVE => Yii::t('adm/label', 'inactive')
                                ),
                                array('class'    => 'dropdownlist',
                                      'onChange' => "js:changeStatus($data->id,this.value)",
                                )
                            );
                        },
                        'htmlOptions' => array('width' => '100px', 'style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'header'      => Yii::t('adm/actions', 'action'),
                        'template'    => '{update}&nbsp;&nbsp;{delete}',
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '1%', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                    ),
                ),
            )); ?>

        </div>
    </div>
</div>

<script language="javascript">
    function changeStatus(id, status) {
        if (confirm('Bạn muốn thay đổi trạng thái?')) {
            $.ajax({
                type: "POST",
                url: '<?=Yii::app()->controller->createUrl('aCustomerType/changeStatus')?>',
                crossDomain: true,
                dataType: 'json',
                data: {id: id, status: status},
                success: function (result) {
                    $('#acustomer-type-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            });
        }
    }
</script>