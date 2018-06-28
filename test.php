<?php
session_start();
require_once 'functions.php';

if (isset($_POST['question']) && isset($_POST['return'])) {   //<---возврат при отсутствии ответа
    $_SESSION['test']['question'] = $_POST['question'];
} elseif (isset($_POST['file'])) {
    $content = file_get_contents('tests/'.$_POST['file']);  //<---распаковка вариантов ответа
    $test = json_decode($content,true);
    $_SESSION['test'] = $test;
} elseif (empty($_POST)) {                //<---тест не выбран
    not_found_404();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Тест</title>
</head>
<body>
  <?php if(isset($_SESSION['test'])) { ?>
    <h3>Выберите правильный ответ:</h3>
    <form action="functions.php" method="POST">
        <fieldset>
            <legend><?php echo $_SESSION['test']['question']; ?></legend>
                <?php foreach ($_SESSION['test']['answers'] as $answer) { ?>    <!---вывод вариантов ответа-->
                    <label>
                        <input type="radio" name="answer" value="<?php echo $answer; ?>"><?php echo $answer; ?>
                    </label>
                <?php } ?>
        </fieldset><br />
        <input type="hidden" name="right" value="<?php echo $test['correct']; ?>">
        <input type="submit" value="Ответить">
    </form>
  <?php } ?>
</body>
</html>