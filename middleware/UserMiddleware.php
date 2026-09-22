<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hanya memastikan user sudah login
if(!isset($_SESSION['login'])){
    header("Location: ../index.php");
    exit;
}
?>