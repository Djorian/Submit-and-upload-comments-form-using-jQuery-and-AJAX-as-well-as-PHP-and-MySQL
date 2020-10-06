<?php

if(isset($_POST['show-comments'])){

    function __autoload($class) {
        include "../Classes/$class.php";
    }

    $mysql = new Database();

    $result = $mysql->select('id, name, email, comment', 'comments', '', '', 'id DESC', '');

    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}