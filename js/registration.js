var pass1Entered=false;
var pass2Entered=false;

$(document).ready(function(){
    document.onkeyup = function (e) {
	    e = e || window.event;
	    if (e.keyCode === 13) {
	        onSubmitClickOnReg();
	    }
	    // Отменяем действие браузера
	    return false;
	}
    $('#password').focus(function(){
        $('#password').val('');
    });
    $('#password_confirm').focus(function(){
        $('#password_confirm').val('');
    });
});

function dropPassword(){
    $('#password_confirm').val('');
    $('#password').val('');
}

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }

function onSubmitClickOnReg(){
    var pass1="";
    var pass2="";
    if (($('#password_confirm').val()!="")){
        $('#password_confirm').val(SHA256($('#password_confirm').val()));
        pass2=$('#password_confirm').val();
    }
    if (($('#password').val()!="")){
        $('#password').val(SHA256($('#password').val()));
        pass1=$('#password').val();
    }

    var email=$('#email').val();
    var b = validateEmail(email);

    if (pass1==pass2){
        if (b){
            return true;
        }else{
            alert("Email введён неверно");
            dropPassword();
            return false;
        }
    }else{
        alert("Пароли не совпадают");
        dropPassword();
        return false;
    }     
}