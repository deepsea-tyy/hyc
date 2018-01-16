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

function http_code($code=0) {
	switch($code)
	{
	    case 200:
	        
	        break;
	    case 404:
	        
	        break;
	    case 405:
	        
	    m47_alert();
	        break;
	    case 500:
	        
	        break;
	    default:

	    m47_alert();
	        break;
	        
	}
}
function m47_alert($data={}) {
	var sa_title = 'title';//Define Sweet Alert Title (Text) 
	var sa_message = 'message';//Define Sweet Alert Message (Text) 
	var sa_type = 'success';	//Defines the icon type to appear (success / error / warning / info) 
	var sa_showConfirmButton = true;//Defines to show confirm button (true / false) 
	var sa_confirmButtonClass = 'btn-info';//Defines any additional css classes to assign to confirm button   (btn-info / btn-success / )
	var sa_showCancelButton = true;//Defines to show cancel button (true / false)
	var sa_cancelButtonClass = 'btn-danger';//Defines any additional css classes to assign to cancel button (btn-danger / btn-default / btn-primary / btn-warning)
	var sa_closeOnConfirm = false;//Defines if Sweet Alert closes when click on confirm button (true / false) 
	var sa_closeOnCancel = false;//Defines if Sweet Alert closes when click on cancel button (true / false)
	var sa_confirmButtonText = 'confirm-button-text';//Defines Sweet Alert confirm button text (Text) 
	var sa_cancelButtonText = 'cancel-button-text';//Defines Sweet Alert cancel button text (Text)
	var sa_popupTitleSuccess = 'popup-title-success';//Defines Sweet Alert confirmation popup Title for Success (Text) 
	var sa_popupMessageSuccess = 'popup-message-success';//Defines Sweet Alert confirmation popup Message for Success 
	var sa_popupTitleCancel = 'popup-title-cancel';//Defines Sweet Alert confirmation popup Title for Cancel 
	var sa_popupMessageCancel = 'popup-message-cancel';//Defines Sweet Alert confirmation popup Message for Cancel
	var sa_allowOutsideClick = true;//外部点击关闭
	if ($.isEmptyObject($data)) {
		$data = {
		  title: sa_title,
		  text: sa_message,
		  type: sa_type,
		  allowOutsideClick: sa_allowOutsideClick,
		  showConfirmButton: sa_showConfirmButton,
		  showCancelButton: sa_showCancelButton,
		  confirmButtonClass: sa_confirmButtonClass,
		  cancelButtonClass: sa_cancelButtonClass,
		  closeOnConfirm: sa_closeOnConfirm,
		  closeOnCancel: sa_closeOnCancel,
		  confirmButtonText: sa_confirmButtonText,
		  cancelButtonText: sa_cancelButtonText,
		}
	}

	swal($data,
		function(isConfirm){
	        if (isConfirm){
	        	swal(sa_popupTitleSuccess, sa_popupMessageSuccess, "success");
	        } else {
				swal(sa_popupTitleCancel, sa_popupMessageCancel, "error");
	        }
		});
}

