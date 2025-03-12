<!--tab-result-->
<?php
    $data_prepaid = new CArrayDataProvider($data['data_prepaid'], array(
        'keyField'   => FALSE,
        'pagination' => FALSE,
    ));
    $data_postpaid = new CArrayDataProvider($data['data_postpaid'], array(
        'keyField'   => FALSE,
        'pagination' => FALSE,
    ));
?>
<div class="tab-result">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-pad-mar">
                <ul class="nav nav-tabs tabl-search-result">
                    <li class="active"><a data-toggle="tab" href="#prepay">Trả trước</a></li>
                    <li><a data-toggle="tab" href="#postpaid">Trả sau</a></li>
                </ul>
                <div class="tab-content">
                    <div id="prepay" class="tab-pane fade in active">
                        <div class="wrap-tabs">
                            <?php $this->renderPartial('_mobile_list_msisdn', array('data' => $data_prepaid)); ?>
                        </div>
                    </div>
                    <div id="postpaid" class="tab-pane fade">
                        <div class="wrap-tabs">
                            <?php $this->renderPartial('_mobile_list_msisdn', array('data' => $data_postpaid)); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end tab-result-->

<!-- Modal sim info-->
<div id="sim_info_modal" class="sim_info_modal modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Chi tiết thông tin sim</h4>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tbody>
                    <tr>
                        <td class="title_sim_text">Số thuê bao :</td>
                        <td id="sim_tex" class="sim_text">01224334645</td>
                    </tr>
                    <tr>
                        <td class="title_sim_text">Giá sim :</td>
                        <td><b id="price_text">60.000 đ</b></td>
                    </tr>
                    <tr>
                        <td class="title_sim_text">Thời gian cam kết :</td>
                        <td><b id="term_text">18 tháng</b></td>
                    </tr>
                    <tr>
                        <td class="title_sim_text">Cước cam kết :</td>
                        <td><b id="price_term_text">150.000/tháng</b></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button id="btn_mobile_add_cart" type="button" class="btn btn_continue" data-dismiss="modal">Mua sim</button>
            </div>
        </div>

    </div>
</div>
<!--./END modal sim info-->