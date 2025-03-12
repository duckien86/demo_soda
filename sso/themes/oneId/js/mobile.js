var currentHeightScreen = jQuery(window).height() + 150;
// scroll to top
$(document).ready(function () {
    //footer sticker
    if ($(document).height() > $(window).height()) {
        $('#footer-sticker').css('position', 'relative');
        $('#footer-sticker').css('display', 'block');
        // alert('1');
    }
    else {
        // $('#footer-sticker').css('position', 'absolute');
        $('#footer-sticker').css('bottom', 0);
        $('#footer-sticker').css('width', '100%');
        // alert('2');
    }
    // menu left
    $('nav#menu').mmenu({
        extensions: true,
        searchfield: false,
        counters: true,
        openingInterval: 0,
        transitionDuration: 5,
        navbar: {
            title: 'Menu'
        },
        navbars: [
            {
                position: 'top',
                content: [
                    'prev',
                    'title',
                    //'close'
                ]
            },
        ],
    }).on('click',
        'a',
        function () {
            $(this).reset();
            return false;
        }
    );

    //Check to see if the window is top if not then display button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.scrollToTop').fadeIn();
        } else {
            $('.scrollToTop').fadeOut();
        }
    });

    //Click event to scroll to top
    $('.scrollToTop').click(function () {
        $('html, body').animate({scrollTop: 0}, 800);
        return false;
    });

    //        click icon expand tại trang chi tiết video
    $('.icon-expand').click(function () {
        $('.video-info-expand').toggle('fast');
        // alert('hihi');
        $('.video-expand').find('.fa-caret-down, .fa-caret-up').toggleClass("fa-caret-down fa-caret-up");
    });
    //Xu ly no-thumbnail
    //thumbnail pick team's image
    // $("img").on('error', function () {
    //     $(this).attr("src", ServerUrl + 'images/thumb_team.png')
    // });
    <!-- JS Search on topmenu-->

    function is_undefined(obj) {
        if ($.type(obj) == "undefined" || obj == undefined) {
            return true;
        } else {
            return false;
        }
    }

    //trường hợp user chưa đăng nhập mà click nội dung video tính phí, thì set link video = '#', và hiện topup lên, video free thì hiển thị link bình thường
    viewVideo();

    //        slide video hot và live tv trang chủ
    $('#video-hot, #live-tv').lightSlider({
        item: 5,
        loop: true,
        slideMove: 1,
        controls: false,
        auto: true,
        pager: false,
        easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
        speed: 600,
        pause: 5000,
        thumbItem : 1,
        responsive: [
            {
                breakpoint: 800,
                settings: {
                    item: 3.5,
                    slideMove: 1,
                    slideMargin: 10,
                }
            },
            {
                breakpoint: 480,
                settings: {
                    item: 2,
                    slideMove: 1,
                    slideMargin: 15
                }
            },
            {
                breakpoint: 320,
                settings: {
                    item: 1.5,
                    slideMove: 1,
                    slideMargin: 15
                }
            }
        ],
//            khi load xong thi ẩn class
        onSliderLoad: function () {
            $('#video-hot').removeClass('cS-hidden');
            $('#live-tv').removeClass('cS-hidden');
        }
    });


    //click icon search header thì xóa value trong input để ko bị lưu lần search trước đó
    $('#icon-click-search').click(function () {
        $('#search-input').val('');
    });

    //focus luôn vào ô search khi form search này hiện
    $('#modalSearch').on('shown.bs.modal', function () {
        $('#search-input').focus();
    })


});
// $(document).mouseup(function (e)
// {
//     var container = $("#result-search");
//
//     if (!container.is(e.target) // if the target of the click isn't the container...
//         && container.has(e.target).length === 0 // ... nor a descendant of the container
//         && !$("#search-input").is(":focus")
//     )
//     {
//         container.hide();
//     }
// });
$(window).load(function () {
    // $('#modal').modal('show');
});
function openModal(type) {
    if (type == 'register') {
        $('#modalLogin #li_register').addClass('active');
        $('#modalLogin #register').addClass('active');
        $('#modalLogin #li_login').removeClass('active');
        $('#modalLogin #login').removeClass('active');
        $('#modalLogin #forgotpassword').removeClass('active');
    } else {
        $('#modalLogin #li_login').addClass('active');
        $('#modalLogin #login').addClass('active');
        $('#modalLogin #li_register').removeClass('active');
        $('#modalLogin #register').removeClass('active');
        $('#modalLogin #forgotpassword').removeClass('active');
    }
    $('#modalLogin').modal('show');
}

