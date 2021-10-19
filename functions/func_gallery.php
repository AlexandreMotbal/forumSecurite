<?php

$servername = "localhost";
$username = "username";
$password = "password";

// Create connection
global  $conn= new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

function getImg($userId, $imageId){
    





}
function upload($userId, $userId, $imageName, $file){

    $sql = "SELECT * FROM `images` WHERE userId == $userId"
    $result = $conn->query($sql


    $sql = "INSERT INTO `images`(`id`, `userId`, `imageFile`, `imageNom`) VALUES ($id,$userId,$file,$imageName)"

    $conn->query($sql
}