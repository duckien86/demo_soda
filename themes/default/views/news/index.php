<?php
/**
 * @var NewsController $this
 * @var array $list_top         - danh sách bài tin đặc sắc hiển thị slider
 * @var array $list_default     - danh sách bài tin thường hiện thị list
 * @var int $page               - trang số của trang tin thường
 * @var int $total_page         - tổng số trang tin tức thường
 */
?>
<div class="alert alert-info">
    <span><h1 class="title-news-page" style="margin: 0px !important; padding: 0px !important; font-size: 23px !important;">Tin tức</h1></span>
</div>
<div id="news-list">
    <div id="news-list-top-items">
        <div class="owl-carousel">
            <?php
            if(empty($list_top)){
                echo Yii::t('adm/label', 'not_found');
            }else{
                foreach ($list_top as $data){
                    $this->renderPartial('_item_top', array(
                        'data' => $data
                    ));
                }
            }
            ?>
        </div>
    </div>

    <div class="news-list-separator"></div>

    <div id="news-list-default-items">
        <?php
        if(empty($list_default)){
            echo Yii::t('adm/label', 'not_found');
        }else{
            $this->renderPartial('_list_item_default', array(
                'list' => $list_default,
                'page' => $page,
            ));
        }
        ?>
    </div>
    <?php if($total_page != 1): ?>
    <div id="news-load_more" data-page="<?php echo $total_page;?>" style="min-height: 1px" class="<?php echo ($this->isMobile)? 'text-center' : 'text-right' ?>">
        <button id="btn-load_more" class="btn btn-small btn-primary"><?php echo Yii::t('adm/label', 'load_more')?></button>
        <div id="news-load_more-message">
            <img id="img-load_more" class="hidden" src="<?php echo Yii::app()->theme->baseUrl . '/images/loading.gif'?>"/>
            <span id="not_found" class="hidden"><?php echo Yii::t('adm/label', 'not_found')?></span>
        </div>
    </div>
    <?php endif;?>

</div>

<script>
$(document).ready(function () {
    $('#news-list-top-items .owl-carousel').owlCarousel({
        autoplay: true,
        autoplayTimeout: 5000,
        autoplaySpeed: 2000,
        loop: false,
        pagination: false,
        stopOnHover: true,
        items:1,
        lazyLoad: false,
        slideBy: 1,
    });

    var loading = false;
    var totalPage = $('#news-load_more').attr('data-page');

    $('#btn-load_more').on('click', function (e) {
        e.preventDefault();
        if(loading){
            return;
        }
        loading = true;

        var list = $('#news-list-default-items'),
            imgLoad = $('#img-load_more'),
            notFound = $('#not_found'),
            page = parseInt(list.find('.news-page').last().attr('data-page'))+1;

        notFound.addClass('hidden');
        imgLoad.removeClass('hidden');
        if(totalPage == page+1){
            $(this).css('display','none');
        }

        $.ajax({
            url: '<?php echo Yii::app()->createUrl('news/loadmore')?>',
            type: 'GET',
            dataType: 'html',
            data: {
                page: page
            },
            success: function (result) {
                if(result.trim().length){
                    list.append(result);
                }else{
                    notFound.removeClass('hidden');
                }
                imgLoad.addClass('hidden');
                loading = false;
            }
        });
    });

});
</script>
