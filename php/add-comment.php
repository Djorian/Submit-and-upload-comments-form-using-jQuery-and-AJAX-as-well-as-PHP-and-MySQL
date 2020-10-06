<?php

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['comment'])) {
    if(empty($_POST['name'])   ||
        empty($_POST['email'])     ||
        empty($_POST['comment'])   ||
        !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
    {
        echo 2;
        exit;
    } else {

        function __autoload($class) {
            include "../Classes/$class.php";
        }

        $name = (string) trim(strip_tags(htmlspecialchars($_POST['name'])));
        $email = (string) trim(strip_tags(htmlspecialchars($_POST['email'])));
        $comment = (string) trim(strip_tags(htmlspecialchars($_POST['comment'])));


        $mysql = new Database();

        $result = $mysql->insert('comments', array('name'=>$name,'email'=>$email, 'comment'=>$comment));

        echo $result;
    }
}