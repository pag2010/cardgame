import React from "react";
import ReactDOM from "react-dom";
import './styles.css';

var page=0;
var count=10;

$(function(){
  var urlstr=document.URL;
  var posc=urlstr.indexOf("friends");
  var mainUrl=urlstr.slice(0, posc);
  /*$.get(
    mainUrl+"friends/list",
    {
      page: page,
      count: count
    },
    function(data){
      alert(data);
    }
  )*/
    $.ajax({
    type: "GET",
    async: true,
    url: mainUrl+"friends/list",
    data: { friends: true, players:true, subscribers:true, page: page, count: count },
    success: function(data) {
      alert(data);
   },
   error: function(jqXHR, exception) {
      if (jqXHR.status === 0) {
        errmes = 'Not connect.\n Verify Network.';
    } else if (jqXHR.status === 401) {
      errmes = 'Неверный логин или пароль';
    }else if (jqXHR.status == 404) {
        errmes = 'Requested page not found. [404]';
    } else if (jqXHR.status == 500) {
        errmes = 'Internal Server Error [500].';
    } else if (exception === 'parsererror') {
        errmes = 'Requested JSON parse failed.';
    } else if (exception === 'timeout') {
        errmes = 'Time out error.';
    } else if (exception === 'abort') {
        errmes = 'Ajax request aborted.';
    } else {
        errmes = 'Uncaught Error.\n' + jqXHR.responseText;
    }
      console.log("Error occurred !");
  }
})
});

class FriendsList extends React.Component {
    constructor(props) {
      super(props);
    }  
    render() {
      return (
        <div>lala</div>
        
      );
    }
  }

ReactDOM.render(
    <FriendsList />, 
    document.getElementById("root")
    );