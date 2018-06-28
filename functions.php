<?php

function location($path)
{
    header("Location: $path.php");
    die;
}

function not_found_404()
{
    echo '<h3 style="color: red">Данные не загружены!</h3><br />';
    echo "<a href='list.php'><button>Перейти к списку тестов</button></a>";
}

function forbidden()                    //<---запрет доступа без авторизации
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    echo '<h3 style="color: red">Ай-ай-ай! Нужно зарегистрироваться!</h3><br />';
    exit;
}

function not_autorized($guestName)      //<---вход под учетной записью гостя
{
    $_SESSION['autorized'] = false;
    $_SESSION['name'] = $guestName;
    location('list');
}

function get_admins()                   //<---распаковка сведений об администраторах
{
    $path = __DIR__ . '/admin.json';
    $list = file_get_contents($path);
    $data = json_decode($list, true);
    return $data;
}

function autorization($login, $password)
{
    $admins = get_admins();
    foreach ($admins as $admin) {
        if ($admin['login'] == $login && $admin['password'] == $password) {
            if  (isset($_POST['numeric']) && (int)$_POST['numeric'] !== $_SESSION['code']) {    //<---неверная капча
                break;
            } else {
                $_SESSION['autorized'] = true;              //<---вход под учетной записью администратора
                $_SESSION['name'] = $admin['name'];
                location('list');
            }
        } elseif (!isset($_SESSION['wrongdata'])) {      //<---неверное сочетание логина и пароля
            $_SESSION['wrongdata'] = true;
        } elseif ($_SESSION['wrongdata'] <= 1) {
            $_SESSION['wrongdata'] += 1;
        } elseif (!isset($_SESSION['captcha'])) {      //<---неверное сочетание более 3 раз = капча
            $_SESSION['captcha'] = true;
        } elseif ($_SESSION['captcha'] <= 1) {
            $_SESSION['captcha'] += 1;
        } else {
            timeout();            //<---неверная капча более 2 раз = функция блокировки
        }
    }
}

function timeout()                      //<---блокировка на 3 секунды (для удобства тестирования)
{
    $_SESSION['timeout'] = time() + 3;
}

if (!empty($_POST['logout'])) {         //<---выход из учетной записи
    session_start();
    session_destroy();
    location('index');
}

if (!empty($_POST['right']) && !empty($_POST['answer'])) {    //<---создание серификата
    session_start();
    if ($_POST['answer'] == $_POST['right']) {
        $evaluation = "Высокоинтеллектульный человек, или просто везунчик.";
    } else {
        $evaluation = "Бесконечно творческая натура, или экспериментатор.";
    }

    $question = $_SESSION['test']['question'];
    $user = $_SESSION['name'];
    $length = 1730 - strlen($user) * 27;
    $img = imagecreatefrompng("img/netology-certificate.png");
    $ColorBlue = imagecolorallocate($img, 0, 188, 255);
    $ColorGray = imagecolorallocate($img, 50, 85, 105);
    $fontMontserrat = __DIR__.'/fonts/Montserrat-Regular.ttf';
    $fontRoboto = __DIR__.'/fonts/RobotoSlab-Regular.ttf';
    imagettftext($img, 130, 0, $length, 1200, $ColorBlue, $fontMontserrat, $user);
    imagettftext($img, 50, 0, 1330, 1440, $ColorGray, $fontRoboto, $question);
    imagettftext($img, 40, 0, 1000, 1670, $ColorGray, $fontRoboto, $evaluation);

    header('Content-Type: image/png');
    imagepng($img);
    imagedestroy($img);
}

if (!empty($_POST['right']) && empty($_POST['answer'])) {   //<---отсутсвие ответа на тест
    session_start();
    echo '<h3 style="color: red">Нужно определиться с ответом!</h3><br />';
    $question = $_SESSION['test']['question'];
    print <<<HTML
        <form action="test.php" method="POST">
             <input type="hidden" name="question" value="$question">
             <input type="submit" name="return" value="Вернуться к выбору ответа">
        </form>
HTML;
    exit;
}