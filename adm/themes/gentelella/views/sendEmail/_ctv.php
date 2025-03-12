<?php
    /* @var $this AOrdersController */
    /* @var $model AOrders */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'orders') => array('admin'),
        Yii::t('adm/actions', 'manage'),
    );

    //    $Total = $model->getTotal($data_agency, array('total_renueve'));

?>

<div class="x_panel">
    <div class="x_title">
        <h2>Báo cáo cộng tác viên cá nhân</h2>
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
                        <th rowspan="2">Đăng ký</th>
                        <th rowspan="2">Cấp mã</th>
                        <th rowspan="2">Tỷ lệ</th>
                        <th colspan="3">Month To Date</th>
                        <th colspan="3">Lũy kế</th>
                    </tr>
                    <tr>
                        <th>Đăng ký</th>
                        <th>Cấp mã</th>
                        <th>Tỷ lệ</th>
                        <th>Đăng ký</th>
                        <th>Cấp mã</th>
                        <th>Tỷ lệ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $key => $value) {
                        ?>
                        <tr>
                            <td><?php echo $key; ?></td>

                            <td style="text-align: right;"><?php echo ($value['total'] != 0) ? number_format($value['total'], 0, '', '.') : 0; ?></td>
                            <td style="text-align: right;"><?php echo ($value['finish_profile'] != 0) ? number_format($value['finish_profile'], 0, '', '.') : 0; ?></td>
                            <td style="text-align: right;"><?php echo ($value['rate'] != 0) ? $value['rate'] : 0; ?></td>
                            <td style="text-align: right;"><?php echo ($value['total_month'] != 0) ? number_format($value['total_month'], 0, '', '.') : 0; ?></td>
                            <td style="text-align: right;"><?php echo ($value['finish_profile_month'] != 0) ? number_format($value['finish_profile_month'], 0, '', '.') : 0; ?></td>
                            <td style="text-align: right;"><?php echo ($value['rate_month'] != 0) ? $value['rate_month'] : 0; ?></td>
                            <td style="text-align: right;"><?php echo ($value['total_accumulated'] != 0) ? number_format($value['total_accumulated'], 0, '', '.') : 0; ?></td>
                            <td style="text-align: right;"><?php echo ($value['finish_profile_accumulated'] != 0) ? number_format($value['finish_profile_accumulated'], 0, '', '.') : 0; ?></td>
                            <td style="text-align: right;"><?php echo ($value['rate_accumulated'] != 0) ? $value['rate_accumulated'] : 0; ?></td>
                        </tr>
                    <?php }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="x_title">
        <h2>Kết quả hoạt động</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <div class="table-responsive" id="subscribers-post" style="margin-top: 10px;">
                <table width="100%" border="1"
                       style="border-color: #b3b3b3;border: solid;border-width: 1px; color: black;"
                       cellpadding="1"
                       rowpadding="1">
                    <thead>
                    <tr>
                        <th rowspan="2">Ngày</th>
                        <th rowspan="2">Số lượng</th>
                        <th colspan="4">Month To Date</th>
                        <th colspan="2">Year To Date</th>
                    </tr>
                    <tr>

                        <th>Phát sinh doanh thu</th>
                        <th>Tỷ lệ</th>
                        <th>Doanh thu lũy kế</th>
                        <th>Doanh thu trung bình</th>
                        <th>Doanh thu lũy kế</th>
                        <th>Doanh thu trung bình</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($data as $key => $value) {

                        ?>
                        <tr>
                            <td><?php echo $key; ?></td>

                            <td style="text-align: right;"><?php echo ($value['finish_profile_accumulated'] != 0) ? number_format($value['finish_profile_accumulated'], 0, '', '.') : 0; ?></td>
                            <td style="text-align: right;"><?php echo ($value['total_create_renueve_month'] != 0) ? number_format($value['total_create_renueve_month'], 0, '', '.') : 0; ?></td>
                            <td style="text-align: right;"><?php echo ($value['total_month'] != 0) ? ROUND(($value['total_create_renueve_month'] / $value['finish_profile_accumulated']) * 100, 3) . '%' : 0; ?></td>
                            <td style="text-align: right;"><?php echo ($value['renueve_month'] != 0) ? number_format($value['renueve_month'], 0, '', '.') : 0; ?></td>
                            <td style="text-align: right;"><?php echo ($value['rate_create_month'] != 0) ? $value['rate_create_month'] : 0; ?></td>
                            <td style="text-align: right;"><?php echo ($value['renueve_year'] != 0) ? number_format($value['renueve_year'], 0, '', '.') : 0; ?></td>
                            <td style="text-align: right;"><?php echo ($value['rate_create_year'] != 0) ? number_format($value['rate_create_year'], 0, '', '.') : 0; ?></td>
                        </tr>
                    <?php }
                    ?>
                    </tbody>
                </table>
            </div>
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
    