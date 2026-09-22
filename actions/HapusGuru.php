<?php
session_start();
include '../config/database.php';

// Validasi ketat: HANYA jika ada tombol 'hapus' yang ditekan melalui POST
if(isset($_POST['hapus'])) {
    $id = $_POST['id'];
    
    // Gunakan prepared statement untuk keamanan ekstra
    $stmt = $conn->prepare("DELETE FROM users WHERE id_user = ?");
    $stmt->bind_param("i", $id);
    
    if($stmt->execute()) {
        echo "<script>
                alert('Guru berhasil dihapus!');
                window.location='../views/guru.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data.');
                window.location='../views/guru.php';
              </script>";
    }
    $stmt->close();
} else {
    // Jika ada yang sengaja akses file ini tanpa submit form, tendang balik!
    header("Location: ../views/guru.php");
    exit;
}
?>