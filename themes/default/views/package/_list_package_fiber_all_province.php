<?php foreach ($list_package as $package) {
    $this->renderPartial('/package/_item_package_fiber', array(
        'model' => $package
    ));
}