<?php
/**
 * @var $this                   PackageController
 * @var $searchPackageForm      SearchPackageForm
 * @var $list_package_hot       array - Gói hấp dẫn
 * @var $list_package_prepaid   array - Gói trả trước
 * @var $list_package_postpaid  array - Gói trả sau
 * @var $list_package_data      array - Gói data
 * @var $list_package_vas       array - Gói GTGT
 * @var $list_package_flexible  array - Gói linh hoạt (chưa dùng)
 *
 * @var $list_package_hot_other       array - Gói hấp dẫn
 * @var $list_package_prepaid_other   array - Gói trả trước
 * @var $list_package_postpaid_other  array - Gói trả sau
 * @var $list_package_data_other      array - Gói data
 * @var $list_package_vas_other       array - Gói GTGT
 * @var $list_package_flexible_other  array - Gói linh hoạt (chưa dùng)
 */
?>
<div id="packages" class="page_detail">
    <section class="ss-bg">
        <section class="ss-box1">
            <div class="no_pad_xs">
                <div class="ss-box1-right-all">
                    <div class="package_filter_area_container">
                        <div class="container">
                            <!-- --><?php /*$this->renderPartial('/package/_filter_area_fiber', array('searchPackageForm' => $searchPackageForm, 'package_search_filter' => true,'list_province'=>$list_province)) */?>
                        </div>
                    </div>

                    <div class="block_package_container">
                        <div id="packages_tabs" class="tab-content content">
                            <?php $this->renderPartial('/package/_block_package_tra_sua', array(
                                'list_package'          => $list_package_tra_sua,

                            )) ?>

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </section>
    <!--end section #ss-bg -->
</div>
<script>
    function showAllPackage(selector){
        $(selector).closest('.block_package').find('div.row-plus').each(function () {
            $(this).removeClass('hidden');
        });
        $(selector).addClass('hidden');
        $(selector).closest('.action').find('.btn-prev').first().removeClass('hidden');
    }
    function shortenPackage(selector){
        $(selector).closest('.block_package').find('div.row-plus').each(function () {
            $(this).addClass('hidden');
        });
        $(selector).addClass('hidden');
        $(selector).closest('.action').find('.btn-next').first().removeClass('hidden');
    }

</script>