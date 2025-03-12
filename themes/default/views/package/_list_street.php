<option value="">--- Chọn Khu / Phố ---</option>
<?php for ($i = 0; $i < count($data_list_street); $i++) {
    $data = $data_list_street[$i]; ?>
    <option value="<?php echo $data['pho_id'] ?>"><?php echo $data['ten_pho'] ?></option>
<?php } ?>
