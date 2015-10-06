<?php
require_once "functions.php";

$error = "";

//если метод запроса POST
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //1. получить данные формы
    $post = getUserPostData();
    //2. проверка введеных данных.
    $check = checkUserForm($post);
    if ($check !== true) {//где-то ошибка
        //тут обработка ошибки.
        $error = $check;
    } else {
        //3. Сохранить в базу
        $res = saveUser($post);
        if (is_numeric($res)) {//сохранилось?
            $_SESSION['userId'] = $res;
            header("Location: profile.php");
            die;
        } else {
            $error = $res;
        }
    }
} else {//метод запроса НЕ POST (значит GET)
    //показать форму.
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<?php
if ($error){//если есть ошибка
    echo "<h3>{$error}</h3>";
}
?>
<div class="form">
    <form action="reg.php" method="POST">

        <p><label for="fio">Ф.И.О<input type="text" name="fio" id="fio"></label></p>
        <p><label for="email">Email<input type="text" name="email" id="email"></label></p>
        <p><label for="phone">Телефон<input type="text" name="phone" id="phone"></label></p>
        <p><label for="id_status">Статус</label>
            <select name="id_status">
                <?
                $statuses = getStatuses();
                foreach($statuses as $row) {
                    echo '<option value='.$row["id_status"].'>'.$row['name_status'].'</option>';
                }
                ?>
            </select>
        </p>

        <p><label>Адрес</label></p>

        <p><label id="id_city">Город</label>
            <select name="id_city">
                <?
                $cities = getCities();
                foreach($cities as $row){
                    echo '<option value='.$row["id_city"].'>'.$row['name_city'].'</option>';
                }
                ?>
            </select>
        </p>

        <p><label id="id_street">Улица</label>
            <select name="id_street">
                <?
                $streets = getStreets();
                foreach($streets as $row) {
                    echo '<option value='.$row["id_street"].'>'.$row['name_street'].'</option>';
                }
                ?>
            </select>
        </p>

        <p><label for="houseNum">Дом</label><input type="text" name="houseNum"></p>
        <p><label for="kv">Квартира</label><input type="text" name="kv"></p>


        <p><label for="login">Login<input type="text" id="login" name="login"></label></p>

        <p><label for="password">Пароль<input type="text" id="password" name="password"></label></p>

        <p><label for="confirm_password">Повторный пароль<input type="text" id="confirm_password" name="confirm_password"></label></p>
        <p><input type="submit" value="Отправить"></p>
    </form>
</div>
</body>
</html>