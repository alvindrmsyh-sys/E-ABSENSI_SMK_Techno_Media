<?php
session_start();
if(!isset($_SESSION['login'])){ header("Location: ../index.php"); exit; }
if($_SESSION['role'] != 'admin'){ header("Location: ../index.php"); exit; }

include '../config/database.php';

// DATA STATISTIK
$totalSiswa   = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM siswa"));
$totalGuru    = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE role='guru'"));
$hadirHariIni = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM absensi WHERE tanggal = CURDATE()"));
?>

<?php include '../includes/header.php'; ?>

<div class="space-y-8 fade-in">
    
    <div class="flex items-end justify-between">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Dashboard Admin</h1>
            <p class="text-slate-500 mt-2 font-medium">Selamat datang, <span class="text-emerald-700 font-bold"><?php echo $_SESSION['nama']; ?></span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php 
        $stats = [
            ['Total Siswa', $totalSiswa],
            ['Total Guru', $totalGuru],
            ['Absensi Hari Ini', $hadirHariIni]
        ];
        foreach($stats as $s): ?>
        <div class="rounded-3xl bg-white p-8 shadow-sm border border-slate-200 card-hover">
            <p class="text-sm font-bold uppercase tracking-widest text-slate-400"><?php echo $s[0]; ?></p>
            <h3 class="mt-4 text-5xl font-black text-slate-800"><?php echo $s[1]; ?></h3>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="rounded-[32px] bg-white p-8 shadow-sm border border-slate-200">
        <h2 class="text-2xl font-bold text-slate-900 mb-8">Menu Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            $menus = [
                ['Kelola Siswa', 'Tambah dan generate QR', 'bg-emerald-50 text-emerald-800 border border-emerald-100', '../views/siswa.php'],
                ['Scan QR', 'Absensi realtime', 'bg-emerald-50 text-emerald-800 border border-emerald-100', '../views/scan.php'],
                ['Laporan', 'Data absensi siswa', 'bg-emerald-50 text-emerald-800 border border-emerald-100', '../views/laporan.php'],
                ['Pengaturan', 'Atur sistem sekolah', 'bg-slate-50 text-slate-700 border border-slate-100', '../views/pengaturan.php'],
            ];
            foreach($menus as $m): ?>
            <a href="<?php echo $m[3]; ?>" class="<?php echo $m[2]; ?> rounded-3xl p-6 transition-all btn-animate hover:shadow-lg hover:border-emerald-200">
                <h4 class="text-xl font-bold"><?php echo $m[0]; ?></h4>
                <p class="text-slate-500 text-sm mt-2"><?php echo $m[1]; ?></p>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>