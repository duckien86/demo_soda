<?php
/* @var $this AOrdersController */
/* @var $model AOrders */

$this->breadcrumbs = array(
    Yii::t('adm/menu', 'search'),
    Yii::t('adm/menu', 'order'),
    'CHI TIẾT ĐH FIBER' => array('detailOrderFiber'),
);
?>
<div class="x_panel">
    <div class="x_title">
        <h2>Thống kê chi tiết</h2>
        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_filter_detail_area_fiber', array('model' => $model)); ?>
    <div class="x_content">
        <div class="row note">
            <div class="tong_quan" style="width: 50%; float: left; padding: 15px">
                <?php if(isset($data_total)){ ?>
                    <div class="detail-order-fiber" style="width: 100%; float: left; font-weight: bold">
                        TỔNG QUAN
                    </div>
                    <?php

                    $confirm_10 = 0;
                    $confirm_2  = 0;
                    $delivered_10 = 0;
                    $total_revenue_confirm_10 = 0;
                    $total_revenue_confirm_2 = 0;
                    $total_revenue_delivered_10 = 0;
                    foreach ($data_total as $item){
                        if($item->status_order_fiber == 'dangthuchien'){
                            $confirm_10 ++;
                            $total_revenue_confirm_10 += $item->cuochoamang + $item->tienttt  + $item->ckhm + $item->ckttt;
                        }
                        if($item->status_order_fiber == 'huy'){
                            $confirm_2 ++;
                            $total_revenue_confirm_2 += $item->cuochoamang + $item->tienttt  + $item->ckhm + $item->ckttt;
                        }
                        if($item->status_order_fiber == 'hoanthanh'){
                            $delivered_10 ++;
                            $total_revenue_delivered_10 += $item->cuochoamang + $item->tienttt  + $item->ckhm + $item->ckttt;
                        }

                    }
                    ?>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Trạng thái ĐH</th>
                        <th>SL</th>
                        <th>Tổng Doanh thu</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Đang thực hiện</td>
                        <td><?php echo $confirm_10 ?></td>
                        <td><?php echo number_format($total_revenue_confirm_10) ?></td>
                    </tr>
                    <tr>
                        <td>Hoàn thành</td>
                        <td><?php echo $delivered_10 ?></td>
                        <td><?php echo number_format($total_revenue_delivered_10) ?></td>
                    </tr>
                    <tr>
                        <td>Hủy</td>
                        <td><?php echo $confirm_2 ?></td>
                        <td><?php echo number_format($total_revenue_confirm_2) ?></td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td>Tổng</td>
                        <td><?php echo $confirm_10 + $confirm_2 +$delivered_10 ?></td>
                        <td><?php echo number_format($total_revenue_confirm_2 + $total_revenue_confirm_10 + $total_revenue_delivered_10) ?></td>
                    </tr>
                    </tbody>
                </table>
                <?php }?>
            </div>
            <div class="left">
            </div>

        </div>
        <?php if(isset($data)){ ?>
            <div class="detail-order-fiber" style="width: 100%; float: left; font-weight: bold">
                CHI TIẾT
            </div>
            <?php if(isset($data)){ ?>
                <div class="right">
                    <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/orderDetailFiber'); ?>" target="_blank">
                        <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date ?>">
                        <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date ?>">
                        <input type="hidden" name="excelExport[province_code]" value="<?php echo $model->province_code ?>">
                        <input type="hidden" name="excelExport[status_fiber]" value="<?php echo $model->status_fiber ?>">
                        <input type="hidden" name="excelExport[type_package]" value="<?php echo $model->type_package ?>">
                        <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                    </form>
                </div>
            <?php }?>
            <div style="overflow-x:auto; width: 100%; float: left;">
                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id'            => 'aorders-grid',
                    'dataProvider'  => $data,
//                'filter'        => $model,
                    'enableSorting' => FALSE,
                    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                    'columns'       => array(
                        array(
                            'header'      => 'Ngày tạo ĐH',
                            'filter'      => FALSE,
                            'type'        => 'raw',
                            'value'       => function($data){
                                $value = date("d/m/Y", strtotime($data->create_date));
                                return $value;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:110px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'id',
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Tên KH',
                            'filter'      => FALSE,
                            'value'       => function($data){
                                return $data->full_name;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;text-align: left;word-break: break-word;vertical-align:middle;width:110px;'),
                        ),
                        array(
                            'header'      => 'Điện thoại',
                            'filter'      => FALSE,
                            'value'       => function($data){
                                return $data->phone_contact;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Gói đăng ký',
                            'filter'      => FALSE,
                            'value'       => function($data){
                                return $data->package_fiber_name;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Đăng ký TTT',
                            'filter'      => FALSE,
                            'value'       => function($data){
                                if($data->period > 0) {
                                    return $data->period . ' Ngày';
                                }else
                                    return '--';
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Kênh bán',
                            'filter'      => FALSE,
                            'value'       => function($data){
                                return $data->promo_code;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'PBH',
                            'filter'      => FALSE,
                            'value'       => function($data){
                                return $data->phong_bh;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'TTKD',
                            'filter'      => FALSE,
                            'value'       => function($data){
                                return $data->province_code;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Ngày Hợp Đồng',
                            'filter'      => FALSE,
                            'value'       => function($data){
                                return $data->ngay_ky_hd;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'NV tạo Hợp đồng',
                            'filter'      => FALSE,
                            'value'       => function($data){
                                return $data->ten_nv;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Mã NV',
                            'filter'      => FALSE,
                            'value'       => function($data){
                                return $data->ma_nv;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'A/c Fiber Number',
                            'filter'      => FALSE,
                            'value'       => function($data){
                                return $data->ma_tb;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Gói thi công',
                            'filter'      => FALSE,
                            'value'       => function($data){
                                return $data->loaihinh_tb;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Số tháng TTT',
                            'filter'      => FALSE,
                            'value'       => function($data){
                                return $data->thangtratruoc;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Cước HM',
                            'filter'      => FALSE,
                            'value'       => function($data){
                                return number_format($data->cuochoamang);
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'CK HM',
                            'filter'      => FALSE,
                            'value'       => function($data){
                                return number_format($data->ckhm);
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Tiền TTT',
                            'filter'      => FALSE,
                            'value'       => function($data){
                                return number_format($data->tienttt);
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'CK TTT',
                            'filter'      => FALSE,
                            'value'       => function($data){
                                return number_format($data->ckttt);
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Tổng DT',
                            'filter'      => FALSE,
                            'value'       => function($data){
                                return number_format($data->cuochoamang + $data->tienttt + $data->ckhm + $data->ckttt);
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;width:140px;text-align: left;word-break: break-word;vertical-align:middle;'),
                        ),
                        array(
                            'header'      => 'Trạng thái đơn hàng',
                            'type'        => 'raw',
                            'filter'      => FALSE,
                            'value'       => function ($data) {

                                return Chtml::encode(AOrders::getStatusFiber($data->id));
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;text-align: left;word-break: break-word;vertical-align:middle;width:100px;'),
                        ),
                        array(
                            'header'      => 'Ghi chú',
                            'type'        => 'raw',
                            'filter'      => FALSE,
                            'value'       => function ($data) {

                                return $data->note;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;text-align: left;word-break: break-word;vertical-align:middle;width:100px;'),
                        ),
                        array(
                            'header'      => 'STBOX',
                            'type'        => 'raw',
                            'filter'      => FALSE,
                            'value'       => function ($data) {

                                return $data->stb_use;
                            },
                            'htmlOptions' => array('style' => 'white-space: nowrap;text-align: left;word-break: break-word;vertical-align:middle;width:100px;'),
                        )
                    ),
                )); ?>
            </div>
        <?php }?>
    </div>
</div>

