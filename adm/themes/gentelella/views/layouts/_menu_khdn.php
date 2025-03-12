<?php
/**
 * @var $this Controller
 */
?>
<div class="menu_section">
<?php $this->widget('zii.widgets.CMenu', array(
    'encodeLabel'        => FALSE,
    'htmlOptions'        => array(
        'class' => 'nav side-menu',
    ),
    'submenuHtmlOptions' => array(
        'class' => 'nav child_menu',
        'style' => 'display: none',
    ),
    'items'              => array(
        array(
            'url'       => 'javascript:;',
            'label'     => '<i class="fa fa-search"></i> Tra cứu <span class="fa fa-chevron-down"></span>',
            'visible'   => (true
            ),
            'items'     => array(
                array(
                    'url'       => array('/aFTReport/sim'),
                    'label'     => 'Tra cứu thuê bao đại lý',
                    'visible' => (Yii::app()->user->checkAccess('AFTReport.*')
                        || Yii::app()->user->checkAccess('AFTReport.Sim')
                    ),
                ),
            ),
        ),
        array(
            'url'       => 'javascript:;',
            'label'     => '<i class="fa fa-briefcase"></i> Quản lý nghiệp vụ <span class="fa fa-chevron-down"></span>',
            'visible'   => (Yii::app()->user->checkAccess('AFTUsers.*')
                || Yii::app()->user->checkAccess('AFTContracts.*')
                || Yii::app()->user->checkAccess('AFTPackage.*')
                || Yii::app()->user->checkAccess('AFTOrders.*')
            ),
            'items'     => array(
                array(
                    'url'     => array('/aFTUsers/admin'),
                    'label'   => 'Khách hàng',
                    'visible' => (Yii::app()->user->checkAccess('AFTUsers.*')
                        || Yii::app()->user->checkAccess('AFTUsers.Admin')
                    ),
                ),
                array(
                    'url'     => array('/aFTContracts/admin'),
                    'label'   => 'Hợp đồng',
                    'visible' => (Yii::app()->user->checkAccess('AFTContracts.*')
                        || Yii::app()->user->checkAccess('AFTContracts.Admin')
                    ),
                ),
                array(
                    'label'   => 'Sản phẩm',
                    'url'     => array('/aFTPackage/admin'),
                    'visible' => (Yii::app()->user->checkAccess('AFTPackage.*')
                        || Yii::app()->user->checkAccess('AFTPackage.Admin')
                    ),
                ),
                array(
                    'url'     => array('/aFTOrders/admin'),
                    'label'   => 'Đơn hàng',
                    'visible' => (Yii::app()->user->checkAccess('AFTOrders.*')
                        || Yii::app()->user->checkAccess('AFTOrders.Admin')
                    ),
                ),
            ),
        ),
        array(
            'url'       => 'javascript:;',
            'label'     => '<i class="fa fa-list"></i> Thống kê chi tiết <span class="fa fa-chevron-down"></span>',
            'visible'   => (Yii::app()->user->checkAccess('AFTReport.*')
            ),
            'items'     => array(
                array(
                    'url'     => array('/aFTReport/index'),
                    'label'   => 'Đơn hàng',
                    'visible' => (Yii::app()->user->checkAccess('AFTReport.*')
                        || Yii::app()->user->checkAccess('AFTReport.Index')
                    ),
                ),
                array(
                    'url'     => array('/aFTReport/renueve'),
                    'label'   => 'Doanh thu',
                    'visible' => (Yii::app()->user->checkAccess('AFTReport.*')
                        || Yii::app()->user->checkAccess('AFTReport.Renueve')
                    ),
                ),
                array(
                    'url'     => array('/aFTReport/remuneration'),
                    'label'   => 'Hoa hồng',
                    'visible' => (Yii::app()->user->checkAccess('AFTReport.*')
                        || Yii::app()->user->checkAccess('AFTReport.Remuneration')
                    ),
                ),
            )
        ),
    ),
)); ?>
</div>
