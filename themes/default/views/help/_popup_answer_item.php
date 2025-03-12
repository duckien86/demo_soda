<?php if (isset($question)):

    ?>
    <div class="modal" id="modal_<?php echo $question->id; ?>" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?= $question->question ?></h4>
                </div>
                <div class="modal-body">
                    <div class="row row-answer">
                        <h5 class="modal-answer"><p><?= $question->answer ?></p></h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default close-answer" data-dismiss="modal">
                        Đóng
                    </button>
                </div>
            </div>

        </div>
    </div>


<?php endif; ?>
