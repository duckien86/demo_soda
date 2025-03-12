<?php
/* @var $this ALogMtController */
/* @var $model ReportOci */
/* @var $form ReportForm */
/* @var $form_validate ReportForm */
/* @var $data CArrayDataProvider */

$this->breadcrumbs = array(
    Yii::t('adm/menu','search'),
    'SMS' => array('admin'),
);
?>
<div class="x_panel">
    <div class="x_title">
        <h3>Tra cứu MT</h3>
    </div>
    <div class="clearfix"></div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search', array('model' => $form, 'model_validate' => $form_validate)); ?>
        </div>
    </div>
    <div class="row">
        <?php if (isset($data) && !empty($data)): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="title">
                        <h5> * Danh sách MT</h5>
                    </div>
                    <?php
                        $this->widget('booster.widgets.TbGridView', array(
                            'dataProvider' => $data,

                            'type'        => 'striped bordered  consended ',
                            'htmlOptions' => array(
                                'class' => 'tbl_style',
                                'id'    => 'thongsoChung',
                            ),
                            'columns'     => array(
                                array(
                                    'header'      => 'Số thuê bao',
                                    'value'       => function ($data) {
                                        $return = $data['MT_MSISDN'];

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px',
                                    ),
                                ),
                                array(
                                    'header'      => 'Ngày gửi',
                                    'value'       => function ($data) {

                                        $return = $data['MT_RXTIME'];

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px;text-align:right;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Nội dung',
                                    'value'       => function ($data) {
                                        $return = '';
                                        if (!ADMIN && !SUPER_ADMIN) {
                                            $return = preg_replace('/(?<![0-9])[0-9]{6}(?![0-9])/', 'xxxxxx', $data['MT_MSG']);
                                        } else {
                                            $return = $data['MT_MSG'];
                                        }

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:200px;text-align:right;',
                                    ),
                                ),
                                array(
                                    'header'      => 'Trạng thái',
                                    'value'       => function ($data) {
                                        $return = 'N/A';
                                        if ($data['MT_BILLINGSTATUS'] == 1) {
                                            $return = 'Thành công';
                                        } else {
                                            $return = 'Thất bại';
                                        }

                                        return CHtml::encode($return);
                                    },
                                    'htmlOptions' => array(
                                        'style' => 'width:100px;text-align:right;',
                                    ),
                                ),
                            ),
                        ));
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>


