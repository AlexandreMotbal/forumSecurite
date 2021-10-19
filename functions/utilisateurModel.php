<?php

require_once 'bdd.php';
$_ENV['conn'] = connect();

//ChangementMail Verification

function _getUserById($idUser){
    $return = [];
    $idUser = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $idUser));
    $keys = ['nom', 'prenom', 'adresse', 'mail', 'tel', 'description'];
    $request = 'SELECT nom, prenom, adresse, mail, tel, description  FROM users WHERE users.id = "%s"';
    $request = sprintf($request, $idUser);
    $result = $_ENV['conn']->query($request);
    $result = $result->fetch_all();
    if($result){
        foreach($result[0] as $key=>$value){
           $return[$keys[$key]] = $value;
        }
        //$result = json_encode($result);
        return json_encode($return);
    }
    else{
        return False;
    }
}

//get id by mail
function _getIdByMail($mail){
    $mail = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $mail));
    $request = 'SELECT id FROM users WHERE users.mail = "%s"';
    $request = sprintf($request, $mail);
    $result = $_ENV['conn']->query($request);
    $result = $result->fetch_all();
    if($result){
        return $result[0][0];
    }
    else{
        return False;
    }
}

//get id by pseudo
function _getIdPseudo($pseudo){
    $mail = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $pseudo));
    $request = 'SELECT id FROM users WHERE users.pseudo = "%s"';
    $request = sprintf($request, $pseudo);
    $result = $_ENV['conn']->query($request);
    $result = $result->fetch_all();
    if($result){
        return $result[0][0];
    }
    else{
        return False;
    }
}

function _getIdLog($idUser){
    $idUser = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $idUser));
    $request = 'SELECT idLog FROM users WHERE users.id = "%s"';
    $request = sprintf($request, $idUser);
    $result = $_ENV['conn']->query($request);
    $result = $result->fetch_all();
    if($result){
        return $result[0][0];
    }
    else{
        return False;
    }
}
//Get pseudo by user id
function _getPseudoByUserId($idUser){
    $idUser = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $idLog));
    $request = 'SELECT pseudo FROM login INNER JOIN users ON login.id = users.idlog WHERE users.id = "%s"';
    $request = sprintf($request, $idUser);
    $result = $_ENV['conn']->query($request);
    $result = $result->fetch_all();
    if(count($result) > 0){
        return $result[0][0];
    }
    else{
        return False;
    }
}
//Get iduser by idlog
function _getIdUser($idLog){
    $idUser = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $idLog));
    $request = 'SELECT id FROM users WHERE users.idlog = "%s"';
    $request = sprintf($request, $idLog);
    $result = $_ENV['conn']->query($request);
    $result = $result->fetch_all();
    if(count($result) > 0){
        return $result[0][0];
    }
    else{
        return False;
    }
}
/*
//Ajout d'ami
function _addFriend($idUser, $idAdd){
    $idUser = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $idUser));
    $idAdd = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $idAdd));
    $return = false;
    if(!(_checkFriend($idUser, $idAdd))){
        $request = 'SELECT ami FROM users WHERE users.id = "%s"';
        $request = sprintf($request, $idUser);
        $result = $_ENV['conn']->query($request);
    
        $result = $result->fetch_all();
        $result = json_decode($result[0][0], true);
        $result[] = strval($idAdd);
        $result = mysqli_real_escape_string($_ENV['conn'], json_encode($result));
        $request = 'UPDATE `users` SET `ami` = "%s" WHERE `users`.`id` = "%s";';
        $request = sprintf($request, $result, $idUser);
        $result = $_ENV['conn']->query($request);
    
        if($result){
            echo "<script> alert('Ajout bien effectué') </script>"; 
            return true;
        }
        else{
            echo "<script> alert('lecture impossible') </script>"; 
            return False;
        }
    }
    else{
        echo "<script> alert('Vous êtes déjà ami') </script>"; 
        return False;
    }
}
*/

