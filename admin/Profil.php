<?php
include '../middleware/UserMiddleware.php';
include '../config/database.php';
include '../includes/header.php';

$id = $_SESSION['id_user'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id_user='$id'");
$user = mysqli_fetch_assoc($query);

// Proses Update Profil
if(isset($_POST['update'])){
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    
    // Proses Upload Foto (jika ada file baru)
    if($_FILES['foto']['name'] != ""){
        $foto = time().'_'.$_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], '../assets/images/'.$foto);
        mysqli_query($conn, "UPDATE users SET nama='$nama', email='$email', no_hp='$no_hp', foto='$foto' WHERE id_user='$id'");
    } else {
        mysqli_query($conn, "UPDATE users SET nama='$nama', email='$email', no_hp='$no_hp' WHERE id_user='$id'");
    }
    
    $_SESSION['nama'] = $nama; 
    echo "<script>alert('Profil berhasil diupdate!'); window.location='Profil.php';</script>";
}
?>

<div class="fade-in max-w-2xl mx-auto p-4">
    <section class="bg-white p-8 rounded-[32px] border border-slate-200 shadow-sm">
        <h1 class="text-3xl font-bold text-slate-800">Profil Saya</h1>
        <form method="POST" enctype="multipart/form-data" class="mt-8 space-y-6">
            <div class="flex items-center gap-6 mb-8">
                <img src="../assets/images/<?php echo ($user['foto'] ? $user['foto'] : 'default.png'); ?>" 
                     class="h-24 w-24 rounded-full object-cover border-4 border-emerald-100 shadow-sm">
                <div>
                    <h2 class="text-xl font-bold text-slate-800"><?php echo $user['nama']; ?></h2>
                    <input type="file" name="foto" class="mt-2 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?php echo $user['nama']; ?>" class="w-full mt-1 p-4 rounded-2xl border border-slate-200 bg-slate-50 outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700">Email</label>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>" class="w-full mt-1 p-4 rounded-2xl border border-slate-200 bg-slate-50 outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700">Nomor HP</label>
                    <input type="text" name="no_hp" value="<?php echo $user['no_hp']; ?>" class="w-full mt-1 p-4 rounded-2xl border border-slate-200 bg-slate-50 outline-none focus:border-emerald-500">
                </div>
            </div>
            
            <button type="submit" name="update" class="w-full bg-emerald-600 text-white font-bold py-4 rounded-2xl hover:bg-emerald-500 transition shadow-lg shadow-emerald-500/20">Simpan Perubahan</button>
        </form>
    </section>
</div>

<?php include '../includes/footer.php'; ?>