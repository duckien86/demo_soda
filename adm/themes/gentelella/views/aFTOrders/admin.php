<?php
    /* @var $this AFTOrdersController */
    /* @var $model AFTOrders */
    $this->breadcrumbs = array(
        Yii::t('adm/label', 'manage_ft_orders') => array('admin'),
        Yii::t('adm/actions', 'manage'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'manage_ft_orders'); ?></h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="row">
            <?php $this->renderPartial('/aFTOrders/_filter_area',array(
                'model' => $model
            ))?>

            <div class="col-md-12">
                <div id="aftorders-grid_container">
                <?php $this->widget('booster.widgets.TbGridView', array(
                        'id'            => 'aftorders-grid',
                        'dataProvider'  => $model->search(),
                        'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                        'columns'       => array(
                            array(
                                'name'        => 'code',
                                'sortable'    => FALSE,
                                'type'        => 'raw',
                                'value'       => 'CHtml::encode($data->code)',
                                'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:120px'),
                            ),
                            array(
                                'header'      => 'Kháchh hàng',
                                'name'        => 'customer',
                                'type'        => 'raw',
                                'value'       => function($data){
                                    $value = '';
                                    if($data->user_type == AFTUsers::USER_TYPE_CTV){
                                        $arr = explode('@', $data->customer);
                                        $value = $arr[0].'(CTV)';
                                    }else{
                                        $value = $data->customer."($data->company)";
                                    }
                                    return $value;
                                },
                                'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:180px'),
                            ),
                            array(
                                'name'        => 'promo_code',
                                'sortable'    => FALSE,
                                'type'        => 'raw',
                                'value'       => 'CHtml::encode($data->promo_code)',
                                'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:100px'),
                            ),
                            array(
                                'name'        => 'orderer_name',
                                'sortable'    => FALSE,
                                'type'        => 'raw',
                                'value'       => 'CHtml::encode($data->orderer_name)',
                                'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:150px'),
                            ),
                            array(
                                'name'        => 'orderer_phone',
                                'sortable'    => FALSE,
                                'type'        => 'raw',
                                'value'       => 'CHtml::encode($data->orderer_phone)',
                                'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:120px'),
                            ),
                            array(
                                'header'      => 'Địa chỉ nhận hàng',
                                'name'        => 'address_detail',
                                'sortable'    => FALSE,
                                'type'        => 'raw',
                                'value'       => function($data){
                                    $value = $data->address_detail;
                                    $district = ADistrict::getDistrictNameByCode($data->district_code);
                                    $ward = AWard::getWardNameByCode($data->ward_code);
                                    $value.= ", $ward, $district";
                                    return $value;
                                },
                                'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:180px'),
                            ),
                            array(
                                'header'      => 'Trung tâm KD Tỉnh/TP',
                                'name'        => 'province_code',
                                'sortable'    => FALSE,
                                'type'        => 'raw',
                                'value'       => function($data){
                                    $value = AProvince::model()->getProvinceVnp($data->province_code);
                                    return $value;
                                },
                                'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:150px'),
                            ),
                            array(
                                'name'        => 'create_time',
                                'sortable'    => FALSE,
                                'type'        => 'raw',
                                'value'       => function($data){
                                    $value = date('d/m/Y H:i:s', strtotime($data->create_time));
                                    return CHtml::encode($value);
                                },
                                'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;width:150px'),
                            ),
                            array(
                                'name'        => 'status',
                                'type'        => 'raw',
                                'sortable'    => FALSE,
                                'value'       => function ($data) {
                                    return CHtml::activeDropDownList($data, 'status',
                                        AFTOrders::model()->getStatusUserActive($data->status),
                                        array('class'    => 'dropdownlist',
                                            'onChange' => "js:showpopupconfirm($data->id,this.value);event.target.selectedIndex=0;",
                                        )
                                    );
                                },
                                'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:center;vertical-align:middle;padding:10px;width:150px'),
                            ),

                            array(
                                'header'      => 'Cấp quyền',
                                'type'        => 'raw',
                                'value'       => function ($data) {
                                    $result = "Cấp quyền";
                                    $assign = FALSE;
                                    if ($data->user_id != '') {
                                        $user = User::model()->findByAttributes(array('id' => $data->user_id));
                                        if ($user) {
                                            $assign = TRUE;
                                            $result = $user->username;
                                        }
                                    }
                                    if (!ADMIN && !SUPER_ADMIN) {
                                        if (PBH_DN) {
                                            return CHtml::link($result, 'javascript:void(0)',
                                                array('style'   => ($assign) ? 'color:red;' : 'color:blue;',
                                                    'onclick' => 'assign("' . $data->id . '")', 'class' => 'btn btn-primary disabled'));
                                        }
                                    } else {
                                        if ($data->status >= AFTOrders::ORDER_CONFIRM && $data->status != AFTOrders::ORDER_COMPLETE) {
                                            return CHtml::link($result, 'javascript:void(0)',
                                                array('style'   => ($assign) ? 'color:red' : '',
                                                    'onclick' => 'assign("' . $data->id . '")', 'class' => ($assign) ? '' : 'btn btn-primary'));
                                        } ELSE IF ($data->status == AFTOrders::ORDER_COMPLETE) {
                                            return "<span style='color: red;'>$result</span>";
                                        } ELSE {
                                            return CHtml::link($result, 'javascript:void(0)',
                                                array(
                                                    'onclick' => 'assign("' . $data->id . '")', 'class' => 'btn btn-primary disabled'));
                                        }
                                    }

                                },
                                'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                            ),
                            array(
                                'header'      => 'Ghép kít',
                                'type'        => 'raw',
                                'value'       => function ($data) {
                                    $active = false;
                                    $error = false;
                                    if ($data->status >= AFTOrders::ORDER_APPROVED) {
                                        if ($data->status != AFTOrders::ORDER_JOIN_KIT && $data->status != AFTOrders::ORDER_STOP) {

                                            if ($data->status >= AFTOrders::ORDER_APPROVED && $data->status < AFTOrders::ORDER_COMPLETE) {
                                                if (!ADMIN && !SUPER_ADMIN) {
                                                    if (PBH_DN || PBH) {
                                                        if (Yii::app()->user->id == $data->user_id) {
                                                            $active = true;
                                                        }
                                                    }
                                                } else {
                                                    $active = true;
                                                }
                                            } else if ($data->status == AFTOrders::ORDER_COMPLETE || $data->status == AFTOrders::ORDER_RECEIVED) {
                                                $active = false;
                                            } else {
                                                $active = false;
                                            }
                                        } else {
                                            if ($data->status == AFTOrders::ORDER_STOP) {
                                                $error = true;
                                                if (!ADMIN && !SUPER_ADMIN) {
                                                    if (PBH_DN || PBH) {
                                                        if (Yii::app()->user->id == $data->user_id) {
                                                            $active = true;
                                                        }
                                                    } else {
                                                        $active = false;
                                                    }
                                                } else {
                                                    $active = true;
                                                }
                                            } else {
                                                return CHtml::encode("Đang ghép KIT");
                                            }
                                        }
                                    }
                                    $btnClass = 'btn';
                                    if($error){
                                        $btnClass.= ' btn-danger';
                                    }else{
                                        $btnClass.= ' btn-warning';
                                    }
                                    if(!$active){
                                        $btnClass.= ' disabled';
                                    }
                                    return CHtml::link('Ghép KIT', 'javascript:void(0)',
                                        array('style'   => 'color:blue;',
                                            'onclick' => 'showjoinkit("' . $data->id . '")', 'class' => "$btnClass"));
                                },
                                'htmlOptions' => array('nowrap'=>'nowrap','style' => 'text-align: left;word-break: break-word;vertical-align:middle;'),
                            ),
                            array(
                                'header'      => 'Chi tiết',
                                'template'    => '{view}',
                                'buttons'     => array(
                                    'view' => array(
                                        'options' => array('target' => '_blank'),
                                    ),
                                ),
                                'class'       => 'booster.widgets.TbButtonColumn',
                                'htmlOptions' => array('style' => 'text-align:center;vertical-align:middle;padding:10px;width:50px'),
                            ),
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="popup_assign">

    </div>
    <div class="popup_confirm_status">

    </div>
    <div class="popup_join_kit">

    </div>
    <div class="popup_pending_join">

    </div>
    <div class="popup_confirm_join_kit">

    </div>
</div>


<style>
    #aftorders-grid table th{
        word-break: keep-all;
        white-space: nowrap;
    }

    #aftorders-grid.grid-view .summary{
        width: 100%;
        text-align: right;
    }
    #aftorders-grid.grid-view .no-class{
        width: 100%;
        text-align: right;
    }

    #aftorders-grid{
        width: 100%;
        overflow-x: auto;
    }
    #aftorders-grid table{
        max-width: 100%;

    }
