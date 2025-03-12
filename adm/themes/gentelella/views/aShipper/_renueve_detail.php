<div class="space_30"></div>
<div class="x_panel">
    <div class="x_content">
        <div class="container">
            <div class="row">
                <div class="data-detail-orders">
                    <?php
                        $this->renderPartial('_renueve_order_detail',
                            array(
                                'data'               => $data,
                                'total_renueve_date' => $total_renueve_date,
                                'total_order'        => $total_order));

                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#renueve-detail-form").submit(function (e) {
        $('#renueve-detail-form .error_form').html("<img style='text-align:center' width='20px' src='abc' />").fadeIn();
        var formObj = $(this);
        var formURL = formObj.attr("action");
        var formData = new FormData(this);
        $.ajax({
            url: formURL,
            type: 'POST',
            data: formData,
            contentType: false,
            cache: false,
            dataType: 'html',
            processData: false,
            success: function (data) {
//                $('.data-detail-orders').children().remove();
                $('.data-detail-orders').html(data);
            },
            error: function () {
                alert("Error occured.please try again");
                $('#renueve-detail-form .error_form').html('').fadeOut();
            }
        });
        e.preventDefault();
    });

</script>
