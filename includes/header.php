<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-ABSENSI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#10b981',
                        brandDark: '#064e3b',
                        brandSoft: '#ecfdf5'
                    }
                }
            }
        }
    </script>
    <style>
        *{ scroll-behavior: smooth; }
        body{ font-family: 'Segoe UI', sans-serif; }
        .page-bg{ background: linear-gradient(135deg, #ecfdf5 0%, #ffffff 40%, #f0fdf4 100%); }
        ::-webkit-scrollbar{ width: 8px; height: 8px; }
        ::-webkit-scrollbar-thumb{ background: rgba(16,185,129,0.25); border-radius: 999px; }
        .sidebar-scroll::-webkit-scrollbar{ width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb{ background: rgba(255,255,255,0.15); border-radius: 999px; }
        .fade-in{ animation: fade .6s ease; }
        @keyframes fade{ from{ opacity: 0; transform: translateY(10px); } to{ opacity: 1; transform: translateY(0); } }
        .glass{ background: rgba(255,255,255,0.75); backdrop-filter: blur(14px); }
    </style>
</head>

<body class="page-bg text-slate-900 h-screen overflow-hidden">
<div class="flex h-screen overflow-hidden">
    <aside id="sidebar" class="fixed lg:static z-50 inset-y-0 left-0 w-72 bg-brandDark text-white flex flex-col transform -translate-x-full lg:translate-x-0 transition duration-300 ease-in-out shadow-2xl">
        <div class="px-7 py-8 border-b border-white/10">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-white flex items-center justify-center shadow-lg overflow-hidden">
                    <img src="../assets/images/logoSMK.jpg" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-wide">E-ABSENSI</h1>
                    <p class="text-sm text-emerald-100/70">SMK TECHNO MEDIA</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto sidebar-scroll px-4 py-6 space-y-2">
            <a href="../admin/Profil.php" class="flex items-center gap-4 rounded-2xl px-5 py-4 text-sm font-medium transition-all <?php echo ($currentPage == 'Profil.php') ? 'bg-emerald-500 text-white shadow-lg' : 'hover:bg-white/10 text-emerald-50'; ?>">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil Saya
            </a>

            <?php $menuItems = [
                ['dashboard.php', 'Dashboard', 'M3 10h4V3H3v7zm7 11h4V3h-4v18zm7 0h4v-8h-4v8z'],
                ['siswa.php', 'Data Siswa', 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0112 20.055a12.083 12.083 0 01-6.16-9.477L12 14z'],
                ['scan.php', 'Scan QR', 'M3 7V5a2 2 0 012-2h2m10 0h2a2 2 0 012 2v2m0 10v2a2 2 0 01-2 2h-2m-10 0H5a2 2 0 01-2-2v-2'],
                ['laporan.php', 'Laporan', 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z']
            ];
            foreach($menuItems as $m): ?>
                <a href="../views/<?php echo $m[0]; ?>" class="flex items-center gap-4 rounded-2xl px-5 py-4 text-sm font-medium transition-all <?php echo ($currentPage == $m[0]) ? 'bg-emerald-500 text-white shadow-lg' : 'hover:bg-white/10 text-emerald-50'; ?>">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="<?php echo $m[2]; ?>"/></svg>
                    <?php echo $m[1]; ?>
                </a>
            <?php endforeach; ?>

            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <a href="../admin/guru.php" class="flex items-center gap-4 rounded-2xl px-5 py-4 text-sm font-medium transition-all <?php echo ($currentPage == 'guru.php') ? 'bg-emerald-500 text-white shadow-lg' : 'hover:bg-white/10 text-emerald-50'; ?>">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0112 20.055a12.083 12.083 0 01-6.16-9.477L12 14z"/></svg>
                    Kelola Guru
                </a>
                <a href="../views/pengaturan.php" class="flex items-center gap-4 rounded-2xl px-5 py-4 text-sm font-medium transition-all <?php echo ($currentPage == 'pengaturan.php') ? 'bg-emerald-500 text-white shadow-lg' : 'hover:bg-white/10 text-emerald-50'; ?>">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                    Pengaturan
                </a>
            <?php endif; ?>
        </nav>

        <div class="border-t border-white/10 p-6">
            <div class="rounded-3xl bg-white/5 p-5 backdrop-blur">
                <p class="text-sm text-emerald-100 font-semibold">Sistem Aktif</p>
                <p class="mt-2 text-xs text-emerald-100/60 leading-relaxed">E-ABSENSI v1.0<br>QR Attendance System</p>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <header class="sticky top-0 z-30 glass border-b border-slate-200 px-4 lg:px-8 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button id="menuBtn" class="lg:hidden flex items-center justify-center h-12 w-12 rounded-2xl border border-slate-200 bg-white shadow-sm hover:bg-slate-50 transition-all">☰</button>
            </div>

            <?php
            $userId = $_SESSION['id_user'];
            $sqlHeader = mysqli_query($conn, "SELECT foto FROM users WHERE id_user = '$userId'");
            $uH = mysqli_fetch_assoc($sqlHeader);
            $fotoProfil = ($uH && $uH['foto'] != '' && file_exists('../assets/images/'.$uH['foto'])) ? $uH['foto'] : 'default.png';
            ?>

            <div class="flex items-center gap-3">
                <a href="../admin/Profil.php" class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 lg:px-4 py-2 shadow-sm">
                    <img src="../assets/images/<?php echo $fotoProfil; ?>" class="h-11 w-11 rounded-full object-cover shadow-lg border border-slate-100">
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-semibold text-slate-800"><?php echo $_SESSION['nama']; ?></p>
                        <p class="text-xs text-slate-500"><?php echo ucfirst($_SESSION['role']); ?></p>
                    </div>
                </a>
                <a href="../auth/logout.php" class="rounded-2xl bg-red-500 px-5 py-3 text-sm font-semibold text-white shadow-lg hover:bg-red-400">Logout</a>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 lg:p-8 fade-in h-0">