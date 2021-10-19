<?php
    require_once 'accountModel.php';
    if (isset($_POST['pseudo'],$_POST['pass'],$_POST['adr'],$_POST['desc'],$_POST['mail'],$_POST['name'],$_POST['firstName'],$_POST['phone'])){
        $pseudo = $_POST['pseudo'];
        $pass = $_POST['pass'];
        $adr = $_POST['adr'];
        $desc = $_POST['desc'];
        $mail = $_POST['mail'];
        $name = $_POST['name'];
        $firstName = $_POST['firstName'];
        $phone = $_POST['phone'];

        if(_createAccount($pseudo, $pass, $adr, $desc, $mail, $name, $firstName, $phone)){
            print json_encode(["result" => true]);
        } else {
            print json_encode(["result" => false]);
        }

    }
?>