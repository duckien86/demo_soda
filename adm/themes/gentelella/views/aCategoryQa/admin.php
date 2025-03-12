<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','website_content'),
        Yii::t('adm/label', 'manage_cate_qa') => array('admin'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'manage_cate_qa'); ?></h2>
        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">

        <?php $this->widget('booster.widgets.TbGridView', array(
            'id'            => 'acategory-qa-grid',
            'dataProvider'  => $model->search(),
            'filter'        => $model,
            'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
            'columns'       => array(
                array(
                    'name'        => 'name',
                    'filter'      => FALSE,
                    'type'        => 'raw',
                    'value'       => 'CHtml::link($data->name, array(\'view\', \'id\' => $data->id))',
                    'htmlOptions' => array('nowrap' => 'nowrap'),
                ),
                array(
                    'name'        => 'status',
                    'type'        => 'raw',
                    'filter'      => FALSE,
                    'value'       => function ($data) {
                        return CHtml::activeDropDownList($data, 'status',
                            array(
                                ACategoryQa::ACTIVE   => Yii::t('adm/label', 'active'),
                                ACategoryQa::INACTIVE => Yii::t('adm/label', 'inactive')
                            ),
                            array('class'    => 'dropdownlist',
                                  'onChange' => "js:changeStatus($data->id,this.value)",
                            )
                        );
                    },
                    'htmlOptions' => array('width' => '150px', 'style' => 'vertical-align:middle;'),
                ),
                array(
                    'header'      => Yii::t('adm/actions', 'action'),
                    'class'       => 'booster.widgets.TbButtonColumn',
                    'template'    => '{update}&nbsp;&nbsp;{delete}',
                    'htmlOptions' => array('width' => '150px', 'nowrap' => 'nowrap', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                ),
            ),
        )); ?>
    </div>
</div>
<script language="javascript">
    function changeStatus(id, status) {
        if (confirm('Bạn muốn thay đổi trạng thái?')) {
            $.ajax({
                type: "POST",
                url: '<?=Yii::app()->controller->createUrl('aCategoryQa/changeStatus')?>',
                crossDomain: true,
                dataType: 'json',
                data: {id: id, status: status},
                success: function (result) {
                    $('#acategory-qa-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            });
        }
    }
</script>

