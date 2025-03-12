<div class="content_body">
    <div class="space_30"></div>
    <div class="container" style="width :100%; font-family: 'SanFranciscoDisplay-Thin'">
        <?php

            $this->widget(
                'booster.widgets.TbThumbnails',
                array(
                    'dataProvider'     => $question->search($type),
                    'template'         => "{items}{pager}",
                    'enablePagination' => TRUE,
                    'itemView'         => '_question_item',
                    'ajaxType'         => 'GET',
                    'emptyText'        => Yii::t('web/game', 'Chưa có câu hỏi nào thuộc mục này!'),
                )
            );
        ?>
    </div>
    <div class="space_30"></div>
    <div class="space_30"></div>
</div>
