<p>Страничка чата фронт</p>
<a href="/chat/create">Создать новый чат</a>
<form name="Chats" method="Get">
<?php
    echo "<div class='chats'>";
    echo "<table>";
    if (isset($data['chats'])){
    for($i=0; $i<count($data['chats']); $i++){
        if ($data['login']!=$data['login1'][$i]){
            echo "<tr><td>".$data['login1'][$i]." (".$data['unRead'][$i].")</td><td>:</td><td><input name='open_chat' class='button' type='submit' value='".$data['chats'][$i]."'></td></tr>";
        }else{
            echo "<tr><td>".$data['login2'][$i]." (".$data['unRead'][$i].")</td><td>:</td><td><input name='open_chat' class='button' type='submit' value='".$data['chats'][$i]."'></td></tr>";
        }
    }
}
    echo "</table>";
    echo "</div>";
    echo '</form>';
?>    

<!--<td>".$data['new']."</td>-->