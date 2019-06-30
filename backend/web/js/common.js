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
        var goUrl = $form.data("goUrl");

	   $.ajax({
	        url: $form.attr("action"),
	        type: $form.attr("method"),
	        data: $form.serialize(),
	    }).then(function (res) {
	    	getAjaxPage(goUrl);
	    },function (res,status) {
	      $form.find(":submit").removeClass("disabled");
	    });
	    return false;
	}).on("click", "a[data-confirm]", function(event) {
		event.preventDefault();
		if ($(this).data("confirm")) {
	        var url = $(this).attr("href");
	        var goUrl = $(this).data("goUrl");
	        if (url=="#" || url=="") {
	            return ;
	        }
	        var $setting = $(this).data("swal");

	        swal($setting,function(isConfirm){
	            if (isConfirm){
			        $.post(url).then(function (res) {
		    			getAjaxPage(goUrl);
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
function getAjaxPage($url) {
	$.get($url,function (res) {
        swal("", "操作成功", "success");
		$("#container").html(res);
	},function (res) {
        swal("", "操作失败", "error");
	})
}
