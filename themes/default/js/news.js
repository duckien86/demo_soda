$(document).ready(function () {
    $('#news-list-top-items .owl-carousel').owlCarousel({
        autoplay: true,
        autoplayTimeout: 5000,
        autoplaySpeed: 2000,
        loop: false,
        pagination: false,
        stopOnHover: true,
        items:1,
        lazyLoad: true
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
            768: {
                items: 4
            },
            1200: {
                items: 5
            }
        },
        lazyLoad: true
    });

    $('#btn-loadmore').on('click', function (e) {
        // e.preventDefault();
        var container = $(this).closest('div');
        var imgLoad = '<img src="/freedoo/themes/default/images/loading.gif">';
        var list = $('#news-list-default-items');
        var page = parseInt(list.find('.news-page').last().attr('data-page'))+1;
        container.find('span').remove();
        container.append(imgLoad);
        $.ajax({
            url: '/freedoo/news/loadmore',
            type: 'GET',
            dataType: 'html',
            data: {
                page: page
            },
            success: function (result) {
                if(result.trim().length){
                    list.append(result);
                }else{
                    container.append('<span>Không tìm thấy tin bài nào nữa!</span>');
                }
                container.find('img').remove();
            }
        });
    });

});
// $(window).scroll(function() {
//     var bottom = $('#news-loadmore'),
//         hT = bottom.offset().top,
//         hH = bottom.outerHeight(),
//         wH = $(window).height(),
//         wS = $(this).scrollTop();
//     if (wS > (hT+hH-wH)){
//         var imgLoad = bottom.find('img');
//         var list = $('#news-list-default-items');
//         var page = list.find('.news-page').last().attr('data-page')+1;
//         imgLoad.removeClass('hidden');
//         $.ajax({
//             url: '/freedoo/news/loadmore',
//             type: 'GET',
//             dataType: 'html',
//             data: {
//                 page: page
//             },
//             success: function (result) {
//                 if(result.trim().length){
//                     list.append(result);
//                 }
//                 imgLoad.addClass('hidden');
//             },
//             error: function (error) {
//                 alert(error)
//             }
//         });
//     }
// });
