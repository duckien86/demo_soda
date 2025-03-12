function formatNumber(number) {
    return number.toFixed(0).replace(/./g, function(c, i, a) {
        return i && c !== "," && ((a.length - i) % 3 === 0) ? '.' + c : c;
    });
}

function showModalChooseOrder() {
    $("#modal_choose_order").modal('show');
}
function showModalChooseOrderCtv(){
    $("#modal_choose_order_ctv").modal('show');
}

function readURL(input, target) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            target
                .attr('src', e.target.result)
                .width(150)
                .height(200);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function showFileName(input_id, target){
    var input = document.getElementById(input_id);
    var name = input.files.item(0).name;
    $(target).text(name);
    // alert('Selected file: ' + input.files.item(0).name);
    // alert('Selected file: ' + input.files.item(0).size);
    // alert('Selected file: ' + input.files.item(0).type);
}