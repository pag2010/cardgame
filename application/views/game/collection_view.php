<?php
echo 'Коллекция игрока '.$_SESSION['login'].'</br>';
foreach ($data as $title) {
    echo "<div class='column'>";
        echo "<div class='card'>";
            echo "<h3>".$title."</h3>";
            echo "<p>Some text</p>";
        echo "</div>";
    echo "</div>";
}
?>