<?php
    require_once 'functions/utilisateurController.php';
    require_once 'functions/connectionController.php';

    $isConnected = False;
    if(isset($_COOKIE['session'])){
        if(!(session_status() == PHP_SESSION_ACTIVE)){
            session_id($_COOKIE['session']);
            session_start();
        }
    } else {
        session_start();
        $_SESSION['logged'] = False;
    }
    
    /*if(isset($_POST["log"]) & isset($_POST["pass"])){
        $pseudo = $_POST["log"];
        $pass = $_POST["pass"];
        if(connectionControler($pseudo, $pass)){ //ConnectionControler return a echo because it's a api
            $isConnected = True;
        }
    }*/
    
?>

<!DOCTYPE html>
    <head>
        <title>Forum Secu</title>
        <link rel="stylesheet" type="text/css" href="styles.css"/>
        <link rel="stylesheet" type="text/css" href="log.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>

    <body>
        <form action="functions/addFriendController.php" method="post">
            <p>Ajoutez des amis</p>
            <input id="friendMail" type="text" name="friendMail" />
            <p><input type="button" onClick="addFriends()" value="OK"></p>
        </form>
        <?php
            if(!$_SESSION['logged']){
                print('
                    <form action="functions/connectionController.php" method="post">
                        <img src="img/icon_account.png" width="20%" alt="ce connecter"/>
                        <p>Votre login : <input id="pseudo" type="text" name="pseudo" /></p>
                        <p>Votre mots de passe : <input id="pass" type="password" name="pass" /></p>
                        <p><input type="button" onClick="requestLogin()" value="OK"></p>
                        <a href="create.php" id="btnCreateAccount" style="border:1px solid #FF7315; padding:5px">Cliquez ici pour créer un compte</a>
                        <a href="change.php" id="btnCreateAccount" style="border:1px solid #FF7315; padding:5px">Mots de passe oublié?</a>
                    </form>
                    
                    ');
            }
            else{
                print($_SESSION['pseudo']);
                print(' vos amis sont :');

                if(isset($_SESSION["id"]) && $_SESSION['logged']){
                    $friends = _listFriend(_getIdUser($_SESSION["id"]));
                }
                
                foreach ($friends[0] as $friend){
                    print (" " . $friend[0]);
                }
            }

        ?>

    </body>

    <script>
        function requestLogin(){
            //
            $.ajax('functions/connectionController.php', {
            type: 'POST',
            dataType: 'json',
            data: { pseudo: $('input#pseudo').val(),
                    pass: $('input#pass').val() },
            
            success: function (data, status, xhr) {
                if(data['result']){
                    window.location.reload();
                } else {
                    alert('Bad login');
                }
            },
            error: function (jqXhr, textStatus, errorMessage) { 
                alert('Erreur');
        }});
        }
    </script>

</html> 