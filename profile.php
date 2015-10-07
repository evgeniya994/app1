<?php
session_start();
require_once "functions.php";
require_once "user_functions.php";

//Получить данные с таблицы Users
$users = getUsers();//functions.php


//Вывод данных из БД в текстовые поля..(не додумала)
foreach($users as $row) {
    $id_users=$row["id_users"];

}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
//Получить данные формы
$post = getUserPostData();


//Проверка введеных данных.
$check = checkUserForm($post);
if ($check !== true) {//Где-то ошибка
    //Обработка ошибки.
    $error = $check;
} else {
    //3. Сохранить в БД
    $res = saveUser($post);
    if (is_numeric($res)) {//Сохранилось?
        $_SESSION['userId'] = $res;
        header("Location: products.php");
        die;
    } else {
        $error = $res;
    }
}
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<div class="form">
    <form action="profile.php" method="POST">

        <p><label for="fio">Ф.И.О<input type="text" name="fio" id="fio" value=".$row['fio']."></label></p>
        <p><label for="email">Email<input type="text" name="email" id="email" value=".$row['email']."></label></p>
        <p><label for="phone">Телефон<input type="text" name="phone" id="phone" value=".$row['phone']."></label></p>

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

        <p><label for="houseNum">Дом</label><input type="text" name="houseNum" value=".$row['houseNum']."></p>
        <p><label for="kv">Квартира</label><input type="text" name="kv" value=".$row['kv']."></p>


        <p><label for="login">Login<input type="text" id="login" name="login" value=".$row['login']."></label></p>

        <p><label for="password">Пароль<input type="text" id="password" name="password" value=".$row['password']."></label></p>

        <p><input type="submit" value="Изменить"></p>
    </form>
</div>

</body>
</html>