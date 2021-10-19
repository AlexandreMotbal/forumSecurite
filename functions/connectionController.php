<?php
    //File to make connections
    require_once 'accountModel.php';
    
    if (isset($_POST['pseudo'], $_POST['pass'])) {
        connectionControler($_POST['pseudo'], $_POST['pass']);
    }
    
    function connectionControler($pseudo, $pass){
        $result = ['result' => False,
                'commentary' => ""];
        session_start();

        $pseudo = htmlspecialchars($pseudo);
        $pass = htmlspecialchars($pass);
        $_SESSION['logged'] = False;
        setcookie("session", session_id(), time() + 3600, '/');

        if(_connectionVerification($pseudo, $pass)){
            $_SESSION['pseudo'] = $pseudo;
            $_SESSION['pass'] = $pass;
            $_SESSION['logged'] = True;

            $id = _getId($pseudo);
            if (isset($id)){
                $_SESSION['id'] = $id;
                $result['result'] = True;
                $result['commentary'] = "Good pass and pseudo";
            } else {
                $result['commentary'] = "Bad Id";
            }
        } else {
            $result['commentary'] = "Bad pass or pseudo";
        } 
        print json_encode($result);
    }

    //connectionControler("Grante","Diegogrante12@");
    
?>