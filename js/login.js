
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

/*ReactDOM.render(
    <h1>Hello, world!</h1>,
    document.getElementById('root')
);*/

function onSubmitClickOnLogin(){
    if ($('#password').val()!=""){
        $('#password').val(SHA256($('#password').val()));
    }
    return true;  
}