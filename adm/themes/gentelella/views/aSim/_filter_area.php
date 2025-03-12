<?php
    /* @var $this SimController */
    /* @var $searchForm SearchForm */
    /* @var $form CActiveForm */
?>
<div class="ss-box1-left-top-4 form">
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                   => 'search_msisdn_form',
        'method'               => 'POST',
        'action'               => Yii::app()->controller->createUrl('aSim/searchAjax'),
        'enableAjaxValidation' => TRUE,
    )); ?>
    <div class="col-xs-12 col-sm-12 col-md-2 selectbox">
        <p class="timso">Đầu Số</p>

        <div class="gc-chon-so mini-detail">
            <?php
                $source = ($searchForm->stock_id == '146') ? 'toanquoc' : null;
                $prefix_msisdn = ASearchForm::getListMsisdnPrefixBySource($source);
                echo $form->dropDownList($searchForm, 'prefix_msisdn', $prefix_msisdn, array('class' => 'dropdownlist'));
            ?>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 selectbox">
        <p class="timso">Kho Số</p>

        <div class="gc-chon-so mini-detail">
            <?php
                $stock_msisdn = Yii::app()->params->stock_config;
                echo $form->dropDownList($searchForm, 'stock_id', $stock_msisdn, array('class' => 'dropdownlist'));
            ?>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 selectbox">
        <p class="timso">Nhập số bạn cần tìm</p>

        <?php echo $form->textField($searchForm, 'suffix_msisdn', array('maxlength' => 255, 'placeholder' => "Ví dụ: '*12' hoặc '*789'")); ?>
    </div>
    <div class="space_1"></div>
    <div class="col-xs-12 col-sm-12 col-md-6 selectbox center_xs">
        <button>Tìm kiếm</button>
        <!--            <button><i class="fa fa-search" aria-hidden="true"></i></button>-->
    </div>
    <div class="space_1"></div>
    <?php $this->endWidget(); ?>
</div>

<script>

    $('#ASearchForm_stock_id').on('change', function(e){
        var stock_id = $(this).val();
        var source = (stock_id == '146') ? 'toanquoc' : null;
        getListMsisdnPrefix(source);
    });

    function getListMsisdnPrefix(source){
        $.ajax({
            url: '<?php echo Yii::app()->createUrl('aSim/getListMsisdnPrefix')?>',
            type: 'post',
            dataType: 'json',
            data: {
                'source': source,
                'YII_CSRF_TOKEN' : '<?php echo Yii::app()->request->csrfToken?>'
            },
            success: function(result){
                $('#ASearchForm_prefix_msisdn').html(result.dataHtml);
            }
        })
    }
</script>
