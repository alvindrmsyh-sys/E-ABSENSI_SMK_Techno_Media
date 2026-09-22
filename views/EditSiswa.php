<?php 
session_start();
include '../config/database.php';
include '../includes/header.php'; 

if (!isset($_SESSION['login'])) {
    header('Location: ../index.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "<script>alert('ID Siswa tidak ditemukan!'); window.location='siswa.php';</script>";
    exit;
}

$id_siswa = $_GET['id'];

$query = "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'";
$result = mysqli_query($conn, $query);
$siswa = mysqli_fetch_assoc($result);

if (!$siswa) {
    echo "<script>alert('Data Siswa tidak ditemukan!'); window.location='siswa.php';</script>";
    exit;
}
?>

<div class="max-w-3xl mx-auto space-y-8 mt-10">

    <section class="rounded-[32px] bg-white p-8 shadow-sm border border-slate-200">
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-slate-900">Edit Data Siswa</h2>
            <p class="text-slate-500">Ubah informasi siswa di bawah ini.</p>
        </div>

        <form action="../controllers/SiswaController.php" method="POST" class="space-y-6">
            
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id_siswa" value="<?php echo $siswa['id_siswa']; ?>">
            <input type="hidden" name="old_nis" value="<?php echo htmlspecialchars($siswa['nis']); ?>">

            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-700">NIS</label>
                <input type="text" name="nis" value="<?php echo htmlspecialchars($siswa['nis']); ?>" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition" />
                <p class="text-xs text-amber-600 mt-1">*Jika NIS diubah, Anda mungkin perlu membuat ulang QR Code secara manual.</p>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                <input type="text" name="nama" value="<?php echo htmlspecialchars($siswa['nama_siswa']); ?>" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition" />
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-700">Kelas</label>
                <input type="text" name="kelas" value="<?php echo htmlspecialchars($siswa['kelas']); ?>" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition" />
            </div>

            <div class="flex gap-4 pt-4">
                <a href="siswa.php" class="inline-flex flex-1 items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-sm font-semibold text-white hover:bg-amber-400 transition shadow-lg shadow-amber-500/20">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </section>

</div>

<?php include '../includes/footer.php'; ?>