//trường hợp user chưa đăng nhập mà click nội dung video tính phí, thì set link video = '#', và hiện topup lên, video free thì hiển thị link bình thường
function viewVideo() {
    $('.video-wrap a').click(function (e) {
        var a_href = $(this).attr('href');
        if (a_href == '#') {
            e.preventDefault();
            $('#myModal').modal('toggle');
        }
    });
}
//    /**
//     * ajax dùng thêm video yêu thích
//     * @param media_id
//     * @param user_id
//     * @param user_ip
//     * @param url
//     */
function favoriteAjax(media_id, user_id, user_ip, url_ajax, csrf_value, text_add, text_remove) {
    $.ajax({
        url: url_ajax,
        type: "POST",
        data: {
            'media_id': media_id,
            'user_id': user_id,
            'user_ip': user_ip,
            'YII_CSRF_TOKEN': csrf_value
        },
        success: function (data) {
            var data_json = JSON.parse(data);
            var status = data_json.status;
            // console.log(data_json);
            //add favorite thành công
            if (status == 1) {
                $('#favorite-' + media_id).addClass("favorite-active");
                $('#favorite-' + media_id).prop('title', 'Bỏ thích');
                $('.favorite-text').html("");
                // alert("Thêm vào danh sách yêu thích thành công!");
            }
            else {
                $('#favorite-' + media_id).removeClass("favorite-active");
                $('#favorite-' + media_id).prop('title', 'Yêu thích');
                $('.favorite-text').html(text_add);
                // alert("Đã xóa khỏi danh sách yêu thích!");
            }
        }
    });
}

var searchMediObj;
/**
 *
 * @param url : url ajax
 * @param csrf_value :token authentication
 *
 */
function searchMedia(url, csrf_value) {
    $('#search-input').keyup(function () {
        var keyword = $(this).val();
        //neu >2 ki tu thi moi tim kiem
        if (keyword.length >= 2) {
            //show hiệu ứng loadding
            $('.search-ajax-loading').show();
            if (!isUndefined(searchMediObj)) {
                //chặn ko cho request liên tục
                searchMediObj.abort();
            }
            searchMediObj = $.ajax({
                url: url,
                type: "POST",
                data: {
                    keyword: keyword,
                    'YII_CSRF_TOKEN': csrf_value
                },
                success: function (data) {
                    if (data != null) {
                        $('#result-search').html(data);
                        $('#result-search').css("display", "block");
                        $('#search-close').css("display", "inline");
                        //ẩn hiệu ứng loading
                        $('.search-ajax-loading').hide();
                        // click nút close thì ẩn box show kết quả
                        $('#search-close').click(function () {
                            $('#result-search').hide();
                            $(this).hide();
                        });
                    }
                    // else{
                    //     $('#result-search').css("overflow-y", "visible");
                    // }
                }
            });
        }
        else {
//                $('.result-search').html("Vui lòng nhập 2 kí tự trở lên");
        }
    });
}

function isUndefined(obj) {
    if ($.type(obj) == "undefined" || obj == undefined) {
        return true;
    } else {
        return false;
    }
}
//function ajax chung cho phần xem thêm
/**
 *
 * @param page: trang tiếp theo muốn lấy dữ liệu
 * @param total_json: số item hiện tại trên 1 trang, dùng để cộng dồn
 * @param category_id: id của category video/video ca nhạc
 * @param url: url ajax
 * @param csrf_value: token authentication
 * @param div_result: id hoặc class truyền vào để hiển thị kết quả ajax trả về, nếu ko truyền thì set giá trị mặc định là #ajax-music-index
 * @param div_button_ajax: id hoặc class truyền vào của nút Xem thêm, nếu ko truyền thì set giá trị mặc định là #video-music-readmore
 */
