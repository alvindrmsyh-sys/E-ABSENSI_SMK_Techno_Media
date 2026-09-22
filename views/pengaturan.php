<?php
session_start();
include '../config/database.php';
include '../includes/header.php';

// Ambil data
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengaturan WHERE id=1"));
?>

<div class="fade-in max-w-4xl">
    <h2 class="text-3xl font-bold text-slate-800">Pengaturan Sekolah</h2>
    <p class="text-slate-500 mb-8">Ubah identitas sekolah untuk keperluan laporan resmi.</p>

    <form action="../actions/UpdatePengaturan.php" method="POST" class="space-y-6 bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700">Nama Sekolah</label>
                <input type="text" name="nama_sekolah" value="<?php echo $data['nama_sekolah']; ?>" class="w-full mt-2 p-4 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" value="<?php echo $data['tahun_ajaran']; ?>" class="w-full mt-2 p-4 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>
        </div>
        <div>
            <label class="block text-sm font-bold text-slate-700">Alamat</label>
            <input type="text" name="alamat" value="<?php echo $data['alamat']; ?>" class="w-full mt-2 p-4 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
        </div>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700">Telepon</label>
                <input type="text" name="telepon" value="<?php echo $data['telepon']; ?>" class="w-full mt-2 p-4 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Nama Kepala Sekolah</label>
                <input type="text" name="kepala_sekolah" value="<?php echo $data['kepala_sekolah']; ?>" class="w-full mt-2 p-4 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>
        </div>
        <button type="submit" class="w-full bg-emerald-700 text-white font-bold py-4 rounded-2xl hover:bg-emerald-800 transition btn-animate">Simpan Perubahan</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>