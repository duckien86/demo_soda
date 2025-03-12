function goToTabValidate(formID, tabID, url, show_modal) {
    var form_data = new FormData(document.getElementById(formID));//formID
    $.ajax({
        type: "POST",
        url: url,
        crossDomain: true,
        dataType: 'json',
        data: form_data,
        enctype: 'multipart/form-data',
        processData: false,  // tell jQuery not to process the data
        contentType: false,   // tell jQuery not to set contentType
        success: function (result) {
            $('.msg').html(result.msg);//for test
            if (result.status == true) {
                if (show_modal == true) {
                    $('#modal_otp_form').modal('show');
                } else {
                    $('#modal_otp_form').modal('hide');
                    //$('.msg').html('');
                    $('.steps a[href="#' + tabID + '"]').tab('show');
                }
            } else {
                $('.msg').html(result.msg);
            }
        }
    });
}

$(document).on('submit', '#search_msisdn_form', function (e) {
    $(':button').prop('disabled', true);
    var html_load = '';
    html_load += '<div class="text-center">';
    html_load += '<div class="space_30"></div>';
    html_load += '<div><img class=" wow bounceIn infinite" src="/themes/default/images/icon_logo_fd.png"/></div>';
    html_load += '<div class="space_30"></div>';
    html_load += '<p>Đang tìm kiếm ...</p>';
    html_load += '</div>';
    $('#list_msisdn').html(html_load);
    e.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        crossDomain: true,
        type: $(this).attr('method'),
        cache: false,
        dataType: "html",
        data: $(this).serialize(),
        success: function (result) {
            $(':button').prop('disabled', false);
            $('#list_msisdn').html(result);
            grecaptcha.reset();

            //show tooltip
            $('#list_msisdn .btnBuySim').hover(function () {
                $(this).next('.hasTooltip').css('display', 'block');
                $(this).next('.hasTooltip').addClass('tooltip fade bottom in');
            }, function () {
                $(this).next('.hasTooltip').css('display', 'none');
                $(this).next('.hasTooltip').removeClass('tooltip fade bottom in');
            });
        },
        error: function (request, status, err) {
            $(':button').prop('disabled', false);
        }
    });
});

$(document).on('submit', '#filter_order_ssoid', function (e) {
    e.preventDefault();
    $(':input[type="submit"]').prop('disabled', true);
    // this.submit();
    $.ajax({
        url: $(this).attr('action'),
        crossDomain: true,
        type: $(this).attr('method'),
        cache: false,
        dataType: "json",
        data: $(this).serialize(),
        success: function (result) {
            $(':input[type="submit"]').prop('disabled', false);
            $('#list_order_results').html(result);
            $("body, html").animate({
                scrollTop: 200
            }, 600);
        },
        error: function (request, status, err) {
            $(':input[type="submit"]').prop('disabled', false);
        }
    });
});
var isMobile;
// convert int to currency
function convertToCurrency(val){
    return val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
}
$(document).on('click', '.btnBuySim', function (e) {
    var btnBuySim = $(this);
    btnBuySim.bind('click', false);
    btnBuySim.append('<img src="/themes/default/images/loading.gif" alt="" class="loading">');
    var modal = $('#confirm_add_cart');
    var confirm = btnBuySim.attr('data-confirm');

    // xác nhận mua sim trên mobile
    if(isMobile == 1){
        var info_modal = $('#sim_info_modal');
        $('#sim_info_modal #sim_tex').text(btnBuySim.attr('data-simnumber'));
        $('#sim_info_modal #price_text').text(convertToCurrency(btnBuySim.attr('data-simprice')) + "đ");
        $('#sim_info_modal #term_text').text(btnBuySim.attr('data-simterm') + ' tháng');
        $('#sim_info_modal #price_term_text').text(convertToCurrency(btnBuySim.attr('data-simpriceterm')) + 'đ/tháng');
        $(info_modal).modal('show');
        info_modal.on('shown.bs.modal', function () {
            //confirmed
            $("#btn_mobile_add_cart").click(function () {
                addToCart(btnBuySim, btnBuySim.attr('data-url'), btnBuySim.attr('data-simnumber'), btnBuySim.attr('data-simprice'), btnBuySim.attr('data-simtype'), btnBuySim.attr('data-simterm'), btnBuySim.attr('data-simpriceterm'), btnBuySim.attr('data-simstore'), btnBuySim.attr('data-csrf'), btnBuySim.attr('data-iframe'));
            });
        }).on('hidden.bs.modal', function (e) { //close modal
            $(e.currentTarget).unbind(); // or $(this)
            $("#btn_mobile_add_cart").unbind('click');
            //remove loading
            btnBuySim.children('.loading').remove();
            btnBuySim.unbind('click', false);
        });
        return false;
    }
    //./END xác nhận mua sim trên mobile

    var data_note_1 = "Thuê bao phải đặt cọc và thanh toán online 01 tháng cước cam kết và sẽ được khấu trừ vào tháng cuối cùng của thời gian cam kết";
    var data_note_2 = "Thuê bao chỉ được chuyển quyền sử dụng và chuyển tỉnh sau 6 tháng hòa mạng";
    var data_note_3 = "Thuê bao sẽ được miễn cam kết chọn số với các kiểu số có cam kết <= 300.000 đồng khi chọn một trong các gói cước trả sau FreeDoo đi kèm";
    var data_note_4 = "Thuê bao không được hủy số hoặc thanh lý hợp đồng trong thời gian cam kết";
    var data_note_5 = "Thuê bao phải thanh toán đầy đủ cước cam kết trong thời gian cam kết.";


    if (confirm >= 1) {

        // if(btnBuySim.attr('data-simpriceterm') > 0 && btnBuySim.attr('data-simtype') == 2){
        //     modal.find('.data_note_3').html('3. '+data_note_4);
        //     modal.find('.data_note_4').html('4. '+data_note_5);
        //     modal.find('.data_note_5').html('').css('display','none');
        // }else{
        //     modal.find('.data_note_3').html('3. ' + data_note_3);
        //     modal.find('.data_note_4').html('4. ' + data_note_4);
        //     modal.find('.data_note_5').html('5. ' + data_note_5).css('display','block');
        // }

        modal.modal('show');
        modal.on('shown.bs.modal', function () {
            //confirmed
            $("#btn_add_cart").click(function () {
                addToCart(btnBuySim, btnBuySim.attr('data-url'), btnBuySim.attr('data-simnumber'), btnBuySim.attr('data-simprice'), btnBuySim.attr('data-simtype'), btnBuySim.attr('data-simterm'), btnBuySim.attr('data-simpriceterm'), btnBuySim.attr('data-simstore'), btnBuySim.attr('data-csrf'), btnBuySim.attr('data-iframe'));
            });
        }).on('hidden.bs.modal', function (e) { //close modal
            $(e.currentTarget).unbind(); // or $(this)
            $("#btn_add_cart").unbind('click');
            //remove loading
            btnBuySim.children('.loading').remove();
            btnBuySim.unbind('click', false);
        });
    } else {
        addToCart(btnBuySim, btnBuySim.attr('data-url'), btnBuySim.attr('data-simnumber'), btnBuySim.attr('data-simprice'), btnBuySim.attr('data-simtype'), btnBuySim.attr('data-simterm'), btnBuySim.attr('data-simpriceterm'), btnBuySim.attr('data-simstore'), btnBuySim.attr('data-csrf'), btnBuySim.attr('data-iframe'));
    }
});

