<option value="">--- Chọn loại hình thuê bao ---</option>
<?php for ($i = 0; $i < count($data_list_type); $i++) {
    $data = $data_list_type[$i]; ?>
    <option value="<?php echo $data['loaitb_id'] ?>"><?php echo $data['ten_loaihinh'] ?></option>
<?php } ?>
