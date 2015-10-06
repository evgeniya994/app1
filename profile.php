<?
require_once "functions.php";
//если в сессии нет тек. пользователя, перенаправляем на тсраницу входа.
if (!$_SESSION['userId']){
    header("Location: index.php");
}
$currentUser = getUserById($_SESSION['userId']);
$userAddress = getUserFullAddress($currentUser['id_address']);
echo '<pre>';
print_r($_SESSION);
print_r($currentUser);
print_r($userAddress);
die;
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>

</body>
</html>