<?php
/**
 * @var $this ReportController
 * @var $form ReportForm
 * @var $form_validate ReportForm
 * @var $data CArrayDataProvider
 * @var $data_detail CArrayDataProvider
 * @var $order ROrders
 */
?>
<div class="x_panel">
    <div class="x_title">
        <h3>Doanh thu bán gói cước kèm SIM</h3>
    </div>

    <div class="clearfix"></div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search_package_simkit', array('model' => $form, 'form_validate' => $form_validate)); ?>
        </div>

        <?php if (isset($data) && !empty($data)): ?>
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="title">
                        <h5> * Doanh thu tổng quan</h5>
                    </div>
                </div>
                <div class="col-sm-6">
                    <form method="post"
                          action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportPackageSimKit'); ?>" target="_blank">
                        <input type="hidden" name="excelExport[start_date]" value="<?php echo $form->start_date ?>">
                        <input type="hidden" name="excelExport[end_date]" value="<?php echo $form->end_date ?>">
                        <input type="hidden" name="excelExport[province_code]" value="<?php echo $form->province_code ?>">
                        <input type="hidden" name="excelExport[sale_office_code]" value="<?php echo $form->sale_office_code ?>">
                        <input type="hidden" name="excelExport[package_id]" value="<?php echo $form->package_id ?>">
                        <input type="hidden" name="excelExport[brand_offices_id]" value="<?php echo $form->brand_offices_id ?>">
                        <input type="hidden" name="excelExport[sim_type]" value="<?php echo $form->sim_type ?>">
                        <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                    </form>
                </div>
            </div>

            <?php
            $list_sum_total = array();
            $list_sum_revenue = array();
            $data_package = array();
            foreach ($data->rawData as $order){
                foreach ($order->packages as $key => $value){
                    if(!isset($data_package[$key])){
                        $data_package[$key] = $value['name'];
                    }
                    if(!isset($list_sum_total[$key])){
                        $list_sum_total[$key] = 0;
                    }
                    if(!isset($list_sum_revenue[$key])){
                        $list_sum_revenue[$key] = 0;
                    }
                    $list_sum_total[$key] += $value['total'];
                    $list_sum_revenue[$key] += $value['revenue'];
                }
            }

            $count_data = count($data->rawData);
            $count_package = count($data_package);
            ?>

            <div class="tbl_style" id="report_packagesimkit">
                <?php if ($count_data > 0){
                    echo "<div class='summary'>Hiển thị 1-$count_data của $count_data kết quả.</div>";
                } ?>
                <table class="items table table-striped table-bordered">
                    <thead>
                    <?php
                    $rowspan = 1;
                    $colspan = 1;
                    if($count_package > 0){
                        $rowspan = 2;
                        $colspan = $count_package;
                    }
                    ?>
                    <tr>
                        <th rowspan="<?php echo $rowspan?>">Tỉnh thành</th>
                        <th colspan="<?php echo $colspan?>">Số lượng</th>
                        <th colspan="<?php echo $colspan?>">Doanh thu</th>
                        <th rowspan="<?php echo $rowspan?>">Tổng doanh thu</th>
                    </tr>
                    <?php if($count_package > 0){ ?>
                    <tr>
                        <?php foreach ($data_package as $key => $value){
                            echo "<th>$value</th>";
                        } ?>
                        <?php foreach ($data_package as $key => $value){
                            echo "<th>$value</th>";
                        } ?>
                    </tr>
                    <?php } ?>
                    </thead>

                    <tbody>
                    <?php
                    if($count_data > 0) {
                        for ($i=0; $i<$count_data; $i++){
                            $order = $data->rawData[$i];
                            $td_class = ($i%2 == 1) ? 'odd' : 'even';
                            ?>
                            <tr class="<?php echo $td_class?>">
                                <td><?php echo AProvince::getProvinceNameByCode($order->province_code)?></td>
                                <?php
                                $sum_revenue_by_province = 0;
                                foreach ($data_package as $key => $value){
                                    echo "<td class='text-right'>".number_format($order->packages[$key]['total'], 0, ',', '.')."</td>";
                                }
                                foreach ($data_package as $key => $value){
                                    echo "<td class='text-right'>".number_format($order->packages[$key]['revenue'], 0, ',', '.')."</td>";
                                    $sum_revenue_by_province+= $order->packages[$key]['revenue'];
                                }
                                ?>
                                <td class="text-right" style="font-weight: bold"><?php echo number_format($sum_revenue_by_province, 0, ',', ',');?></td>
                            </tr>
                            <?php
                        }
                    }else{
                        echo '<tr><td colspan="4" class="empty"><span class="empty">Không có dữ liệu.</span></td></tr>';
                    }
                    ?>
                    </tbody>
                    <tfoot style="font-weight: bold">
                    <tr>
                        <td>Tổng</td>
                        <?php
                        if($count_package > 0){
                            foreach ($data_package as $key => $value){
                                echo "<td class='text-right'>".number_format($list_sum_total[$key],0,',','.')."</td>";
                            }
                            foreach ($data_package as $key => $value){
                                echo "<td class='text-right'>".number_format($list_sum_revenue[$key],0,',','.')."</td>";
                            }
                        }else{
                            echo "<td class='text-right'>0</td>";
                            echo "<td class='text-right'>0</td>";
                        }
                        ?>
                        <td class='text-right'><?php echo number_format(array_sum($list_sum_revenue),0,',','.');?></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <div class="space_30"></div>

        <?php if ($form->on_detail == 'on'):?>
            <?php if (isset($data_detail) && !empty($data_detail)): ?>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="title">
                            <h5> * Danh sách chi tiết</h5>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <form method="post"
                              action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/reportPackageSimKitDetail'); ?>" target="_blank">
                            <input type="hidden" name="excelExport[start_date]" value="<?php echo $form->start_date ?>">
                            <input type="hidden" name="excelExport[end_date]" value="<?php echo $form->end_date ?>">
                            <input type="hidden" name="excelExport[province_code]" value="<?php echo $form->province_code ?>">
                            <input type="hidden" name="excelExport[sale_office_code]" value="<?php echo $form->sale_office_code ?>">
                            <input type="hidden" name="excelExport[package_id]" value="<?php echo $form->package_id ?>">
                            <input type="hidden" name="excelExport[brand_offices_id]" value="<?php echo $form->brand_offices_id ?>">
                            <input type="hidden" name="excelExport[sim_type]" value="<?php echo $form->sim_type ?>">
                            <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                        </form>
                    </div>
                </div>

                <?php
                    $this->widget('booster.widgets.TbGridView', array(
                        'dataProvider' => $data_detail,
                        'type'        => 'striped bordered  consended ',
                        'htmlOptions' => array(
                            'class' => 'tbl_style',
                            'id'    => 'report_packagesimkit_detail',
                        ),
                        'columns'     => array(
                            array('header' => 'STT',
                                  'value'  => '++$row',
                            ),
                            array(
                                'header'      => 'Mã đơn hàng',
                                'value'       => function ($data) {
                                    $return = $data->id;

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),
                            array(
                                'header'      => 'Số thuê bao',
                                'value'       => function ($data) {

                                    $return = $data->sim;

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px; text-align:left;',
                                ),
                            ),
                            array(
                                'header'      => 'Tên gói',
                                'value'       => function ($data) {

                                    $return = $data->item_name;

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px; text-align:left;',
                                ),
                            ),
                            array(
                                'header'      => 'Ngày mua',
                                'value'       => function ($data) {

                                    $return = $data->create_date;

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),
                            array(
                                'header'      => 'Nhóm gói',
                                'value'       => function ($data) {
                                    $return = Report::getTypeName($data->type_package);

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px; text-align:left;',
                                ),
                            ),
                            array(
                                'header'      => 'TTKD',
                                'value'       => function ($data) {

                                    $return = Report::getProvince($data->province_code);

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),
                            array(
                                'header'      => 'Phòng BH',
                                'value'       => function ($data) {
                                    $sale = SaleOffices::model()->getSaleOffices($data->sale_office_code);

                                    return CHtml::encode($sale);
                                },
                                'htmlOptions' => array(
                                    'style' => '',
                                ),
                            ),
                            array(
                                'header'      => 'Doanh thu',
                                'value'       => function ($data) {

                                    $return = number_format($data->renueve, 0, "", ".");

                                    return $return;
                                },
                                'htmlOptions' => array(
                                    'style' => 'width:100px; text-align:right;',
                                ),
                            ),


                        ),
                    ));
                ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</div>
