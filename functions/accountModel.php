<?php
    require_once 'bdd.php';
    require_once 'phpmailer/src/Exception.php';
    require_once 'phpmailer/src/PHPMailer.php';
    require_once 'phpmailer/src/SMTP.php';
    
    $_ENV['conn'] = connect();
    function _connectionVerification($pseudo, $pass){
        //Boolean function, true if pseudo and pass exist in db, false else.
        $request = 'SELECT pass FROM login WHERE BINARY pseudo="' . mysqli_real_escape_string($_ENV['conn'], $pseudo) . '"';
        $result = $_ENV['conn']->query($request);
        
        $result = $result->fetch_all();
        
        $returnVar = False;

        if (count($result) > 0){
            $hashedPass = $result[0][0];
            if (password_verify($pass, $hashedPass)){
                $returnVar = True;
            }
        }
        
        return $returnVar;
    }

    function _createAccount($pseudo, $pass, $adr, $desc, $mail, $name, $firstName, $phone){
        //Return True account is create, false else.
        
        //Formatage for db
        $pseudo = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $pseudo));
        $pass = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $pass));
        $adr = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $adr));
        $desc = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $desc));
        $mail = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $mail));
        $name = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $name));
        $firstName = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $firstName));
        $phone = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $phone));

        //Verification regex
        $resultInput = _inputVerification($pseudo, $pass, $mail, $phone);

        if(!($resultInput['pseudo'] && $resultInput['pass'] && $resultInput['mail'] && $resultInput['phone'])){
            
            return False;
        }

        //Insert into login
        $request = 'INSERT INTO login (pseudo, pass) VALUES ("%s", "%s")';
        $request = sprintf($request, $pseudo, password_hash($pass, PASSWORD_DEFAULT));

        $result = $_ENV['conn']->query($request);

        if (!$result){
            return False;
        }

        //Insert into users
        $request = 'INSERT INTO users (nom, prenom, adresse, mail, tel, description, idlog) VALUES ("%s", "%s", "%s", "%s", "%s", "%s", "%s")';
        $idlog = mysqli_insert_id($_ENV['conn']); //Get last id insert in db

        $request = sprintf($request, $name, $firstName, $adr, $mail, $phone, $desc, $idlog);

        $result = $_ENV['conn']->query($request);

        if (!$result){
            //Dans le cas ou l'insert dans la db ne fonctionne pas on remove dans la tab log
            $request = 'DELETE FROM login WHERE id=%s';
            $request = sprintf($request, $idlog);
            $resultRemove = $_ENV['conn']->query($request);
            return False;
        }
        return True;      
    }

    function _inputVerification($pseudo, $pass, $mail, $phone){
        $resultVar = [];
        $resultVar['pseudo'] = False;
        if (preg_match_all('/^[A-Za-z][A-Za-z0-9]{4,31}$/', $pseudo)){
            $resultVar['pseudo'] = True;
        }
        
        $resultVar['pass'] = False;
        if (preg_match_all('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/', $pass)){
            //Une majuscule, un alphanumérique, un chiffre, taille de 8 mini
            $resultVar['pass'] = True;
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
    
    function _deleteAccount($iduser){
        // Return True if account is deleted, false else.
        //iduser peut être aussi un idLogin
        $request = 'DELETE users, login FROM login INNER JOIN users ON users.idlog = login.id WHERE login.id = %s';
        $request = sprintf($request, $iduser);
        $result = $_ENV['conn']->query($request);

        if(!$result){
            return False;
        }

        return True;
    }

    function _createToken($iduser){
        //return token
        $token = rand(10**15, 9.9*10**15);
        $date = date("Y-m-d H:i:s");
        
        $request = 'UPDATE login SET token="' . $token .'", date="' . $date . '" WHERE id="' . $iduser . '"';
        $result = $_ENV['conn']->query($request);

        if (!$result){
            return "";
        }

        return $token;
    }
    
    function _sendMail($iduser, $subject, $body){        
        $config = parse_ini_file("conf.ini");
        
        $request = 'SELECT mail from users WHERE idlog="%s"';
        $request = sprintf($request, $iduser);
        $result = $_ENV['conn']->query($request);

        $result = $result->fetch_all();
        $dest = $result[0][0];
        
        //Configuration phpMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->IsSMTP();
        //$mail->isSendmail();
        $mail->Mailer = "smtp";
        $mail->SMTPDebug  = 0;  
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = "tls";
        $mail->Port       = $config['portTLS'];
        $mail->Host       = $config['hostGmail'];
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
                )
            );
        $mail->Username   = $config['mail'];
        $mail->Password   = $config['passMail'];

        $mail->IsHTML(true);
        $mail->AddAddress($dest, "recipient-name");
        $mail->SetFrom($config['mail'], $config['nameGmail']);
        $mail->Subject = $subject;

        //Send mail

        $mail->MsgHTML($body); 
        if(!$mail->Send()) {
            return False;
        } else {
            return True;
        }
    }

    function _getId($pseudo){
        //Return null if no result
        $request = 'SELECT id FROM login where BINARY pseudo="%s"';
        $request = sprintf($request, mysqli_real_escape_string($_ENV['conn'], $pseudo));
        $result = $_ENV['conn']->query($request);
        $result = $result->fetch_all();
        if (count($result) > 0){
            return $result[0][0];
        }
    }

    function _changePass($token, $pass){
        $token = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $token));
        $pass = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $pass));

        $request = 'SELECT date FROM login WHERE token="%s"';
        $request = sprintf($request, $token);

        $result = $_ENV['conn']->query($request);
        $result = $result->fetch_all();

        if(count($result[0])>0){
            $isExpired = (strtotime($result[0][0]) + 900 - time()) > 0;
            if ($isExpired) {
                if (preg_match_all('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/', $pass)){
                    $request = 'UPDATE login SET pass="%s" WHERE token="%s"';
                    $request = sprintf($request, password_hash($pass, PASSWORD_DEFAULT), $token);
                    //$request = sprintf($request, $pass, $token);
                    
                    $result = $_ENV['conn']->query($request);
                    return $result;
                } else {
                    return False; //Invalid patern pass
                }
            } else {
                return False; //Token Expire
            }
        } else {
            return False; //Token invalid
        }
        
    }
    //_createAccount("Grante","Diegogrante12@","ici","aze","diegogrante@gmail.com","azjeh", "iazbdiazbdjsqd","0781882385");
    //_createToken(8);
    //_sendMail(8, "TestSubject", "TestBody");

    //_changePass("7814110823441520", "Diegogrante12@");
?>