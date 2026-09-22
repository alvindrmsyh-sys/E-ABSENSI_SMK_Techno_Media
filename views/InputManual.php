<?php 
session_start();
include '../config/database.php';
include '../includes/header.php'; 

if (!isset($_SESSION['login'])) {
    header('Location: ../index.php');
    exit;
}

// Ambil data siswa untuk dropdown
if($_SESSION['role'] == 'admin'){
    $querySiswa = "SELECT * FROM siswa ORDER BY nama_siswa ASC";
} else {
    $idGuru = $_SESSION['id_user'];
    $querySiswa = "SELECT * FROM siswa WHERE id_guru = '$idGuru' ORDER BY nama_siswa ASC";
}
$resultSiswa = mysqli_query($conn, $querySiswa);
?>

<div class="max-w-3xl mx-auto space-y-8 mt-10">

    <section class="rounded-[32px] bg-white p-8 shadow-sm border border-slate-200">
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-slate-900">Input Absensi Manual</h2>
            <p class="text-slate-500">Masukkan data kehadiran siswa untuk status Izin, Sakit, atau Alpha.</p>
        </div>

        <form action="../controllers/AbsensiController.php" method="POST" class="space-y-6">
            
            <input type="hidden" name="action" value="input_manual">

            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-700">Tanggal</label>
                <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition" />
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-700">Pilih Siswa</label>
                <select name="nis" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition appearance-none">
                    <option value="" disabled selected>-- Pilih Siswa --</option>
                    <?php while($row = mysqli_fetch_assoc($resultSiswa)): ?>
                        <option value="<?php echo htmlspecialchars($row['nis']); ?>">
                            <?php echo htmlspecialchars($row['nis']) . ' - ' . htmlspecialchars($row['nama_siswa']) . ' (' . htmlspecialchars($row['kelas']) . ')'; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-700">Status Kehadiran</label>
                <div class="grid grid-cols-3 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="Sakit" class="peer sr-only" required>
                        <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-center font-medium text-slate-600 hover:bg-slate-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition shadow-sm">
                            Sakit
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="Izin" class="peer sr-only" required>
                        <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-center font-medium text-slate-600 hover:bg-slate-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition shadow-sm">
                            Izin
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="Alpha" class="peer sr-only" required>
                        <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-center font-medium text-slate-600 hover:bg-slate-50 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 transition shadow-sm">
                            Alpha
                        </div>
                    </label>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-700">Keterangan (Opsional)</label>
                <textarea name="keterangan" rows="3" placeholder="Contoh: Surat dokter menyusul..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"></textarea>
            </div>

            <div class="flex gap-4 pt-4">
                <a href="laporan.php" class="inline-flex flex-1 items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-2xl bg-blue-500 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-600 transition shadow-lg shadow-blue-500/20">
                    Simpan Absensi
                </button>
            </div>

        </form>
    </section>

</div>

<?php include '../includes/footer.php'; ?>