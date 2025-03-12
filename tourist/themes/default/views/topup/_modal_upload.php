<?php
/**
 * @var $this TopupController
 *
 */
?>

<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array(
        'id' => 'modal_upload_topup',
//        'autoOpen' => true,
    )
); ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload Topup File</h4>
    </div>
    <div class="modal-body">

        <form method="post" id="formUploadTopup" enctype="multipart/form-data" onsubmit="return false">
            <input type="hidden" name="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken?>"/>
            <div class="form-group">
                <div id="TTopupQueue_upload_msg"></div>
                <div id="TTopupQueue_upload_error" class="error"></div>

                <img class="loading_gif right in-block hidden" src="<?php echo Yii::app()->theme->baseUrl?>/images/loading.gif"/>

                <input class="in-block unset-w" id="TTopupQueue_upload" name="TTopupQueue[upload]" type="file" accept="text/plain">
            </div>

            <a id="btnSubmitUpload" class="btn btn-primary disabled">Upload</a>
            <button id="btnOk" type="button" class="btn btn-success hidden" data-dismiss="modal">OK</button>

            <div class="space_10"></div>

            <div id="topup_upload_detail">
                <?php echo $this->renderPartial('/topup/_table_topup')?>
            </div>
        </form>
    </div>

<?php $this->endWidget(); ?>

<script>
    $(document).ready(function () {

        $('#modal_upload_topup').appendTo('body');


        var buttonSubmit = $('#btnSubmitUpload');
        var buttonOk = $('#btnOk');
        var error = $('#TTopupQueue_upload_error');
        var msg = $('#TTopupQueue_upload_msg');
        var emptyHtml = "<tr><td colspan='5'><?php echo Yii::t('adm/label','empty_topup');?></td></tr>";

        var loading = $('.loading_gif');

        $('#TTopupQueue_upload').on('change', function (e) {
            e.preventDefault();
            if(buttonSubmit.hasClass('hidden')){
                buttonSubmit.removeClass('hidden');
            }
            if(!buttonOk.hasClass('hidden')){
                buttonOk.addClass('hidden');
            }
            buttonSubmit.addClass('disabled');
            error.html('');
            msg.html('');
            $('#tableTopup tbody').html(emptyHtml);

            var value = $(this).val();
            if(value.length){
                var form_data = new FormData(document.getElementById("formUploadTopup"));//id_form
                loading.removeClass('hidden');
                $.ajax({
                    url: '<?php echo Yii::app()->controller->createUrl('topup/getUploadFileContent')?>',
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
                        $('#topup_upload_detail').html(result.data_html);
                        loading.addClass('hidden');
                    }
                });
            }
        });

        buttonSubmit.on('click', function (e) {
            e.preventDefault();
            if(!confirm("Xác nhận upload file kho thẻ doanh nghiệp!")){
                return;
            }
            var form_data = new FormData(document.getElementById("formUploadTopup"));//id_form
            buttonSubmit.addClass('disabled');
            loading.removeClass('hidden');
            $.ajax({
                url: '<?php echo Yii::app()->controller->createUrl('topup/upload')?>',
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
                        $.fn.yiiGridView.update('topup-grid');
                        $('#tableTopup tbody').html(emptyHtml);

                    }
                    msg.html(result.msg);
                    loading.addClass('hidden');
                }
            });
        });
    });
</script>
