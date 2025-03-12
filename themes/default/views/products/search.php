<div id="main_container" class="container">
    <div class="table-responsive">
        <table class="table-simso table table-bordered">
            <thead>
            <tr>
                <th class="col-stt">STT</th>
                <th class="col-number">
                    Sim
                </th>
                <th class="col-price">
                    Giá trả trước
                </th>
                <th class="col-price">
                    Giá trả sau
                </th>
                <th class="col-shopping">Chọn mua</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="col-stt">1</td>
                <td class="col-number"><a
                        href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>">0868859241</a>
                </td>
                <td class="col-price">50.000</td>
                <td class="col-price">60.000</td>

                <td class="col-shopping">
                    <a class="btn-buy btnBuySim" href="javascript:void(0)" data-simid="478295"
                       data-simnumber="868859241"
                       data-url="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>"><span>Mua</span></a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>