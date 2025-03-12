<?php
$this->breadcrumbs = array(
    'Rights' => Rights::getBaseUrl(),
    Rights::t('core', 'Assignments') => array('assignment/view'),
    $model->getName(),
);
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div id="userAssignments">
                <div class="x_title">
                    <h2><?php
                        echo Rights::t('core', 'Assignments for :username', array(
                            ':username' => $model->getName()
                        ));
                        ?></h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <div class="row">

                        <div class="col-md-6 col-sm-6 col-xs-12">

                            <?php
                            $this->widget('zii.widgets.grid.CGridView', array(
                                'dataProvider' => $dataProvider,
                                'template' => '{items}',
                                'hideHeader' => true,
                                'emptyText' => Rights::t('core', 'This user has not been assigned any items.'),
//                'htmlOptions' => array('class' => 'grid-view user-assignment-table mini'),
                                'itemsCssClass' => 'table table-striped table-bordered table-hover responsive-utilities jambo_table',
                                'columns' => array(
                                    array(
                                        'name' => 'name',
                                        'header' => Rights::t('core', 'Name'),
                                        'type' => 'raw',
                                        'htmlOptions' => array('class' => 'name-column'),
                                        'value' => '$data->getNameText()',
                                    ),
                                    array(
                                        'name' => 'type',
                                        'header' => Rights::t('core', 'Type'),
                                        'type' => 'raw',
                                        'htmlOptions' => array('class' => 'type-column'),
                                        'value' => '$data->getTypeText()',
                                    ),
                                    array(
                                        'header' => '&nbsp;',
                                        'type' => 'raw',
                                        'htmlOptions' => array('class' => 'actions-column'),
                                        'value' => '$data->getRevokeAssignmentLink()',
                                    ),
                                )
                            ));
                            ?>

                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                            <h4><?php echo Rights::t('core', 'Assign item'); ?></h4>

                            <?php if ($formModel !== null): ?>


                                <?php
                                $this->renderPartial('_form', array(
                                    'model' => $formModel,
                                    'itemnameSelectOptions' => $assignSelectOptions,
                                ));
                                ?>


                            <?php else: ?>

                                <p class="info"><?php echo Rights::t('core', 'No assignments available to be assigned to this user.'); ?>

                                <?php endif; ?>

                        </div>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>