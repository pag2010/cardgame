import React from "react";
import ReactDOM from "react-dom";
import './styles.css';

var page=0;
var count=10;
var jsondata;
var urlstr;
var posc;
var mainUrl;

$(function(){
    urlstr=document.URL;
    posc=urlstr.indexOf("auction");
    mainUrl=urlstr.slice(0, posc);
    updateAuction();
});

function updateAuction(){
  $.ajax({
    type: "POST",
    async: true,
    url: mainUrl+"auction/",
    data: { submit:true },
    success: function(data) {
      jsondata=JSON.parse(data);
      //alert(data);
      
      ReactDOM.render(
        <Auction/>, 
        document.getElementById("root")
        );
        $(".price_up").on("click", function(){
          var id = $(this).attr("id");
          var price = $('#price'+id).val().length>=1 ? $('#price'+id).val() : $('#price'+id).attr("placeholder");
          changePrice(id, price);
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
}

function changePrice(id, price){
  $.ajax({
    type: "POST",
    async: true,
    url: mainUrl+"auction/changePrice",
    data: { "submit":true, "id":id, "new_price":price },
    success: function(data) {

      alert(data);
      
     /* ReactDOM.render(
        <Auction/>, 
        document.getElementById("root")
        );
        $(".price_up").on("click", function(){
          var id = $(this).attr("id");
          alert(id);
      });*/
   },
   error: function(jqXHR, exception) {
     var errmes;
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
      console.log("Error occurred ! "+errmes);
  }
})
}

class Auction_Card extends React.Component {
  constructor(props) {
    super(props);
  }  
  render() {
    return (
      <div>
        <div>id аукционного элемента:{this.props.id}</div>
        <div>Название карты:{this.props.card_id}</div>
        <div>Продавец:{this.props.seller}</div>
        <div>Дата начала аукциона:{this.props.start_date}</div>
        <div>Дата конца аукциона:{this.props.sell_date}</div>
        <div>Текущий покупатель:{this.props.buyer}</div>
        <div>Цена:{this.props.price}</div>
        <button id={this.props.id} className="price_up">Поднять ставку</button>
        <input id={"price"+this.props.id} type="number" name="price" placeholder={this.props.price+1}/>
        <br/>
        <br/>
      </div>
    );
  }
}

class Auction extends React.Component {
    constructor(props) {
      super(props);
    }  
    render() {
      let cards=[];
      for (var i=0; i<jsondata.length; i++){
        cards.push(<Auction_Card id={jsondata[i].id} card_id={jsondata[i].card.title} price={jsondata[i].sell_price==null ? jsondata[i].start_price : jsondata[i].sell_price} seller={jsondata[i].seller} buyer={jsondata[i].buyer} start_date={jsondata[i].start_date} sell_date={jsondata[i].sell_date}></Auction_Card>);
      }
      //alert(jsondata[0].id);
      return (
        <div>
          <div>{cards}</div>
        </div>
      );
    }
  }