//Ajout d'ami
function _addFriend($idUser, $friend_mail){
    $idUser = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $idUser));
    $friend_mail = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $friend_mail));
    $return = false;
    if(!(_checkFriend($idUser, $idAdd))){
        $request = 'SELECT ami FROM users WHERE users.id = "%s"';
        $request = sprintf($request, $idUser);
        $result = $_ENV['conn']->query($request);
    
        $result = $result->fetch_all();
        $result = json_decode($result[0][0], true);
        $result[] = strval($idAdd);
        $result = mysqli_real_escape_string($_ENV['conn'], json_encode($result));
        $request = 'UPDATE `users` SET `ami` = "%s" WHERE `users`.`id` = "%s";';
        $request = sprintf($request, $result, $idUser);
        $result = $_ENV['conn']->query($request);
    
        if($result){
            echo "<script> alert('Ajout bien effectué') </script>"; 
            return true;
        }
        else{
            echo "<script> alert('lecture impossible') </script>"; 
            return False;
        }
    }
    else{
        echo "<script> alert('Vous êtes déjà ami') </script>"; 
        return False;
    }
}

//delete ami
function _deleteFriend($idUser, $idDelFriend){
    $idUser = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $idUser));
    $idDelFriend = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $idDelFriend));
    $return = false;
    if((_checkFriend($idUser, $idDelFriend))){
        $request = 'SELECT ami FROM users WHERE users.id = "%s"';
        $request = sprintf($request, $idUser);

        $result = $_ENV['conn']->query($request);

        if ($result){
            $result = $result->fetch_all();
            $result = json_decode($result[0][0], true);
            foreach($result as $key => $value) {
                if($value == $idDelFriend){
                    unset($result[$key]);
                    $result = mysqli_real_escape_string($_ENV['conn'], json_encode($result));
                    $request = 'UPDATE `users` SET `ami` = "%s" WHERE `users`.`id` = "%s";';
                    $request = sprintf($request, $result, $idUser);
                    $result = $_ENV['conn']->query($request);
                
                
                    if($result){
                        echo "<script> alert('Suppression bien effectué') </script>"; 
                        return true;
                    }
                    else{
                        echo "<script> alert('lecture impossible') </script>"; 
                        return False;
                    }
                }
            }
        }
        else {
            $return = false;
        }
    }
    else {
        echo "<script> alert('Vous n\'êtes pas ami') </script>";
        $return = false;
    }
    return $return;
}

//Check ami
function _checkFriend($idUser, $idFriend){
    $idUser = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $idUser));
    $idFriend = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $idFriend));
    $return = false;

    $request = 'SELECT ami FROM users WHERE users.id = "%s"';
    $request = sprintf($request, $idUser);

    $result = $_ENV['conn']->query($request);

    if ($result){
        $result = $result->fetch_all();
        $result = json_decode($result[0][0]);
        foreach($result as $key => $value) {
            if($value == $idFriend){
                $return = true;
            }
        }
    }
    else {
        $return = false;
    }
    return $return;
}


//Voir ami
function _listFriend($idUser){
    $idUser = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $idUser));
    $listFriends = array();

    $request = 'SELECT ami FROM users WHERE users.id = "%s"';
    $request = sprintf($request, $idUser);
    $result = $_ENV['conn']->query($request);
    $result = $result->fetch_all();

    if (count($result) > 0){
        $result = json_decode($result[0][0]);
        foreach($result as $key => $value) {
            $request = 'SELECT login.pseudo, nom, prenom, adresse, mail, tel, description, ami FROM users INNER JOIN login ON login.id = users.idlog WHERE users.id = "%s"';
            $request = sprintf($request, $value);
            $resultReq = $_ENV['conn']->query($request);
            $resultReq = $resultReq->fetch_all();
            $listFriends[] = $resultReq;
        }
    } else {
        return False;
    }
    return $listFriends;
}

//CheckRegex
function _inputVerification2($pseudo, $mail, $phone){
    $pseudo = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $pseudo));
    $mail = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $mail));
    $phone = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $phone));
    $resultVar = [];
    $resultVar['pseudo'] = False;
    if (preg_match_all('/^[A-Za-z][A-Za-z0-9]{4,31}$/', $pseudo)){
        $resultVar['pseudo'] = True;
    }

    $resultVar['mail'] = False;
    if (preg_match_all('/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/', $mail)){
        $resultVar['mail'] = True;
    }

    $resultVar['phone'] = False;
    if (preg_match_all('/[0][0-9]{9}/', $phone)){
        $resultVar['phone'] = True;
    }

    return $resultVar;
}


