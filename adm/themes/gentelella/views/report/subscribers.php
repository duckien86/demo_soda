<div class="x_panel">
    <div class="x_title">
        <h3><?php echo Yii::t('report/menu', 'subscribers') ?></h3>
    </div>
    <div class="clearfix"></div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <?php $this->renderPartial('_search_subscribers', array('model' => $form, 'form_validate' => $form_validate)); ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                    if (isset($data_post) && !empty($data_post)) {
                        ?>
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="title">
                                    <h5 style="float: left;"> * Thuê bao trả sau online</h5>
                                    <a class="btn btn-warning" id="btnExport_post"
                                       style="float: right;"><?php echo Yii::t('adm/label', 'export_csv');?></a>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive" id="subscribers-post" style="margin-top: 10px;">
                                    <table class="table table-bordered table-striped table-hover jambo_table responsive-utilities table post-subscribes">
                                        <thead>
                                        <tr>
                                            <th rowspan="2">Mã tỉnh</th>
                                            <th colspan="2">TB phát triển mới</th>
                                            <th rowspan="2">TB đang hoạt động</th>
                                            <th rowspan="2">TB hủy</th>
                                            <th rowspan="2">TB khóa 1C (khóa IC)</th>
                                            <th rowspan="2">TB khóa 1C (khóa OC)</th>
                                            <th rowspan="2">TB khóa 2C</th>
                                            <th rowspan="2">TB khôi phục</th>
                                        </tr>
                                        <tr>
                                            <th>Hòa mạng mới</th>
                                            <th>Trả trước sang trả sau</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($data_post as $key => $value) {
                                            ?>
                                            <tr>
                                                <td class="vnp_province_id"><?php echo CHtml::encode($value['MATINH']); ?></td>
                                                <td><?php echo CHtml::encode($value['ACTIVE']); ?></td>
                                                <td></td>
                                                <td><?php echo CHtml::encode($value['ACTIVE']); ?></td>
                                                <td><?php echo CHtml::encode($value['CANCEL']); ?></td>
                                                <td><?php echo CHtml::encode($value['LOCK_IC']); ?></td>
                                                <td><?php echo CHtml::encode($value['LOCK_OC']); ?></td>
                                                <td><?php echo CHtml::encode($value['LOCK_2C']); ?></td>
                                                <td><?php echo CHtml::encode($value['RESTORE']); ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
            </div>
            <div class="col-md-12">
                <?php
                    if (isset($data_pre) && !empty($data_pre)) {
                        ?>
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="title">
                                    <h5 style="float: left;"> * Thuê bao trả trước online</h5>
                                    <a class="btn btn-warning" id="btnExport_pre"
                                       style="float: right;"><?php echo Yii::t('adm/label', 'export_csv');?></a>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive" style="margin-top: 10px;">
                                    <table id="subscribers-pre"
                                           class="table table-bordered table-striped table-hover jambo_table responsive-utilities table post-subscribes">
                                        <thead>
                                        <tr>
                                            <th rowspan="2">Mã tỉnh</th>
                                            <th colspan="2">TB phát triển mới</th>
                                            <th rowspan="2">TB đang hoạt động</th>
                                            <th rowspan="2">TB hủy</th>
                                            <th rowspan="2">TB khóa 1C (khóa IC)</th>
                                            <th rowspan="2">TB khóa 1C (khóa OC)</th>
                                            <th rowspan="2">TB khóa 2C</th>
                                            <th rowspan="2">TB khôi phục</th>
                                        </tr>
                                        <tr>
                                            <th>Hòa mạng mới</th>
                                            <th>Trả sau sang trả trước</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($data_pre as $key => $value) {
                                            ?>
                                            <tr>
                                                <td class="vnp_province_id"><?php echo CHtml::encode($value['MATINH']); ?></td>
                                                <td><?php echo CHtml::encode($value['ACTIVE']); ?></td>
                                                <td></td>
                                                <td><?php echo CHtml::encode($value['ACTIVE']); ?></td>
                                                <td><?php echo CHtml::encode($value['CANCEL']); ?></td>
                                                <td><?php echo CHtml::encode($value['LOCK_IC']); ?></td>
                                                <td><?php echo CHtml::encode($value['LOCK_OC']); ?></td>
                                                <td><?php echo CHtml::encode($value['LOCK_2C']); ?></td>
                                                <td><?php echo CHtml::encode($value['RESTORE']); ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
            </div>
        </div>
    </div>
</div>
<style>
    .post-subscribes th {
        text-align: center;
        vertical-align: middle;
    }

    .table tbody td {
        text-align: right;
    }

    .vnp_province_id {
        text-align: left;
    }
</style>
<script>
    $(document).ready(function () {
        $("#btnExport_pre").on('click', function () {
            var uri = $("#subscribers-pre").battatech_excelexport({
                containerid: "subscribers-pre"
                , worksheetName: 'Báo cáo thuê bao trả trước'
                , datatype: 'table'
                , returnUri: true
            });
            $(this).attr('download', "Báo cáo thuê bao trả trước").attr('href', uri).attr('target', '_blank');
        });
        $("#btnExport_post").on('click', function () {
            var uri = $("#subscribers-post").battatech_excelexport({
                containerid: "subscribers-post"
                , worksheetName: 'Báo cáo thuê bao trả sau'
                , datatype: 'table'
                , returnUri: true
            });
            $(this).attr('download', "Báo cáo thuê bao trả sau").attr('href', uri).attr('target', '_blank');
        });

    });
</script>

