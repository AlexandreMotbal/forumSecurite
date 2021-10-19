<?php
require_once 'utilisateurController.php';

if(isset($_SESSION['id']) && isset($_POST['friend_mail'])){
    $idUser = _getId($_SESSION['id']);
    $friend_mail = htmlspecialchars(mysqli_real_escape_string($_ENV['conn'], $_POST['friend_mail']));
    $idAdd = _getIdByMail($friend_mail);
    addFriend($idUser, $idAdd);
}
?>

