<?php
/**
 * @var $this AFTReportController
 * @var $model AFTReport
 * @var $data array
 */

$count_data = count($data);
?>

<div class="x_panel">

    <div class="x_title">
        <h3>Tra cứu TB đại lý</h3>
    </div>
    <div class="clearfix"></div>
    <div class="row" style="margin-top: 10px;">

        <div class="col-md-12">
            <?php $this->renderPartial('/aFTReport/_search_sim', array('model' => $model)); ?>
        </div>

        <?php if (isset($_REQUEST['AFTReport']) && $model->validate()):?>
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-6">
                        <h5 class="title">
                            * Lịch sử
                        </h5>
                </div>
            </div>

            <div class="tbl_style" id="thongsoChung">
                <table class="items table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Mã CTV</th>
                        <th>Số TB</th>
                        <th>Sản phẩm</th>
                        <th>Ngày đặt hàng</th>
                        <th>Thời gian khởi tạo</th>
                        <th>Trạng thái</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!empty($data)){
                        foreach ($data as $item){?>
                        <tr>
                            <td><?php echo $item['ORDER']->code; ?></td>
                            <td>
                                <?php
                                $arr = explode("@",$item['USER']->username);
                                echo $arr[0];
                                ?>
                            </td>
                            <td><?php echo $item['USER']->invite_code ?></td>
                            <td><?php echo $item['MSISDN'] ?></td>
                            <td><?php echo $item['PACKAGE']->name ?></td>
                            <td><?php echo $item['ORDER']->create_time ?></td>
                            <td><?php echo $item['ASSIGN_KIT_TIME'] ?></td>
                            <td>
                                <?php
                                echo ($item['ASSIGN_KIT_STATUS'] == 10)
                                    ? 'Khởi tạo thành công' : 'Khởi tạo thất bại';
                                ?>
                            </td>
                        </tr>
                        <?php
                        }
                    }else{
                        echo '<td colspan="8" class="empty"><span class="empty">Không có dữ liệu.</span></td>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php


            ?>
        </div>
        <?php endif; ?>
    </div>

</div>
