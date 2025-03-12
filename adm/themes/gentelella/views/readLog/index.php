<div class="x_panel">
    <div class="x_title">
        <div class="clearfix"></div>
    </div>
    <div class="x_content">

        <?php

            $this->widget('application.extensions.cfilebrowser.CFileBrowserWidget', array(
                'script'           => array('/readLog/fileBrowser', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                'root'             => '"' . $root_path . '"',
                'folderEvent'      => 'click',
                'expandSpeed'      => 0,
                'collapseSpeed'    => 0,
                'multiFolder'      => TRUE,
                'loadMessage'      => 'File Browser Is Loading...hang on a sec',
                'callbackFunction' => "$('#path_log_file').val(f)"
            )); ?>

        <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id'     => 'inlineForm',
                'method' => 'post',
            ));

            echo CHtml::hiddenField('post', 1);
            echo CHtml::openTag('div', array('class' => 'control-group'));
            echo '<b>Query String</b> ';
            echo CHtml::textField('path_log_file', Yii::app()->session['query_string'], array(
                    'placeholder' => 'Enter path of log directory',
                    'style'       => 'width: 500px',
                    'id'          => 'path_log_file',
                )
            );
            echo CHtml::submitButton('Open', array('style' => 'width:100px'));
            echo CHtml::closeTag('div');

            $this->endWidget(); ?>

        <!--<PRE class="my_pre">-->
        <?php
            if ($data_file) {
                echo '<pre>';
                echo trim($data_file);
            }
        ?>
        <!--</PRE>-->
    </div>
</div>
<style>
    .my_pre {
        display: table;
        padding: 8.5px;
        margin: 0 0 9px;
        font-size: 12.025px;
        line-height: 18px;
        word-break: break-all;
        word-wrap: break-word;
        white-space: pre;
        background-color: #f5f5f5;
        height: 500px;
    }
</style>