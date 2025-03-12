<div class="x_panel">
    <div class="x_title">
        <h3>Báo cáo hoa hồng khuyến khích ĐLTC</h3>
    </div>
    <div class="clearfix"></div>

    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search_incentives_agency', array('model' => $form, 'form_validate' => $form_validate)); ?>
        </div>
    </div>
    <div class="row" style="margin-top: 10px;">
        <?php if (isset($data) && !empty($data)) { ?>
        <div class="col-lg-12">
            <?php
            $total = Yii::app()->cache->get('incentives_detail_cache');
            $total_agency = 0;
            $total_vnp = 0;
            $total_postpaid = 0;
            $sum_commission = 0;
            $timeSpent = array();
            foreach ($total as $item){
                $timeSpent[] = $item['affiliate_channel'];
                $total_agency += $item['affiliate_channel'];

                if($item->campaign_category_id == 1){
                    $total_vnp ++;
                }elseif ($item->campaign_category_id == 2){
                    $total_postpaid ++;
                }
                $sum_commission += $item->amount;
            }
            ?>
            <div class="" style="margin: 10px 0px; font-weight: bold">Thống kê tổng quan</div>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Tổng số ĐLTC hưởng KK</th>
                    <th>Tổng sản lượng SIM trả sau</th>
                    <th>Số lượng TB hòa mạng VNP trả sau</th>
                    <th>Số lượng chuyển đổi trả trước sang trả sau</th>
                    <th>Tổng thù lao khuyến khích TB trả sau</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo $total_agency + 1; ?></td>
                    <td><?php echo $total_postpaid + $total_vnp ?></td>
                    <td><?php echo $total_vnp; ?></td>
                    <td><?php echo $total_postpaid; ?></td>
                    <td><?php echo number_format($sum_commission); ?></td>
                    <td><button class="btn btn-success" data-toggle="collapse" data-target="#showdata">Xem chi tiết</button></td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php }?>
    </div>
    <div id="showdata" class="collapse">
    <div class="row" style="margin-top: 10px;">
        <?php if (isset($data) && !empty($data)) { ?>
            <div class="col-lg-12">
                <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/incentiveAgency'); ?>"
                      name="fday" target="_blank">
                    <input type="hidden" name="YII_CSRF_TOKEN"
                           value="<?php echo Yii::app()->request->csrfToken ?>">
                    <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
                </form>
            </div>
            <style>
               #incentives-grid table tr th{
                   text-align: center;
               }

            </style>
            <div class="col-md-12">
                <div class="" style="margin: 10px 0px; font-weight: bold">Chi tiết thù lao khuyến khích phát triển thuê bao trả sau </div>
                <?php $this->widget('booster.widgets.TbGridView', array(
                    'id' => 'incentives-grid',
                    'dataProvider' => $data,
                    'itemsCssClass' => 'table table-bordered table-striped table-hover responsive-utilities',
                    'columns' => array(
                        array('header' => 'STT',
                            'value'  => '++$row',
                        ),
                        array(
                            'header' => 'Tên đăng nhập',
                            'type' => 'raw',
                            'value' => function ($data) {
                                return Chtml::encode($data->affiliate_channel);
                            },
                            'htmlOptions' => array('style' => 'text-align:left;vertical-align:middle;width:120px;'),
                        ),
                        array(
                            'header' => 'Tên ĐLTC',
                            'type' => 'raw',
                            'value' => function ($data) {
                                return Chtml::encode($data->agency_name);
                            },
                            'htmlOptions' => array('style' => 'text-align:left;vertical-align:middle;width:120px;'),
                        ),
                        array(
                            'header' => 'Mã ĐLTC',
                            'type' => 'raw',
                            'value' => function ($data) {
                                return Chtml::encode($data->affiliate_channel);
                            },
                            'htmlOptions' => array('style' => 'text-align:left;vertical-align:middle;width:120px;'),
                        ),
                        array(
                            'header' => 'Nhóm ĐLTC',
                            'type' => 'raw',
                            'value' => function ($data) {
                                return Chtml::encode('Có hệ thống');
                            },
                            'htmlOptions' => array('style' => 'text-align:left;vertical-align:middle;width:120px;'),
                        ),

                        array(
                            'header' => 'Mã dơn hàng',
                            'type' => 'raw',
                            'value' => function ($data) {
                                return Chtml::encode($data->order_id);
                            },
                            'htmlOptions' => array('style' => 'text-align:left;vertical-align:middle;width:120px;'),
                        ),
                        array(
                            'header' => 'Số thuê bao',
                            'type' => 'raw',
                            'value' => function ($data) {
                                return Chtml::encode($data->item_name);
                            },
                            'htmlOptions' => array('style' => 'text-align:center;vertical-align:middle;width:120px;'),
                        ),
                        array(
                            'header' => 'Ngày mua',
                            'type' => 'raw',
                            'value' => function ($data) {
                                return Chtml::encode($data->order_create_date);
                            },
                            'htmlOptions' => array('style' => 'text-align:right;vertical-align:middle;width:150px;'),
                        ),

                        array(
                            'header' => 'Loại dịch vụ',
                            'type' => 'raw',
                            'value' => function ($data) {
                                return Chtml::encode($data->type_service);
                            },
                            'htmlOptions' => array('style' => 'text-align:right;vertical-align:middle;width:150px;'),
                        ),
                        array(
                            'header' => 'TTKD',
                            'type' => 'raw',
                            'value' => function ($data) {
                                return Chtml::encode($data->province_code);
                            },
                            'htmlOptions' => array('style' => 'text-align:center;vertical-align:middle;width:150px;'),
                        ),
                        array(
                            'header' => 'Thù lao khuyến khích',
                            'type' => 'raw',
                            'value' => function ($data) {
                                return Chtml::encode($data->amount);
                            },
                            'htmlOptions' => array('style' => 'text-align:right;vertical-align:middle;width:150px;'),
                        ),
                    ),
                )); ?>
            </div>
        <?php } ?>
    </div>
    </div>
</div>
