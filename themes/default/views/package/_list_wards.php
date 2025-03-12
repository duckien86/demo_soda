<option value="">--- Chọn Phường / Xã ---</option>
<?php for ($i = 0; $i < count($data_list_wards); $i++) {
    $data = $data_list_wards[$i]; ?>
    <option value="<?php echo $data['phuong_id'] ?>"><?php echo $data['ten_phuong'] ?></option>
<?php } ?>
