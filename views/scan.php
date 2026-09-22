<?php
// 1. Middleware dan Database HARUS di atas header
include '../middleware/UserMiddleware.php';
include '../config/database.php';
include '../includes/header.php'; 
?>

<div class="max-w-4xl mx-auto mt-10 p-8 bg-white rounded-[32px] shadow-sm border border-slate-200">
    <h2 class="text-2xl font-bold mb-6 text-slate-800">Pemindaian Absensi</h2>

    <div id="reader" class="w-full bg-slate-900 rounded-2xl overflow-hidden mb-4 shadow-inner"></div>

    <div id="status" class="p-4 bg-emerald-50 rounded-2xl text-center font-bold text-emerald-700">
        Siap melakukan scan...
    </div>

    <button id="btn-scan" class="w-full mt-4 bg-emerald-600 text-white p-4 rounded-2xl font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/20">
        Klik Untuk Aktifkan Kamera
    </button>

    <div class="mt-8 p-6 border-2 border-dashed border-slate-200 rounded-3xl text-center bg-slate-50">
        <p class="text-slate-500 mb-4 font-semibold text-sm uppercase tracking-wider">Atau Upload Foto QR</p>
        <input type="file" id="qr-input-file" accept="image/*"
               class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
const statusBox = document.getElementById('status');


// AKTIFKAN KAMERA
document.getElementById('btn-scan').addEventListener('click', function () {
    const html5QrCode = new Html5Qrcode("reader");
    this.style.display = 'none';
    statusBox.innerText = "Mencari QR Code...";
    statusBox.classList.replace('bg-slate-100', 'bg-emerald-100');

    html5QrCode.start(
        { facingMode: "environment" }, // Pakai kamera belakang (environment)
        { fps: 10, qrbox: { width: 250, height: 250 } },
        (decodedText) => {
            statusBox.innerText = "QR berhasil dibaca! Mengalihkan...";
            html5QrCode.stop().then(() => {
                window.location.href = '../controllers/AbsensiController.php?nis=' + encodeURIComponent(decodedText);
            });
        }
    ).catch(err => {
        console.error(err);
        statusBox.innerText = "Gagal mengakses kamera";
    });
});

// ======================================
// UPLOAD FILE QR
// ======================================
document.getElementById('qr-input-file').addEventListener('change', function (e) {
    if (e.target.files.length === 0) return;
    const file = e.target.files[0];
    statusBox.innerText = "Membaca file QR...";

    const fileScanner = new Html5Qrcode("reader");
    fileScanner.scanFile(file, true)
        .then(decodedText => {
            window.location.href = '../controllers/AbsensiController.php?nis=' + encodeURIComponent(decodedText);
        })
        .catch(err => {
            alert("QR tidak dapat dibaca.");
            statusBox.innerText = "Siap melakukan scan...";
        });
});
</script>

<?php include '../includes/footer.php'; ?>