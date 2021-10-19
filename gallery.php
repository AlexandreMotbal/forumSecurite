<?php
    if(isset($_COOKIE['session'])){
        if(!(session_status() == PHP_SESSION_ACTIVE)){
            session_id($_COOKIE['session']);
            session_start();
        }
        if($_SESSION['logged']){
            if(isset($_FILES["photo"])){
                $imgName = $_FILES["photo"]["name"];
                $imgType = $_FILES["photo"]["type"];
                $imgSize = $_FILES["photo"]["size"];
                if ($_FILES["photo"]["error"] == 1){
                    echo('Fichier trop grand');
                }
                if($imgSize< 50**6){
                    if($imgType == 'image/jpeg'){
                        if(file_exists("img/" . $imgName)){
                            echo $imgName . " existe déjà.";
                        } else{
                            move_uploaded_file($_FILES["photo"]["tmp_name"], "img/" . $imgName);
                            echo "Votre fichier a été téléchargé avec succès.";
                            echo "<script>getImageList()</script>";
                        } 
                    }
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Forum Secu</title>
        <link rel="stylesheet" type="text/css" href="styles.css"/>
        <link rel="stylesheet" type="text/css" href="gallery.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body>
        <h1>Gallery</h1>
        <?php
            if(isset($_COOKIE['session'])){
                if(!(session_status() == PHP_SESSION_ACTIVE)){
                    session_id($_COOKIE['session']);
                    session_start();
                }
                if($_SESSION['logged']){
                    echo('<img id="preview" src="img/capy.jpg" style="width:50%;" alt="image">
                    <div id="bottom">
                        <img src="img/row.png" style="transform: rotate(180deg);" onClick="changeImage()" alt="image_precedente"/>
                        <p>Description</p>
                        <img src="img/row.png" onClick="changeImage()" alt="image_suivante" />
                    </div>

                    <div>
                        <form action="gallery.php" method="post" enctype="multipart/form-data">
                            <p>Upload your images here</p>
                            <p>you can upload only jpg under 1 Mo</p>
                            <p><input type="file" name="photo"/></p>
                            <p><input type="submit" value="OK"></p>
                        </form>');
                } else {
                    echo('<p>Vous devez vous connecter');
                }
            } else {
                echo('Vous devez vous connecter');
            }
        
            ?>
    </body>
    <script>
        $(document).ready(function(){
            getImageList()
            indexImgList = 0;
        });
        
        
        function changeImage(){
            if (indexImgList == returnTab.length - 1){
                indexImgList = 0;
            }
            console.log(indexImgList);
            console.log(returnTab[indexImgList]);
            $('img#preview').attr('src', "img/" + returnTab[indexImgList]);
            indexImgList++

        }

        function getImageList(){
            /*$.get("img/.", function(data) 
        {
            let regex = "[a-zA-Z]+[.][j][p][g]";
            let tab = [...data.matchAll(regex)];
            returnTab = new Array();
            for(let i = 0; i<tab.length; i+=2){
                returnTab.push(tab[i][0]);
            }

        });*/
            $.ajax('functions/galleryAdapter.php', {
                type: 'POST',
                data: {ajaxRequest: true},
                success: function (data, status, xhr) {
                    returnTab = data.split(" ");
                },
                error: function (jqXhr, textStatus, errorMessage) { 
                    alert('Erreur');
            }});
        }
    </script>

</html>