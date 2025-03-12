<?php if (isset($id)):
    ?>
    <div class="modal" id="modal_<?php echo $id; ?>" role="dialog">
        <div class="modal-dialog" style="width: 60%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Thông tin tài khoản</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php $this->widget('booster.widgets.TbDetailView', array(
                                'data'       => $model,
                                'attributes' => array(
                                    array(
                                        'name'  => 'Tên đăng nhập',
                                        'value' => function ($data) {
                                            return CHtml::encode(ACustomers::getName($data->sso_id));
                                        }
                                    ),
                                    array(
                                        'name'  => 'phone',
                                        'value' => function ($data) {
                                            return CHtml::encode($data->phone);
                                        }
                                    ),
                                    array(
                                        'name'  => 'email',
                                        'value' => function ($data) {
                                            return CHtml::encode($data->email);
                                        }
                                    ),
                                    array(
                                        'name'  => 'birthday',
                                        'value' => function ($data) {
                                            return CHtml::encode($data->birthday);
                                        }
                                    ),
                                    array(
                                        'name'  => 'full_name',
                                        'value' => function ($data) {
                                            return CHtml::encode($data->full_name);
                                        }
                                    ),
                                    array(
                                        'name'  => 'genre',
                                        'value' => function ($data) {
                                            return CHtml::encode(ACustomers::getGenre($data->genre));
                                        }
                                    ),
                                    array(
                                        'name'  => 'Ngày tham gia',
                                        'value' => function ($data) {
                                            return CHtml::encode($data->create_time);
                                        }
                                    ),

                                    array(
                                        'name'  => 'level',
                                        'value' => function ($data) {
                                            return CHtml::encode($data->level);
                                        }
                                    ),
                                    array(
                                        'name'  => 'job',
                                        'value' => function ($data) {
                                            return CHtml::encode($data->job);
                                        }
                                    ),
                                ),
                            )); ?>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php $this->widget('booster.widgets.TbDetailView', array(
                                'data'       => $model,
                                'attributes' => array(
                                    array(
                                        'name'  => 'province_code',
                                        'value' => function ($data) {
                                            return CHtml::encode($data->province_code);
                                        }
                                    ),
                                    array(
                                        'name'  => 'district_code',
                                        'value' => function ($data) {
                                            return CHtml::encode(ADistrict::getDistrict($data->district_code));
                                        }
                                    ),
                                    array(
                                        'name'  => 'address_detail',
                                        'value' => function ($data) {
                                            return CHtml::encode($data->address_detail);
                                        }
                                    ),
                                    array(
                                        'name'  => 'personal_id',
                                        'value' => function ($data) {
                                            return CHtml::encode($data->personal_id);
                                        }
                                    ),
                                    array(
                                        'name'  => 'personal_id_create_date',
                                        'value' => function ($data) {
                                            return CHtml::encode($data->personal_id_create_date);
                                        }
                                    ),

                                    array(
                                        'name'  => 'bank_account_id',
                                        'value' => function ($data) {
                                            return CHtml::encode($data->bank_account_id);
                                        }
                                    ),
                                    array(
                                        'name'  => 'bank_account_name',
                                        'value' => function ($data) {
                                            return CHtml::encode($data->bank_account_name);
                                        }
                                    ),
                                    array(
                                        'name'  => 'bank_name',
                                        'value' => function ($data) {
                                            return CHtml::encode($data->bank_name);
                                        }
                                    ),
                                    array(
                                        'name'  => 'nation',
                                        'value' => function ($data) {
                                            return CHtml::encode($data->nation);
                                        }
                                    ),


                                ),
                            )); ?>
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" id="hidden_id" value="<?php echo $id; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>

        </div>
    </div>


<?php endif; ?>

