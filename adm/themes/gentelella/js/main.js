$(document).on('submit', '#search_msisdn_form', function (e) {
    $(':button').prop('disabled', true);
    var html_load = '';
    html_load += '<div class="text-center">';
    html_load += '<div class="space_30"></div>';
    html_load += '<div><img class=" wow bounceIn infinite" src="themes/gentelella/images/icon_logo_fd.png"/></div>';
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
            //show tooltip
            $('#list_msisdn .btnBuySim').hover(function () {
                $(this).next('.hasTooltip').css('display', 'block');
                $(this).next('.hasTooltip').addClass('tooltip fade bottom in');
            }, function () {
                $(this).next('.hasTooltip').css('display', 'none');
                $(this).next('.hasTooltip').removeClass('tooltip fade bottom in');
            });
            $("#list_msisdn .table-responsive").removeClass('hidden');
        },
        error: function (request, status, err) {
            $(':button').prop('disabled', false);
        }
    });
});


$(document).on('click', '.btnBuySim', function (e) {
    var btnBuySim = $(this);
    btnBuySim.bind('click', false);
    btnBuySim.append('<img src="themes/gentelella/images/loading_sim.gif" alt="" class="loading_sim">');
    var confirm = btnBuySim.attr('data-confirm');
    addToCart(btnBuySim, btnBuySim.attr('data-url'), btnBuySim.attr('data-simnumber'), btnBuySim.attr('data-simprice'), btnBuySim.attr('data-simtype'), btnBuySim.attr('data-simterm'), btnBuySim.attr('data-simpriceterm'), btnBuySim.attr('data-simstore'), btnBuySim.attr('data-csrf'));
});

function displayWarning() {
    var modal = $('#modal_warning');
    modal.addClass('in');
    modal.css('display', 'block');
    modal.modal('show');
}

function addToCart(obj, url, simnumber, simprice, simtype, simterm, simpriceterm, simstore, csrf) {
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
            YII_CSRF_TOKEN: csrf
        },
        success: function (result) {
            obj.unbind('click', false);
            if (result.error_code == 0)
                location.href = result.url;
            else {
                $('.loading_sim').css('display', 'none');
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

$(document).ready(function () {
    //show tooltip
    $('#list_msisdn .btnBuySim').hover(function () {
        $(this).next('.hasTooltip').css('display', 'block');
        $(this).next('.hasTooltip').addClass('tooltip fade bottom in');
    }, function () {
        $(this).next('.hasTooltip').css('display', 'none');
        $(this).next('.hasTooltip').removeClass('tooltip fade bottom in');
    });
});

function unsigned_string(str) {
    str = str.replace(/^\s+|\s+$/g, ''); // trim
    str = str.toLowerCase();

    // remove accents, swap ñ for n, etc
    var from = "àáạảãăằắặẳẵâầấậẩẫÀÁẠẢÃĂẰẮẶẲẴÂẦẤẬẨẪèéẹẻẽêềếệểễÈÉẸẺẼÊỀẾỆỂỄđĐỳýỵỷỹỲÝỴỶỸùúụủũưừứựửữÙÚỤỦŨƯỪỨỰỬỮìíịỉĩÌÍỊỈĨòóọỏõôồốộổỗơờớợởỡÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠ";
    var to = "aaaaaaaaaaaaaaaaaAAAAAAAAAAAAAAAAAeeeeeeeeeeeEEEEEEEEEEEdDyyyyyYYYYYuuuuuuuuuuuUUUUUUUUUUUiiiiiIIIIIoooooooooooooooooOOOOOOOOOOOOOOOOO";
    for (var i = 0, l = from.length; i < l; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    str = str.replace(/[–…“”~!@#$%^&*/\\?<>\'\":;\{\}\[\]|\(\),.`\+=-]/g, '-');

    str = str.replace(/[^a-z0-9- ]/g, '') // remove invalid chars
        .replace(/\s/g, '-') // collapse whitespace and replace by -
        .replace(/^-+|-+$/g, ''); // collapse whitespace and replace by -

    return str;
}