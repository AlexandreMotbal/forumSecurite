<?php
    /*
    include('functions/changePasswordRequest.php');
    if(isset($_POST['pseudo'])){
        $ifSuccess = changePasswordRequest($pseudo);
        if($ifSuccess){
            print("<p style='background-color:green;'>Success</p>");
        }
        else{
            print("<p style='background-color:red;'>Something went wrong</p>");
        }
    }
    */

?>

<!DOCTYPE html>
    <head>
        <title>Forum Secu</title>
        <link rel="stylesheet" type="text/css" href="styles.css"/>
        <link rel="stylesheet" type="text/css" href="log.css"/>
    </head>

    <body>
        <?php
            if (isset($_GET['token'])){
                $token = $_GET['token'];

                echo('<form action="functions/changepasswordController.php" method="post" style="display:flex; justify-content:space-around; align-items:left;flex-direction:column">
                    <img src="img/icon_account.png" width="20%" alt="Se connecter"/>
                    <p>new password : <input type="password" name="pass" /></p>
                    <input type="hidden" name="token" value = "' . $token . '"/>
                    <p><input type="submit" value="OK"/></p>
                </form>');
            } else {
                echo('<form action="functions/changePasswordRequest.php" method="post" style="display:flex; justify-content:space-around; align-items:left;flex-direction:column">
                    <img src="img/icon_account.png" width="20%" alt="Se connecter"/>
                    <p>login : <input type="text" name="pseudo" /></p>
                    <p><input type="submit" value="OK"/></p>
                </form>');
            }
        ?>
        
    </body>
</html> 