<?php 
session_start();
include '../config/database.php';
include '../includes/header.php'; 

if (!isset($_SESSION['login'])) {
    header('Location: ../index.php');
    exit;
}

$id_user = $_SESSION['id_user'];
$role    = $_SESSION['role'];
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : '';

// 1. Pagination Setup
$batas = 10;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

// 2. Query untuk Menghitung Total Data (untuk pagination)
$queryCount = "SELECT COUNT(*) FROM absensi JOIN siswa ON absensi.nis = siswa.nis";
if($role != 'admin'){
    $queryCount .= " WHERE absensi.id_user = '$id_user'";
    if($tanggal != '') $queryCount .= " AND absensi.tanggal = '$tanggal'";
} else {
    if($tanggal != '') $queryCount .= " WHERE absensi.tanggal = '$tanggal'";
}

$resultCount = mysqli_query($conn, $queryCount);
$total_data = mysqli_fetch_array($resultCount)[0];
$total_halaman = ceil($total_data / $batas);

// 3. Query Data Utama dengan LIMIT
$query = "SELECT absensi.*, siswa.nama_siswa, siswa.kelas 
          FROM absensi 
          JOIN siswa ON absensi.nis = siswa.nis";

if($role != 'admin'){
    $query .= " WHERE absensi.id_user = '$id_user' ";
    if($tanggal != '') $query .= " AND absensi.tanggal = '$tanggal' ";
} else {
    if($tanggal != '') $query .= " WHERE absensi.tanggal = '$tanggal' ";
}

$query .= " ORDER BY absensi.tanggal DESC, absensi.jam DESC LIMIT $halaman_awal, $batas";
$result = mysqli_query($conn, $query);

// Data Statistik
$totalAbsensi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM absensi" . ($role != 'admin' ? " WHERE id_user = '$id_user'" : "")))['total'];
$hadirHariIni = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM absensi WHERE tanggal = CURDATE()" . ($role != 'admin' ? " AND id_user = '$id_user'" : "")))['total'];
?>

<div class="space-y-8">
    <section class="rounded-[32px] bg-white p-8 shadow-sm border border-slate-200">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Laporan Absensi</p>
                <h1 class="mt-3 text-3xl font-semibold text-slate-900">Rekapitulasi Kehadiran Siswa</h1>
                <p class="mt-2 text-slate-500">Pantau absen harian, izin, sakit, alpha dan laporan lengkap.</p>
            </div>
            <div class="flex gap-3 flex-wrap">
                <a href="../views/InputManual.php" class="inline-flex items-center gap-3 rounded-2xl border border-emerald-700 bg-emerald-800 px-5 py-3 text-sm font-semibold text-white shadow hover:bg-emerald-900 transition">Input Manual</a>
                <a href="../print/cetak_laporan.php?tanggal=<?php echo $tanggal; ?>" target="_blank" class="inline-flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-500 px-5 py-3 text-sm font-semibold text-white shadow hover:bg-emerald-600 transition">Cetak Laporan</a>
            </div>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
            <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Total Absensi</p>
            <p class="mt-4 text-3xl font-semibold text-slate-900"><?php echo $totalAbsensi; ?></p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
            <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Hadir Hari Ini</p>
            <p class="mt-4 text-3xl font-semibold text-emerald-600"><?php echo $hadirHariIni; ?></p>
        </div>
    </section>

    <section class="rounded-[32px] bg-white p-8 shadow-sm border border-slate-200">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between mb-8">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Riwayat Absensi</h2>
                <p class="text-slate-500">Data terbaru (Total: <?php echo $total_data; ?>)</p>
            </div>
            <form method="GET" class="flex flex-col gap-3 sm:flex-row">
                <input type="date" name="tanggal" value="<?php echo $tanggal; ?>" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-emerald-500">
                <button type="submit" class="rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-600 transition">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-4 text-left font-semibold text-slate-600">Tanggal</th>
                        <th class="px-5 py-4 text-left font-semibold text-slate-600">Nama Siswa</th>
                        <th class="px-5 py-4 text-left font-semibold text-slate-600">Kelas</th>
                        <th class="px-5 py-4 text-left font-semibold text-slate-600">Jam Masuk</th>
                        <th class="px-5 py-4 text-left font-semibold text-slate-600">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php if(mysqli_num_rows($result) > 0): while($row = mysqli_fetch_assoc($result)): 
                        $bg = ($row['status'] == 'Izin') ? 'bg-yellow-100 text-yellow-700' : (($row['status'] == 'Sakit') ? 'bg-blue-100 text-blue-700' : (($row['status'] == 'Alpha') ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700'));
                    ?>
                    <tr>
                        <td class="px-5 py-4"><?php echo htmlspecialchars($row['tanggal']); ?></td>
                        <td class="px-5 py-4 font-semibold"><?php echo htmlspecialchars($row['nama_siswa']); ?></td>
                        <td class="px-5 py-4"><?php echo htmlspecialchars($row['kelas']); ?></td>
                        <td class="px-5 py-4"><?php echo htmlspecialchars($row['jam']); ?></td>
                        <td class="px-5 py-4"><span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold <?php echo $bg; ?>"><?php echo $row['status']; ?></span></td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="5" class="px-5 py-10 text-center text-slate-500">Tidak ada data absensi.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex justify-center gap-2">
            <a href="?halaman=<?php echo max(1, $halaman - 1); ?>&tanggal=<?php echo $tanggal; ?>" class="px-4 py-2 border rounded-lg hover:bg-emerald-50 <?php echo ($halaman <= 1) ? 'opacity-50 pointer-events-none' : ''; ?>">Prev</a>
            <?php for($i=1; $i<=$total_halaman; $i++): ?>
                <a href="?halaman=<?php echo $i; ?>&tanggal=<?php echo $tanggal; ?>" class="px-4 py-2 border rounded-lg <?php echo ($halaman == $i) ? 'bg-emerald-600 text-white' : 'hover:bg-emerald-50'; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            <a href="?halaman=<?php echo min($total_halaman, $halaman + 1); ?>&tanggal=<?php echo $tanggal; ?>" class="px-4 py-2 border rounded-lg hover:bg-emerald-50 <?php echo ($halaman >= $total_halaman) ? 'opacity-50 pointer-events-none' : ''; ?>">Next</a>
        </div>
    </section>
</div>

<?php include '../includes/footer.php'; ?>