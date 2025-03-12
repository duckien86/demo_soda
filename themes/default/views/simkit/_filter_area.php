<?php
    /* @var $this SimController */
    /* @var $searchForm SearchForm */
    /* @var $form CActiveForm */
?>
<script src='https://www.google.com/recaptcha/api.js?hl=vi'></script>
<div class="ss-box1-left-top-4 form">
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                   => 'search_msisdn_form',
        'method'               => 'POST',
        'action'               => Yii::app()->controller->createUrl('simkit/searchAjax'),
        'enableAjaxValidation' => TRUE,
    )); ?>
    <?php echo $form->hiddenField($searchForm, 'stock_id'); ?>
    <div class="col-xs-12 col-sm-12 col-md-4 selectbox">
        <p class="timso">Đầu Số</p>

        <div class="gc-chon-so mini-detail">
            <?php
                $prefix_msisdn = array(
//                    '8491'  => '091',
//                    '8494'  => '094',
//                    '84123' => '0123',
//                    '84124' => '0124',
//                    '84125' => '0125',
//                    '84127' => '0127',
//                    '84129' => '0129',
                    '8488' => '088',
                    '8485' => '085',
                );
                echo $form->dropDownList($searchForm, 'prefix_msisdn', $prefix_msisdn, array('class' => 'dropdownlist'));
            ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 selectbox">
        <p class="timso">Nhập số bạn cần tìm </p>

        <?php echo $form->textField($searchForm, 'suffix_msisdn', array('maxlength' => 255, 'placeholder' => "Ví dụ: '*12' hoặc '*789'")); ?>
    </div>
    <div class="space_1"></div>
    <div class="col-xs-12 col-sm-12 col-md-4 selectbox">
        <div id="captcha_place_holder"
             style="display: <?= (Yii::app()->session['search_msisdn_count'] > 4 || TRUE) ? 'block' : 'none' ?>"
             class="g-recaptcha"
             data-sitekey="6Lcr1B8aAAAAAHfl5_cIZIIpy3a0hU5NGf4znMyO"></div>
			 
        <?php echo $form->error($searchForm, 'captcha'); ?>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 selectbox center_xs">
        <button>Tìm kiếm</button>
        <!--            <button><i class="fa fa-search" aria-hidden="true"></i></button>-->
    </div>
    <div class="space_1"></div>
    <?php $this->endWidget(); ?>
</div>
