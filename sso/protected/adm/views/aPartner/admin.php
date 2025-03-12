<?php
    /* @var $this APartnerController */
    /* @var $model APartner */

    $this->breadcrumbs = array(
        'Apartners' => array('index'),
        'Manage',
    );

    $this->menu = array(
        array('label' => 'Tạo mới', 'url' => array('create')),
    );

    Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#apartner-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="x_panel">
    <div class="x_title">
        <h1>Quản lý đối tác</h1>
    </div>
    <div class="clearfix"></div>

    <div class="x_content">
        <?php $this->widget('booster.widgets.TbGridView', array(
            'id'           => 'apartner-grid',
            'dataProvider' => $model->search(),
            'filter'       => $model,
            'itemsCssClass' => 'items table-bordered table-striped table-hover responsive-utilities',
            'columns'      => array(
                array(
                    'name'  => 'name',
//                    'filter' => TRUE,
                    'type'  => 'raw',
                    'value' => 'CHtml::link($data->name, array(\'view\', \'id\' => $data->id))',
                ),
                array(
                    'name'  => 'phone',
//                    'filter' => TRUE,
                    'type'  => 'raw',
                    'value' => 'CHtml::link($data->phone, array(\'view\', \'id\' => $data->id))',
                ),
                array(
                    'name'  => 'email',
//                    'filter' => TRUE,
                    'type'  => 'raw',
                    'value' => 'CHtml::link($data->email, array(\'view\', \'id\' => $data->id))',
                ),
                array(
                    'name'        => 'cp_id',
//                    'filter' => TRUE,
                    'type'        => 'raw',
                    'value'       => 'CHtml::encode($data->cp_id, array(\'view\', \'id\' => $data->id))',
                    'htmlOptions' => array('style' => 'text-align:center;vertical-align:middle;padding:10px'),
                ),
                array(
                    'name'  => 'return_url',
//                    'filter' => TRUE,
                    'type'  => 'raw',
                    'value' => 'CHtml::link($data->return_url, array(\'view\', \'id\' => $data->id))',
                ),
                array(
                    'name'   => 'created_at',
                    'filter' => FALSE,
                    'type'   => 'raw',
                    'value'  => 'CHtml::link($data->created_at, array(\'view\', \'id\' => $data->id))',
                ),
                array(
                    'name'        => 'status',
                    'type'        => 'raw',
                    'filter'      => CHtml::activeDropDownList($model, 'status', array(APartner::ACTIVE => APartner::TEXT_ACTIVE, APartner::INACTIVE => APartner::TEXT_INACTIVE), array('empty' => 'Tất cả', 'class' => 'form-control')),
                    'value'       => function ($data) {
                        $icon   = $data->status == APartner::ACTIVE ? "<i class=\"fa fa-check-circle\"></i>" : "<i class=\"fa fa-times-circle\"></i>";
                        $status = $data->status == APartner::ACTIVE ? APartner::INACTIVE : APartner::ACTIVE;

                        return CHtml::link($icon, "javascript:;", array(
                            'title'               => '',
                            'class'               => '',
                            'data-toggle'         => 'tooltip',
                            'data-original-title' => 'Thay đổi trạng thái',
                            'onclick'             => 'changeStatus(' . $data->id . ',' . $status . ');',
                        ));

                    },
                    'htmlOptions' => array( 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                ),
                array(
                    'class'       => 'booster.widgets.TbButtonColumn',
                    'template'    => '{view} {update} {delete}',
                    'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '80px;', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                ),
            ),
        )); ?>
    </div>
</div>
<script>
    function changeStatus(id, status) {
        $.ajax({
            type: "POST",
            url: '<?=Yii::app()->createUrl('aPartner/changeStatus')?>',
            crossDomain: true,
            dataType: 'json',
            data: {id: id, status: status, 'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'},
            success: function (result) {
                if (result === true) {
                    $('#apartner-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            }
        });
    }
</script>

