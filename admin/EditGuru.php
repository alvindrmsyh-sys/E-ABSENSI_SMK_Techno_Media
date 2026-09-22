<?php
include '../middleware/AdminMiddleware.php';
include '../config/database.php';
include '../includes/header.php';

// Pastikan ID ada di URL
if(!isset($_GET['id'])) {
    header("Location: guru.php");
    exit;
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id_user='$id'");
$g = mysqli_fetch_assoc($query);

// Jika guru tidak ditemukan
if(!$g) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='guru.php';</script>";
    exit;
}

// PROSES UPDATE
if(isset($_POST['update'])){
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    
    mysqli_query($conn, "UPDATE users SET nama='$nama', username='$username' WHERE id_user='$id'");
    
    echo "<script>alert('Data berhasil diupdate!'); window.location='guru.php';</script>";
}
?>

<div class="fade-in max-w-2xl">
    <h2 class="text-3xl font-bold text-slate-800">Edit Data Guru</h2>
    <form method="POST" class="mt-8 bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-6">
        <div>
            <label class="block text-sm font-bold text-slate-700">Nama Lengkap</label>
            <input type="text" name="nama" value="<?php echo $g['nama']; ?>" required class="w-full mt-2 p-4 rounded-2xl border border-slate-200">
        </div>
        <div>
            <label class="block text-sm font-bold text-slate-700">Username</label>
            <input type="text" name="username" value="<?php echo $g['username']; ?>" required class="w-full mt-2 p-4 rounded-2xl border border-slate-200">
        </div>
        <div class="flex gap-4">
            <a href="guru.php" class="w-full text-center bg-slate-100 text-slate-600 font-bold py-4 rounded-2xl">Batal</a>
            <button type="submit" name="update" class="w-full bg-emerald-700 text-white font-bold py-4 rounded-2xl">Simpan Perubahan</button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>