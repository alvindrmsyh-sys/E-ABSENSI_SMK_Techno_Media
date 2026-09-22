<?php
session_start();
include '../config/database.php';
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| 1. LOGIKA SCAN OTOMATIS (QR)
|--------------------------------------------------------------------------
*/
if (isset($_GET['nis'])) {
    $nis     = mysqli_real_escape_string($conn, $_GET['nis']);
    $tanggal = date('Y-m-d');
    $jam     = date('H:i:s');
    $status  = "Hadir";
    $id_user = $_SESSION['id_user'];

    // Cek apakah siswa terdaftar
    $cekSiswa = mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$nis'");
    if (mysqli_num_rows($cekSiswa) == 0) {
        die("Siswa tidak ditemukan");
    }

    // Cek apakah sudah absen hari ini
    $cekAbsen = mysqli_query($conn, "SELECT * FROM absensi WHERE nis='$nis' AND tanggal='$tanggal'");
    if (mysqli_num_rows($cekAbsen) > 0) {
        echo "<script>alert('Siswa sudah absen hari ini!'); window.location='../views/scan.php';</script>";
        exit;
    }

    // Simpan Absensi Scan
    $query = "INSERT INTO absensi (nis, tanggal, jam, status, id_user) VALUES ('$nis', '$tanggal', '$jam', '$status', '$id_user')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Absensi berhasil!'); window.location='../views/laporan.php';</script>";
    } else {
        echo "<script>alert('Absensi gagal!'); window.location='../views/scan.php';</script>";
    }
    exit;
}

/*
|--------------------------------------------------------------------------
| 2. LOGIKA INPUT MANUAL (IZIN/SAKIT/ALPHA)
|--------------------------------------------------------------------------
*/
if (isset($_POST['action']) && $_POST['action'] == 'input_manual') {
    $tanggal    = $_POST['tanggal'];
    $nis        = $_POST['nis'];
    $status     = $_POST['status'];
    $keterangan = $_POST['keterangan'];
    $jam        = '00:00:00'; // Jam default untuk absen manual
    $id_user    = $_SESSION['id_user'];

    // Cek apakah sudah ada data di tanggal tersebut
    $cek_query = "SELECT * FROM absensi WHERE nis = '$nis' AND tanggal = '$tanggal'";
    $cek_result = mysqli_query($conn, $cek_query);

    if (mysqli_num_rows($cek_result) > 0) {
        echo "<script>alert('Siswa ini sudah memiliki data absensi pada tanggal tersebut!'); window.location='../views/InputManual.php';</script>";
        exit;
    }

    // Insert ke database (sesuaikan dengan tabel absensi Anda)
    $query = "INSERT INTO absensi (nis, tanggal, jam, id_user, status, keterangan) 
              VALUES ('$nis', '$tanggal', '$jam', '$id_user', '$status', '$keterangan')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data absensi manual berhasil disimpan!'); window.location='../views/laporan.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data absensi!'); window.location='../views/InputManual.php';</script>";
    }
    exit;
}
?>