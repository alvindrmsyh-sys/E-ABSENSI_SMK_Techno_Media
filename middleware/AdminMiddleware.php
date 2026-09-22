<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['login'])){

    header("Location: ../index.php");
    exit;

}

if($_SESSION['role'] != 'admin'){

    header("Location: ../index.php");
    exit;

}
?>