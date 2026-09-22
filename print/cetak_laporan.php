<?php
session_start();
require '../vendor/autoload.php';
include '../config/database.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION['login'])) {
    header('Location: ../index.php');
    exit;
}

// 1. Ambil data pengaturan
$sekolah = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengaturan WHERE id=1"));

$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : '';
$query = "SELECT absensi.*, siswa.nama_siswa, siswa.kelas
          FROM absensi
          JOIN siswa ON absensi.nis = siswa.nis";

if($tanggal != ''){
    $query .= " WHERE absensi.tanggal = '$tanggal' ";
}
$query .= " ORDER BY absensi.tanggal DESC, absensi.jam DESC";

$result = mysqli_query($conn, $query);
$judulTanggal = ($tanggal != '') ? 'Tanggal : ' . date('d-m-Y', strtotime($tanggal)) : 'Semua Data Absensi';

$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body{ font-family: Arial, sans-serif; padding: 20px; }
        .kop-surat{ text-align: center; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat h2{ margin: 0; text-transform: uppercase; }
        .kop-surat p{ margin: 2px 0; font-size: 13px; }
        h1{ text-align: center; margin-bottom: 5px; color: #10b981; }
        .sub{ text-align: center; color: #555; margin-bottom: 25px; }
        table{ width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th{ background: #10b981; color: white; padding: 12px; border: 1px solid #ddd; font-size: 14px; }
        table td{ border: 1px solid #ddd; padding: 10px; font-size: 13px; text-align: center; }
        .badge{ background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 8px; font-size: 12px; }
        .badge-izin{ background: #fef9c3; color: #854d0e; padding: 4px 8px; border-radius: 8px; font-size: 12px; }
        .badge-sakit{ background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 8px; font-size: 12px; }
        .badge-alpha{ background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 8px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="kop-surat">
        <h2>'.$sekolah['nama_sekolah'].'</h2>
        <p>'.$sekolah['alamat'].' | Telp: '.$sekolah['telepon'].'</p>
        <p style="font-weight: bold;">Tahun Ajaran: '.$sekolah['tahun_ajaran'].'</p>
    </div>

    <h1>Laporan Absensi Siswa</h1>
    <div class="sub">'.$judulTanggal.'</div>
    <table>
        <thead>
            <tr>
                <th>No</th><th>Tanggal</th><th>Nama Siswa</th><th>Kelas</th><th>Jam Masuk</th><th>Status</th>
            </tr>
        </thead>
        <tbody>';

$no = 1;
if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        $status = $row['status'];
        $badge = ($status == 'Hadir') ? 'badge' : (($status == 'Izin') ? 'badge-izin' : (($status == 'Sakit') ? 'badge-sakit' : 'badge-alpha'));
        $html .= '<tr>
            <td>'.$no++.'</td>
            <td>'.$row['tanggal'].'</td>
            <td>'.$row['nama_siswa'].'</td>
            <td>'.$row['kelas'].'</td>
            <td>'.$row['jam'].'</td>
            <td><span class="'.$badge.'">'.$status.'</span></td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="6">Tidak ada data absensi</td></tr>';
}

$html .= '</tbody></table></body></html>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("laporan-absensi.pdf", array("Attachment" => false));
?>