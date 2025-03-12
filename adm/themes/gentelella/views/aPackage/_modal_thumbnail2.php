<?php
    /* @var $model APackage*/
    /* @var $form CActiveForm */
?>
<div class="modal fade img_thumbnail2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"><?= $model->name ?></h4>
            </div>

            <div class="modal-body">
                <?php
                    // Render detail form
                    $this->renderPartial('_thumbnail2_form', array(
                        'model'       => $model
                    ));
                ?>
            </div>
        </div>
    </div>
</div>