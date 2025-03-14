<?php
    $this->breadcrumbs = array(
        'Rights' => Rights::getBaseUrl(),
        Rights::t('core', 'Roles'),
    );
?>

<div class="x_panel">
    <div id="roles">
        <div class="x_content">
            <div class="space_10"></div>
            <p>
                <?php echo Rights::t('core', 'A role is group of permissions to perform a variety of tasks and operations, for example the authenticated user.'); ?>
                <br/>
                <?php echo Rights::t('core', 'Roles exist at the top of the authorization hierarchy and can therefore inherit from other roles, tasks and/or operations.'); ?>
            </p>

            <p><code><?php
                        echo CHtml::link(Rights::t('core', 'Create a new role'), array('authItem/create', 'type' => CAuthItem::TYPE_ROLE), array(
                            'class' => 'add-role-link',
                        ));
                    ?></code></p>

            <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider'  => $dataProvider,
                    'template'      => '{items}',
                    'emptyText'     => Rights::t('core', 'No roles found.'),
                    'htmlOptions'   => array('class' => 'grid-view role-table'),
                    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'columns'       => array(
                        array(
                            'name'        => 'name',
                            'header'      => Rights::t('core', 'Name'),
                            'type'        => 'raw',
                            'htmlOptions' => array('class' => 'name-column'),
                            'value'       => '$data->getGridNameLink()',
                        ),
                        array(
                            'name'        => 'description',
                            'header'      => Rights::t('core', 'Description'),
                            'type'        => 'raw',
                            'htmlOptions' => array('class' => 'description-column'),
                        ),
                        array(
                            'name'        => 'bizRule',
                            'header'      => Rights::t('core', 'Business rule'),
                            'type'        => 'raw',
                            'htmlOptions' => array('class' => 'bizrule-column'),
                            'visible'     => Rights::module()->enableBizRule === TRUE,
                        ),
                        array(
                            'name'        => 'data',
                            'header'      => Rights::t('core', 'Data'),
                            'type'        => 'raw',
                            'htmlOptions' => array('class' => 'data-column'),
                            'visible'     => Rights::module()->enableBizRuleData === TRUE,
                        ),
                        array(
                            'header'      => '&nbsp;',
                            'type'        => 'raw',
                            'htmlOptions' => array('class' => 'actions-column'),
                            'value'       => '$data->getDeleteRoleLink()',
                        ),
                    )
                ));
            ?>

            <p class="info"><?php echo Rights::t('core', 'Values within square brackets tell how many children each item has.'); ?></p>
        </div>
    </div>
</div>
