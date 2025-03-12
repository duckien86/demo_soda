<?php
    /* @var $this APostCategoryController */
    /* @var $model APostCategory */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','forum'),
        Yii::t('adm/label', 'manage_post_cate') => array('admin'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'manage_post_cate'); ?></h2>

        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'            => 'apost-category-grid',
                'dataProvider'  => $model->search(),
                'filter'        => $model,
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'       => array(
                    array(
                        'name'  => 'name',
                        'type'  => 'raw',
                        'value' => 'CHtml::encode($data->name, array(\'view\', \'id\' => $data->id))',
//                        'htmlOptions' => array('nowrap' => 'nowrap'),
                    ),
                    array(
                        'name'   => 'description',
                        'filter' => FALSE,
                        'type'   => 'raw',
                        'value'  => 'CHtml::encode($data->description, array(\'view\', \'id\' => $data->id))',
//                        'htmlOptions' => array('nowrap' => 'nowrap'),
                    ),
                    array(
                        'name'   => 'parent_id',
                        'filter' => FALSE,
                        'type'   => 'raw',
                        'value'  => '$data->getMenu($data->parent_id)',
//                        'htmlOptions' => array('nowrap' => 'nowrap'),
                    ),
                    array(
                        'name'   => 'sort_order',
                        'filter' => FALSE,
                    ),
                    array(
                        'name'   => 'home_display',
                        'type'   => 'raw',
                        'filter' => FALSE,
                        'value'  => '$data->getShowHome($data->home_display)',
//                        'htmlOptions' => array('nowrap' => 'nowrap'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'filter'      => FALSE,
                        'value'       => function ($data) {
                            return CHtml::activeDropDownList($data, 'status',
                                array(
                                    APostCategory::ACTIVE   => Yii::t('adm/label', 'active'),
                                    APostCategory::INACTIVE => Yii::t('adm/label', 'inactive'),
                                ),
                                array('class'    => 'dropdownlist',
                                      'onChange' => "js:changeStatus($data->id,this.value)",
                                )
                            );
                        },
                        'htmlOptions' => array('width' => '150px', 'style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
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
                url: '<?=Yii::app()->controller->createUrl('aPostCategory/changeStatus')?>',
                crossDomain: true,
                data: {id: id, status: status},
                success: function (result) {
                    if (status == 'active' || status == 'pending') {
                        window.location.reload();
                    }
                    $('.show-popup-abc').html(result);
                    var modal_id = 'modal_' + id;
                    $('#' + modal_id).modal('show');
                    return false;
                }
            });
        } else {
            window.location.reload();
        }
    }
</script>