function loadMoreAjax(page, total_json, category_id, url, csrf_value, div_result, div_button_ajax) {
    if (typeof(div_result) === 'undefined') div_result = '#ajax-music-index';
    if (typeof(div_button_ajax) === 'undefined') div_button_ajax = '#video-music-readmore';
    var page = page + 1;
    var total_json = total_json;
    $(div_button_ajax).click(function (e) {
        e.preventDefault();
        //show hiệu ứng loading
        $('#music-readmore-img').show();
        //đổi text khi load thêm
        $('.video-readmore-ajax').html("Đang tải...");

        $.ajax({
            url: url,
            type: "POST",
            data: {
                'page': page,
                'category_id': category_id,
                'YII_CSRF_TOKEN': csrf_value
            },
            dataType: 'json',
            success: function (data) {
////                        //append dữ liệu vào div
                $(div_result).append(data.data_html);
//                   //trường hợp user chưa đăng nhập mà click nội dung video tính phí, thì set link video = '#', và hiện topup lên, video free thì hiển thị link bình thường
                viewVideo();
//////                        //ẩn icon loading
                $('#music-readmore-img').hide();
                //đổi tại text khi đã load xong
                $('.video-readmore-ajax').html("Xem thêm");
//////                        //tăng page lên 1
                page++;
                total_json += data.data_list.length;
                //nếu dữ liệu trả về vượt quá tổng số item, dữ liệu đã hết, thì ẩn nút xem thêm
                console.log(total_json);
                if (total_json >= data.total_item) {
                    $('.video-readmore-ajax').css("display", "none");
                }
            },
            error: function (response, status) {
                console.log(response + status);
            },
        })
        ;
    });

}
/**
 * Update lượt view cho mỗi video
 * @param url
 * @param meida_id
 * @param csrf_value
 */

function loadMoreAjax_news(page, total_json, url, csrf_value, div_result, div_button_ajax) {
    if (typeof(div_result) === 'undefined') div_result = '.news-item-wrap';
    if (typeof(div_button_ajax) === 'undefined') div_button_ajax = '#news-music-readmore';
    var page = page + 1;

    var total_json = total_json;
    $(div_button_ajax).click(function (e) {
        e.preventDefault();
        //show hiệu ứng loading
        $('#news-readmore-img').show();
        //đổi text khi load thêm
        $('.news-readmore-ajax').html("Đang tải...");

        $.ajax({
            url: url,
            type: "POST",
            data: {
                'page': page,
                'YII_CSRF_TOKEN': csrf_value
            },
            dataType: 'json',
            success: function (data) {
                // console.log(JSON.stringify(data));
////                        //append dữ liệu vào div\

                $(div_result).append(data.data_html);
//                   //trường hợp user chưa đăng nhập mà click nội dung video tính phí, thì set link video = '#', và hiện topup lên, video free thì hiển thị link bình thường
                viewVideo();
//////                        //ẩn icon loading
                $('#news-readmore-img').hide();
                //đổi tại text khi đã load xong
                $('.news-readmore-ajax').html("Xem thêm");
//////                        //tăng page lên 1
                page++;

                total_json += data.count;

                //nếu dữ liệu trả về vượt quá tổng số item, dữ liệu đã hết, thì ẩn nút xem thêm
                if (total_json >= data.total_item-1) {
                    $('#news-music-readmore').css("display", "none");
                }
            },
            error: function (response, status) {
                console.log(response + status);
            },
        })
        ;
    });

}
function updateViewVideo(url, media_id, csrf_value) {
    $.ajax({
        url: url,
        type: "POST",
        data: {
            'media_id': media_id,
            'YII_CSRF_TOKEN': csrf_value
        },
        // dataType: 'json',
        success: function (data) {
            // console.log(data);
        }
    });

}
//
// $(function () {
//
// });
// var api = $("#menu").data("mmenu");
// //    console.log(api);
// //    api.on("closed", function () {
// //        api.closeAllPanels();
// //    });