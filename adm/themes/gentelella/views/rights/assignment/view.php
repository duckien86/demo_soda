<?php
    $this->breadcrumbs = array(
        'Rights' => Rights::getBaseUrl(),
        Rights::t('core', 'Assignments'),
    );
?>
<div class="x_panel">
    <div id="assignments">
        <div class="x_content">
            <div class="space_10"></div>
            <p>
                <?php echo Rights::t('core', 'Here you can view which permissions has been assigned to each user.'); ?>
            </p>
            <?php $this->renderPartial('_filter_area', array('model' => $model)); ?>
            <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider'  => $dataProvider,
                    'template'      => "{items}\n{pager}",
//                    'filter'        => $model,
                    'emptyText'     => Rights::t('core', 'No users found.'),
                    'htmlOptions'   => array('class' => 'grid-view assignment-table'),
                    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'columns'       => array(
                        array(
                            'name'        => 'name',
                            'header'      => Rights::t('core', 'Name'),
                            'type'        => 'raw',
                            'htmlOptions' => array('class' => 'name-column'),
                            'value'       => '$data->getAssignmentNameLink()',
                        ),
                        array(
                            'name'        => 'assignments',
                            'header'      => Rights::t('core', 'Roles'),
                            'type'        => 'raw',
                            'htmlOptions' => array('class' => 'role-column'),
                            'value'       => '$data->getAssignmentsText(CAuthItem::TYPE_ROLE)',
                        ),
                        array(
                            'name'        => 'assignments',
                            'header'      => Rights::t('core', 'Tasks'),
                            'type'        => 'raw',
                            'htmlOptions' => array('class' => 'task-column'),
                            'value'       => '$data->getAssignmentsText(CAuthItem::TYPE_TASK)',
                        ),
                        array(
                            'name'        => 'assignments',
                            'header'      => Rights::t('core', 'Operations'),
                            'type'        => 'raw',
                            'htmlOptions' => array('class' => 'operation-column'),
                            'value'       => '$data->getAssignmentsText(CAuthItem::TYPE_OPERATION)',
                        ),
                    )
                ));
            ?>
        </div>
    </div>
</div>