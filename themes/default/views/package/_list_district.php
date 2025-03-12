<option value="">--- Chọn Quận / Huyện ---</option>
<?php for ($i = 0; $i < count($data_list_district); $i++) {
    $data = $data_list_district[$i]; ?>
    <option value="<?php echo $data['quan_id'] ?>"><?php echo $data['ten_quan'] ?></option>
<?php } ?>

