import React from "react";
import ReactDOM from "react-dom";
import './styles.css';

var page=0;
var count=10;
var jsondata;
var urlstr;
var posc;
var mainUrl;
var errmes;
var errChat;

function errFunc(jqXHR, exception) {
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

$(function(){
  urlstr=document.URL;
  posc=urlstr.indexOf("friends");
  mainUrl=urlstr.slice(0, posc);

    $.ajax({
    type: "GET",
    async: true,
    url: mainUrl+"friends/list",
    data: { friends: true, players:true, subscribers:true, page: page, count: count },
    success: function(data) {
      jsondata=JSON.parse(data);
      //alert(data);
      ReactDOM.render(
        <FriendsList />, 
        document.getElementById("root")
        );
        $(".Msg").on("click", function(){
          var chat_id = $(this).attr("id");
          //var chat_id =1;
          if (chat_id !=null){
            $(location).attr('href',"/chat?open_chat="+chat_id);
        }
      });
        $(".Add").on("click", function(){
          var user = $(this).attr("id");
          $.ajax({
            type: "POST",
            async: true,
            url: mainUrl+"friends/add",
            data: { login: user, submit:true},
            success: function(data){
                alert("Пользователь теперь у вас в друзьях!");
            },
          error: function(jqXHR, exception) {
            errFunc(jqXHR, exception);
          }
        })
      });

      $(".Del").on("click", function(){
        var id = $(this).attr("id");
        var user=id.slice(3, id.length);
        $.ajax({
          type: "POST",
          async: true,
          url: mainUrl+"friends/del",
          data: { login: user, submit:true},
          success: function(data){
              alert("Пользователь удалён из списка");
          },
        error: function(jqXHR, exception) {
          errFunc(jqXHR, exception);
        }
      })
    });

      $(".CrChat").on("click", function(){
        var id = $(this).attr("id");
        var user=id.slice(6, id.length);
        var chat_data;
        $.ajax({
          type: "POST",
          async: true,
          url: mainUrl+"chat/create",
          data: { login: user, submit:true},
          success: function(data){
            $.ajax({
              type: "GET",
              async: false,
              url: mainUrl+"chat/getChat",
              data: { login: user},
              success: function(data) {
                errChat=false;
                alert(data);
                chat_data=JSON.parse(data);
            },
            error: function(jqXHR, exception) {
              errFunc(jqXHR, exception);
              errChat=true;
              alert("error");
            }
          })
            if (!errChat){
              $(location).attr('href',"/chat?open_chat="+chat_data.chat_id);
            }
          },
        error: function(jqXHR, exception) {
          errFunc(jqXHR, exception);
        }
      })
    });
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

class SendMsg extends React.Component {
  constructor(props) {
    super(props);
  }  
  render() {
      var chat;
      var chat_data;
      $.ajax({
        type: "GET",
        async: false,
        url: mainUrl+"chat/getChat",
        data: { login: this.props.login},
        success: function(data) {
          errChat=false;
          //alert(data);
          chat_data=JSON.parse(data);
      },
      error: function(jqXHR, exception) {
        errFunc(jqXHR, exception);
        errChat=true;
      }
    })
    if (errChat) {
      return (
        <button id={"CrChat"+this.props.login} className="CrChat">Создать чат</button>
      );
    }else{
      return (
        <button id={chat_data.chat_id} className="Msg">Написать сообщение</button>
      );
    }
  }
}

class Friend extends React.Component {
  constructor(props) {
    super(props);
  }  
  render() {
      return (
        <div>
          <div>Логин: {this.props.login}</div>
          <div>
            <SendMsg login={this.props.login}></SendMsg>
            <button id={"del"+this.props.login} className="Del">Удалить</button>
          </div>
          <br/>
        </div>
      );
  }
}

class Subscriber extends React.Component {
  constructor(props) {
    super(props);
  }  
  render() {
      return (
        <div>
          <div>Логин: {this.props.login}</div>
          <button id={this.props.login} className="Add">Добавить в друзья</button>
          <br/>
        </div>
      );
  }
}

class Player extends React.Component {
  constructor(props) {
    super(props);
  }  
  render() {
      return (
        <div>
          <div>Логин: {this.props.login}</div>
          <button id={this.props.login} className="Del">Отписаться</button>
          <br/>
        </div>
      );
  }
}

class FriendsList extends React.Component {
    constructor(props) {
      super(props);
    }  
    render() {
      let friends=[];
      if (jsondata.friends!=null){
        for (var i=0; i<jsondata.friends.length; i++){
          friends.push(<Friend login={jsondata.friends[i]}></Friend>);
        }
    }
      let subscribers=[];
      if (jsondata.subscribers!=null){
        for (var i=0; i<jsondata.subscribers.length; i++){
          var isFriend=false;
          if (jsondata.friends!=null){
            for (var j=0; i<jsondata.friends.length; j++){
              if (jsondata.friends[j]===jsondata.subscribers[i]) {
                  isFriend=true;
                  break;
              }
            }
        }
          if (!isFriend){
            subscribers.push(<Subscriber login={jsondata.subscribers[i]}></Subscriber>);
          }
        }
      }

      let players=[];
      if (jsondata.players!=null){
      for (var i=0; i<jsondata.players.length; i++){
        var isFriend=false;
        if (jsondata.friends!=null){
        for (var j=0; i<jsondata.friends.length; j++){
          if (jsondata.friends[j]===jsondata.players[i]) {
              isFriend=true;
              break;
          }
        }
      }
          if (!isFriend){
            players.push(<Player login={jsondata.players[i]}></Player>);
          }
      }
    }
      return (
        <div>
          <div>
            <p>Друзья</p>
            <div>{friends}</div>
          </div>
          <hr/>
          <div>
            <p>Заявки в друзья</p>
            <div>{subscribers}</div>
          </div>
          <hr/>
          <div>
            <p>Ваши заявки</p>
          < div>{players}</div>
          </div>
        </div>
      );
    }
  }

