<?php
/**
 * @var $this OrderCtvController
 * @var $model TOrders
 * @var $model_details TOrderDetails
 */
$this->pageTitle = 'Freedoo - ' . Yii::t('tourist/label', 'freedoo_tourist') . ' - ' . Yii::t('tourist/label', 'order') . ' #' . $model->id;
$this->breadcrumbs=array(
    Yii::t('tourist/label', 'order') . ' ' . $model->code,
);
?>

<div id="order">

    <?php if($model->status == TOrders::ORDER_DRAFTS){
        $this->step = OrderCtvController::STEP_CONFIRM_ORDER;
        $this->renderPartial('/orderCtv/_block_form_wizard');
    }?>

    <div class="row">
        <div class="col-md-6">
            <div class="title-order">
                <?php echo CHtml::encode(Yii::t('tourist/label','order_info')) ?>
            </div>
            <?php $this->widget('booster.widgets.TbDetailView', array(
                'data'       => $model,
                'type'       => '',
                'htmlOptions'=> array(
                    'class' => 'table table-bordered table-striped table-responsive table-hover td-width-50',
                    'style' => 'margin-top: 20px',
                ),
                'attributes' => array(
                    array(
                        'name'        => Yii::t('tourist/label','order_code'),
                        'value'       => function ($data) {
                            return Chtml::encode($data->code);
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
//                    array(
//                        'name'        => Yii::t('tourist/label','contract_id'),
//                        'value'       => function ($data) {
//                            return Chtml::encode(TContracts::getContractCodeByOrder($data));
//                        },
//                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
//                    ),
                    array(
                        'name'        => Yii::t('tourist/label','create_time'),
                        'value'       => function ($data) {
                            return Chtml::encode(date('d/m/Y H:i:s', strtotime($data->create_time)));
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => Yii::t('tourist/label','delivery_date'),
                        'value'       => function ($data) {
                            if(strtotime($data->delivery_date)){
                                return CHtml::encode(date('d/m/Y', strtotime($data->delivery_date)));
                            }else{
                                return '';
                            }
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => Yii::t('tourist/label','business_center'),
                        'value'       => function ($data) {
                            return Chtml::encode(TProvince::model()->getProvinceVnp($data->province_code));
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => Yii::t('tourist/label','payment_method'),
                        'value'       => function ($data) {
                            return Chtml::encode(TOrders::getPaymentLabel($data->payment_method));
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        'visible'     => (Yii::app()->user->user_type = TUsers::USER_TYPE_CTV) ? true : false
                    ),
                    array(
                        'name'        => 'File xác thực thanh toán',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            $url = TFiles::getFileUrl(TFiles::OBJECT_FILE_ACCEPT_PAYMENT,$data->id);
                            $name = TFiles::getFileName(TFiles::OBJECT_FILE_ACCEPT_PAYMENT,$data->id);
                            $link = '';
                            if($url){
                                $link = CHtml::link($name,$url, array(
                                    'target' => '_blank',
                                ));
                            }
                            return $link;
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        'visible'     => (Yii::app()->user->user_type = TUsers::USER_TYPE_CTV) ? true : false
                    ),
                    array(
                        'name'      => Yii::t('tourist/label', 'file_sim'),
                        'type'      => 'raw',
                        'value'     => function($data){
                            $value = '';
                            $list_file_sim = TFiles::getALlFiles(TFiles::OBJECT_FILE_SIM,$data->id);
                            $baseUrl = Yii::app()->baseUrl.'/../';
                            foreach ($list_file_sim as $file){
                                $name = $file->file_name . '.' .$file->file_ext;
                                $fileUrl = $file->folder_path . $file->file_name . '.' . $file->file_ext;
                                $url = $baseUrl . $fileUrl;
                                $value.= CHtml::link($name,$url, array(
                                    'target' => '_blank',
                                ));
                                $value.= "<br/>";
                            }
                            return $value;
                        },
                        'visible'   => (Yii::app()->user->user_type == TUsers::USER_TYPE_CTV)
                    ),
                    array(
                        'name'        => Yii::t('tourist/label', 'status'),
                        'value'       => function ($data) {
                            return Chtml::encode(TOrders::getOrdersStatusLabel($data));
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => Yii::t('tourist/label', 'order_total'),
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            $order_total = TOrders::getTotalOrders($data->id);

                            return CHtml::link(number_format($order_total, 0, '', '.') . " đ", 'javascript:void(0)', array('style' => 'font-size:16px; color:#ed0677;'));
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
//                    array(
//                        'name'      => Yii::t('tourist/label', 'promo_code_2'),
//                        'type'      => 'raw',
//                        'value'     => function($data){
//                            return CHtml::encode($data->promo_code);
//                        }
//                    ),
//                    array(
//                        'name'      => Yii::t('tourist/label', 'accepted_payment_files'),
//                        'type'      => 'raw',
//                        'value'     => function($data){
//                            $url = TFiles::getFileUrl(TFiles::OBJECT_FILE_ACCEPT_PAYMENT,$data->id);
//                            $name = TFiles::getFileName(TFiles::OBJECT_FILE_ACCEPT_PAYMENT,$data->id);
//                            $link = '';
//                            if($url){
//                                $url = Yii::app()->baseUrl."/".$url;
//                                $link = CHtml::link($name,$url, array(
//                                    'target' => '_blank',
//                                ));
//                            }
//                            return $link;
//                        }
//                    ),
                ),
            )); ?>
        </div>
        <div class="col-md-6">
            <div class="title-order">
                <?php echo CHtml::encode(Yii::t('tourist/label','customer_info')) ?>
            </div>
            <?php $this->widget('booster.widgets.TbDetailView', array(
                'data'       => $model,
                'type'       => '',
                'htmlOptions'=> array(
                    'class' => 'table table-bordered table-striped table-responsive table-hover td-width-50',
                    'style' => 'margin-top: 20px',
                ),
                'attributes' => array(
                    array(
                        'name'        => Yii::t('tourist/label', 'orderer'),
                        'value'       => function ($data) {
                            return Chtml::encode($data->orderer_name);
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => Yii::t('tourist/label', 'receiver'),
                        'value'       => function ($data) {
                            return Chtml::encode($data->receiver_name);
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => Yii::t('tourist/label','orderer_phone'),
                        'value'       => function ($data) {
                            return Chtml::encode($data->orderer_phone);
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => Yii::t('tourist/label','address'),
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            $address = $data->address_detail;
                            if(!empty($data->ward_code)){
                                $address .= ', ' . TWard::model()->getWard($data->ward_code);
                            }
                            if(!empty($data->district_code)){
                                $address .= ', ' . $district = TDistrict::model()->getDistrict($data->district_code);
                            }
                            if(!empty($data->province_code)){
                                $address .= ', ' . TProvince::model()->getProvinceVnp($data->province_code);
                            }
                            return $address;
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                    array(
                        'name'        => Yii::t('tourist/label','note'),
                        'value'       => function ($data) {
                            return Chtml::encode($data->note);
                        },
                        'htmlOptions' => array('style' => 'vertical-align:middle;'),
                    ),
                ),
            )); ?>
        </div>
        <div class="col-md-12" style="margin-top: 30px;">
            <div class="title-order">
                <?php echo CHtml::encode(Yii::t('tourist/label','order_detail')) ?>
            </div>
            <?php
            $this->renderPartial('/orderCtv/_block_order_details', array(
                'model_details' => $model_details,
                'model' => $model
            ));

//            $this->widget('booster.widgets.TbTabs', array(
//                'type'        => 'tabs',
//                'tabs'        => array(
//                    array(
//                        'label'   => Yii::t('tourist/label','order_detail'),
//                        'content' => $this->renderPartial('/orderCtv/_block_order_details', array(
//                            'model_details' => $model_details,
//                            'model' => $model),
//                        TRUE),
//                        'active'  => TRUE,
//                    ),
//                ),
//                'htmlOptions' => array('class' => 'site_manager')
//            ));
            ?>
        </div>

    <?php if($model->status >= TOrders::ORDER_ASSIGNED){
        echo '<div class="col-md-12 step_confirm_order" style="margin-top: 30px; margin-bottom: 30px">';
        echo CHtml::link(CHtml::encode(Yii::t('tourist/label','report')), Yii::app()->createUrl('orderCtv/report', array('id' => $model->id)), array(
            'class' => 'btn btn-lg',
            'id'    => 'btn-report',
        ));
        echo '</div>';
    }?>


    <?php if($model->status == TOrders::ORDER_DRAFTS){
        echo '<div class="col-md-12 step_confirm_order" style="margin-top: 30px; margin-bottom: 30px">';
        echo CHtml::link(CHtml::encode(Yii::t('tourist/label','edit')), Yii::app()->createUrl('orderCtv/update', array('id' => $model->id)), array(
            'class' => 'btn btn-lg',
            'id'    => 'btn-update',
        ));
        echo CHtml::link(CHtml::encode(Yii::t('tourist/label','confirm')), Yii::app()->createUrl('orderCtv/confirm', array('id' => $model->id)), array(
            'class' => 'btn btn-lg',
            'id'    => 'btn-confirm',
        ));
        echo '</div>';
    }?>

    </div>
</div>