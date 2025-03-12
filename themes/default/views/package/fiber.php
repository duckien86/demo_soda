<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css"/>
<!--popup-->
<!-- Modal -->
<div id="choseprovince" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chọn tỉnh / thành phố</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-1"></div>
                    <div class="col-lg-10 chose-province">

                        <select class="selectpicker" data-live-search="true" id="provinceid">
                            <option>--- Chọn Tỉnh / TP ---</option>
                            <?php foreach ($list_province as $item) {?>
                                <option value="<?php echo $item['fiber_province_id'] ?>"><?php echo $item['name'] ?></option>
                            <?php } ?>
                        </select>

                        <div class="" id="meg-error" style="width: 100%; text-align: center; color: red"></div>
                    </div>
                    <div class="col-lg-1"></div>
                </div>
            </div>
            <div class="modal-footer text-center" style="text-align: center">
                <button type="button" class="btn btn-default btn-next-step" onclick="nextstep()">Tiếp tục</button>
            </div>
        </div>

    </div>
</div>
<!--end popup-->
<div class="wrapper-fiber">
    <div class="container w-fiber" id="list_package_fiber">

    </div>
</div>
<style>
    .t-title {
        color: #fff;
        margin-top: 8px;
    }

    .w-100 {
        width: 100% !important;
    }

    .mar-bottom {
        margin-bottom: 10px;
    }

    .active-fiber {
        border-top: #02A1E5 1px solid;
        border-right: #F53E6E 1px solid;
        border-bottom: #02A1E5 1px solid;
        border-left: #F53E6E 1px solid;
        padding: 5px;
    }

    .active-fiber img {
        height: 180px;
    }

    .w-29 {
        width: 29% !important;
    }

    .btn-next-step {
        background: #d71962 !important;
        border-color: #d71962;
        border-radius: 30px !important;
        margin: auto;
        font-size: 15px;
        color: #fff !important;
    }

    .select2-drop-active {
        width: 197px !important;
    }

    .select2-container {
        min-width: 197px !important;
    }

    .bootstrap-select {
        border: #ccc 1px solid !important;
        border-radius: 5px !important;
    }

    .chose-province .bootstrap-select {
        width: 197px !important;
    }

    #list_package_fiber {
        background: #fff;
        margin-top: 10px;
    }

    #list_package_fiber img {
        width: 20%;
    }

    .w-fiber {
        background: #fff;
        margin-top: 10px;
        border-radius: 10px;
        margin-bottom: 10px;
    }

    .title-fiber {
        text-transform: uppercase;
        padding: 6px 0px !important;
        font-size: 22px !important;
        color: #BA4083;
        font-weight: bold;
    }
</style>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script>
    $(window).on('load', function () {
        $('#choseprovince').modal('show');
    });

    function addclass(elem) {
        var div = document.getElementsByTagName('div');
        for (i = 0; i < div.length; i++) {
            div[i].classList.remove('active-fiber')
        }
        elem.classList.add('active-fiber');
    }

    function getFiberPackageDetail(id) {
        // document.getElementById("fiber_id").setAttribute('value',id);
        $.ajax({

            url: '<?=Yii::app()->controller->createUrl("package/getdetailfiber");?>',
            method: 'POST',
            data: {
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
                id: id
            },
            dataType: 'json',

            beforeSend: function () {
                $('#info-fiber-package').html("<img src='https://merchant.vban.vn/freedoo/Resources/images/preload.svg' />");
            },
            success: function (result) {
                $('#info-fiber-package').html(result.content);
            }
        });
    }

    function nextstep() {
        var provinceid = document.getElementById('provinceid').value;
        var meg = "Vui lòng chọn Tỉnh / Thành phố";
        if (provinceid > 0) {
            $('#choseprovince').modal('hide');
            $.ajax({
                url: '<?=Yii::app()->controller->createUrl("package/getlistfiber");?>',
                method: 'POST',
                data: {
                    'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
                    provinceid: provinceid
                },
                dataType: 'json',
                beforeSend: function () {
                    $('#list_package_fiber').html("<img src='https://merchant.vban.vn/freedoo/Resources/images/preload.svg' />");
                },
                success: function (result) {
                    $('#list_package_fiber').html(result.content);
                }
            });
        } else {
            document.getElementById("meg-error").innerHTML = meg;
        }
    }
</script>
