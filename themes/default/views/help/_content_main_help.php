<div class="container">
    <div class="space_30"></div>
    <div class="row">
        <div class="col-md-4 col-xs-0"></div>
        <div class="col-md-4 col-xs-12 help-title-row">
            <span class="help-title">CÂU HỎI THƯỜNG GẶP</span>
        </div>
        <div class="col-md-4 col-xs-0"></div>
    </div>
    <div class="space_60"></div>
    <div class="row">

        <div class="col-md-12 col-xs-12">
            <div class="clearfix"></div>
            <div class="content_tab_body">
                <?php
                    $tabs = array();
                    $stt  = 0;
                    if (isset($cate) && !empty($cate)) {
                        foreach ($cate as $key => $value) {
                            if (isset($tab) && $tab == 'CTV') {
                                if ($stt == 4) {
                                    $tabs[] = array(
                                        'label'   => $value->name,
                                        'content' => $this->renderPartial('_tab_list_question_by_type', array('type' => $value->id, 'question' => $question), TRUE),
                                        'active'  => TRUE,
                                    );
                                } else {
                                    $tabs[] = array(
                                        'label'   => $value->name,
                                        'content' => $this->renderPartial('_tab_list_question_by_type', array('type' => $value->id, 'question' => $question), TRUE),
                                        'active'  => FALSE,
                                    );
                                }
                            } else {
                                if ($stt == 0) {
                                    $tabs[] = array(
                                        'label'   => $value->name,
                                        'content' => $this->renderPartial('_tab_list_question_by_type', array('type' => $value->id, 'question' => $question), TRUE),
                                        'active'  => TRUE,
                                    );
                                } else {
                                    $tabs[] = array(
                                        'label'   => $value->name,
                                        'content' => $this->renderPartial('_tab_list_question_by_type', array('type' => $value->id, 'question' => $question), TRUE),
                                        'active'  => FALSE,
                                    );
                                }
                            }
                            $stt++;
                        }
                    }

                    $this->widget(
                        'booster.widgets.TbTabs',
                        array(
                            'type' => 'tabs',
                            'tabs' => $tabs
                        )
                    ); ?>
            </div>
        </div>
        <div class="col-md-4 col-xs-0"></div>

        <div class="col-md-4 col-xs-0"></div>
    </div>
</div>
<div class="space_100"></div>
<div class="show-answer"></div>
<script language="javascript">
    function showAnswer(id) {
        $.ajax({
            type: "POST",
            url: '<?=Yii::app()->controller->createUrl('help/showAnswer')?>',
            crossDomain: true,
            data: {id: id, 'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'},
            success: function (result) {
                $('.show-answer').html(result);
                var modal_id = 'modal_' + id;
                $('#' + modal_id).modal('show');
                return false;
            }
        });
    }
</script>



