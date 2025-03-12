<?php
    /* @var $this ASimController */
    /* @var $data */
?>

<div class="ss-box1-right-bottom">
    <?php if ($data): ?>
        <div class="table-responsive<?php echo (empty($data_output)) ? ' hidden' : '' ?>">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'            => 'msisdn-grid',
                'dataProvider'  => $data,
                'enableSorting' => FALSE,
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
                            $html .= ' data-url="' . Yii::app()->controller->createUrl('aSim/addtocart') . '"';
                            $html .= ' data-csrf="' . Yii::app()->request->csrfToken . '"';
                            $html .= ' >';
                            $html .= ' <span>' . CHtml::encode($data['msisdn']) . '</span>';
                            $html .= ' </a>';
                            if ($data['price_term'] > 0) {
                                $html .= '<div class="hasTooltip">';
                                $html .= '<div class="tooltip-inner">';
                                $html .= Yii::t('web/portal', 'tooltip_sim');
                                $html .= '</div></div>';
                            }

                            return $html;
                        },
                        'htmlOptions' => array('class' => 'link', 'style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'Loại thuê bao',
                        'value'       => function ($data) {
                            if ($data['msisdn_type'] == ASim::TYPE_POSTPAID)
                                $msisdn_type = Yii::t('web/portal', 'postpaid');
                            else
                                $msisdn_type = Yii::t('web/portal', 'prepaid');

                            return $msisdn_type;

                        },
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'Giá',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return CHtml::encode(number_format($data['price'], 0, "", ".")) . 'đ';
                        },
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'Thời gian cam kết',
                        'value'       => function ($data) {
                            return $data['term'] . ' Tháng';
                        },
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'Cước cam kết',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return CHtml::encode(number_format($data['price_term'], 0, "", ".")) . 'đ/Tháng';
                        },
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => '',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            $html = '<a class="btnBuySim" href="javascript:void(0)"';
                            $html .= ' data-simnumber="' . CHtml::encode($data['msisdn']) . '"';
                            $html .= ' data-simprice="' . $data['price'] . '"';
                            $html .= ' data-simtype="' . CHtml::encode($data['msisdn_type']) . '"';
                            $html .= ' data-simterm="' . CHtml::encode($data['term']) . '"';
                            $html .= ' data-simpriceterm="' . CHtml::encode($data['price_term']) . '"';
                            $html .= ' data-simstore="' . CHtml::encode($data['store']) . '"';
                            $html .= ' data-url="' . Yii::app()->controller->createUrl('aSim/addtocart') . '"';
                            $html .= ' data-csrf="' . Yii::app()->request->csrfToken . '">';
                            $html .= ' <span>Mua ngay</span>';
                            $html .= ' </a> ';

                            return $html;
                        },
                        'htmlOptions' => array('class' => 'btn-cart uppercase', 'style' => 'word -break: break-word;vertical - align:middle;'),
                    ),
                ),
            )); ?>
        </div>
    <?php else:
        if (!empty($msg)):?>
            <div class="text-center">
                <div class="space_10"></div>
                <div><img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_logo_fd.png" alt="image"></div>
                <div class="space_30"></div>
                <p><?= $msg ?></p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>