<?php
/* @var $this SimController */
/* @var $searchForm SearchForm */
/* @var $form CActiveForm */
?>
<script src='https://www.google.com/recaptcha/api.js?hl=vi'></script>
<div class="ss-box1-left-top-4 form">
    <div class="row">
        <div>
            <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                'id'                   => 'search_msisdn_form',
                'method'               => 'POST',
                'action'               => Yii::app()->controller->createUrl('sim/searchAjax'),
                'enableAjaxValidation' => TRUE,
                'htmlOptions' => array('style'=>'margin-left:35px;'),

            )); ?>

            <div class="row">
                <div class="col-md-1 col-sm-2 col-xs-3">
                    <p class="timso" style="margin-bottom: 0; padding: 8px 0px; font-family: SanFranciscoDisplay-Bold">KHO SỐ</p>
                </div>
                <div class="col-md-10 col-sm-10 col-xs-8">
                    <div id="list_source">
                        <a href="<?php echo Yii::app()->createUrl('sim/index', array('source' => '')); ?>" class="btn <?php echo (empty($searchForm->source)) ? 'active' : '' ?>" data-value="">HSSV</a>
                        <a href="<?php echo Yii::app()->createUrl('sim/index', array('source' => 'toanquoc')); ?>" class="btn <?php echo ($searchForm->source == 'toanquoc') ? 'active' : '' ?>" data-value="toanquoc">TOÀN QUỐC</a>
                    </div>
                    <?php echo $form->hiddenField($searchForm, 'source'); ?>
                </div>

            </div>

            <div class="space_10"></div>

            <div class="row">
                <div class="col-md-1 col-sm-2 col-xs-3 selectbox chosen_msisdn">
                    <p class="timso">Đầu Số</p>

                    <div class="gc-chon-so mini-detail">
                        <?php
                        $prefix_msisdn = SearchForm::getListMsisdnPrefixBySource($searchForm->source);
                        echo $form->dropDownList($searchForm, 'prefix_msisdn', $prefix_msisdn, array('class' => 'dropdownlist'));
                        ?>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-8 selectbox input_msisdn">

                    <p class="timso">Nhập số bạn cần tìm </p>

                    <?php echo $form->textField($searchForm, 'suffix_msisdn', array('maxlength' => 255, 'placeholder' => "Ví dụ: '*12' hoặc '*789'")); ?>

                    <div class="space_5"></div>
                    <ul>
                        <li>Nhập *123 để tìm các số có đuôi 123</li>
                        <li>Nhập 123* để tìm các số có đuôi bất kỳ</li>
                    </ul>
                </div>
            </div>

            <div class="space_1"></div>

            <div class="row">
                <div class="col-md-1 col-sm-2 col-xs-4 selectbox chosen_msisdn">
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 selectbox">
                    <div id="captcha_place_holder" style="display: <?= (Yii::app()->session['search_msisdn_count'] > 4 || TRUE) ? 'block' : 'none' ?>" class="g-recaptcha" data-sitekey="6LeeB0saAAAAAAsEYp1XIhXlVS3fwyC7qTvLaNUK"></div>
                    <?php echo $form->error($searchForm, 'captcha'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1 col-sm-2 col-xs-4 selectbox chosen_msisdn">
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 selectbox center_xs">
                    <button>Tìm kiếm</button>
                    <!--            <button><i class="fa fa-search" aria-hidden="true"></i></button>-->
                </div>
            </div>

            <div class="space_1"></div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>

<script>
    /*$(function(){
        $('#list_source').on('click', 'a', function(){
            $('#list_source').find('a').removeClass('active');
            $(this).addClass('active');
            $('#SearchForm_source').val($(this).attr('data-value'));
            getListMsisdnPrefix();
        });
    });*/

    function getListMsisdnPrefix() {
        var source = $('#SearchForm_source').val();
        $.ajax({
            url: '<?php echo Yii::app()->createUrl('sim/getListMsisdnPrefix') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                'source': source,
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken ?>'
            },
            success: function(result) {
                $('#SearchForm_prefix_msisdn').html(result.dataHtml);
            }
        })
    }
</script>
<style>
    .poster {
        width: 100%;
        float: left;
        vertical-align: middle;
        color: #fff;
        font-size: 40px;
        font-weight: bold;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center
    }

    .poster a img {
        width: 100%;
        float: left;
    }
</style>