(function ($) {
	$(document).on("beforeSubmit","form", function (event) {
	    var $form = $(event.target),
	        data = $form.data("yiiActiveForm");
	    var $button = data.submitObject,
	        extData = "&" + data.settings.ajaxParam + "=" + $form.attr("id");
	    if ($button && $button.length && $button.attr("name")) {
	        extData += "&" + $button.attr("name") + "=" + $button.attr("value");
	    }

       	$form.find(":submit").addClass("disabled");
        var goUrl = $form.attr("data-goUrl");
	    $.ajax({
	        url: $form.attr("action"),
	        type: $form.attr("method"),
	        data: $form.serialize(),
	    }).then(function (res) {
	    	getAjaxPage(goUrl,res);
	    },function (res,status) {
	      $form.find(":submit").removeClass("disabled");
	    });
	    return false;
	}).on("click", "a[data-swal]", function(event) {
		event.preventDefault();
		if ($(this).attr("data-swal")) {
	        var url = $(this).attr("href");
	        var goUrl = $(this).attr("data-goUrl");
	        if (url=="#" || url=="") {
	            return ;
	        }
	        var $setting = $(this).data("swal");

	        swal($setting,function(isConfirm){
	            if (isConfirm){
			        $.post(url).then(function (res) {
		    			getAjaxPage(goUrl,res);
			        },function (res,b,c,d,e) {
			        	request_error(res.status,res.responseText);
			        });
	            }
	        });
		}
	}).on("pjax:send", function() {
        App.startPageLoading({animate: true});
	}).on("pjax:complete", function() {
        App.stopPageLoading();
	}).on("click", "[data-refresh]", function() {
        App.startPageLoading({animate: true});
        $.get(window.location.href,function (res) {
			$("#container").html(res);
        	App.stopPageLoading();
		})
	}).pjax("a[data-pjax]", "#container");
})($);


function request_error($code=0,$msg='') {
	switch($code)
	{
	    case 403:
	    	swal("", $msg, "info");
	        break;
	    case 404:
	    	swal("", $msg, "info");
	        break;
	    case 405:
	    	swal("", $msg, "info");
	        break;
	    case 500:
	    	swal("", "服务器错误", "info");
	        break;
	    default:
	        break;
	}
}

function menuSearch() {
	$(".sidebar-search").submit(function () {
		console.log(".sidebar-search")
		return false;
	});
}
function getAjaxPage($url,$res) {
	if ($res.status == 200) {
        swal("", $res.msg, "success");
		$.get($url,function (res) {
			$("#container").html(res);
		})
	}else{
		if (!$res.status) {$("#container").html($res);return;}
		swal("", $res.msg, "info");
	}
}

/**
 * $id 元素id
 * $data 数据
 * $do 1增加 2删除 3覆盖
 */
function json_value($id,$data,$do=1) {
	var value = $($id).val() ?  JSON.parse($($id).val()) : new Array();
	if ($do == 1) {
		value.push($data);
	}else if ($do == 2) {
		value.map(function (val,index) {
			if (val['image_id'] == $data['image_id']) {
    			value.splice(index, 1);
			}
		})
	}else if ($do == 3) {
		value = $data;
	}
	$($id).val(JSON.stringify(value));
}
