<?php
    /* @var $this AOrdersController */
    /* @var $model AOrders */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'orders') => array('admin'),
        Yii::t('adm/actions', 'manage'),
    );
?>

<div class="x_panel">
    <h2>Thời gian cập nhật: <?= date("d/m/Y", strtotime(date("Y-m-d") . ' -1 day')) ?></h2>
    <div class="x_title">
        <h2>Báo cáo doanh thu</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <div class="table-responsive" id="subscribers-post" style="margin-top: 10px;">
                <table width="100%" border="1"
                       style="border-color: #b3b3b3;border: solid;border-width: 1px;color: black;"
                       cellpadding="1"
                       rowpadding="1">
                    <thead>
                    <tr>
                        <th rowspan="2">Ngày</th>
                        <th colspan="5">Số lượng</th>
                        <th colspan="5">Doanh thu</th>
                        <th colspan="2">Lũy kế</th>
                    </tr>
                    <tr>
                        <th>Sim trả trước</th>
                        <th>Sim trả sau</th>
                        <th>Gói cước kèm sim</th>
                        <th>Gói cước đơn lẻ</th>
                        <th>Sim du lịch</th>
                        <th>Sim</th>
                        <th>Gói cước kèm sim</th>
                        <th>Gói cước đơn lẻ</th>
                        <th>Sim du lịch</th>
                        <th>Tổng doanh thu</th>
                        <th>Số lượng sim đã bán</th>
                        <th>Tổng doanh thu lũy kế</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                        $stt = 0;
                        foreach ($data_renueve as $value) {
                            $Total_sim         = $model->getTotal($data_renueve, array('sim_pre_total', 'sim_post_total', 'package_total', 'package_single_total', 'sim_pre_renueve', 'sim_post_renueve', 'package_renueve', 'package_single_renueve'), $stt);
                            $Total_sim_tourist = $model->getTotal($data_tourist, array('total_tourist', 'renueve_tourist'), $stt);
                            foreach ($data_tourist as $value_tourist) {

                                if ($value['date'] == $value_tourist['date']) {
                                    ?>
                                    <tr>
                                        <td><?php echo CHtml::encode($value['date']); ?></td>
                                        <td style="text-align: right;"><?php echo CHtml::encode($value['sim_pre_total']); ?></td>
                                        <td style="text-align: right;"><?php echo CHtml::encode($value['sim_post_total']); ?></td>
                                        <td style="text-align: right;"><?php echo CHtml::encode($value['package_total']); ?></td>
                                        <td style="text-align: right;"><?php echo CHtml::encode($value['package_single_total']); ?></td>
                                        <td style="text-align: right;"><?php echo CHtml::encode($value_tourist['total_tourist']); ?></td>
                                        <td style="text-align: right;"><?php echo CHtml::encode(number_format($value['sim_pre_renueve'] + $value['sim_post_renueve']), 0, "", "."); ?></td>
                                        <td style="text-align: right;"><?php echo CHtml::encode(number_format($value['package_renueve'], 0, "", ".")); ?></td>
                                        <td style="text-align: right;"><?php echo CHtml::encode(number_format($value['package_single_renueve'], 0, "", ".")); ?></td>
                                        <td style="text-align: right;"><?php echo CHtml::encode(number_format($value_tourist['renueve_tourist']), 0, "", "."); ?></td>
                                        <td style="text-align: right;"><?php echo CHtml::encode(number_format(($value['package_renueve'] + $value['package_single_renueve'] + $value['sim_pre_renueve'] + $value['sim_post_renueve'] + $value_tourist['renueve_tourist']), 0, "", ".")); ?></td>
                                        <td style="text-align: right;"><?php echo CHtml::encode(number_format($data_accumulated[0]['total'] + $data_tourist_accumulated[0]['total_tourist'] - ($Total_sim['sim_pre_total'] + $Total_sim['sim_post_total'] + $Total_sim_tourist['total_tourist']), 0, "", ".")); ?></td>
                                        <td style="text-align: right;"><?php echo CHtml::encode(number_format($data_accumulated[0]['renueve'] + +$data_tourist_accumulated[0]['renueve_tourist'] - ($Total_sim['sim_pre_renueve'] + $Total_sim['sim_post_renueve'] + $Total_sim['package_renueve'] + $Total_sim['package_single_renueve'] + $Total_sim_tourist['renueve_tourist']), 0, "", ".")); ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            $stt++;
                        }
                    ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="x_title">
        <h2>Báo cáo đơn hàng</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <div class="table-responsive" id="subscribers-post" style="margin-top: 10px;">
                <table width="100%" border="1"
                       style="border-color: #b3b3b3;border: solid;border-width: 1px;color: black;"
                       cellpadding="1"
                       rowpadding="1">
                    <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Đơn phát sinh trong ngày</th>
                        <th>Đơn phát sinh trong ngày đã hoàn thành</th>
                        <th>Tỉ lệ hoàn thành</th>
                        <th>Tỉ lệ đơn bị hủy</th>
                        <th>Tỉ lệ đơn chưa xử lý</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                        $stt = 0;
                        foreach ($data_order as $value) {

                            $Total_sim = $model->getTotal($data_order, array('order_confirm', 'order_success', 'order_cancel'), $stt);

                            foreach ($data_order_create as $order_create) {

                                if ($order_create['date'] == $value['date']) {
                                    $percent_success    = ROUND(($value['order_success'] / $order_create['total']) * 100, 3);
                                    $percent_cancel     = ROUND(($value['order_cancel'] / $order_create['total']) * 100, 3);
                                    $percent_no_process = ROUND((($order_create['total'] - $value['order_cancel'] - $value['order_success']) / $order_create['total']) * 100, 3);

                                    if ($percent_success < 0) {
                                        $percent_success = 0;
                                    }
                                    if ($percent_cancel < 0) {
                                        $percent_cancel = 0;
                                    }
                                    if ($percent_no_process < 0) {
                                        $percent_no_process = 0;
                                    }

                                    if ($percent_success > 100) {
                                        $percent_success = 100;
                                    }
                                    if ($percent_cancel > 100) {
                                        $percent_cancel = 100;
                                    }
                                    if ($percent_no_process > 100) {
                                        $percent_no_process = 100;
                                    }

                                    if ($percent_success + $percent_cancel + $percent_no_process > 100) {
                                        $percent_cancel = 100 - ($percent_success + $percent_no_process);
                                    }

                                    ?>
                                    <tr>
                                        <td><?php echo CHtml::encode($value['date']); ?></td>
                                        <td style="text-align: right;"><?php echo CHtml::encode($order_create['total']); ?></td>
                                        <td style="text-align: right;"><?php echo CHtml::encode($value['order_success']); ?></td>
                                        <td style="text-align: right;"><?php echo CHtml::encode($percent_success . "%"); ?></td>
                                        <td style="text-align: right;"><?php echo CHtml::encode($percent_cancel . "%"); ?></td>
                                        <td style="text-align: right;"><?php echo CHtml::encode($percent_no_process . "%"); ?></td>
                                    </tr>
                                    <?php

                                }
                            }
                            $stt++;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="x_title">
        <h2>Báo cáo doanh thu sim du lịch</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <div class="table-responsive" id="subscribers-post" style="margin-top: 10px;">
                <table width="100%" border="1"
                       style="border-color: #b3b3b3;border: solid;border-width: 1px;color: black;"
                       cellpadding="1"
                       rowpadding="1">
                    <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Sản lượng</th>
                        <th>Doanh thu</th>
                        <th>Doanh thu lũy kế</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                        $stt = 0;
                        IF (isset($data_tourist_accumulated) && isset($data_tourist)) {
                            foreach ($data_tourist as $value) {
                                $Total_tourist = $model->getTotal($data_tourist, array('renueve_tourist'), $stt);
                                ?>
                                <tr>
                                    <td><?php echo CHtml::encode($value['date']); ?></td>
                                    <td style="text-align: right;"><?php echo CHtml::encode(number_format($value['total_tourist'], 0, '', '.')); ?></td>
                                    <td style="text-align: right;"><?php echo CHtml::encode(number_format($value['renueve_tourist'], 0, '', '.')); ?></td>
                                    <td style="text-align: right;"><?php echo CHtml::encode(number_format(($data_tourist_accumulated[0]['renueve_tourist'] - $Total_tourist['renueve_tourist']), 0, '', '.')); ?></td>

                                </tr>
                                <?php
                                $stt++;
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="x_title">
        <h2>Báo cáo số lượng sim và doanh thu lũy kế theo năm</h2>
        <div class="clearfix"></div>
    </div>
    <div class="table-responsive tbl_style center">
        <div class="table-responsive" id="subscribers-post" style="margin-top: 10px;">
            <table width="100%" border="1"
                   style="border-color: #b3b3b3;border: solid;border-width: 1px;color: black;"
                   cellpadding="1"
                   rowpadding="1">
                <thead>
                <tr>
                    <th>NĂM</th>
                    <th>SL SIM</th>
                    <th>DOANH THU SIM</th>
                    <th>SL SIM DU LỊCH</th>
                    <th>DOANH THU SIM DU LICH</th>
                    <th>TỔNG DOANH THU</
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data_result as $item) { ?>
                    <tr>
                    <td><?php echo $item['year'] ?></td>
                    <td><?php echo number_format($item['total_sim']) ?></td>
                    <td><?php echo number_format($item['renueve_sim']) ?></td>
                    <td><?php echo number_format($item['total_sim_tourist']) ?></td>
                    <td><?php echo number_format($item['renueve_sim_tourist']) ?></td>
                        <td><?php echo number_format($item['renueve_sim_tourist']+ $item['renueve_sim']) ?></td>
                    </tr>

                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
    #content {
        overflow-x: scroll;
    }
</style>
<script type="text/javascript">
    $('#search_enhance').click(function () {
        $('.search_enhance').toggle();
        return false;
    });
</script>