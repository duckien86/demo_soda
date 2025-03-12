<?php
/**
 * @var NewsController $this
 * @var WNews $model
 * @var array $list_related (related news)
 */
?>

<div class="row">
    <div class="col- col-xs-12 col-md-12 col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Yii::app()->controller->createUrl('site/index'); ?>">Trang
                        chủ</a></li>
                <li class="breadcrumb-item"><a href="<?= Yii::app()->controller->createUrl('news/index'); ?>">Tin
                        tức</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo CHtml::encode($model->title) ?></li>
            </ol>
        </nav>
    </div>
</div>
<div id="news-<?php echo $model->id ?>" class="news-view">
    <div class="news-title">
        <h1><?php echo CHtml::encode($model->title) ?></h1>
        <p class="news-publish_date">
            <i class="fa fa-clock-o"></i>
            <?php echo CHtml::encode(date('d-m-Y', strtotime($model->last_update))) ?>
        </p>
    </div>
    <div class="news-content">
        <?php echo $model->full_des ?>
    </div>
</div>

<div id="news-list-related-items">
    <?php
    if (!empty($list_related)) {
        ?>
        <div class="owl-carousel">
            <?php
            foreach ($list_related as $data) {
                $this->renderPartial('_item_related', array(
                    'data' => $data
                ));
            }
            ?>
        </div>
        <?php
    }
    ?>
</div>

<div class="fb-like" data-layout="<?php echo ($this->isMobile) ? 'button_count' : 'standard' ?>" data-action="like"
     data-size="small" data-show-faces="true" data-share="true"></div>


<!--comment-->
<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#cm">Bình luận</a></li>
    <li ><a data-toggle="tab" href="#fb">Bình luận Facebook</a></li>
</ul>

<div class="tab-content">

    <div id="cm" class="tab-pane fade in active">
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="POST" style="margin-top: 20px" id="comment_form">
                    <input id="news_id" type="hidden" name="news_id" value="<?php echo CHtml::encode($model->id); ?>">
                    <input id="ip" type="hidden" name="ip" value="<?php echo $_SERVER['REMOTE_ADDR'] ?>">
                    <input id="created_on" type="hidden" name="created_on" value="<?php echo date("Y-m-d H:i:s"); ?>">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="username" placeholder="Vui lòng nhập tên"
                                   name="username" required>
                        </div>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <textarea class="form-control" rows="5" id="content" name="content"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10">
                            <input type="hidden" name="id" id="id" value="0"/>
                            <input type="button" class="btn btn-primary comment-nomal" name="submit" id="submit" value="Bình luận">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="comment_message">

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="display_comment">

                  <?php foreach ($data_comment as $row){ ?>
                      <div class="panel panel-default">
                          <div class="panel-heading"><b><?php echo CHtml::encode($row['username']) ?></b> | <i><?php echo CHtml::encode($row['created_on']) ?></i> </div>
                          <div class="panel-body"><?php echo CHtml::encode($row['content']) ?></div>
                          <div class="panel-footer" align="right"><button type="button" class="btn btn-default reply" id='<?php echo CHtml::encode($row['id']) ?>'>Trả lời</button> </div>
                      </div>
                      <?php foreach ($data_comment_reply as $item){ ?>
                          <?php if(CHtml::encode($row['id'])==CHtml::encode($item['comment_parent'])){ ?>
                              <div class="panel panel-default" style="margin-left: 20px">
                                  <div class="panel-heading"><b><?php echo CHtml::encode($item['username']) ?></b> | <i><?php echo CHtml::encode($item['created_on']) ?></i> </div>
                                  <div class="panel-body"><?php echo CHtml::encode($item['content']) ?></div>
                              </div>
                              <?php }?>
                      <?php }?>
                  <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v3.2&appId=1446986758771727&autoLogAppEvents=1';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
    <div id="fb" class="tab-pane fade ">
        <div class="fb-comments" data-href="<?= 'http://' . SERVER_HTTP_HOST . CHtml::encode($_SERVER['REQUEST_URI']); ?>" data-numposts="5"></div>
    </div>
</div>

<script>

    $(document).ready(function () {

        $(document).on('click','.comment-nomal',function () {
            news_id = document.getElementById('news_id').value;
            ip = document.getElementById('ip').value;
            created_on = document.getElementById('created_on').value;
            username = document.getElementById('username').value;
            email = document.getElementById('email').value;
            content = document.getElementById('content').value;
            id = document.getElementById('id').value;

            $.ajax({
                url: '<?=Yii::app()->controller->createUrl("newscomments/create");?>',
                method: "POST",
                data: {
                    'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
                    news_id: news_id,
                    ip: ip,
                    username: username,
                    email: email,
                    content: content,
                    created_on: created_on,
                    id:id,
                },
                dataType: "JSON",
                success: function (data) {
                    if (data.error != '') {
                        $('#comment_message').html(data.error);
                    }else{
                        window.location.reload();
                    }


                }
            })
        });
        $(document).on('click','.reply',function () {
            var id = $(this).attr("id");
            $('#id').val(id);
            $('#username').focus();
            $('#email').focus();
            $('#content').focus();

        });


        $('#news-list-related-items .owl-carousel').owlCarousel({
            autoplay: true,
            autoplayTimeout: 5000,
            autoplaySpeed: 2000,
            loop: false,
            pagination: false,
            stopOnHover: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 3
                },
                1200: {
                    items: 4
                }
            },
            lazyLoad: false,
            slideBy: 1,
        });


        $('table').wrapAll('<div class="table-responsive" />');

    });
</script>
