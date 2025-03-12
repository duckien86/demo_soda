/*
 * jQuery File Upload Plugin JS Example 8.9.1
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global $, window */

$(function () {
    var video_id  = $('#video_id').val();
    var video_file_id  = $('#video_file_id').val();
    var max_file_size  = $('#max_file_size').val();
    var form_id = '#afiles-form';
    'use strict';

    // Initialize the jQuery File Upload widget:
    $(form_id).fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: 'index.php?r=aMedia/uploadFiles&id='+video_id+'&file_id=' +video_file_id,
        maxFileSize: max_file_size,
        maxNumberOfFiles:5
    });
   /* $('#avideofiles-form').fileupload(

    ).bind('fileuploadprocessdone', function (e, data) {alert('ef')});*/
    // Enable iframe cross-domain access via redirect option:
    $(form_id).fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );

    if (window.location.hostname != 'blueimp.github.io') {
        // Load existing files:
       /*$('#avideofiles-form').addClass('fileupload-processing');
       $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
           // url: $('#avideofiles-form').fileupload('option', 'url'),
            url: 'index.php?r=aVideos/uploadVideoFiles&id='+video_id+'&file_id=' +video_file_id,
            dataType: 'json',
            context: $('#avideofiles-form')[0]
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});
        });*/
    }
});
