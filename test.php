<?php
require_once 'core.php';
if (!isAuthorized()) {
    location('index');
}
if (!empty($_POST['test']) && !empty($_POST['delete'])) {
    unlink('json_tests/'.$_POST['test']);
    location('list');
}
if (!empty($_POST['right']) && !empty($_POST['answer'])) {
    image();
    exit;
}
if (!empty($_POST['right']) && empty($_POST['answer'])) {
    echo '<h3>Вы не заполнили тест!</h3>';
}
if (isset($_POST['test'])) {
    $content = file_get_contents('json_tests/'.$_POST['test']);
    $test = json_decode($content,true);
    if(!isset($test[0]['question'])) {
        header('HTTP/1.1 404 Not Found');
        echo '<h3>Неправильный формат теста!</h3>';
        exit;
    }
}
if (isset($_POST['list']) && empty($_POST['test'])) {
    echo '<h3>Вы не выбрали тест!</h3>';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>test.php</title>
</head>
<body>
<?php if(isset($test[0]['answers'])) { ?>
    <h3>Заполните тест:</h3>
    <form action="test.php" method="POST">
        <fieldset>
            <legend><?php echo $test[0]['question']; ?></legend>
            <?php foreach ($test[0]['answers'] as $key => $value) {?>
                <label><input type="radio" name="answer" value="<?php echo $value; ?>"><?php echo $value; ?></label>
            <?php } ?>
        </fieldset><br>
        <input type="hidden" name="right" value="<?php echo $test[0]['correct']; ?>">
        <!--<input type="text" name="user" placeholder="Ваше имя"><br><br>-->
        <input type="reset" value="Очистить">
        <input type="submit" value="Отправить">
    </form>
<?php } ?>
</body>
</html>