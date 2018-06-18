<?php
function login($login, $password)
{
    $users = getUsers();
    foreach ($users as $user) {
        if ($user['login'] == $login && $user['password'] == $password) {
            unset($user['password']);
            $_SESSION = $user;
            return true;
        }
    }
    return false;
}
function getLoggedUserData()
{
    if (empty($_SESSION)) {
        return null;
    }
    return $_SESSION;
}
function isAuthorized()
{
    return getLoggedUserData() !== null;
}
function getUsers()
{
    $path = __DIR__ . '/admin.json';
    $fileData = file_get_contents($path);
    $data = json_decode($fileData, true);
    if (!$data) {
        return [];
    }
    return $data;
}
function isPOST()
{
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}
function getParam($name)
{
    //!empty($_POST[$name]) ? $_POST[$name] : '';
    return filter_input(INPUT_POST, $name);
}
function location($path)
{
    header("Location: $path.php");
    die;
}
function logout()
{
    session_destroy();
    // location('index');
}
function score()
{
    if ($_POST['answer'] == $_POST['right']) {
        $valuation = 5;
    } else {
        $valuation = 2;
    }
    return $valuation;
}
function image()
{
    $sertificate = 'Сертификат';
    $user = $_SESSION['name'];
    $value = ('Оценка теста: '.score());
    $im = imagecreatefrompng("src/image.png");
    $textColor = imagecolorallocate($im, 66, 74, 154);
    $fontFile = __DIR__.'/src/font.ttf';
    imagettftext($im, 30, 0, 270, 200, $textColor, $fontFile, $sertificate);
    imagettftext($im, 30, 0, 300, 300, $textColor, $fontFile, $user);
    imagettftext($im, 30, 0, 240, 400, $textColor, $fontFile, $value);

    header('Content-Type: image/png');

    imagepng($im);
    imagedestroy($im);
}
