<?php
$this->breadcrumbs = array(
    'Rights' => Rights::getBaseUrl(),
    Rights::t('core', 'Assignments'),
);
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div id="assignments">
                <div class="x_title">
                    <h2><?php echo Rights::t('core', 'Assignments'); ?></h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <p>
                        <?php echo Rights::t('core', 'Here you can view which permissions has been assigned to each user.'); ?>
                    </p>

                    <?php
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $dataProvider,
                        'template' => "{items}\n{pager}",
                        'emptyText' => Rights::t('core', 'No users found.'),
                        'htmlOptions' => array('class' => 'grid-view assignment-table'),
                        'itemsCssClass'=>'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                        'columns' => array(
                            array(
                                'name' => 'name',
                                'header' => Rights::t('core', 'Name'),
                                'type' => 'raw',
                                'htmlOptions' => array('class' => 'name-column'),
                                'value' => '$data->getAssignmentNameLink()',
                            ),
                            array(
                                'name' => 'assignments',
                                'header' => Rights::t('core', 'Roles'),
                                'type' => 'raw',
                                'htmlOptions' => array('class' => 'role-column'),
                                'value' => '$data->getAssignmentsText(CAuthItem::TYPE_ROLE)',
                            ),
                            array(
                                'name' => 'assignments',
                                'header' => Rights::t('core', 'Tasks'),
                                'type' => 'raw',
                                'htmlOptions' => array('class' => 'task-column'),
                                'value' => '$data->getAssignmentsText(CAuthItem::TYPE_TASK)',
                            ),
                            array(
                                'name' => 'assignments',
                                'header' => Rights::t('core', 'Operations'),
                                'type' => 'raw',
                                'htmlOptions' => array('class' => 'operation-column'),
                                'value' => '$data->getAssignmentsText(CAuthItem::TYPE_OPERATION)',
                            ),
                        )
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>