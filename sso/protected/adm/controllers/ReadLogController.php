<?php

    class ReadLogController extends AController
    {
        private $root_path;

        public function init()
        {
            parent::init();
            $this->defaultAction = 'index';
            $this->pageTitle     = 'ReadLog Controller';
            $this->root_path     = Yii::app()->basePath.'/../';
        }

        public function filters()
        {
            return array(
//                'accessControl', // perform access control for CRUD operations
                'rights', // perform access control for CRUD operations
            );
        }

        public function accessRules()
        {
            return array(
                array(
                    'allow',
                    'ips' => array('222.252.19.197', '118.70.177.77', '10.2.0.*', '10.30.9.11'),
                ),
                array(
                    'deny',
                    'ips' => array('*'),
                )
            );
        }

        public function actionIndex()
        {
            $dataProvider = array();
            $option       = Yii::app()->request->getParam('option', 1);
            $log_path     = $this->root_path;
            $data_file    = NULL;

            if (isset($_REQUEST['post']) || Yii::app()->request->isAjaxRequest) {
                $file_name                          = $_POST['path_log_file'];
                $file_path                          = $log_path . DIRECTORY_SEPARATOR . $file_name;
                Yii::app()->session['query_string'] = $file_name;
                if ($file_name != '' && is_file($file_path)) {
                    $handle = fopen($file_path, "r");
                    if (pathinfo($file_path, PATHINFO_EXTENSION) == 'php') {
                        $data_file = CHtml::encode(fread($handle, filesize($file_path)));
                    } else {
                        $data_file = fread($handle, filesize($file_path));
                    }
                    fclose($handle);

                } else {
                    $a_user = Yii::app()->getComponent('user');
                    $a_user->setFlash(
                        'success',
                        'Không đọc được file'
                    );
                }

            }
            $this->render('index', array(
                'root_path'    => $this->root_path,
                'dataProvider' => $dataProvider,
                'option'       => $option,
                'data_file'    => $data_file
            ));

        }

        public function actionFileBrowser()
        {
            $root = $this->root_path;

            $_POST['dir'] = urldecode($_POST['dir']);

            if (file_exists($root . $_POST['dir'])) {
                $files = scandir($root . $_POST['dir']);
                natcasesort($files);
                if (count($files) > 2) { /* The 2 accounts for . and .. */
                    echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
                    // All dirs
                    foreach ($files as $file) {
                        if (file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && is_dir($root . $_POST['dir'] . $file)) {
                            echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "/\">" . htmlentities($file) . "</a></li>";
                        }
                    }
                    // All files
                    foreach ($files as $file) {
                        if (file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && !is_dir($root . $_POST['dir'] . $file)) {
                            $ext = preg_replace('/^.*\./', '', $file);
                            echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "\">" . htmlentities($file) . "</a></li>";
                        }
                    }
                    echo "</ul>";
                }
            }
        }

    }

?>