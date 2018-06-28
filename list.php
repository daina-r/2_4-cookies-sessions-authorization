<?php
session_start();
require_once 'functions.php';

$dir = 'tests';
$files = array_diff( scandir( $dir), array('..', '.'));


if (empty($_SESSION['name'])) {              //<---запрет доступа без регистрации
    forbidden();
} elseif ($_SESSION['autorized'] == true) {  //<---доступ по авторизации
    $autorized = $_SESSION['autorized'];
    $account = 'администратор';
} else {
    $account = 'гость';    //<---доступ по регистрации без авторизации
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Страница выбора тестов</title>
</head>
<body>
    <?php echo "<h3>Здравствуйте, ".$_SESSION['name']."! (статус: ".$account.")</h3>";

    if(isset($files)) { ?>          <!---вывод формы выбора теста-->
        <h3>Выберите тест</h3>
        <form action="test.php" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>Тест №..</legend>
                    <?php foreach ($files as $key => $file) {
                        $num = $key - 1;
                        $questio = file_get_contents('tests/'.$file);
                        $quest = json_decode($questio, true); ?>
                        <label>
                            <input type="radio" name="file" value="<?php echo $file; ?>">
                            <?php echo "$num. "; echo $quest['question']; ?><br />
                        </label>
                    <?php } ?>
            </fieldset><br>
            <input type="submit" value="Выбрать">
        </form><br><br>
    <?php }

    if (isset($autorized)) { ?>                             <!---ссылка на административный раздел-->
        <a href='admin.php'><button>Управление базой тестов</button></a><br><br>
    <?php } ?>

        <form action="functions.php" method="POST">         <!---кнопка завершения сессии-->
            <input type="submit" name="logout" value="Выход из системы">
        </form>
</body>
</html>