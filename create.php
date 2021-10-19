<?php

    //include ('functions\accountModel.php');

    if (isset($_POST['pseudo'],$_POST['pass'],$_POST['adr'],$_POST['desc'],$_POST['mail'],$_POST['name'],$_POST['firstName'],$_POST['phone'])){

        $pseudo = $_POST['pseudo'];
        $pass = $_POST['pass'];
        $adr = $_POST['adr'];
        $desc = $_POST['desc'];
        $mail = $_POST['mail'];
        $name = $_POST['name'];
        $firstName = $_POST['firstName'];
        $phone = $_POST['phone'];
        /*
        $isSuccess = _createAccount($pseudo, $pass, $adr, $desc, $mail, $name, $firstName, $phone);

        
        if($isSuccess){
            print("<p style='background-color:green;'>Success</p>");
        }
        else{
            print("<p style='background-color:red;'>Something went wrong</p>");
        }
        */
    }

?>
<!DOCTYPE html>
    <head>
        <title>Forum Secu</title>
        <link rel="stylesheet" type="text/css" href="styles.css"/>
        <link rel="stylesheet" type="text/css" href="log.css"/>
    </head>

    <body>
        <form action="functions/createAccountController.php" method="post" style="display:flex; justify-content:space-around; align-items:left;flex-direction:column">
            <img src="img/icon_account.png" width="20%" alt="Se connecter"/>
            <p>login : <input type="text" name="pseudo" /></p>
            <p>password : <input type="password" name="pass" /></p>
            <p>mail : <input type="text" name="mail" /></p>
            <p>phone : <input type="text" name="phone" /></p>
            <p>adress : <input type="text" name="adr" /></p>
            <p>firstName : <input type="text" name="firstName" /></p>
            <p>Name : <input type="text" name="name" /></p>
            <p>biography: <input type="text" name="desc" /></p>
            <p><input type="submit" value="OK"/></p>
        </form>
    </body>
</html> 