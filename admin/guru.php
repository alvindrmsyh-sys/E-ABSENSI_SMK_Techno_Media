<?php
include '../middleware/AdminMiddleware.php';
include '../config/database.php';
include '../includes/header.php';

// TAMBAH GURU
if(isset($_POST['tambah'])){
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if(mysqli_num_rows($cek) > 0){
        echo "<script>alert('Username sudah digunakan!'); window.location='guru.php';</script>";
    } else {
        mysqli_query($conn, "INSERT INTO users(nama, username, password, role) VALUES('$nama','$username','$password','guru')");
        echo "<script>alert('Akun guru berhasil dibuat!'); window.location='guru.php';</script>";
    }
}

// 1. Pagination Setup
$batas = 10;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

// 2. Query Data
$queryCount = "SELECT COUNT(*) FROM users WHERE role='guru'";
$resultCount = mysqli_query($conn, $queryCount);
$total_data = mysqli_fetch_array($resultCount)[0];
$total_halaman = ceil($total_data / $batas);

$guru = mysqli_query($conn, "SELECT * FROM users WHERE role='guru' ORDER BY id_user DESC LIMIT $halaman_awal, $batas");
?>

<div class="space-y-8 fade-in">
    <section class="rounded-[32px] bg-white p-8 shadow-sm border border-slate-200">
        <h1 class="text-3xl font-bold text-slate-800">Kelola Guru</h1>
        <p class="mt-2 text-slate-500">Tambahkan dan kelola akun guru untuk sistem absensi.</p>
    </section>

    <section class="rounded-[32px] bg-white p-8 shadow-sm border border-slate-200">
        <h2 class="text-2xl font-bold text-slate-800">Tambah Akun Guru</h2>
        <form method="POST" class="grid gap-6 mt-8 lg:grid-cols-3">
            <input type="text" name="nama" placeholder="Nama Guru" required class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 outline-none focus:border-emerald-500">
            <input type="text" name="username" placeholder="Username" required class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 outline-none focus:border-emerald-500">
            <input type="password" name="password" placeholder="Password" required class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 outline-none focus:border-emerald-500">
            <button type="submit" name="tambah" class="lg:col-span-3 rounded-2xl bg-emerald-600 hover:bg-emerald-500 transition px-5 py-4 text-white font-semibold">Tambah Guru</button>
        </form>
    </section>

    <section class="rounded-[32px] bg-white p-8 shadow-sm border border-slate-200">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Data Guru</h2>
            <div class="rounded-full bg-slate-100 px-4 py-2 text-sm text-slate-600 font-bold">Total: <?php echo $total_data; ?></div>
        </div>

        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari nama atau username guru..." 
               class="w-full p-4 mb-6 rounded-2xl border border-slate-200 bg-slate-50 outline-none focus:border-emerald-500 transition">

        <div class="overflow-x-auto">
            <table class="w-full text-left" id="guruTable">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-400 uppercase text-sm">
                        <th class="py-4">No</th>
                        <th class="py-4">Nama</th>
                        <th class="py-4">Username</th>
                        <th class="py-4">Role</th>
                        <th class="py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = $halaman_awal + 1; while($g = mysqli_fetch_assoc($guru)): ?>
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="py-5 font-bold text-slate-700"><?php echo $no++; ?></td>
                        <td class="py-5 font-medium text-slate-800"><?php echo htmlspecialchars($g['nama']); ?></td>
                        <td class="py-5 text-slate-600"><?php echo htmlspecialchars($g['username']); ?></td>
                        <td class="py-5"><span class="rounded-full bg-emerald-100 text-emerald-700 px-3 py-1 text-sm font-semibold">Guru</span></td>
                        <td class="py-5 text-center flex justify-center gap-4">
                            <a href="EditGuru.php?id=<?php echo $g['id_user']; ?>" class="text-slate-400 hover:text-slate-600 transition" title="Edit">Edit</a>
                            <form action="../actions/HapusGuru.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus guru ini?')">
                                <input type="hidden" name="id" value="<?php echo $g['id_user']; ?>">
                                <button type="submit" name="hapus" class="text-slate-400 hover:text-red-600 transition" title="Hapus">Hapus</button>
                            </form>
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
function filterTable() {
    let filter = document.getElementById('searchInput').value.toLowerCase();
    let rows = document.querySelectorAll('#guruTable tbody tr');
    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(filter) ? '' : 'none';
    });
}
</script>

<?php include '../includes/footer.php'; ?>