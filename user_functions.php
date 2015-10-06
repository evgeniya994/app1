<?php
function saveUser(array $data)
{
    global $handle;
    $handle->autocommit(false);
    //начало транзакции
    $handle->begin_transaction();

    $query = "INSERT INTO addresses (
              `id_city`,
              `id_street`,
              `houseNum`,
              `kv`
              )
              VALUES (
                {$data['id_city']},
                {$data['id_street']},
                '{$data['houseNum']}',
                '{$data['kv']}'
              )";
    $result = $handle->query($query);
    if ($result === false) {
        //откат изменений
        $handle->rollback();
        return "Не удалось сохранить адрес.";
    }
    $id_address = $handle->insert_id;//получаем ID сохраненного только что адреса.
    $query = "INSERT INTO users (
              `fio`,
              `email`,
              `id_status`,
              `phone`,
              `login`,
              `password`,
              `id_address`
              )
              VALUES (
                '{$data['fio']}',
                '{$data['email']}',
                {$data['id_status']},
                '{$data['phone']}',
                '{$data['login']}',
                '{$data['password']}',
                {$id_address}
              )";
    $result = $handle->query($query);
    if ($result === true) {
        //если сохранился успешно, возвращаем ID
        //return $handle->insert_id;
        $userId = $handle->insert_id;
        //применение изменений.
        $handle->commit();
        return $userId;
    } else {
        //откат изменений
        $handle->rollback();
        return "Не удалось сохранить пользователя.";
    }
}

function getUserPostData()
{
    $data = array();
    $data['fio'] = $_POST['fio'];
    $data['email'] = $_POST['email'];
    $data['phone'] = $_POST['phone'];
    $data['id_status'] = $_POST['id_status'];
    $data['id_city'] = $_POST['id_city'];
    $data['id_street'] = $_POST['id_street'];
    $data['houseNum'] = $_POST['houseNum'];
    $data['kv'] = $_POST['kv'];
    $data['login'] = $_POST['login'];
    $data['password'] = $_POST['password'];
    $data['confirm_password'] = $_POST['confirm_password'];
    return $data;
}

/**
 * Проверка формы регистрации пользователя
 * @param array $post
 * @return bool|string
 */
function checkUserForm(array $post)
{
    if (mb_strlen($post['fio']) < 10) {
        return "ФИО доджно быть не менее 10 символов.";
    }

    if (mb_strlen($post['phone']) < 11) {
        return "Номер телефона должне быть не менее 11 цифр";
    }

    if (mb_strlen($post['login']) < 10) {
        return "Логин должен быть не менее 10 символов";
    }

    if (mb_strlen($post['password']) < 10) {
        return "Пароль должен быть не менее 10 символов";
    }

    if ($post['password'] != $post['confirm_password']) {
        return "Пароли не совпадают";
    }
    $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
    if (preg_match($pattern, $post['email']) !== 1) {
        return "Не правильный адрес почты";
    }

    if (!empty ($print['login']))
    {
        return "Такой login уже существует";
    }


    //Если есть пользователь с такой почтой
    if (!is_null(getUserByEmail($post['email']))) {
        return "Указанная почта \"{$post['email']}\" уже используется другим человеком.";
    }
    //Если есть пользователь с таким логином
    if (!is_null(getUserByLogin($post['login']))) {
        return "Указанный login \"{$post['login']}\" уже используется другим человеком.";
    }
    //проверить остальные поля.

    //если все поля заполнены корректно, функция вернет true
    return true;
}

/**
 * Возвращает пользователя по E-mail или null если такой почты не в бд
 * @param $email
 * @return array|null
 */
function getUserByEmail($email)
{
    global $handle;
    $query = "SELECT *
	       FROM users
	       WHERE email='{$email}'";
    $result = $handle->query($query);
    if ($result->num_rows == 0) {
        return null;
    }
    return $result->fetch_assoc();
}
function getUserByLogin($login)
{
    global $handle;
    $query = "SELECT *
	       FROM users
	       WHERE login='{$login}'";
    $result = $handle->query($query);
    if ($result->num_rows == 0) {
        return null;
    }
    return $result->fetch_assoc();
}
/**
 * Возвращает полный адрес по ID
 * @param $addressId
 * @return array|null
 */
function getUserFullAddress($addressId)
{
    global $handle;
    $sql = "SELECT CONCAT(cities.name_city, ', ', streets.name_street, ', ', houseNum) as 'fullAddress',
cities.name_city, streets.name_street, houseNum, kv
FROM addresses
LEFT JOIN cities ON (addresses.id_city = cities.id_city)
LEFT JOIN streets ON (addresses.id_street = streets.id_street)
WHERE addresses.id_address = {$addressId}";
    $result = $handle->query($sql);
    if ($result->num_rows == 0) {
        return null;
    }
    return $result->fetch_assoc();
}

function getUserById($userId)
{
    global $handle;
    $sql = "SELECT *
FROM users
WHERE id_users = {$userId}";
    $result = $handle->query($sql);
    if ($result->num_rows == 0) {
        return null;
    }
    return $result->fetch_assoc();
}
