<?php
    echo "<div class='chat-history'>";
    if (isset($data['sender'])){
    if (count($data['sender'])>0){
        
            
        echo "<table class='msg-table'>";
       
        for ($i=0;$i<count($data['sender']);$i++){
            echo "<tr>";
            echo "<td class='login'>".$data['sender'][$i]."<td class='dots'>:</td></td>"."<td class='msg'>".$data['message'][$i]."</td><td class='time'>11:40 11.04.18</td>";
            echo "</tr>";
        }
        echo "</table>";
    }else{
        echo "У вас пока нет сообщений.";
    }
    echo "</div>";
}else{
    echo "<div class='chat-history'>";
    echo "<table class='msg-table'>";
    echo "</table>";
    echo "У вас пока нет сообщений.";
    echo "</div>";
   
}
?>
<form id="msg-form" class='input-form' name="make_msg" method="post">
<table id="input-table" class="input-table">
    <tr>
    <td><textarea id="msg" class='input-msg' placeholder='Напишите сообщение' cols="82" rows="4"></textarea></td>   
    <td><input id="submit" class='button' name="submit" type="button" value="Отправить"></td>
    </tr>
</table>
</form>