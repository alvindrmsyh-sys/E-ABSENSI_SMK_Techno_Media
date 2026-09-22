<?php
session_start();
include '../config/database.php';

// VALIDASI LOGIN
if(!isset($_SESSION['login'])){
    header('Location: ../index.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| 1. PROSES HAPUS DATA (DELETE)
|--------------------------------------------------------------------------
*/
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id_siswa = $_GET['id'];

    // Cari tahu NIS-nya dulu untuk menghapus file QR-nya dari folder server
    $queryGet = "SELECT nis FROM siswa WHERE id_siswa = '$id_siswa'";
    $resultGet = mysqli_query($conn, $queryGet);

    if ($row = mysqli_fetch_assoc($resultGet)) {
        $file = "../uploads/qr_codes/" . $row['nis'] . ".png";
        if (file_exists($file)) {
            unlink($file); // Menghapus file gambar QR agar memori tidak penuh
        }
    }

    // Hapus data dari tabel database
    $queryDelete = "DELETE FROM siswa WHERE id_siswa = '$id_siswa'";
    mysqli_query($conn, $queryDelete);

    echo "<script>alert('Data siswa dan QR berhasil dihapus!'); window.location='../views/siswa.php';</script>";
    exit;
}


/*
|--------------------------------------------------------------------------
| 2. PROSES UPDATE DATA (EDIT)
|--------------------------------------------------------------------------
*/
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $id_siswa = $_POST['id_siswa'];
    $nis      = $_POST['nis'];
    $nama     = $_POST['nama'];
    $kelas    = $_POST['kelas'];
    $old_nis  = $_POST['old_nis'];

    // Update data di database
    $query = "UPDATE siswa SET nis = '$nis', nama_siswa = '$nama', kelas = '$kelas' WHERE id_siswa = '$id_siswa'";
    $update = mysqli_query($conn, $query);

    if ($update) {
        // Jika NIS diubah, kita otomatis ubah juga nama file gambar QR lamanya
        if ($old_nis != $nis) {
            $old_file = "../uploads/qr_codes/" . $old_nis . ".png";
            $new_file = "../uploads/qr_codes/" . $nis . ".png";
            if (file_exists($old_file)) {
                rename($old_file, $new_file);
            }
        }
        echo "<script>alert('Data berhasil diperbarui!'); window.location='../views/siswa.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!'); window.location='../views/EditSiswa.php?id=$id_siswa';</script>";
    }
    exit;
}


/*
|--------------------------------------------------------------------------
| 3. PROSES TAMBAH DATA (INSERT) - Ini Kode Asli Anda
|--------------------------------------------------------------------------
*/
// Jika tidak ada parameter action (bukan update & bukan delete), jalankan insert
if (isset($_POST['nis']) && !isset($_POST['action'])) {
    
    // AMBIL DATA
    $nis   = $_POST['nis'];
    $nama  = $_POST['nama'];
    $kelas = $_POST['kelas'];

    // ID GURU LOGIN
    $idGuru = $_SESSION['id_user'];

    // SIMPAN SISWA
    $query = "
        INSERT INTO siswa
        (nis, nama_siswa, kelas, id_guru)
        VALUES
        ('$nis', '$nama', '$kelas', '$idGuru')
    ";

    $insert = mysqli_query($conn, $query);

    if($insert){

        // QR DIRECTORY
        $folder = "../uploads/qr_codes/";

        if(!file_exists($folder)){
            mkdir($folder, 0777, true);
        }

        // API QR
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($nis);
        $qrImage = file_get_contents($qrUrl);
        $filePath = $folder . $nis . ".png";

        file_put_contents($filePath, $qrImage);

        $_SESSION['last_qr'] = $filePath;
        $_SESSION['last_qr_nis'] = $nis;

        header('Location: ../views/siswa.php');
        exit;

    }else{
        echo "Gagal tambah siswa: " . mysqli_error($conn);
    }
}
?>