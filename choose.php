<?php

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Forum Secu</title>
        <link rel="stylesheet" type="text/css" href="styles.css"/>
        <link rel="stylesheet" type="text/css" href="choose.css"/>
    </head>
    <body>
        <div id="choose">
            <a href="log.php" class="card">
                <img src="img/icon_account.png" alt="ce connecter"/>
                <p>CONNEXION</p>
            </a>
            <?php
                if(1 == True){
                    print('
                        <a href="chat.php" class="card">
                            <img src="img/icon_chat.png" alt="chat" >
                            <p>CHAT</p>
                        </a>

                        <a href="gallery.php" class="card">
                            <img src="img/icon_gallery.png" alt="Gallery" width="120%"/>
                            <p>GALLERY</p>
                        </a>
                    ');
                }
            ?>
        </div>




    </body>
</html>
