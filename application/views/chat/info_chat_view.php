<p>Страничка чата фронт</p>
<a href="/chat/create">Создать новый чат</a>
<form name="Chats" method="Get">
<?php
    echo "<div class='chats'>";
    echo "<table>";
    for($i=0; $i<count($data['chats']); $i++){
        if ($data['login']!=$data['login1'][$i]){
            echo "<tr><td>".$data['login1'][$i]."</td><td>:</td><td><input name='open_chat' class='button' type='submit' value='".$data['chats'][$i]."'></td><td>".$data['new']."</td></tr>";
        }else{
            echo "<tr><td>".$data['login2'][$i]."</td><td>:</td><td><input name='open_chat' class='button' type='submit' value='".$data['chats'][$i]."'></td></tr>";
        }
    }
    echo "</table>";
    echo "</div>";
    echo '</form>';
?>    