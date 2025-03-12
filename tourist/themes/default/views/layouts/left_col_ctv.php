<?php
/**
 * @var $this SiteController
 */
?>

<div class="left_col scroll-view">
    <nav class="nav-sidebar">
        <ul class="nav">
            <?php
            $this->widget('zii.widgets.CMenu',
                array(
                    'encodeLabel'        => FALSE,
                    'htmlOptions'        => array(
                        'class' => 'nav',
                    ),
                    'items'              => array(
                        array(
                            'url'     => Yii::app()->createUrl('site/index'),
                            'label'   => Yii::t('tourist/label', 'dashboard'),
                            'itemOptions' => array(
                                'class' => (Yii::app()->controller->id == 'site') ? 'active' : '',
                            )
                        ),
                        array(
                            'url'     => '',
                            'label'   => Yii::t('tourist/label', 'order_create'),
                            'itemOptions' => array(
                                'class' => (Yii::app()->controller->id == 'order' && Yii::app()->controller->action->id == 'create') ? 'active' : '',
                                'onclick' => 'showModalChooseOrderCtv()',
                            ),
                        ),
                        array(
                            'url'     => Yii::app()->createUrl('orderCtv/admin'),
                            'label'   => Yii::t('tourist/label', 'orders'),
                            'itemOptions' => array(
                                'class' => (Yii::app()->controller->id == 'order' && Yii::app()->controller->action->id == 'admin') ? 'active' : '',
                            )
                        ),
//                        array(
//                            'url'     => Yii::app()->createUrl('topup/admin'),
//                            'label'   => Yii::t('tourist/label', 'topup'),
//                            'itemOptions' => array(
//                                'class' => (Yii::app()->controller->id == 'topup' && Yii::app()->controller->action->id == 'admin') ? 'active' : '',
//                            )
//                        ),
//                        array(
//                            'url'     => Yii::app()->createUrl('topup/report'),
//                            'label'   => Yii::t('tourist/label', 'topup_report'),
//                            'itemOptions' => array(
//                                'class' => (Yii::app()->controller->id == 'topup' && Yii::app()->controller->action->id == 'report') ? 'active' : '',
//                            )
//                        ),
//                        array(
//                            'url'     => Yii::app()->createUrl('user/info'),
//                            'label'   => Yii::t('tourist/label', 'enterprises_info'),
//                            'itemOptions' => array(
//                                'class' => (Yii::app()->controller->id == 'user' && Yii::app()->controller->action->id == 'info') ? 'active' : '',
//                            )
//                        ),
//                        array(
//                            'url'     => Yii::app()->createUrl('user/logout'),
//                            'label'   => Yii::t('tourist/label', 'logout'),
//                        ),
                        array(
                            'url'     => Yii::app()->createUrl('report'),
                            'label'   => 'Báo cáo tổng quan',
                            'itemOptions' => array(
                                'class' => (Yii::app()->controller->id == 'report' && Yii::app()->controller->action->id == 'overview') ? 'active' : '',
                            )
                        ),
                        array(
                            'url'     => Yii::app()->createUrl('report/remuneration'),
                            'label'   => Yii::t('tourist/label', 'report_remuneration'),
                            'itemOptions' => array(
                                'class' => (Yii::app()->controller->id == 'report' && Yii::app()->controller->action->id == 'remuneration') ? 'active' : '',
                            )
                        ),
                    ),
                )
            );
            ?>
        </ul>
    </nav>
</div>

<?php $this->renderPartial('/order/_modal_choose_order_ctv'); ?>
