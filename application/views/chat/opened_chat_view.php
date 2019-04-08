<?php
    echo "<div class='chat-history'>";
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
?>
<form class='input-form' name="make_msg" onsubmit="return onSubmitClick()" method="POST">
<table>
    <tr>
    <td><input autocomplete='off' class='input-msg' required name="msg" type="text" placeholder='Напишите сообщение'></td>
    <td><input class='button' name="submit" type="submit" value="Отправить"></td>
    </tr>
</table>
</form>