function addToCart(obj, url, simnumber, simprice, simtype, simterm, simpriceterm, simstore, csrf, iframe) {
    $.ajax({
        url: url,
        type: 'post',
        cache: false,
        dataType: "json",
        data: {
            sim_number: simnumber,
            sim_price: simprice,
            sim_type: simtype,
            sim_term: simterm,
            sim_priceterm: simpriceterm,
            sim_store: simstore,
            iframe: iframe,
            YII_CSRF_TOKEN: csrf
        },
        success: function (result) {
            obj.unbind('click', false);
            if (result.error_code == 0)
                location.href = result.url;
            else {
                $('.loading').css('display', 'none');
                displayAlert(result.msg);
            }
        },
        error: function (request, status, err) {
            obj.unbind('click', false);
        }
    });
}

function displayAlert(message, isError) {
    var modal = $('#modal_alert');
    modal.find('.modal-body #body-content').html(message);
    if (isError) {
        modal.find('.modal-body').removeClass('textSuccess').addClass('textError');
    } else {
        modal.find('.modal-body').removeClass('textError').addClass('textSuccess');
    }
    modal.modal('show');
}

function displayWarning() {
    var modal = $('#modal_warning');
    modal.addClass('in');
    modal.css('display', 'block');
    modal.modal('show');
}

function autoFillToOrderCard(price_discount) {
    $('#order_phone').text($('#WOrders_phone_contact').val());
    var price = parseInt($("input[name='WOrderDetails[price]']:checked").val());
    var quantity = $('#WOrderDetails_quantity').val();

    var amount = ((price * quantity) * price_discount);
    //$('#card_order .thumbnail ').addClass('bg_' + price);
    $('#card_order #thumbnail').attr('class', 'bg_' + price);

    $('#order_quantity').text(quantity);
    $('#order_price').text(formatNumberValue(price));
    $('#order_price_thumb').text(formatNumberValue(price));
    $('#order_amount').text(formatNumberValue(amount));
    $('#order_total_amount').text(formatNumberValue(amount));
}

function autoFillToOrderPackage() {
    $('#order_phone').text($('#WOrders_phone_contact').val());
}

