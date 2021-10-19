<?php
    require_once 'accountModel.php';

    if(isset($_POST['pass'], $_POST['token'])){
        changePassword($_POST['token'], $_POST['pass']);
    }
    
    function changePassword($token, $pass){
        $return = ["result"=>false];
        $token = htmlspecialchars($token);
        $pass = mysqli_real_escape_string($_ENV['conn'], htmlspecialchars($pass));
        $result = _changePass($token, $pass);
        if ($result){
            $return['result'] = true;
        }

        print json_encode($return);
    }

    //changePasswordRequest("Grante");
?>