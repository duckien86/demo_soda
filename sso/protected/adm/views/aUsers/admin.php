<?php
    /* @var $this AUsersController */
    /* @var $model AUsers */

    $this->breadcrumbs = array(
        'Ausers' => array('index'),
        'Manage',
    );

    $this->menu = array();

    Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#ausers-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="x_panel">
    <div class="x_title">
        <h1>Quản lý người dùng</h1>
    </div>
    <div class="clearfix"></div>

    <div class="x_content">
        <div class="search-form" style="display:none">
            <?php $this->renderPartial('_search', array(
                'model' => $model,
            )); ?>
        </div><!-- search-form -->

        <?php $this->widget('booster.widgets.TbGridView', array(
            'id'            => 'ausers-grid',
            'dataProvider'  => $model->search(),
            'filter'        => $model,
            'itemsCssClass' => 'items table-bordered table-striped table-hover responsive-utilities',
            'columns'       => array(
                array(
                    'name'  => 'username',
//                    'filter' => TRUE,
                    'type'  => 'raw',
                    'value' => 'CHtml::encode($data->username)',
                ),
                array(
                    'name'  => 'fullname',
//                    'filter' => FALSE,
                    'type'  => 'raw',
                    'value' => 'CHtml::encode($data->fullname)',
                ),
                array(
                    'name'  => 'email',
//                    'filter' => FALSE,
                    'type'  => 'raw',
                    'value' => 'CHtml::encode($data->email)',
                ),
                array(
                    'name'  => 'phone',
//                    'filter' => FALSE,
                    'type'  => 'raw',
                    'value' => 'CHtml::encode($data->phone)',
                ),
                array(
                    'name'  => 'genre',
//                    'filter' => FALSE,
                    'type'  => 'raw',
                    'value' => 'CHtml::encode($data->genre)',
                ),
                array(
                    'name'  => 'address',
//                    'filter' => FALSE,
                    'type'  => 'raw',
                    'value' => 'CHtml::encode($data->address)',
                ),
                array(
                    'name'        => 'cp_id',
//                    'filter' => FALSE,
                    'type'        => 'raw',
                    'value'       => 'CHtml::encode($data->cp_id)',
                    'htmlOptions' => array('width' => '100px;', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                ),
                array(
                    'name'        => 'status',
                    'type'        => 'raw',
                    'filter'      => CHtml::activeDropDownList($model, 'status', array(AUsers::ACTIVE => AUsers::TEXT_ACTIVE, AUsers::INACTIVE => AUsers::TEXT_INACTIVE), array('empty' => 'Tất cả', 'class' => 'form-control')),
                    'value'       => function ($data) {
                        $icon   = $data->status == AUsers::ACTIVE ? "<i class=\"fa fa-check-circle\"></i>" : "<i class=\"fa fa-times-circle\"></i>";
                        $status = $data->status == AUsers::ACTIVE ? AUsers::INACTIVE : AUsers::ACTIVE;

                        return CHtml::link($icon, "javascript:;", array(
                            'title'               => '',
                            'class'               => '',
                            'data-toggle'         => 'tooltip',
                            'data-original-title' => 'Thay đổi trạng thái',
                            'onclick'             => 'changeStatus("' . $data->username . '",' . $status . ');',
                        ));

                    },
                    'htmlOptions' => array('style' => 'text-align:center;vertical-align:middle;padding:10px'),
                ),

                array(
                    'name'        => 'is_admin',
                    'type'        => 'raw',
                    'filter'      => CHtml::activeDropDownList($model, 'is_admin', array(AUsers::ADMIN_SOCIAL => AUsers::TEXT_ADMIN_SOCIAL, AUsers::NOT_ADMIN_SOCIAL => AUsers::TEXT_NOT_ADMIN_SOCIAL, AUsers::SUB_ADMIN_SOCIAL => AUsers::TEXT_SUB_ADMIN_SOCIAL), array('empty' => 'Tất cả', 'class' => 'form-control')),
                    'value'       => function ($data) {

                        return CHtml::activeDropDownList($data, 'is_admin',
                            array(
                                AUsers::ADMIN_SOCIAL     => AUsers::TEXT_ADMIN_SOCIAL,
                                AUsers::NOT_ADMIN_SOCIAL => AUsers::TEXT_NOT_ADMIN_SOCIAL,
                                AUsers::SUB_ADMIN_SOCIAL => AUsers::TEXT_SUB_ADMIN_SOCIAL),
                            array('class'    => 'dropdownlist',
                                  'onChange' => 'isAdmin("' . $data->username . '",this.value);',
                            )
                        );
                    },
                    'htmlOptions' => array('width' => '150px', 'style' => 'vertical-align:middle;'),
                ),

                array(
                    'class'       => 'booster.widgets.TbButtonColumn',
                    'template'    => '{delete}',
                    'buttons'     => array(
                        'delete' => array(
                            'label' => '',
                            'url'   => 'Yii::app()->createUrl("aUsers/delete", array("id"=>"$data->id"))',
                        ),
                    ),
                    'htmlOptions' => array('nowrap' => 'nowrap', 'width' => '1%', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                ),
            ),
        )); ?>
    </div>
</div>
<script>
    function changeStatus(username, status) {

        $.ajax({
            type: "POST",
            url: '<?=Yii::app()->createUrl('aUsers/changeStatus')?>',
            crossDomain: true,
            dataType: 'json',
            data: {username: username, status: status, 'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'},
            success: function (result) {
                if (result === true) {
                    $('#ausers-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            }
        });
    }

    function isAdmin(username, is_admin) {
        $.ajax({
            type: "POST",
            url: '<?=Yii::app()->createUrl('aUsers/isAdmin')?>',
            crossDomain: true,
            dataType: 'json',
            data: {
                username: username,
                is_admin: is_admin,
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
            },
            success: function (result) {
                if (result === true) {
                    $('#ausers-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            }
        });
    }
</script>

