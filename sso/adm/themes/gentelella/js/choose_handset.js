
$(document).ready(function()
{
    var img = ADM_HOST_PATH+'/themes/gentelella/images/loading.gif';
	$("#dialogHandset").dialog({
		autoOpen:false,
		maxHeight:1000,
		width:800,
		height:800,
		modal: true, 
		resizable: false,
        buttons:false,
	});
    
    $("#dialog1").dialog({
		autoOpen:false,
		maxHeight:1000,
		width:700,
		height:400,
		modal: true, 
		resizable: false,
        buttons:false,
	});
    
   /* $("#android").dialog({
		autoOpen:false,
		maxHeight:1000,
		width:550,
		height:320,
		modal: true, 
		resizable: false,
        buttons:false,
	});*/
    countHandsetExist();
});

function openDialog(){
    var opid = $("#AGamesFiles_file").val();
    var name = $("#AGamesFiles_file option[value='"+opid+"']").text();
    $("#name_file").html(name);
    if(opid != 0){
        $("#dialogHandset").dialog("open");
    }else{
        $.alerts.dialogClass = $(this).attr('id'); // set custom style class
        jAlert(CHOOSE_FILE, WARNING, function () {
            $.alerts.dialogClass = null; // reset to default
        });
    }
}

function openAndroid(){
    var opid = $("#AGamesFiles_file").val();
    var name = $("#AGamesFiles_file option[value='"+opid+"']").text();
    $("#file_name").html(name);
    if(opid != 0){
        $("#android").dialog("open");
    }else{
        $.alerts.dialogClass = $(this).attr('id'); // set custom style class
        jAlert(CHOOSE_FILE, WARNING, function () {
            $.alerts.dialogClass = null; // reset to default
        });
    }
}

function openDialog1(){
    $("#dialog1").dialog("open");
}

function searchHandset(){
	var opid = $("#AGamesFiles_file").val(); 
    var csrf = $("#csrf").val();
	//$("#arrHandsetIDOld").val(json.arr);
    var loadUrl = ADM_HOST_PATH+"/index.php?r=aHandset/loadhandset";
       var key      = $("#sr_device").val();
       var catphone = $("#AHandset_device_type").val();
       var manufacturer = $("#AHandset_manufactory").val();
       var operating = $("#AHandset_operating").val();
       var check = $('#check_search:checked').val();
       var handold = $("#hidden_handset").val();        
       var notin = $("#hidden_handset").val();
       var data = {'key':key,'check':check,'manufacturer':manufacturer,'operating':operating,'catphone':catphone,'notin':notin,'YII_CSRF_TOKEN':csrf};

       $.ajax({
           url: loadUrl,
           dataType: 'html',
           type: 'POST',
           data : data,
           success:function(html){
               $("#result_search_handset").html("");
               $("#result_search_handset").html(html);
           }
       });
}

var lasttimeout = 0;
var device_search_flag = 1;
function search_phones() {
	var search_string = $('#sr_device').val();
	if(search_string.length >= 0 && device_search_flag == 1) {
		if( lasttimeout ) clearTimeout(lasttimeout);
		lasttimeout = setTimeout('searchHandset()', 1000 );
	}
}

function kindOfHandset(type){
        $("#check_remove").val(0);
        if(type == 'custom'){
            $("#box_select").css('display','block');
        }else{
            $("#box_select").css('display','none');
        }
}

function loadExistHandset(){
    var opid = $("#AGamesFiles_file").val();
    var img = ADM_HOST_PATH+'/themes/gentelella/images/loading.gif';
    var gameid = $("#gameid").val();   
    var csrf = $("#csrf").val(); 
    var loadUrl = ADM_HOST_PATH+"/index.php?r=aHandset/loadhandsetexist"; 
	$("#result_search_handset").html("");
    $("#result_search_handset").append("<p id='preview-loading'><img src='"+
        +"' alt='' /></p>");
        var data = {'fileid':opid,'gameid':gameid,'YII_CSRF_TOKEN':csrf};
        $.ajax({
            url: loadUrl,
            dataType: 'html',
            type: 'POST',
            data : data,
            success:function(html){
                $("#result_search_handset").html("");
                $("#result_search_handset").html(html);
                $("#check_remove").val(1);
			}
    });    
}

function countHandsetExist(){
    var opid = $("#AGamesFiles_file").val();
    var gameid = $("#gameid").val();
    var csrf = $("#csrf").val();
    var loadUrl = ADM_HOST_PATH+"/index.php?r=aHandset/counthandsetexist";
    var img = ADM_HOST_PATH+'/themes/gentelella/images/loading.gif';
    $("#view_total_handset").html("");
    $("#view_total_handset").append("<p id='preview-loading'><img src='"+ img +"' alt='' /></p>");
    var data = {'fileid':opid,'YII_CSRF_TOKEN':csrf,'gameid':gameid};
        $.ajax({
            url: loadUrl,
            dataType: 'json',
            type: 'POST',
            data : data,
            success:function(json){
                if(json.status == true){
                    $("#view_total_handset").html("");
                    $("#view_total_handset").html(json.msg);
                    $("#hidden_handset").val(json.handset);
                }
                
			}
    });    
}

