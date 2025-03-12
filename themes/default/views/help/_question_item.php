<div class="col-md-6 col-xs-12 faq-div">
    <li><a href="#" onclick="showAnswer(<?= $data->id ?>);return false;"><?= CHtml::encode($data->question) ?></a><img class="image-arrow"
                                                                  src="<?= Yii::app()->theme->baseUrl; ?>/images/arrow-faqs.png">
    </li>
</div>
