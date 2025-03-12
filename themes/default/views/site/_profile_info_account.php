<?php

    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use kartik\date\DatePicker;
    use yii\captcha\Captcha;
    use kartik\widgets\FileInput;
    use common\helpers\Helper;

?>
<div id="page-content-wrapper" class="page-content-wrappers">
    <div class="tab-content">
        <!--        cập nhật thông tin user-->
        <div role="tabpanel" class="site-mains tab-pane active" id="profile">
            <div class="rows">
                <div class="row">
                    <div class="col-md-2 nameinput">
                        Họ & Tên
                    </div>
                    <div class="col-md-10 upimg">
                        <?= $form->field($model, 'full_name')->label(FALSE) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 nameinput">
                        Ngày sinh
                    </div>
                    <div class="col-md-10 upimg">
                        <?=
                            $form->field($model, 'birthday')->widget(
                                DatePicker::className(), [
                                'name'          => 'check_issue_date',
                                'value'         => date('d-m-Y'),
                                'type'          => DatePicker::TYPE_COMPONENT_APPEND,
                                'options'       => ['placeholder' => 'Chọn ngày sinh ...'],
                                'removeButton'  => FALSE,
//                                    'readonly' => true,
                                'pluginOptions' => [
                                    'format'         => 'dd-mm-yyyy',
                                    'todayHighlight' => TRUE,
                                    'autoclose'      => TRUE,
                                ]
                            ])->label(FALSE);
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 nameinput">
                        Giới tính
                    </div>
                    <div class="col-md-10 upimg">
                        <?= $form->field($model, 'sex')->dropdownList(['Nữ', 'Nam'], ['prompt' => 'Chọn'])->label(FALSE) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 nameinput">
                        Email
                    </div>
                    <div class="col-md-10 upimg">
                        <?php
                            if ($model->username == $model->phone) {
                                ?>
                                <?= $form->field($model, 'email')->textInput()->label(FALSE) ?>
                                <?php
                            } else {
                                ?>
                                <?= $form->field($model, 'email')->textInput(['readonly' => TRUE])->label(FALSE) ?>
                            <?php } ?>


                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 nameinput">
                        Mobile
                    </div>
                    <div class="col-md-10 upimg">
                        <?= $form->field($model, 'phone')->textInput(['readonly' => TRUE])->label(FALSE) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 nameinput">
                        Địa chỉ
                    </div>
                    <div class="col-md-10 upimg">
                        <?= $form->field($model, 'address')->label(FALSE) ?>
                    </div>
                </div>

                <div id="refcaptcha">
                    <?=
                        $form->field($model, 'code')->widget(Captcha::className(), [
                            'template'     => '<div class="row"><div class="inputcaptcha">{image}</div><div class="inputcaptcha">{input}</div></div>',
                            'imageOptions' => [
                                'id' => 'infosetting-code-image'
                            ]
                        ])->label(FALSE)
                    ?>
                    <i class="fa fa-refresh refresh" aria-hidden="true" id='refresh-captcha'></i>
                </div>
                <div class="form-groupinfo">
                    <?= Html::submitButton('Cập nhật', ['class' => 'btn btn-warning updateinfo', 'name' => 'capnhat']) ?>
                </div>
            </div>
        </div>

        <!--        phần thông tin gói cước của sub cp-->
        <div role="tabpanel" class="tab-pane profilest" id="goicuoc">
            <?php
                if (!empty($package_user)) {
                    ?>
                    <h3>Thông tin gói cước mà bạn đã đăng ký</h3>
                    <table class="table-package-user table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên gói cước</th>
                            <th>Quyền lợi gói</th>
                            <th>Ngày đăng ký</th>
                            <th>Ngày hết hạn</th>
                            <th>Giá gói cước</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            //lặp để hiển thị dánh sách gói cước của sub cp đang đăng nhập
                            foreach ($package_user as $k => $package) {
                                ?>
                                <tr>
                                    <td><?= $k + 1 ?></td>
                                    <td><?= Html::encode($package['package_name']) ?></td>
                                    <td><?= Html::encode($package['package_description']) ?></td>
                                    <td><?= Helper::convertDateTimePackage($package['init_date']) ?></td>
                                    <td><?= Helper::convertDateTimePackage($package['expire_date']) ?></td>
                                    <td><?= Helper::product_price($package['price']) ?></td>
                                    <td></td>
                                </tr>
                            <?php }
                        ?>
                        </tbody>
                    </table>

                    <?php
                } else {
                    ?>
                    <h3>Hiện tại bạn chưa đăng ký gói cước nào</h3>
                <?php }
            ?>
        </div>
        <div role="tabpanel" class="tab-pane lsu profilest" id="lichsuthanhtoan">
            <?php
                //phần hiển thị thông tin lịch sử tải game tương ứng với sub cp đang đăng nhập
                if (!empty($game_download_history)) {
                    ?>
                    <h3>Danh sách game bạn đã download</h3>
                    <table class="table-package-user table  table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên game</th>
                            <th>Loại game</th>
                            <th>Giá tiền</th>
                            <th>Ngày tải</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach ($game_download_history as $k => $game) {
                                //phân loại trường hợp mua bằng coin hay cash
                                if (!empty($game['credit'])) {
                                    $game_price = Helper::product_price($game['credit'], "") . " Coin";
                                } else {
                                    $game_price = Helper::product_price($game['price']);
                                }
                                ?>
                                <tr>
                                    <td><?= $k + 1 ?></td>
                                    <td>
                                        <a class="game-name"
                                           href="<?= Yii::$app->urlManager->createAbsoluteUrl('/chi-tiet-game/' . Html::encode($game['alias']) . '-' . $game['item_id']) ?>"
                                           title="<?= Html::encode($game['game_name']) ?>">
                                            <?= Html::encode($game['game_name']) ?>
                                        </a>
                                    </td>
                                    <td><?= Html::encode($game['item_type']) ?></td>
                                    <td><?= $game_price ?></td>
                                    <td><?= Helper::convertDateTimePackage($game['create_time']) ?></td>
                                    <td></td>
                                </tr>
                            <?php }
                        ?>
                        </tbody>
                    </table>

                    <?php
                } else {
                    ?>
                    <h3>Bạn chưa mua game nào</h3>
                <?php }
            ?>
        </div>
    </div>
</div>
