<?php
    /* @var $this SimController */
    /* @var $data */
?>
<?php if (isset($msg) && !empty($msg)): ?>
    <div class="help-block"><?= $msg; ?></div>
<?php endif; ?>

    <?php if ($data): ?>
        <div class="table-responsive">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'            => 'msisdn-grid',
                'dataProvider'  => $data,
                'enableSorting' => FALSE,
                'template' => '{items}{pager}',
                'columns'       => array(
                    array(
                        'name'        => 'Số thuê bao',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            $html = '<a class="btnBuySim" href="javascript:void(0)"';
                            $html .= ' data-simnumber="' . CHtml::encode($data['msisdn']) . '"';
                            $html .= ' data-simprice="' . $data['price'] . '"';
                            $html .= ' data-simtype="' . CHtml::encode($data['msisdn_type']) . '"';
                            $html .= ' data-simterm="' . CHtml::encode($data['term']) . '"';
                            $html .= ' data-simpriceterm="' . CHtml::encode($data['price_term']) . '"';
                            $html .= ' data-simstore="' . CHtml::encode($data['store']) . '"';
                            $html .= ' data-url="' . Yii::app()->controller->createUrl('sim/addtocart') . '"';
                            if ($data['price_term'] > 0) {
                                $html .= ' data-confirm="1"';
                            }
                            $html .= ' data-csrf="' . Yii::app()->request->csrfToken . '"';
                            $html .= ' >';
                            $html .= ' <span>' . CHtml::encode($data['msisdn']) . '</span>';
                            $html .= ' </a>';

                            return $html;
                        },
                        'htmlOptions' => array('class' => 'link', 'style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'Giá',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return CHtml::encode(number_format($data['price'], 0, "", ".")) . 'đ';
                        },
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                ),
            )); ?>
        </div>
    <?php else: ?>
        <div class="text-center">
            <div class="space_30"></div>
            <div><img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_logo_fd.png" alt="image"></div>
            <div class="space_30"></div>
            <p>Không có kết quả tìm kiếm của bạn, quý khách vui lòng chọn số khác</p>
        </div>
    <?php endif; ?>