function autoFillToOrderSim() {
    var sim_price = parseInt($('#WSim_price').val());
    var packageValue = parseInt($('#package_amount').val());
    var cardValue = parseInt($('#WOrders_card').val());
    var ship_price = parseInt($('#order_ship_price').val());
    var amount = sim_price + packageValue + ship_price;
    if (cardValue > 0) {
        amount = amount + cardValue;
        $('#order_card_value').text(formatNumberValue(cardValue) + 'đ');
    } else {
        cardValue = 0;
        $('#order_card_value').text('');
    }

    $('#card_amount').val(cardValue);
    $('#total_amount').val(amount);
    $('#order_amount').text(formatNumberValue(amount));
    $('#order_total_amount').text(formatNumberValue(amount));
}

function formatNumberValue(value) {
    value = value.toString().replace(/\$|\./g, '');

    if (isNaN(value))
        value = "";
    value = Math.floor(value * 100 + 0.50000000001);
    value = Math.floor(value / 100).toString();

    for (var i = 0; i < Math.floor((value.length - (1 + i)) / 3); i++)
        value = value.substring(0, value.length - (4 * i + 3)) + '.' + value.substring(value.length - (4 * i + 3));
    return value;
}

function verifyTokenKey(url_otp, url) {
    var form_data = new FormData(document.getElementById('otp_form'));//formID
    $.ajax({
        type: "POST",
        url: url_otp,
        crossDomain: true,
        dataType: 'json',
        data: form_data,
        processData: false,  // tell jQuery not to process the data
        contentType: false,   // tell jQuery not to set contentType
        success: function (result) {
            if (result.status == true) {
                $('#modal_otp_form').modal('show');
                goToTabValidate('form_step1', 'checkout2', url, false);
            } else {
                $('.msg_otp_form').html(result.msg);
            }
        }
    });
}

function goToTab(tabID) {
    $('.steps a[href="#' + tabID + '"]').tab('show');
}

function goToTop() {
    $("body, html").animate({
        scrollTop: 0
    }, 600);
}

/*package home page*/
$(window).on('load', function () {
    $('#package_slider').owlCarousel({
        autoplay: true,
        autoplayTimeout: 2000,
        loop: false,
        //nav: true,
        //navText: ['<i class="fa fa-chevron-circle-left"></i>', '<i class="fa fa-chevron-circle-right"></i>'],
        pagination: false,
        stopOnHover: true,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1
            },
            480: {
                items: 1
            },
            1000: {
                items: 3
            }
        }
    });
});

$(window).on('load', function () {
    var div_slider = $(".list_package_slider");
    var loop = false;
    var div_id;
    var viewport = $(window).width();
    var itemCount;
    div_slider.each(function () {
        div_id = $(this).attr('id');
        itemCount = $("#" + div_id + " .item").length;
        if ((viewport >= 481 && itemCount > 3) //desktop || tablet
            || (viewport <= 480 && itemCount > 1) //mobile
        ) {
            loop = true;
        } else {
            loop = false;
        }

        $(this).owlCarousel({
            autoplay: true,
            autoplayTimeout: 5000,
            loop: loop,
            nav: true,
            navText: ['<i class="fa fa-chevron-circle-left"></i>', '<i class="fa fa-chevron-circle-right"></i>'],
            pagination: false,
            stopOnHover: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 1
                },
                1000: {
                    items: 3
                }
            }
        });
    });
});

$(document).ready(function () {
    $('#package_slider img').hover(function () {
        $(this).addClass('transition');

    }, function () {
        $(this).removeClass('transition');
    });
    //show tooltip
    // $('#list_msisdn .btnBuySim').hover(function () {
    //     $(this).next('.hasTooltip').css('display', 'block');
    //     $(this).next('.hasTooltip').addClass('tooltip fade bottom in');
    // }, function () {
    //     $(this).next('.hasTooltip').css('display', 'none');
    //     $(this).next('.hasTooltip').removeClass('tooltip fade bottom in');
    // });
});
//disable button submit
$(document).on('submit', '#form_step1', function (e) {
    e.preventDefault();
    $(':input[type="submit"]').prop('disabled', true);
    this.submit();
});
$(document).on('submit', '#form_step2', function (e) {
    e.preventDefault();
    $(':input[type="submit"]').prop('disabled', true);
    this.submit();
});
$(document).on('submit', '#otp_form', function (e) {
    e.preventDefault();
    $(':input[type="submit"]').prop('disabled', true);
    this.submit();
});
$(document).on('submit', '#filter_order', function (e) {
    e.preventDefault();
    $(':input[type="submit"]').prop('disabled', true);
    this.submit();
});

$.fn.enterKey = function (fnc) {
    return this.each(function () {
        $(this).keypress(function (ev) {
            var keycode = (ev.keyCode ? ev.keyCode : ev.which);
            if (keycode == '13') {
                fnc.call(this, ev);
            }
        })
    })
}