<?php
session_start();
include '../config/database.php';

// Cek login
if (!isset($_SESSION['login'])) {
    header('Location: ../index.php');
    exit;
}

if (!isset($_GET['id'])) {
    die("ID Siswa tidak ditemukan.");
}

$id_siswa = $_GET['id'];
$query = "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'";
$result = mysqli_query($conn, $query);
$siswa = mysqli_fetch_assoc($result);

if (!$siswa) {
    die("Data siswa tidak ditemukan.");
}

$qrFile = '../uploads/qr_codes/' . $siswa['nis'] . '.png';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Absensi - <?= htmlspecialchars($siswa['nama_siswa']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        /* Agar saat di-print ke kertas, warna background hijau emerald tetap muncul */
        @media print {
            body { background-color: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-slate-200 flex flex-col items-center justify-center min-h-screen p-6">

    <div id="kartu-absen" class="bg-white w-[380px] shadow-2xl relative overflow-hidden rounded-md border border-gray-100">
        
        <div class="bg-[#10B981] pt-10 pb-12 text-center text-white">
            <h2 class="font-bold tracking-widest text-lg font-mono uppercase">KARTU ABSENSI SISWA</h2>
        </div>
        
        <div class="px-8 pb-12 bg-white relative">
            
            <div class="flex justify-center -mt-10 mb-8 relative z-10">
                <div class="bg-white p-1 shadow-md">
                    <?php if (file_exists(__DIR__ . '/../uploads/qr_codes/' . $siswa['nis'] . '.png')): ?>
                        <img src="<?= htmlspecialchars($qrFile) ?>" alt="QR Code" class="w-52 h-52 object-contain">
                    <?php else: ?>
                        <div class="w-52 h-52 flex items-center justify-center border-2 border-dashed border-gray-300 text-sm text-gray-400">QR Belum Dibuat</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="space-y-4 text-left font-mono text-gray-900 text-sm font-bold pl-4">
                <p>Nama &nbsp;&nbsp;: <?= htmlspecialchars($siswa['nama_siswa']) ?></p>
                <p>NIS &nbsp;&nbsp;&nbsp;&nbsp;: <?= htmlspecialchars($siswa['nis']) ?></p>
                <p class="text-gray-500 font-medium">Kelas &nbsp;: <?= htmlspecialchars($siswa['kelas']) ?></p>
            </div>

        </div>
    </div>

    <div class="mt-8 flex gap-3 no-print">
        <button onclick="downloadKartu()" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-emerald-700 transition flex items-center gap-2 shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Download Kartu (.png)
        </button>
        <button onclick="window.print()" class="bg-slate-800 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-slate-700 transition shadow-lg">
             Print Kertas
        </button>
        <button onclick="window.close()" class="bg-white border border-slate-300 text-slate-600 px-5 py-2.5 rounded-xl font-bold hover:bg-slate-50 transition">
            Tutup
        </button>
    </div>

    <script>
        function downloadKartu() {
            // Mengambil elemen HTML area kartu
            const card = document.getElementById('kartu-absen');
            
            // Menggunakan html2canvas untuk mengubah elemen HTML menjadi canvas gambar
            // scale: 3 digunakan agar hasil gambar tajam (resolusi tinggi)
            html2canvas(card, { scale: 3 }).then(canvas => {
                // Membuat link unduhan otomatis
                let link = document.createElement('a');
                link.download = 'Kartu_Absen_<?= htmlspecialchars($siswa['nis']) ?>.png';
                // Mengonversi canvas menjadi data URL PNG
                link.href = canvas.toDataURL("image/png");
                // Memicu klik pada link unduhan
                link.click();
            });
        }
    </script>

</body>
</html>