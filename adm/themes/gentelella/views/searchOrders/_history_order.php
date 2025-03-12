
<div class="row">
    <div class="col-md-12">
        <div class="x_content">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'history-detail-grid',
                'dataProvider' => $data_history,
                'columns'      => array(
                    array(
                        'name'        => 'Trạng thái',
                        'value'       => function ($data) {

                            return Chtml::encode($data['status']);
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'Ngày',
                        'value'       => function ($data) {
                            return Chtml::encode($data['create_date']);
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                ),
            )); ?>
        </div>
    </div>
    <div class="space_30"></div>
</div>