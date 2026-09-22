<?php
session_start();
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_sekolah  = mysqli_real_escape_string($conn, $_POST['nama_sekolah']);
    $alamat        = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon       = mysqli_real_escape_string($conn, $_POST['telepon']);
    $kepala        = mysqli_real_escape_string($conn, $_POST['kepala_sekolah']);
    $tahun_ajaran  = mysqli_real_escape_string($conn, $_POST['tahun_ajaran']);

    $query = "UPDATE pengaturan SET 
                nama_sekolah = '$nama_sekolah', 
                alamat = '$alamat', 
                telepon = '$telepon', 
                kepala_sekolah = '$kepala', 
                tahun_ajaran = '$tahun_ajaran' 
              WHERE id = 1";

    if (mysqli_query($conn, $query)) {
        header("Location: ../views/pengaturan.php?status=berhasil");
    } else {
        header("Location: ../views/pengaturan.php?status=gagal");
    }
}
?>