<?php 
session_start();
include '../config/database.php';
include '../includes/header.php'; 

if (!isset($_SESSION['login'])) {
    header('Location: ../index.php');
    exit;
}

// 1. Pagination Setup
$batas = 10;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

// 2. Base Query
$idGuru = $_SESSION['id_user'];
if($_SESSION['role'] == 'admin'){
    $queryCount = "SELECT COUNT(*) FROM siswa";
    $queryData = "SELECT * FROM siswa ORDER BY id_siswa DESC LIMIT $halaman_awal, $batas";
} else {
    $queryCount = "SELECT COUNT(*) FROM siswa WHERE id_guru = '$idGuru'";
    $queryData = "SELECT * FROM siswa WHERE id_guru = '$idGuru' ORDER BY id_siswa DESC LIMIT $halaman_awal, $batas";
}

// Total Data
$resultCount = mysqli_query($conn, $queryCount);
$total_data = mysqli_fetch_array($resultCount)[0];
$total_halaman = ceil($total_data / $batas);

// Data untuk Tabel
$result = mysqli_query($conn, $queryData);
?>

<div class="space-y-8">
    <section id="form-tambah" class="rounded-[32px] bg-white p-8 shadow-sm border border-slate-200">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Tambah Data Siswa</h2>
            <p class="text-slate-500">Isi data siswa dan generate QR otomatis.</p>
        </div>
        <form action="../controllers/SiswaController.php" method="POST" class="mt-8 grid gap-6 lg:grid-cols-4 items-end">
            <div class="space-y-2"><label class="block text-sm font-medium text-slate-700">NIS</label><input type="text" name="nis" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:outline-none focus:border-emerald-500 transition" /></div>
            <div class="space-y-2"><label class="block text-sm font-medium text-slate-700">Nama Lengkap</label><input type="text" name="nama" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:outline-none focus:border-emerald-500 transition" /></div>
            <div class="space-y-2"><label class="block text-sm font-medium text-slate-700">Kelas</label><input type="text" name="kelas" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:outline-none focus:border-emerald-500 transition" /></div>
            <div><button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-500 transition">+ Simpan & Buat QR</button></div>
        </form>
    </section>

    <?php if (!empty($_SESSION['last_qr'])): ?>
    <section class="rounded-[32px] bg-emerald-50 p-6 border border-emerald-100 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-emerald-900">QR Berhasil Dibuat untuk NIS: <?php echo htmlspecialchars($_SESSION['last_qr_nis']); ?></h2>
        <img src="<?php echo htmlspecialchars($_SESSION['last_qr']); ?>" class="h-16 rounded-xl bg-white p-2 shadow" />
    </section>
    <?php unset($_SESSION['last_qr'], $_SESSION['last_qr_nis']); endif; ?>

    <section class="rounded-[32px] bg-white p-8 shadow-sm border border-slate-200">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Data Siswa</h2>
                <p class="text-slate-500">Kelola siswa (Total: <?php echo $total_data; ?>)</p>
            </div>
            <input type="text" id="searchInput" placeholder="Cari nama / NIS..." class="w-full sm:w-72 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:outline-none focus:border-emerald-500 transition">
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200">
            <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap">
                <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">NIS</th>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Kelas</th>
                        <th class="px-6 py-4 text-center">Identitas</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-slate-100">
                    <?php 
                    $no = $halaman_awal + 1;
                    while ($row = mysqli_fetch_assoc($result)): 
                        $hasQr = file_exists(__DIR__ . '/../uploads/qr_codes/' . $row['nis'] . '.png');
                    ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4"><?php echo $no++; ?></td>
                        <td class="px-6 py-4 font-medium text-slate-900"><?php echo htmlspecialchars($row['nis']); ?></td>
                        <td class="px-6 py-4 font-medium text-slate-900"><?php echo htmlspecialchars($row['nama_siswa']); ?></td>
                        <td class="px-6 py-4"><span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700"><?php echo htmlspecialchars($row['kelas']); ?></span></td>
                        <td class="px-6 py-4 text-center">
                            <?php if ($hasQr): ?>
                                <a href="RenderKartu.php?id=<?php echo $row['id_siswa']; ?>" target="_blank" class="inline-flex items-center gap-1.5 border border-slate-200 bg-white px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 transition shadow-sm">Lihat QR</a>
                            <?php else: ?>
                                <span class="text-xs text-slate-400 italic">- Belum ada -</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right flex gap-3 justify-end">
                            <a href="EditSiswa.php?id=<?php echo $row['id_siswa']; ?>" class="text-slate-400 hover:text-amber-500 transition">Edit</a>
                            <a href="#" 
                               onclick="konfirmasiHapus('../controllers/SiswaController.php?action=delete&id=<?php echo $row['id_siswa']; ?>', '<?php echo htmlspecialchars($row['nama_siswa']); ?>'); return false;" 
                               class="text-slate-400 hover:text-rose-500 transition">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex justify-center gap-2">
            <a href="?halaman=<?php echo max(1, $halaman - 1); ?>" class="px-4 py-2 border rounded-lg hover:bg-emerald-50 <?php echo ($halaman <= 1) ? 'opacity-50 pointer-events-none' : ''; ?>">Prev</a>
            <?php for($i=1; $i<=$total_halaman; $i++): ?>
                <a href="?halaman=<?php echo $i; ?>" class="px-4 py-2 border rounded-lg <?php echo ($halaman == $i) ? 'bg-emerald-600 text-white' : 'hover:bg-emerald-50'; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            <a href="?halaman=<?php echo min($total_halaman, $halaman + 1); ?>" class="px-4 py-2 border rounded-lg hover:bg-emerald-50 <?php echo ($halaman >= $total_halaman) ? 'opacity-50 pointer-events-none' : ''; ?>">Next</a>
        </div>
    </section>
</div>

<script>
    function konfirmasiHapus(url, namaSiswa) {
        if (confirm('Yakin ingin menghapus ' + namaSiswa + '?')) {
            window.location.href = url;
        }
    }

    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        document.querySelectorAll('#tableBody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(filter) ? '' : 'none';
        });
    });
</script>

<?php include '../includes/footer.php'; ?>