<?php
    /* @var $this APaymentMethodController */
    /* @var $model APaymentMethod */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'payment_method') => array('admin'),
        Yii::t('adm/action', 'manage'),
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'payment_method'); ?></h2>

        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_filter_area', array('model' => $model)); ?>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'apayment-method-grid',
                'dataProvider' => $model->search(),
                'columns'      => array(
                    array(
                        'name'        => 'id',
                        'htmlOptions' => array('style' => 'width:100px;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'logo',
                        'filter'      => FALSE,
                        'type'        => 'raw',
                        'value'       => 'CHtml::link($data->getImageUrl($data->logo), array(\'update\', \'id\' => $data->id))',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '1%'),
                    ),
                    array(
                        'name'        => 'name',
                        'type'        => 'raw',
                        'value'       => 'CHtml::link(CHtml::encode($data->name), array(\'update\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'config_param',
                        'type'        => 'html',
                        'value'       => 'nl2br(CHtml::encode($data->config_param))',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'description',
                        'type'        => 'html',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return CHtml::activeDropDownList($data, 'status',
                                array(
                                    APaymentMethod::PAYMENT_METHOD_ACTIVE   => Yii::t('adm/label', 'active'),
                                    APaymentMethod::PAYMENT_METHOD_INACTIVE => Yii::t('adm/label', 'inactive')
                                ),
                                array('class'    => 'dropdownlist',
                                      'onChange' => "js:changeStatus($data->id,this.value)",
                                )
                            );
                        },
                        'htmlOptions' => array('width' => '120px', 'style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'header'      => Yii::t('adm/actions', 'action'),
                        'template'    => '{update}',
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '70', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
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
                url: '<?=Yii::app()->controller->createUrl('aPaymentMethod/changeStatus')?>',
                crossDomain: true,
                dataType: 'json',
                data: {id: id, status: status},
                success: function (result) {
                    $('#apayment-method-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            });
        }
    }
</script>