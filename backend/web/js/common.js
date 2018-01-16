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
	   $.ajax({
	        url: $form.attr('action'),
	        type: $form.attr('method'),
	        data: $form.serialize(),
	        // dataType: data.settings.ajaxDataType,
	    }).then(function (html) {
	    	$('#container').html(html);
	    },function (res,status) {
	      $form.find(":submit").removeClass("disabled");
	    });
	
	    return false;
	});
})($);

function http_code($code=0,$msg='') {
	switch($code)
	{
	    case 200:
	        
	        break;
	    case 404:
	    	m47_sa({title:$code,text:$msg,type:'error',showConfirmButton:false});
	        break;
	    case 405:
	        
	    	m47_sa({title:$code,text:$msg,type:'error',showConfirmButton:false});
	        break;
	    case 500:
	        
	    	m47_sa({title:$code,text:$msg,type:'error',showConfirmButton:false});
	        break;
	    default:

	    	m47_sa({title:$code,text:'未设置响应码' + $code,type:'error',showConfirmButton:false});
	        break;
	        
	}
}
function m47_sa($setting={}) {
	var title = 'title';//Define Sweet Alert Title (Text) 
	var message = 'message';//Define Sweet Alert Message (Text) 
	var type = 'success';	//Defines the icon type to appear (success / error / warning / info) 
	var showConfirmButton = true;//Defines to show confirm button (true / false) 
	var confirmButtonClass = 'btn-info';//Defines any additional css classes to assign to confirm button   (btn-info / btn-success / )
	var showCancelButton = true;//Defines to show cancel button (true / false)
	var cancelButtonClass = 'btn-danger';//Defines any additional css classes to assign to cancel button (btn-danger / btn-default / btn-primary / btn-warning)
	var closeOnConfirm = true;//Defines if Sweet Alert closes when click on confirm button (true / false) //关闭二级信息
	var closeOnCancel = true;//Defines if Sweet Alert closes when click on cancel button (true / false) //关闭二级信息
	var confirmButtonText = 'confirm-button-text';//Defines Sweet Alert confirm button text (Text) 
	var cancelButtonText = 'cancel-button-text';//Defines Sweet Alert cancel button text (Text)
	var popupTitleSuccess = 'popup-title-success';//Defines Sweet Alert confirmation popup Title for Success (Text) 
	var popupMessageSuccess = 'popup-message-success';//Defines Sweet Alert confirmation popup Message for Success 
	var popupTitleCancel = 'popup-title-cancel';//Defines Sweet Alert confirmation popup Title for Cancel 
	var popupMessageCancel = 'popup-message-cancel';//Defines Sweet Alert confirmation popup Message for Cancel
	var allowOutsideClick = true;//外部点击关闭

	$default = {
	  title: title,
	  text: message,
	  type: type,
	  allowOutsideClick: allowOutsideClick,
	  showConfirmButton: showConfirmButton,
	  showCancelButton: showCancelButton,
	  confirmButtonClass: confirmButtonClass,
	  cancelButtonClass: cancelButtonClass,
	  closeOnConfirm: closeOnConfirm,
	  closeOnCancel: closeOnCancel,
	  confirmButtonText: confirmButtonText,
	  cancelButtonText: cancelButtonText,
	}
	$setting = $.extend({}, $default, $setting);

	swal($setting,
		function(isConfirm){
	        if (isConfirm){
	        	swal(popupTitleSuccess, popupMessageSuccess, "success");
	        } else {
				swal(popupTitleCancel, popupMessageCancel, "error");
	        }
		});
}

