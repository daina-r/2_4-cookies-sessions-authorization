
<?php
require_once 'core.php';
if (isset($_FILES["json"]["tmp_name"]) && !empty($_FILES["json"]["tmp_name"])) {
    // Перемещаем файл из временной директории в конечную
    move_uploaded_file($_FILES["json"]["tmp_name"], "json_tests/".$_FILES["json"]["name"]);
    location('list');
}
if (empty($_FILES["json"]["tmp_name"])) {
    echo '<h3>Вы не выбрали файл для загрузки!<h3>';
}