<?php

    function connect(){
        $ini_array = parse_ini_file("conf.ini");

        $servername = $ini_array['host'];
        $username = $ini_array['username'];
        $password = $ini_array['pass'];
        $dbname = $ini_array['dbname'];
    
        $conn = new mysqli($servername, $username, $password, $dbname);
    
        if($conn->connect_error){
            die('Erreur : ' .$conn->connect_error);
        }

        return $conn;
    }


?>