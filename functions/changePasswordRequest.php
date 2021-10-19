<?php
    require 'accountModel.php';
    if(isset($_POST['pseudo'])){
        changePasswordRequest($_POST['pseudo']);
    }
    //region Dans le cas ou on a un pseudo
    function changePasswordRequest($pseudo){
        $return = ["result"=>false, "error"=>""];
        $pseudo = htmlspecialchars($pseudo);
        
        $id = _getId($pseudo);
        if (isset($id)){
            $token = _createToken($id);
            $subject = "Changement de mot de passe";
            
            $body = "Bonjour,<br />";
            $body .= "Voici votre token de changement de mot de passe : " . $token . "<br />";
            $body .= 'Vous pouvez aussi cliquer sur ce  <a href="localhost/changepassword.php?token=' . $token . '">lien</a>';
            
            if(_sendMail($id, $subject, $body)){
                $return['result'] = true;
            } else {
                $return['error'] = "Mail fail";
            }
            
        } else {
            $return['error'] = "Invalid pseudo";
        }
        print json_encode($return);
    }        
    //endregion
?>