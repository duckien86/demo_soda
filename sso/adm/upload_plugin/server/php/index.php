<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */
error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');
$begin_dir = 'products_land/'.date('Y').'/'.date('m').'/'.date('d').'/';
$options_arr =array(
    'script_url' => 'http://localhost/adm/upload_plugin/server/php/',
    'upload_dir' => 'E:/xampp/htdocs/tp/uploads/',
    'upload_url' => 'http://localhost/tp/uploads/',
    'begin_dir' => $begin_dir,
);
$upload_handler = new UploadHandler($options_arr);
