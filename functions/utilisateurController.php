<?php
require_once 'utilisateurModel.php';

//FRIEND
function addFriend(){
    if(isset($_COOKIE['session']) ){
        if(!(session_status() == PHP_SESSION_ACTIVE)){
            session_id($_COOKIE['session']);
            session_start();
        }

        if(isset($_SESSION["id"],$_POST["mailAdd"]) && $_SESSION['logged']){
            $idUser = _getId($_SESSION['id']);
            $mailAdd = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $_POST["mailAdd"]));
            $idAdd = _getIdByMail($mailAdd); 
            if($idAdd){
                _addFriend(_getIdUser($idUser,$idAdd);
            }
        }
    }
}

function deleteFriend(){
    if(isset($_COOKIE['session'])){
        if(!(session_status() == PHP_SESSION_ACTIVE)){
            session_id($_COOKIE['session']);
            session_start();
        }

        if(isset($_SESSION["id"],$_POST["mailDelete"]) && $_SESSION['logged']){
            $mailDelete = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $_POST["mailDelete"]));
            $idDel = _getIdByMail($mailDelete); 
            if($idDel){
            _deleteFriend($_SESSION["id"],$idDel);
            }
        }
    }
}

function listFriend() {
    if(isset($_COOKIE['session'])){
        if(!(session_status() == PHP_SESSION_ACTIVE)){
            session_id($_COOKIE['session']);
            session_start();
        }
        if(isset($_SESSION["id"]) && $_SESSION['logged']){
            _listFriend(_getIdUser($_SESSION["id"]));
        }
    }

    
}

//ACCOUNT

function updateAccount(){
    if(isset($_COOKIE['session'])){
        if(!(session_status() == PHP_SESSION_ACTIVE)){
            session_id($_COOKIE['session']);
            session_start();
        }
    
        if(isset($_POST["pseudo"], $_POST["adr"], $_POST["desc"], $_POST["mail"], $_POST["name"], $_POST["firstName"], $_POST["phone"], $_SESSION["id"])){
            $pseudo = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $_POST["pseudo"]));
            $adr = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $_POST["adr"]));
            $desc = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $_POST["desc"]));
            $mail = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $_POST["mail"]));
            $name = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $_POST["name"]));
            $firstName = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $_POST["firstName"]));
            $phone = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $_POST["phone"]));
            $idLog = _getIdLog($_SESSION["id"]);
            if($idLog){
                _updateAccount($pseudo, $adr, $desc, $mail, $name, $firstName, $phone, $_SESSION["id"], $idLog);
            }
        }
    }
}

function getUserById(){
    if(isset($_SESSION["id"])){
        _getUserById($_SESSION["id"]);
    }
}