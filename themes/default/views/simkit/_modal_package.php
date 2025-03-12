<?php
/**
 * @var $this SimkitController
 * @var $model WPackage
 */
?>
<div id="modal_package" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <a class="btn btn-close" data-dismiss="modal">&times;</a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="modal_package_thumbnail col-xs-3">
                        <img src="<?php echo Yii::app()->baseUrl . '/uploads/' . $model->thumbnail_2 ?>">
                    </div>

                    <div class="modal_package_content col-xs-9">
                        <div class="title">
                            <?php echo CHtml::encode(Yii::t('web/portal','detail_info'))?>
                        </div>
                        <div class="content">
                            <?php echo $model->description ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    $('#modal_package').appendTo("body");
</script>
