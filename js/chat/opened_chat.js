var url;
$(function(){
  urlstr=document.URL;
  $(".chat-history").scrollTop($(".chat-history")[0].scrollHeight);
})

var timerId = setInterval(function() {
    $.ajax({
      type: "get",
      url: urlstr
    }).done(function( msg ) {
      var d = $(msg).find("table.msg-table");
      $("table.msg-table").replaceWith(d);
  });
}, 1000);
    