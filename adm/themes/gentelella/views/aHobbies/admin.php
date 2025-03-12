<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','forum'),
        Yii::t('adm/label', 'hobbies') => array('admin'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'manage_hobbies'); ?></h2>

        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">

        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'            => 'ahobbies-grid',
                'dataProvider'  => $model->search(),
                'filter'        => $model,
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'       => array(
                    array(
                        'name'        => 'name',
                        'type'        => 'raw',
                        'value'       => 'CHtml::link(CHtml::encode($data->name), array(\'view\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'index_order',
                        'type'        => 'raw',
                        'value'       => 'CHtml::link(CHtml::encode($data->index_order), array(\'view\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'filter'      => FALSE,
                        'value'       => function ($data) {
                            return CHtml::activeDropDownList($data, 'status',
                                array(
                                    AHobbies::ACTIVE   => Yii::t('adm/label', 'active'),
                                    AHobbies::INACTIVE => Yii::t('adm/label', 'inactive'),
                                ),
                                array('class'    => 'dropdownlist',
                                      'onChange' => "js:changeStatus($data->id,this.value)",
                                )
                            );
                        },
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle; width:150px;'),
                    ),
                    array(
                        'header'      => Yii::t('adm/actions', 'action'),
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '1%', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                    ),
                ),
            )); ?>
        </div>
    </div>
</div>
<script language="javascript">
    function changeStatus(id, status, sso_id) {
        if (confirm("Bạn có chắc muốn thay đổi trạng thái!")) {
            $.ajax({
                type: "POST",
                url: '<?=Yii::app()->controller->createUrl('aHobbies/changeStatus')?>',
                crossDomain: true,
                data: {id: id, status: status, sso_id: sso_id},
                success: function (result) {
                    window.location.reload();
                    return false;
                }
            });
        } else {
            window.location.reload();
        }
    }
</script>