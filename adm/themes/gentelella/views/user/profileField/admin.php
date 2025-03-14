<?php
    $this->breadcrumbs = array(
        UserModule::t('Profile Fields') => array('admin'),
        UserModule::t('Manage'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo UserModule::t('Manage Profile Fields'); ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php
            echo $this->renderPartial('_menu', array(
                'list' => array(
                    CHtml::link(UserModule::t('Create Profile Field'), array('create')),
                ),
            ));
        ?>

        <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider'  => $dataProvider,
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'       => array(
                    'id',
                    'varname',
                    array(
                        'name'  => 'title',
                        'value' => 'UserModule::t($data->title)',
                    ),
                    'field_type',
                    'field_size',
                    //'field_size_min',
                    array(
                        'name'  => 'required',
                        'value' => 'ProfileField::itemAlias("required",$data->required)',
                    ),
                    //'match',
                    //'range',
                    //'error_message',
                    //'other_validator',
                    //'default',
                    'position',
                    array(
                        'name'  => 'visible',
                        'value' => 'ProfileField::itemAlias("visible",$data->visible)',
                    ),
                    //*/
                    array(
                        'class' => 'CButtonColumn',
                    ),
                ),
            ));
        ?>
    </div>
</div>