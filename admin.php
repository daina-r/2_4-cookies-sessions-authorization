<?php
session_start();
require_once 'functions.php';

$dir = 'tests';
$files = array_diff( scandir( $dir), array('..', '.'));


if (empty($_SESSION['name']) || $_SESSION['autorized'] == false) {
    forbidden();                                            //<---запрет доступа без авторизации
}

if (!empty($_FILES["json"])) {                              //<---проверка на наличие и тип файла
    $validation = pathinfo($_FILES["json"]["name"]);
    if (isset($validation['extension']) && $validation['extension'] === 'json') {   //<---загружен корректный тип файла
        move_uploaded_file($_FILES["json"]["tmp_name"], "tests/".$_FILES["json"]["name"]);
        header("Location: admin.php");
    } elseif (empty($_FILES["json"]["name"])) {            //<---файл не выбран
        $notice = "Вы не выбрали файл!";
    } else {                                               //<---загружен НЕКОРРЕКТНЫЙ тип файла
        $notice = "Можно загрузить только файл в формате .json";
    }
}

if (!empty($_POST["test"]) && !empty($_POST["delete"])) {  //<---удаление файла
    unlink("tests/".$_POST["test"]);
    header("Location: admin.php");
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Страница загрузки файлов с тестами</title>
</head>
<body>

  	<h3>Вы можете добавить файл с тестом на сервер</h3>
    <p>(поддерживаются только файлы с расширением .json)</p><br>

    <h3 style="color: red"><?php echo (isset($notice)) ? $notice : ''; ?></h3>

    <h3>Пример структуры файла test_1.json:</h3>
    <pre>
  {
  "question": "СКОЛЬКО ДНЕЙ В ГОДУ?",
  "answers": [
    1024,
    512,
    365,
    256
  ],
  "correct": 365
  }
    </pre>
                                                    <!---форма загрузки файлов-->
  	<form method="POST" enctype="multipart/form-data">
        <input type="file" name="json">
        <input type="reset" value="Очистить">
        <input type="submit" value="Отправить"><br/><br/><br/>
    </form>

    <h3>Список загруженных тестов:</h3>
        <?php foreach ($files as $key => $test) {
            $num = $key - 1;
            echo "$num. $test &emsp;"; ?>
                                                      <!---кнопка удаления файла с тестом-->
                <form method="POST" style="display: inline-block">
                    <input type="hidden" name="test" value="<?php echo $test; ?>">
                    <input type="submit" name="delete" value="удалить" style="color: red">
                </form><br>
            <?php echo '<br />';
        } ?>
                                                      <!---ссылка на раздел тестирования-->
    <a href="list.php"><button>Перейти к тестам</button></a><br><br><br><br>

    <form action="functions.php" method="POST">       <!---кнопка завершения сессии-->
        <input type="submit" name="logout" value="Выход из системы">
    </form>
</body>
</html>