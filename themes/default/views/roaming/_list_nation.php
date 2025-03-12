<?php
    /* @var $this RoamingController */
    /* @var $nations_prepaid WNations */
    /* @var $nations_postpaid WNations */
?>
<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#nations_prepaid">Trả trước</a></li>
    <li><a data-toggle="tab" href="#nations_postpaid">Trả sau</a></li>
</ul>

<div class="tab-content">
    <div id="nations_prepaid" class="tab-pane fade in active">
        <div class="table-responsive">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'            => 'nations-grid_prepaid',
                'dataProvider'  => $nations_prepaid,
                'enableSorting' => FALSE,
                'template'      => "{items}",
                'columns'       => array(
                    array(
                        'name'        => 'name',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'telco_prepaid',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'continent',
                        'type'        => 'raw',
                        'value'       => '$data->getContinentLabel($data->continent)',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                ),
            )); ?>
        </div>
    </div>
    <div id="nations_postpaid" class="tab-pane fade">
        <div class="table-responsive">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'            => 'nations-grid_postpaid',
                'dataProvider'  => $nations_postpaid,
                'enableSorting' => FALSE,
                'template'      => "{items}",
                'columns'       => array(
                    array(
                        'name'        => 'name',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'telco_postpaid',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'continent',
                        'type'        => 'raw',
                        'value'       => '$data->getContinentLabel($data->continent)',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                ),
            )); ?>
        </div>
    </div>
</div>
