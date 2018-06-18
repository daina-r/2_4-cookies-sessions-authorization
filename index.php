<?php
require_once 'core.php';
// var_dump($_REQUEST);
if (isPOST()) {
    if (login(getParam('login'), getParam('password'))) {
        location('list');
    }
    if (!empty($_POST['name'])) {
        $_SESSION = $_POST;
        location('list');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Вход</title>
</head>
<body>
<form method="POST">
    <p><b>Войти как зарегистрированный пользователь:</b></p>
    <label>Логин: <input name="login" id="login"></label>
    <label>Пароль: <input name="password" id="password"></label>
    <p><b>Войти как гость:</b></p>
    <label>Имя: <input name="name" id="name"></label><br><br>
    <button type="submit">Вход в систему</button>
</form><br>
<a href="logout.php"><button>Выход из системы</button></a>
</body>
</html>