</style>

<script type="text/javascript">

    function showjoinkit(order_id) {
        $.ajax({
            type: "POST",
            url: '<?= Yii::app()->createUrl('aFTOrders/showPopupJoinKit') ?>',
            crossDomain: true,
            data: {
                order_id: order_id,
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken ?>'
            },
            success: function (result) {
                $('.modal-backdrop').remove();
                $('.popup_join_kit').html(result);
                var modal_id = 'modal_kit_' + order_id;
                $('#' + modal_id).modal('show');
                return false;
            }
        });
    }

    function assign(order_id) {
        $.ajax({
            type: "POST",
            url: '<?= Yii::app()->createUrl('aFTOrders/showPopupAssign') ?>',
            crossDomain: true,
            data: {
                order_id: order_id,
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken ?>'
            },
            success: function (result) {
                $('.modal-backdrop').remove();
                $('.popup_assign').html(result);
                var modal_id = 'modal_' + order_id;
                $('#' + modal_id).modal('show');
                return false;
            }
        });
    }

    function showpopupconfirm(id, status) {
        $.ajax({
            type: "POST",
            url: '<?=Yii::app()->createUrl('aFTOrders/showPopupConfirmStatus')?>',
            crossDomain: true,
            data: {id: id, status: status, 'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'},
            success: function (result) {
                $('.modal-backdrop').remove();
                $('.popup_confirm_status').html(result);
                var modal_id = 'modal_' + id + '_' + status;
                $('#' + modal_id).modal('show');
            }
        });
    }
</script>

