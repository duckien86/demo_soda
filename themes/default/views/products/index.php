<div id="main_container" class="container">
    <div style="margin-top: 50px;">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id'     => 'form_search',
            'action' => Yii::app()->controller->createUrl('products/search'),
//            'method' => 'get',
        )); ?>
        <div class="form-group">
            <div class="input-group add-on">
                <input class="textbox" placeholder="<?= Yii::t('web/portal', 'search'); ?>" name="WProducts['keyword']"
                       id="WProducts_keyword" type="text">

                <div class="input-group-btn">
                    <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </div>
        <!-- /input-group -->
        <?php $this->endWidget(); ?>
    </div>
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
                <td class="col-number">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>">0868859241</a>
                </td>
                <td class="col-price">50.000</td>
                <td class="col-price">60.000</td>

                <td class="col-shopping">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>"><span>Mua</span></a>
                </td>
            </tr>
            <tr>
                <td class="col-stt">2</td>
                <td class="col-number">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>">0868859241</a>
                </td>
                <td class="col-price">50.000</td>
                <td class="col-price">60.000</td>

                <td class="col-shopping">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>"><span>Mua</span></a>
                </td>
            </tr>
            <tr>
                <td class="col-stt">3</td>
                <td class="col-number">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>">0868859241</a>
                </td>
                <td class="col-price">50.000</td>
                <td class="col-price">60.000</td>

                <td class="col-shopping">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>"><span>Mua</span></a>
                </td>
            </tr>
            <tr>
                <td class="col-stt">4</td>
                <td class="col-number">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>">0868859241</a>
                </td>
                <td class="col-price">50.000</td>
                <td class="col-price">60.000</td>

                <td class="col-shopping">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>"><span>Mua</span></a>
                </td>
            </tr>
            <tr>
                <td class="col-stt">5</td>
                <td class="col-number">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>">0868859241</a>
                </td>
                <td class="col-price">50.000</td>
                <td class="col-price">60.000</td>

                <td class="col-shopping">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>"><span>Mua</span></a>
                </td>
            </tr>
            <tr>
                <td class="col-stt">6</td>
                <td class="col-number">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>">0868859241</a>
                </td>
                <td class="col-price">50.000</td>
                <td class="col-price">60.000</td>

                <td class="col-shopping">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>"><span>Mua</span></a>
                </td>
            </tr>
            <tr>
                <td class="col-stt">7</td>
                <td class="col-number">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>">0868859241</a>
                </td>
                <td class="col-price">50.000</td>
                <td class="col-price">60.000</td>

                <td class="col-shopping">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>"><span>Mua</span></a>
                </td>
            </tr>
            <tr>
                <td class="col-stt">8</td>
                <td class="col-number">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>">0868859241</a>
                </td>
                <td class="col-price">50.000</td>
                <td class="col-price">60.000</td>

                <td class="col-shopping">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>"><span>Mua</span></a>
                </td>
            </tr>
            <tr>
                <td class="col-stt">9</td>
                <td class="col-number">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>">0868859241</a>
                </td>
                <td class="col-price">50.000</td>
                <td class="col-price">60.000</td>

                <td class="col-shopping">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>"><span>Mua</span></a>
                </td>
            </tr>
            <tr>
                <td class="col-stt">10</td>
                <td class="col-number">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>">0868859241</a>
                </td>
                <td class="col-price">50.000</td>
                <td class="col-price">60.000</td>

                <td class="col-shopping">
                    <a href="<?= Yii::app()->controller->createUrl('checkout/checkout', array('id' => '12345')); ?>"><span>Mua</span></a>
                </td>
            </tr>

            </tbody>
        </table>
    </div>
</div>