(function ($) {
	$(document).on('beforeSubmit','form', function (event) {
	    var $form = jQuery(event.target),
	        data = $form.data('yiiActiveForm');
	    var $button = data.submitObject,
	        extData = '&' + data.settings.ajaxParam + '=' + $form.attr('id');
	    if ($button && $button.length && $button.attr('name')) {
	        extData += '&' + $button.attr('name') + '=' + $button.attr('value');
	    }
	    /*$.ajax({
	        url: $form.attr('action'),
	        type: $form.attr('method'),
	        data: $form.serialize() + extData,
	        dataType: data.settings.ajaxDataType,
	    });*/
	    
       	$form.find(":submit").addClass("disabled");
		if ($form.attr('enctype')) {
			//表单提交图片
	    	var fmData = new FormData($form[0]);
	        $form.find(":submit").addClass("disabled");
		   	$.ajax({
		        url: $form.attr('action'),
		        type: $form.attr('method'),
		        data: fmData,
		        cache:false,
				processData: false, 
		        contentType: false,
		    }).then(function (html) {
	    		Layout.loadAjaxContent(res.url,"GET");
		    },function (res,status) {
		      	$form.find(":submit").removeClass("disabled");
        		App.initAjax();
		    });
		}else{
		   $.ajax({
		        url: $form.attr('action'),
		        type: $form.attr('method'),
		        data: $form.serialize(),
		    }).then(function (res) {
		    	if (!$.isEmptyObject(res.url)) {
		    		// console.log(res.url);
		    		Layout.loadAjaxContent(res.url,"GET");
		    	}else{
		    		// console.log(res);
		    		$('#container').html(res);
                	App.initAjax();
		    	}
		    },function (res,status) {
		      $form.find(":submit").removeClass("disabled");
		    });
		}
	    return false;
	}).on('click','a.ajax-request', function (event) {
		event.preventDefault();
		// alert();return;
        var url = $(this).attr("href");
        if (url=="#" || url=="") {
            return ;
        }
        var $setting = {
          title: $(this).data('title'),
          text: $(this).data('message'),
          type: $(this).data('type'),
          allowOutsideClick: $(this).data('allow-outside-click'),
          showConfirmButton: $(this).data('show-confirm-button'),
          showCancelButton: $(this).data('show-cancel-button'),
          confirmButtonClass: $(this).data('confirm-button-class'),
          cancelButtonClass: $(this).data('cancel-button-class'),
          closeOnConfirm: $(this).data('close-on-confirm'),
          closeOnCancel: $(this).data('close-on-cancel'),
          confirmButtonText: $(this).data('confirm-button-text'),
          cancelButtonText: $(this).data('cancel-button-text'),
        };

        swal($setting,function(isConfirm){
            if (isConfirm){
		        $.post(url,function (res) {
	                Layout.addAjaxContentSuccessCallback(function (res) {
	                    swal('', '操作成功', "success");
	                });
	                Layout.addAjaxContentErrorCallback(function (res) {
	                    swal('', '操作失败', "error");
	                });
	                Layout.loadAjaxContent(res.url);
		        });
            }
        });
	});
})($);
