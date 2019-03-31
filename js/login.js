$(document).ready(function(){
    document.onkeyup = function (e) {
	    e = e || window.event;
	    if (e.keyCode === 13) {
	        onSubmitClickOnLogin();
	    }
	    // Отменяем действие браузера
	    return false;
	}
    $('#password').focus(function(){
        $('#password').val('');
    });
});

function onSubmitClickOnLogin(){
    if ($('#password').val()!=""){
        $('#password').val(SHA256($('#password').val()));
    }
    return true;  
}