<?php
require_once 'core.php';
if (!isAuthorized()) {
    header('HTTP/1.1 403 Forbidden');
    echo '<h3>Доступ запрещен!</h3>';
    exit;
}
if (!empty($_SESSION['login'])) {
    echo "<h3>Вы вошли как зарегистрированный пользователь!</h3>";
}
else {
    echo "<h3>Вы вошли как гость!</h3>";
}
$dir = 'json_tests/';
$listTest = scandir($dir);
unset($listTest[0], $listTest[1]);
foreach ($listTest as $value) {
    $testList[] = $value;
}
if(isset($testList)) {
    echo '<b>Список файлов в директории:</b>';
    echo '<br/>';
    foreach ($testList as $key => $value) {
        $num = $key + 1;
        echo "{$num}. {$value}";
        echo '<br/>';
    }
}
echo '<br/>';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>list.php</title>
</head>
<body>
<?php if (!empty($_SESSION['login'])) { ?>
    <form action="redirect.php" method="POST" enctype="multipart/form-data">
        <label><b>Добавить тест:</b> <input type="file" name="json"></label>
        <input type="reset" value="Очистить">
        <input type="submit" value="Отправить"><br/><br/>
    </form>
<?php } ?>

<?php if(isset($testList)) { ?>
    <h4>Выбрать тест:</h4>
    <form action="test.php" method="POST">
        <fieldset>
            <legend><?php echo 'Тест №..' ?></legend>
            <?php foreach ($testList as $key => $value) {?>
                <label><input type="radio" name="test" value="<?php echo $value; ?>"><?php echo ($key + 1); ?></label>
            <?php } ?>
        </fieldset><br>
        <?php if (!empty($_SESSION['login'])) { ?>
            <label>Удалить тест! <input type="checkbox" name="delete"></label>
        <?php } ?>
        <input type="hidden" name="list">
        <input type="reset" value="Очистить">
        <input type="submit" value="Отправить">
    </form>
<?php } ?><br><br>
<a href="index.php"><button>Выход из системы</button></a>
</body>
</html>