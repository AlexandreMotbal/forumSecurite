<?php

    if(isset($_POST['ajaxRequest'])){
        foreach (scandir("../img/") as $item){
            if (preg_match_all("/[a-zA-Z]+[.][j][p][g]/", $item)){
                echo $item . " ";
            }
        }
    }

?>