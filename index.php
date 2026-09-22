<?php
session_start();

if (isset($_SESSION['login'])) {

    if($_SESSION['role'] == 'admin'){

        header('Location: admin/dashboard.php');

    }else{

        header('Location: views/dashboard.php');

    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login | E-ABSENSI</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#10b981'
                    }
                }
            }
        }
    </script>

</head>

<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-slate-100 overflow-hidden">

    <!-- BACKGROUND BLUR -->
    <div class="absolute top-0 left-0 w-72 h-72 bg-emerald-300 rounded-full blur-3xl opacity-20 -translate-x-20 -translate-y-20"></div>

    <div class="absolute bottom-0 right-0 w-96 h-96 bg-emerald-400 rounded-full blur-3xl opacity-20 translate-x-20 translate-y-20"></div>

    <!-- WRAPPER -->
    <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-10">

        <div class="grid w-full max-w-7xl overflow-hidden rounded-[40px] bg-white shadow-2xl lg:grid-cols-2">

            <!-- LEFT -->
            <div class="relative hidden lg:flex flex-col justify-between bg-emerald-600 p-14 text-white">

                <div>

                    <div class="inline-flex items-center gap-3">

                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20 backdrop-blur">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-8 w-8"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />

                            </svg>

                        </div>

                        <div>

                            <p class="text-sm uppercase tracking-[0.3em] text-emerald-100">
                                E-ABSENSI
                            </p>

                            <h1 class="text-3xl font-bold">
                                SMK TECHNO MEDIA
                            </h1>

                        </div>

                    </div>

                    <div class="mt-16">

                        <h2 class="text-5xl font-bold leading-tight">

                            Absensi Digital
                            <br>
                            Lebih Cepat &
                            Modern

                        </h2>

                        <p class="mt-6 max-w-xl text-lg text-emerald-50/90 leading-relaxed">

                            

                        </p>

                    </div>

                </div>

                <!-- FEATURE -->
                <div class="grid grid-cols-2 gap-4">

                    <div class="rounded-3xl bg-white/10 p-5 backdrop-blur">

                        <div class="text-3xl font-bold">QR</div>

                        <p class="mt-2 text-sm text-emerald-50/80">
                            Scan absensi super cepat
                        </p>

                    </div>

                    <div class="rounded-3xl bg-white/10 p-5 backdrop-blur">

                        <div class="text-3xl font-bold">Live</div>

                        <p class="mt-2 text-sm text-emerald-50/80">
                            Data realtime & otomatis
                        </p>

                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="flex items-center justify-center p-8 md:p-14">

                <div class="w-full max-w-md">

                    <!-- MOBILE LOGO -->
                    <div class="mb-10 text-center lg:hidden">

                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-emerald-600 text-white shadow-xl">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-10 w-10"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />

                            </svg>

                        </div>

                        <h1 class="mt-5 text-3xl font-bold text-slate-800">
                            E-ABSENSI
                        </h1>

                    </div>

                    <!-- TITLE -->
                    <div>

                        <h2 class="text-4xl font-bold text-slate-800">
                            Selamat Datang
                        </h2>

                        <p class="mt-3 text-slate-500">
                            Silakan login menggunakan akun guru atau admin sekolah.
                        </p>

                    </div>

                    <!-- FORM -->
                    <form action="auth/login.php"
                          method="POST"
                          class="mt-10 space-y-6">

                        <!-- USERNAME -->
                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-700">

                                Username

                            </label>

                            <div class="relative">

                                <input
                                    type="text"
                                    name="username"
                                    required
                                    placeholder="Masukkan username"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"
                                >

                            </div>

                        </div>

                        <!-- PASSWORD -->
                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-700">

                                Password

                            </label>

                            <div class="relative">

                                <input
                                    type="password"
                                    name="password"
                                    required
                                    placeholder="Masukkan password"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"
                                >

                            </div>

                        </div>

                        <!-- BUTTON LOGIN -->
                        <button
                            type="submit"
                            class="w-full rounded-2xl bg-emerald-600 px-5 py-4 text-lg font-semibold text-white shadow-xl shadow-emerald-500/20 transition hover:bg-emerald-500 hover:scale-[1.01]"
                        >

                            Login Sekarang

                        </button>

                    </form>

                    <!-- REGISTER -->
                    <div class="mt-8 text-center">

                        <p class="text-sm text-slate-500">

                            Belum punya akun admin?

                        </p>

                        <a href="auth/register.php"
                           class="mt-4 inline-flex items-center justify-center rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-700 hover:bg-emerald-100 transition">

                            Register Admin

                        </a>

                    </div>

                    <!-- FOOTER -->
                    <div class="mt-10 text-center text-sm text-slate-400">

                        © <?php echo date('Y'); ?> E-ABSENSI SEKOLAH

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>