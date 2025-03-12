<?php
/**
 * @var $this ACardStoreController
 *
 */
?>

<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array(
        'id' => 'modal_upload_card_store',
//        'autoOpen' => true,
    )
); ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload File Kho thẻ bán lẻ</h4>
    </div>
    <div class="modal-body">
        <form method="post" id="formUploadCardStore" enctype="multipart/form-data" onsubmit="return false">
            <input type="hidden" name="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken?>"/>
            <div class="form-group">
                <div id="ACardStore_upload_msg"></div>
                <div id="ACardStore_upload_error" class="error"></div>

                <img class="loading_gif right in-block hidden" src="<?php echo Yii::app()->theme->baseUrl?>/images/loading.gif"/>

                <input class="in-block unset-w" id="ACardStore_upload" name="ACardStore[upload]" type="file" accept="text/plain">
            </div>

            <a id="btnSubmitUpload" class="btn btn-primary disabled">Upload</a>
            <button id="btnOk" type="button" class="btn btn-success hidden" data-dismiss="modal">OK</button>

            <div class="space_10"></div>

            <div id="card_upload_detail">
                <?php echo $this->renderPartial('/aCardStore/_table_card')?>
            </div>
        </form>
    </div>

<?php $this->endWidget(); ?>

<style>
    @media(min-width: 1024px){
        #modal_upload_card_store .modal-dialog{
            width: 800px;
        }
    }
</style>

<script>
    $(document).ready(function () {
        var buttonSubmit = $('#btnSubmitUpload');
        var buttonOk = $('#btnOk');
        var error = $('#ACardStore_upload_error');
        var msg = $('#ACardStore_upload_msg');
        var emptyHtml = "<tr><td colspan='5'><?php echo Yii::t('adm/label','empty_card');?></td></tr>";
        var emptyHtmlSummaryHead = "<tr><th>&nbsp;</th><th>10.000</th><th>20.000</th><th>50.000</th><th>100.000</th><th>200.000</th><th>500.000</th></tr>";
        var emptyHtmlSummaryBody = "<tr><td>SL</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>";
        var loading = $('.loading_gif');

        $('#ACardStore_upload').on('change', function () {
            if(buttonSubmit.hasClass('hidden')){
                buttonSubmit.removeClass('hidden');
            }
            if(!buttonOk.hasClass('hidden')){
                buttonOk.addClass('hidden');
            }
            buttonSubmit.addClass('disabled');
            error.html('');
            msg.html('');
            $('#tableCard tbody').html(emptyHtml);
            $('#tableCardSummary thead').html(emptyHtmlSummaryHead);
            $('#tableCardSummary tbody').html(emptyHtmlSummaryBody);
            var value = $(this).val();
            if(value.length){
                var form_data = new FormData(document.getElementById("formUploadCardStore"));//id_form
                loading.removeClass('hidden');
                $.ajax({
                    url: '<?php echo Yii::app()->controller->createUrl('aCardStore/getUploadFileContent')?>',
                    type: 'post',
                    dataType: 'json',
                    data: form_data,
                    enctype: 'multipart/form-data',
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,   // tell jQuery not to set contentType
                    crossDomain: true,
                    success: function (result) {
                        console.log(result);
                        if(result.error){
                            msg.css('color', '#dc3545');
                            error.html(result.error);
                        }else{
                            msg.css('color', '#28a745');
                            error.html('');
                            buttonSubmit.removeClass('disabled');
                        }
                        msg.html(result.msg);
                        $('#card_upload_detail').html(result.data_html);
                        loading.addClass('hidden');
                    }
                });
            }
        });

        buttonSubmit.on('click', function () {
            if(!confirm("Xác nhận upload file kho thẻ doanh nghiệp!")){
                return;
            }
            var form_data = new FormData(document.getElementById("formUploadCardStore"));//id_form
            buttonSubmit.addClass('disabled');
            loading.removeClass('hidden');
            $.ajax({
                url: '<?php echo Yii::app()->controller->createUrl('aCardStore/upload')?>',
                type: 'post',
                dataType: 'json',
                data: form_data,
                enctype: 'multipart/form-data',
                processData: false,  // tell jQuery not to process the data
                contentType: false,   // tell jQuery not to set contentType
                crossDomain: true,
                success: function (result) {
                    console.log(result);
                    buttonSubmit.addClass('hidden');
                    buttonOk.removeClass('hidden');
                    if(result.error){
                        msg.css('color', '#dc3545');
                        error.html(result.error);
                    }else{
                        msg.css('color', '#28a745');
                        error.html('');
                        $.fn.yiiGridView.update('acardstore-grid');
                        $('#tableCard tbody').html(emptyHtml);
                        $('#tableCardSummary thead').html(emptyHtmlSummaryHead);
                        $('#tableCardSummary tbody').html(emptyHtmlSummaryBody);
                    }
                    msg.html(result.msg);
                    loading.addClass('hidden');
                }
            });
        });
    });
</script>