//checkSession pour update (champ non obligatoire initialiser a nul dans la fonction)
function _updateAccount($pseudo, $adr, $desc = "", $mail, $name, $firstName, $phone, $idLog, $idUser){
    //Return True account is update, false else.
  
    //Formatage for db
    $pseudo = mysqli_real_escape_string($_ENV['conn'], $pseudo);
    $adr = mysqli_real_escape_string($_ENV['conn'], $adr);
    $desc = mysqli_real_escape_string($_ENV['conn'], $desc);
    $mail = mysqli_real_escape_string($_ENV['conn'], $mail);
    $name = mysqli_real_escape_string($_ENV['conn'], $name);
    $firstName = mysqli_real_escape_string($_ENV['conn'], $firstName);
    $phone = mysqli_real_escape_string($_ENV['conn'], $phone);

    //Verification regex
    $resultInput = _inputVerification2($pseudo, $mail, $phone);

    if(!($resultInput['pseudo'] && $resultInput['mail'] && $resultInput['phone'])){
        echo "<script> alert('lecture impossible') </script>"; 
        return False;
    }
  
    //Update into login
    $request = 'UPDATE login SET pseudo = "%s" WHERE `login`.`id` = "%s"';
    $request = sprintf($request, $pseudo, $idLog);

    $result = $_ENV['conn']->query($request);

    if (!$result){
        echo "<script> alert('lecture impossible') </script>"; 
        return False;
    }

    //Update into users
    $request = 'UPDATE users SET nom = "%s", prenom = "%s", adresse = "%s", mail = "%s", tel = "%s", description = "%s" WHERE users.id = "%s"';

    $request = sprintf($request, $name, $firstName, $adr, $mail, $phone, $desc, $idUser);

    $result = $_ENV['conn']->query($request);

    if (!$result){
        echo "<script> alert('lecture impossible') </script>"; 
        return False;
    }
    return True;      
}

//Recherche Par Login
function SearchByLog($login) {
  $db = getDatabase();
  if($categorie == "All"){
    $Recherche = $db->query("SELECT nom, login, categorie FROM t_utilisateur WHERE login LIKE '$login'");
  }
    if (!$Recherche) { 
    echo "<script> alert('lecture impossible') </script>";
            } 
    else {
        echo "<table border=\"1\">"; 
        echo "<tr> Recherche par login <th> Login </th> <th> Nom </th> <th> Catégorie </th></tr>"; 
        while ($ligne=$Recherche->fetch(PDO::FETCH_ASSOC)) { 
            echo " <tr> <td>". $ligne['login'] ."</td>" ."<td>". $ligne['nom'] ."</td>" . "<td>". $ligne['categorie'] 
            ."</td></tr>"; 
            } 
        echo "</table>"; 
      }
}

//Recherche Par Nom
function SearchByName($nom) {
  $db = getDatabase();
  if($categorie == "All"){
    $Recherche = $db->query("SELECT nom, login, categorie FROM t_utilisateur WHERE nom LIKE '$nom'");
  }
  
    if (!$Recherche) { 
        echo "<script> alert('lecture impossible') </script>"; 
        } 
    else {
        echo "<table border=\"1\">"; 
        echo "<tr> Recherche par nom <th> Login </th> <th> Nom </th> <th> Catégorie </th></tr>"; 
        while ($ligne=$Recherche->fetch(PDO::FETCH_ASSOC)) { 
            echo " <tr> <td>". $ligne['login'] ."</td>" ."<td>". $ligne['nom'] ."</td>" . "<td>". $ligne['categorie'] 
            ."</td></tr>"; 
            } 
        echo "</table>"; 
      }
}

//_updateAccount("Grante","ici","aze","diegogrante@gmail.com","azjeh", "iazbdiazbdjsqd","0781882385",1,1);
//_listFriend(1);
//_checkFriend(1,4);
//_addFriend(1,5);
//_deleteFriend(1,4);
//_getUserById(1);
?>
