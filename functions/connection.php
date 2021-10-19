<?php
    //File to make connections
    require 'accountModel.php';

    $result = ['result' => False,
               'commentary' => ""];
    
    if(isset($_GET['pseudo']) && isset($_GET['pass'])){
        session_start();

        $pseudo = htmlspecialchars($_GET['pseudo']);
        $pass = htmlspecialchars($_GET['pass']);
        
        if(_connectionVerification($pseudo, $pass)){
            $_SESSION['pseudo'] = $pseudo;
            $_SESSION['pass'] = $pass;
            
            $request = 'SELECT id FROM login WHERE pseudo="%s"';
            $request = sprintf($request, $_SESSION['pseudo']);
            
            $id = $_ENV['conn']->query($request);
            $id = $id->fetch_all();
            $_SESSION['id'] = $id[0][0];

            $result['result'] = True;
            $result['commentary'] = "Good pass and pseudo";
        } else {
            $result['commentary'] = "Bad pass or pseudo";
        } 
    } else {
            $result['commentary'] = "Args invalids or missing";
    }
?>