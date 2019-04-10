<form name="card_create" method="POST">
    <table>
        <tr>
            <td>Название карты</td><td><input required name="title" type="text"></td>
        </tr>
        <tr>
            <td>Описание</td><td><input required name="description" type="text"></td>
        </tr>
        <tr>
            <td>Редкость</td><td>
                <select required name="rarity">
                    <?php
                        foreach ($data['rarity'] as $opt){
                            echo "<option value='".$opt."'>".$opt."</option>";
                        }
                    ?>
                </select></td>
        </tr>
        <tr>
            <td>Мана</td><td><input required name="mana_cost" type="text"></td>
        </tr>
        <tr>
            <td>Жизни</td><td><input required name="life" type="text"></td>
        </tr>
        <tr>
            <td>Атака</td><td><input required name="attack" type="text"></td>
        </tr>
        <tr>
            <td>Тип</td><td>
                <select required name="kind">
                    <?php
                        for ($i=0; $i<count($data['kind']['id']); $i++){
                            echo "<option value='".$data['kind']['id'][$i]."'>".$data['kind']['title'][$i]."</option>";
                        }
                        /*foreach ($data["kind"] as $opt){
                            echo "<option value='".$opt['id']."'>".$opt['title']."</option>";
                        }*/
                    ?>
                </select></td></td>
        </tr>
        <tr>
            <td><input name="submit" type="submit" value="Создать карту"></td>
        </tr>
    </table>
</form>