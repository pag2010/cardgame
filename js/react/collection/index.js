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
  console.log("Error occurred ! "+jqXHR.status);
}

$(function(){
  urlstr=document.URL;
  posc=urlstr.indexOf("/collection");
  mainUrl=urlstr.slice(0, posc+1);
    $.ajax({
    type: "GET",
    async: true,
    url: mainUrl+"collection",
    data: { page: page, count: count },
    success: function(data) {
      jsondata=JSON.parse(data);
      //alert(data);
      ReactDOM.render(
        <Collection />, 
        document.getElementById("root")
        );
   },
   error: function(jqXHR, exception) {
      errFunc(jqXHR, exception);
   }
})
});

class Card extends React.Component {
  constructor(props) {
    super(props);
  }  
  render() {
    return (
      <div>
        <div>id карты:{this.props.id}</div>
        <div>Мана:{this.props.mana_cost}</div>
        <div>Название карты:{this.props.title}</div>
        <div>Описание:{this.props.description}</div>
        <div>Жизнь:{this.props.life}</div>
        <div>Атака:{this.props.attack}</div>
        <div>Редкость:{this.props.rarity}</div>
        <div>Количество:{this.props.quantity}</div>
        <br/>
      </div>
    );
  }
}

class Collection extends React.Component {
  constructor(props) {
    super(props);
  }  
  render() {
    let cards=[];
    for (var i=0; i<jsondata.length; i++){
      cards.push(<Card id={jsondata[i].id} title={jsondata[i].title} description={jsondata[i].description} quantity={jsondata[i].quantity} life={jsondata[i].life} attack={jsondata[i].attack} mana_cost={jsondata[i].mana_cost} rarity={jsondata[i].rarity_title}></Card>);
    }
    //alert(jsondata[0].id);
    return (
      <div>
        <div>{cards}</div>
      </div>
    );
  }
}

