<?php
session_start();
require_once 'functions.php';


if (!empty($_POST['guest'])) {
    if (!empty($_POST['login'])) {          //<---запрет одновременного выбора разных типов учетных записей
        $notice = 'Нужно определиться с типом учетной записи!';
    } else {
    not_autorized(htmlspecialchars($_POST['guest'])); //<---вход под учетной записью гостя
    }
} elseif (!empty($_POST['login']) && !empty($_POST['password'])) {   //<---введены логин и пароль
    autorization(htmlspecialchars($_POST['login']), htmlspecialchars($_POST['password']));    //<---функция авторизации

    if (isset($_SESSION['wrongdata'])) {    //<---возврат из ф-ции неверного сочетания логина и пароля
        $notice = 'Введите корректные данные!';
    }
} elseif ($_POST != null) {                 //<---запрет отправки пустой формы
    $notice = 'Введите данные!';
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
        <h3>Войти как зарегистрированный пользователь:</h3>
        <label>Логин: <input name="login"></label>
        <label>Пароль: <input name="password"></label>
        <h3 style="color: red">
            <?php
            if (isset($notice)) {
                echo $notice;
            }
            if (isset($_SESSION['captcha'])) {
                echo '<br><br><img src="captcha.php"><br><br>
                <label>Введите число с картинки: <input name="numeric"></label>';
            }
            if (isset($_SESSION['timeout']) && $_SESSION['timeout'] > time()) {
                $timeout = $_SESSION['timeout'] - time();
                echo "<br><br>Слишком много ошибочных запросов. Подождите немного.<br><br>
                <a href='index.php'><button>Обновить через ".$timeout." секунд</button></a>";
                exit;
            } ?>
        </h3>
        <h3>Войти как гость:</h3>
        <label>Имя: <input name="guest"></label><br><br>
        <button type="submit">Вход в систему</button>
    </form><br>
    <form action="functions.php" method="POST">
        <input type="submit" name="logout" value="Выход из системы">
    </form>
</body>
</html>