function check(handsetID){
    var matches = [];
    $(".checkHandset:checked").each(function() {
        matches.push(this.value);
    });
    $("#arrHandsetID").val(matches);
    
}

function addHandset(){
    var arrhand = $("#arrHandsetID").val();
    var csrf = $("#csrf").val();
    if(arrhand != ''){
        var fileid  = $("#AGamesFiles_file").val();
        var gameid = $("#gameid").val();
        var loadUrl = ADM_HOST_PATH+"/index.php?r=aHandset/addhandset";
        var check_remove = $("#check_remove").val();
        var version = $("#game_version").val();        
        var data = {'arrhand':arrhand,'fileid':fileid,'gameid':gameid,'check_remove':check_remove,'version':version,'YII_CSRF_TOKEN':csrf};
		$("#image_loading_java").append("<p id='preview-loading'><img src='"+ img +"' alt='' /></p>");           
        if(check_remove == 1){
            $.ajax({
                    url: loadUrl,
                    dataType: 'json',
                    type: 'POST',
                    data : data,
                    success:function(json){
						$("#image_loading_java").html("");
                        $("#result_search_handset").html("");
                        $("#result_search_handset").html("");
                        $("#arrHandsetIDOld").val(''); 
                        $("#hidden_handset").val(json.add_handset);
                        alert(json.msg);
                        loadExistHandset();

        			}
                });
        }else{
            $.ajax({
                url: loadUrl,
                dataType: 'json',
                type: 'POST',
                data : data,
                success:function(json){
					$("#image_loading_java").html("");
                    alert(json.msg);
                    if(json.status == true){
                        $("#arrHandsetIDOld").val($("#arrHandsetIDOld").val() + "," + json.add_handset); 
                        $("#hidden_handset").val($("#hidden_handset").val() +"," + json.add_handset);
						countHandsetExist();

                    }
    			}
            });
        }
    }else{
		$("#image_loading_java").html("");
        alert(NOT_HANDSET);
    }
        
}

function addOperating(){
    var arrhand = $("#arrHandsetID").val();
    var csrf = $("#csrf").val();
    if(arrhand != ''){
        var fileid  = $("#AGamesFiles_file").val();
        var gameid = $("#gameid").val();
        var loadUrl = ADM_HOST_PATH+"/index.php?r=aHandset/addandroid";
        var version = $("#game_version").val();        
		$("#image_loading").append("<p id='preview-loading'><img src='"+ img +"' alt='' /></p>");  
        var data = {'arrhand':arrhand,'fileid':fileid,'gameid':gameid,'version':version,'YII_CSRF_TOKEN':csrf};           
            $.ajax({
                    url: loadUrl,
                    dataType: 'json',
                    type: 'POST',
                    data : data,
                    success:function(json){
						$("#preview-loading").html("");
                        alert(json.msg);
                        countHandsetExist();
        			}
                });
    }else{
		$("#preview-loading").html("");
        alert(json.msg);
    }
        
}

function removeByIndex(arr, index) {
	arr.splice(index, 1);
}

function removeByValue(arr, val) {
    alert(arr.length);
    alert(typeof arr);
    var arr = $.makeArray(arr);
	for(var i=0; i<arr.length; i++) {
	   alert(arr.length);
	   alert(arr);
       alert(val);
		if(arr[i] == val) {
			arr.splice(i, 1);
            alert(arr);
			break;
            
		}
	}
}

    String.prototype.replaceAll = function(
        strTarget, // The substring you want to replace
        strSubString // The string you want to replace in.
        )
    {
        var strText = this;
        var intIndexOfMatch = strText.indexOf( strTarget );
         
        // Keep looping while an instance of the target string
        // still exists in the string.
        while (intIndexOfMatch != -1){
        // Relace out the current instance.
        strText = strText.replace( strTarget, strSubString )
         
        // Get the index of any next matching substring.
        intIndexOfMatch = strText.indexOf( strTarget );
        }
         
        // Return the updated string with ALL the target strings
        // replaced out with the new substring.
        return( strText );
    }

function saveContent(){
    var content = $("#content_box").val();
    var rs=content.replaceAll("\n",",");
    $("#sr_device").val(rs);
    $("#dialog1").dialog("close");
}

function selectAll(){

    if($('#content_all').prop("checked")==true || $('#content_all').prop("checked") == undefined){
        $("input[name*=handsetID]").each(function() {
            $(this).prop("checked",false);
        });
        var matches = [];
        $(".checkHandset:checked").each(function() {
            matches.push(this.value);
        });
        $("#arrHandsetID").val(matches);
    }else{
        $("input[name*=handsetID]").each(function() {
            $(this).prop("checked",true);
        });
        
        var matches = [];
        $(".checkHandset:checked").each(function() {
            matches.push(this.value);
        });
        $("#arrHandsetID").val(matches);
    }
   // return false;
}


/*

function checkAll(checktoggle)
{
    $('.handset_item_result input.checkHandset').each(function(){
        $(this).prop("checked",checktoggle);
    });
}
*/
