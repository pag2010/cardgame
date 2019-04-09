var url;
var chat;
$(function(){
  urlstr=document.URL;
  pos=urlstr.indexOf('=');
  chat=urlstr.slice(pos+1);
  $(".chat-history").scrollTop($(".chat-history")[0].scrollHeight);
  $("#submit").click(function(){
    if ($("#msg").val()!=""){
      $.ajax({
        type: "POST",
        url: "http://card-collection-game/chat/send",
        data: { submit:"true", chat_id: parseInt(chat), msg: $("#msg").val() }
      })
      $("#msg").val("");
    }
  });
    $("#msg-form").on("submit", function(event){
      event.preventDefault();
      if ($("#msg").val()!=""){
        $.ajax({
          type: "POST",
          url: "http://card-collection-game/chat/send",
          data: { submit:"true", chat_id: parseInt(chat), msg: $("#msg").val() }
        })
        $("#msg").val("");
      }
    });
  });
var timerId = setInterval(function() {
    $.ajax({
      type: "get",
      url: urlstr
    }).done(function( msg ) {
      var d = $(msg).find("table.msg-table");
      $("table.msg-table").replaceWith(d);
  });
}, 1000);
