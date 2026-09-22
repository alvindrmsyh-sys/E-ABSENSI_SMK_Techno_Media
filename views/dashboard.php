<?php
session_start();
include '../config/database.php';
include '../includes/header.php';

if (!isset($_SESSION['login'])) {
    header('Location: ../index.php');
    exit;
}

// SESSION
$role = $_SESSION['role'];
$idGuru = $_SESSION['id_user'];


// FILTER QUERY BERDASARKAN ROLE

if($role == 'admin'){
    $qSiswa = mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa");
    $qKelas = mysqli_query($conn, "SELECT COUNT(DISTINCT kelas) as total FROM siswa");
    $qHadir = mysqli_query($conn, "SELECT COUNT(*) as total FROM absensi WHERE tanggal = CURDATE() AND status = 'Hadir'");
    
    $aktivitas = mysqli_query($conn, "
        SELECT absensi.*, siswa.nama_siswa 
        FROM absensi 
        JOIN siswa ON absensi.nis = siswa.nis 
        ORDER BY absensi.id_absensi DESC 
        LIMIT 5");
} else {
    $qSiswa = mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa WHERE id_guru = '$idGuru'");
    $qKelas = mysqli_query($conn, "SELECT COUNT(DISTINCT kelas) as total FROM siswa WHERE id_guru = '$idGuru'");
    $qHadir = mysqli_query($conn, "SELECT COUNT(*) as total FROM absensi JOIN siswa ON absensi.nis = siswa.nis WHERE siswa.id_guru = '$idGuru' AND absensi.tanggal = CURDATE() AND absensi.status = 'Hadir'");
    
    $aktivitas = mysqli_query($conn, "
        SELECT absensi.*, siswa.nama_siswa 
        FROM absensi 
        JOIN siswa ON absensi.nis = siswa.nis 
        WHERE siswa.id_guru = '$idGuru' 
        ORDER BY absensi.id_absensi DESC 
        LIMIT 5");
}

$totalSiswa = mysqli_fetch_assoc($qSiswa)['total'];
$totalKelas = mysqli_fetch_assoc($qKelas)['total'];
$hadirHariIni = mysqli_fetch_assoc($qHadir)['total'];
$belumHadir = max(0, $totalSiswa - $hadirHariIni);
?>

<div class="space-y-8">
    <section class="rounded-[32px] bg-gradient-to-r from-emerald-600 to-teal-500 p-10 text-white shadow-xl">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.25em] text-emerald-100">Dashboard</p>
                <h1 class="mt-3 text-4xl font-bold">Selamat Datang, <?php echo $_SESSION['nama']; ?></h1>
                <p class="mt-4 max-w-2xl text-emerald-50">Sistem absensi SMK TECHNO MEDIA.</p>
            </div>
            <div class="rounded-[28px] bg-white/10 px-6 py-4 backdrop-blur">
                <p class="text-sm text-emerald-100">Status</p>
                <p class="mt-2 text-2xl font-bold">Online</p>
            </div>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-4">
        <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-500">Jumlah Siswa</p>
            <p class="mt-4 text-4xl font-bold text-slate-800"><?php echo $totalSiswa; ?></p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-500">Jumlah Kelas</p>
            <p class="mt-4 text-4xl font-bold text-slate-800"><?php echo $totalKelas; ?></p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-500">Hadir Hari Ini</p>
            <p class="mt-4 text-4xl font-bold text-emerald-600"><?php echo $hadirHariIni; ?></p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-500">Belum Hadir</p>
            <p class="mt-4 text-4xl font-bold text-red-500"><?php echo $belumHadir; ?></p>
        </div>
    </section>

    <section class="rounded-[32px] bg-white p-8 shadow-sm border border-slate-200">
        <h2 class="text-2xl font-bold text-slate-800">Aktivitas Terbaru</h2>
        <p class="mt-2 text-slate-500">Riwayat absensi terbaru.</p>
        <div class="mt-8 space-y-4">
            <?php if(mysqli_num_rows($aktivitas) > 0): ?>
                <?php while($a = mysqli_fetch_assoc($aktivitas)): 
                    $warna = ($a['status'] == 'Hadir') ? 'text-emerald-600' : 
                             (($a['status'] == 'Sakit') ? 'text-blue-600' : 
                             (($a['status'] == 'Izin') ? 'text-amber-500' : 'text-red-600'));
                ?>
                <div class="flex items-center justify-between rounded-2xl border border-slate-200 p-5 hover:border-emerald-200 transition">
                    <div>
                        <h3 class="font-semibold text-slate-800"><?php echo $a['nama_siswa']; ?></h3>
                        <p class="text-sm text-slate-500">NIS: <?php echo $a['nis']; ?></p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold <?php echo $warna; ?>"><?php echo $a['status']; ?></p>
                        <p class="text-sm text-slate-500"><?php echo $a['tanggal']; ?> - <?php echo $a['jam']; ?></p>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="rounded-3xl bg-slate-50 p-10 text-center text-slate-500">Belum ada aktivitas absensi hari ini.</div>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php include '../includes/footer.php'; ?>