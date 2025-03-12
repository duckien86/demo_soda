<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css"/>
<div class="banner-fiber">

</div>
<div class="chose-province-fiber">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="email" style="margin-top: 8px">Chọn nơi bạn đang
                        ở:</label>
                    <div class="col-sm-3">
                        <p class="form-control-static">
                            <select class="selectpicker" data-live-search="true" id="province_code" name="province_code"
                                    onchange="getlistpackagefiber();">
                                <option value="">-- CHỌN --</option>
                                <?php foreach ($list_province as $key => $value) { ?>
                                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                <?php } ?>
                            </select>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="list-package-fiber-by-province">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div id="list-item-package">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="wrap-item-package-fiber">
                                <div class="row">
                                    <!--item-package-->
                                    <?php $i = 0; ?>
                                    <?php foreach ($list_package_freedoo as $item) { ?>
                                        <div class="col-lg-6">
                                            <div class="item-package-fiber <?php if ($i == 0) { ?> active-fiber <?php } ?>"
                                                 onclick="addactive(this); getdetailpackagesfiber('<?php echo $item['id'] ?>');">
                                                <?php if ($i == 0) { ?>
                                                    <input type="hidden" id="hidetoken"
                                                           value="<?php echo $item['id'] ?>">
                                                <?php } ?>
                                                <div class="title-package-fiber">
                                                    <?php echo $item['name']; ?>
                                                </div>
                                                <div class="img-tick">
                                                    <img src="<?= Yii::app()->theme->baseUrl; ?>/images/tick.png"
                                                         alt="">
                                                </div>
                                                <div class="price-package">
                                                    <span>Gía gói :</span> <span
                                                            style="color: red"><?php echo number_format($item['price']) . ' vnđ' ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $i++;
                                    } ?>
                                    <!--end item-package-->
                                </div>
                                <div class="row collapse" id="morepackagefiber" >
                                    <!--item-package-->
                                    <?php $i = 0; ?>
                                    <?php foreach ($list_package_national as $item) { ?>
                                        <div class="col-lg-6">
                                            <div class="item-package-fiber"
                                                 onclick="addactive(this); getdetailpackagesfiber('<?php echo $item['id'] ?>');">
                                                <div class="title-package-fiber">
                                                    <?php echo $item['name']; ?>
                                                </div>
                                                <div class="img-tick">
                                                    <img src="<?= Yii::app()->theme->baseUrl; ?>/images/tick.png"
                                                         alt="">
                                                </div>
                                                <div class="price-package">
                                                    <span>Gía gói :</span> <span
                                                            style="color: red"><?php echo number_format($item['price']) . ' vnđ' ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $i++;
                                    } ?>
                                    <!--end item-package-->
                                </div>
                                <div class="more-package">
                                    <div class="btn-more-package" style="width: 100%; text-align: center">
                                        <?php if($list_province){ ?>
                                        <?php if($list_package_national){ ?>
                                            <button onclick="showmore()" class="btn btn-register" id="showmore" data-toggle="collapse" data-target="#morepackagefiber">Xem thêm</button>
                                         <?php }}?>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="col-lg-4">
                            <div class="wrap-detail-package-fiber" id="detail-fiber">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .banner-fiber {
        width: 100%;
        float: left;
    }
    .more-package{
        width: 100%;
    }
    .chose-province-fiber {
        width: 100%;
        float: left;
        padding: 20px 0px;
    }

    .list-package-fiber-by-province {
        width: 100%;
        float: left;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .item-package-fiber {
        width: 100%;
        height: 200px;
        margin-bottom: 15px;
        border-top: 1px #2fa0d7 solid;
        border-right: 1px #f53e6e solid;
        border-bottom: 1px #f53e6e solid;
        border-left: 1px #2fa0d7 solid;
        cursor: pointer;
        position: relative;
    }

    .wrap-item-package-fiber {
        width: 100%;
        padding: 15px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px #ccc;
        position: relative;
    }

    .title-package-fiber {
        width: 100%;
        padding: 10px;
        font-weight: bold;
        font-size: 25px;
    }

    .wrap-item-package-fiber .active-fiber {
        background-image: linear-gradient(to bottom, rgba(255, 0, 0, 0), rgba(255, 0, 0, 0.3));
    }

    .img-tick {
        position: absolute;
        top: 0px;
        text-align: right;
        display: none;
    }

    .img-tick img {
        width: 12%;
        right: 0px;
    }

    .wrap-item-package-fiber .active-fiber .img-tick {
        display: block;
    }

    .wrap-detail-package-fiber {
        width: 100%;
        padding: 15px;
        background: #fff;
        box-shadow: 0 0 10px #ccc;
        border-radius: 10px;
        height: 458px;
    }

    .price-package {
        width: 100%;
        text-align: right;
        font-weight: bold;
        padding: 15px;
        position: absolute;
        bottom: 5px;
        font-size: 17px;
    }
</style>
<script>
    function getlistpackagefiber() {
        var province_code = document.getElementById('province_code').value;
        $.ajax({
            url: '<?=Yii::app()->controller->createUrl("package/listfiber");?>',
            method: 'POST',
            data: {
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
                province_code: province_code,
            },
            dataType: 'json',
            success: function (result) {
                $('#list-item-package').html(result.content);
                var package_id = document.getElementById('hidetoken').value;
                $.ajax({
                    url: '<?=Yii::app()->controller->createUrl("package/getdetailpackagefiber");?>',
                    method: 'POST',
                    data: {
                        'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
                        package_id: package_id,
                    },
                    dataType: 'json',
                    beforeSend:
                        function () {
                            $('#detail-fiber').html("<img src='https://merchant.vban.vn/freedoo/Resources/images/preload.svg' />");
                        },
                    success: function (result) {
                        $('#detail-fiber').html(result.content);
                    }
                });
            }
        });
    }

    function addactive(elem) {
        var add = document.getElementsByClassName('item-package-fiber');
        for (i = 0; i < add.length; i++) {
            add[i].classList.remove('active-fiber')
        }
        elem.classList.add('active-fiber');
    }

    function getdetailpackagesfiber(package_id) {
        $.ajax({
            url: '<?=Yii::app()->controller->createUrl("package/getdetailpackagefiber");?>',
            method: 'POST',
            data: {
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
                package_id: package_id,
            },
            dataType: 'json',
            beforeSend:
                function () {
                    $('#detail-fiber').html("<img src='https://merchant.vban.vn/freedoo/Resources/images/preload.svg' />");
                },
            success: function (result) {
                $('#detail-fiber').html(result.content);
            }
        });
    }

    $(window).on('load', function () {
        var package_id = document.getElementById('hidetoken').value;
        $.ajax({
            url: '<?=Yii::app()->controller->createUrl("package/getdetailpackagefiber");?>',
            method: 'POST',
            data: {
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
                package_id: package_id,
            },
            dataType: 'json',
            beforeSend:
                function () {
                    $('#detail-fiber').html("<img src='https://merchant.vban.vn/freedoo/Resources/images/preload.svg' />");
                },
            success: function (result) {
                $('#detail-fiber').html(result.content);
            }
        });
    })
    function showmore() {
        var show = document.getElementById("showmore");
        var close = document.getElementById("closemore");
        show.style.display = "none";

    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>