<?php

include 'Diego/bdd.php';
$_ENV['conn'] = connect();

//ChangementMail Verification

//Ajout d'ami
function _addFriend($idUser, $idAdd){
    $return = false;
    if(!(_checkFriend($idUser, $idAdd))){
        $request = 'SELECT ami FROM users WHERE users.id = "%s"';
        $request = sprintf($request, $idUser);
        $result = $_ENV['conn']->query($request);
    
        $result = $result->fetch_all();
        $result = json_decode($result[0][0], true);
        $result[] = strval($idAdd);
        $result = mysqli_real_escape_string($_ENV['conn'], json_encode($result));
        var_dump($request);
        $request = 'UPDATE `users` SET `ami` = "%s" WHERE `users`.`id` = "%s";';
        $request = sprintf($request, $result, $idUser);
        $result = $_ENV['conn']->query($request);
    
        var_dump($request);
    
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
    $return = false;
    if((_checkFriend($idUser, $idDelFriend))){
        $request = 'SELECT ami FROM users WHERE users.id = "%s"';
        $request = sprintf($request, $idUser);
        var_dump($request);

        $result = $_ENV['conn']->query($request);

        if ($result){
            $result = $result->fetch_all();
            $result = json_decode($result[0][0], true);
            foreach($result as $key => $value) {
                if($value == $idDelFriend){
                    unset($result[$key]);
                    var_dump($result);
                    $result = mysqli_real_escape_string($_ENV['conn'], json_encode($result));
                    $request = 'UPDATE `users` SET `ami` = "%s" WHERE `users`.`id` = "%s";';
                    $request = sprintf($request, $result, $idUser);
                    $result = $_ENV['conn']->query($request);
                
                    var_dump($request);
                
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
    $return = false;

    $request = 'SELECT ami FROM users WHERE users.id = "%s"';
    $request = sprintf($request, $idUser);
    var_dump($request);

    $result = $_ENV['conn']->query($request);

    if ($result){
        $result = $result->fetch_all();
        $result = json_decode($result[0][0]);
        foreach($result as $key => $value) {
            var_dump($value);
            if($value == $idFriend){
                var_dump(true);
                $return = true;
            }
        }
    }
    else {
        $return = false;
    }
    var_dump($return);
    return $return;
}


//Voir ami
function _listFriend($idUser){
    $listFriends = array();

    $request = 'SELECT ami FROM users WHERE users.id = "%s"';
    $request = sprintf($request, $idUser);
    var_dump($request);
    $result = $_ENV['conn']->query($request);

    if ($result){
        $result = $result->fetch_all();
        $result = json_decode($result[0][0]);
        var_dump($result);
        foreach($result as $key => $value) {
            $request = 'SELECT nom, prenom, adresse, mail, tel, description, ami FROM users WHERE users.id = "%s"';
            $request = sprintf($request, $value);
            $result = $_ENV['conn']->query($request);
            $result = $result->fetch_all();
            $listFriends[] = $result;
        }
    }
    else {
        return false;
    }
    var_dump($listFriends);
    return $listFriends;
}

//CheckRegex
function _inputVerification($pseudo, $mail, $phone){
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
    $resultInput = _inputVerification($pseudo, $mail, $phone);

    if(!($resultInput['pseudo'] && $resultInput['mail'] && $resultInput['phone'])){
        echo "<script> alert('lecture impossible') </script>"; 
        return False;
    }
  
    //Update into login
    $request = 'UPDATE login SET pseudo = "%s" WHERE `login`.`id` = "%s"';
    $request = sprintf($request, $pseudo, $idLog);
    var_dump($request);

    $result = $_ENV['conn']->query($request);

    if (!$result){
        echo "<script> alert('lecture impossible') </script>"; 
        return False;
    }

    //Update into users
    $request = 'UPDATE users SET nom = "%s", prenom = "%s", adresse = "%s", mail = "%s", tel = "%s", description = "%s" WHERE users.id = "%s"';

    $request = sprintf($request, $name, $firstName, $adr, $mail, $phone, $desc, $idUser);
    var_dump($request);

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
_deleteFriend(1,4);
?>
