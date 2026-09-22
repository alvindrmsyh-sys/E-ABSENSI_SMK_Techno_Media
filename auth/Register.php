<?php
session_start();
include '../config/database.php';

// PROSES REGISTER
if(isset($_POST['register'])){

    $nama      = mysqli_real_escape_string($conn, $_POST['nama']);
    $username  = mysqli_real_escape_string($conn, $_POST['username']);
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm_password'];

    // VALIDASI PASSWORD
    if($password != $confirm){

        $error = "Konfirmasi password tidak cocok!";

    }else{

        // CEK USERNAME
        $cek = mysqli_query(
            $conn,
            "SELECT * FROM users WHERE username='$username'"
        );

        if(mysqli_num_rows($cek) > 0){

            $error = "Username sudah digunakan!";

        }else{

            // HASH PASSWORD
            $hashPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // SIMPAN ADMIN
            $insert = mysqli_query(
                $conn,
                "INSERT INTO users
                (
                    nama,
                    username,
                    password,
                    role
                )

                VALUES
                (
                    '$nama',
                    '$username',
                    '$hashPassword',
                    'admin'
                )"
            );

            if($insert){

                $_SESSION['success'] =
                    "Registrasi admin berhasil!";

                header('Location: ../index.php');
                exit;

            }else{

                $error = "Gagal register!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register Admin | E-ABSENSI</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="min-h-screen bg-gradient-to-br from-emerald-700 via-emerald-600 to-emerald-500 flex items-center justify-center p-6">

    <div class="w-full max-w-md bg-white rounded-[32px] p-10 shadow-2xl">

        <div class="text-center">

            <h1 class="text-4xl font-bold text-emerald-600">
                E-ABSENSI
            </h1>

            <p class="mt-3 text-slate-500">
                Registrasi Admin Baru
            </p>

        </div>

        <?php if(isset($error)): ?>

        <div class="mt-6 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm">

            <?php echo $error; ?>

        </div>

        <?php endif; ?>

        <form method="POST" class="mt-8 space-y-5">

            <!-- NAMA -->
            <div>

                <label class="text-sm font-semibold text-slate-700">
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    name="nama"
                    required
                    class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                >

            </div>

            <!-- USERNAME -->
            <div>

                <label class="text-sm font-semibold text-slate-700">
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    required
                    class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                >

            </div>

            <!-- PASSWORD -->
            <div>

                <label class="text-sm font-semibold text-slate-700">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                >

            </div>

            <!-- CONFIRM -->
            <div>

                <label class="text-sm font-semibold text-slate-700">
                    Konfirmasi Password
                </label>

                <input
                    type="password"
                    name="confirm_password"
                    required
                    class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                >

            </div>

            <!-- BUTTON -->
            <button
                type="submit"
                name="register"
                class="w-full rounded-2xl bg-emerald-600 py-4 text-white font-bold hover:bg-emerald-500 transition"
            >

                Register Admin

            </button>

        </form>

        <!-- LOGIN -->
        <div class="mt-8 text-center text-sm text-slate-500">

            Sudah punya akun?

            <a href="../index.php"
               class="font-semibold text-emerald-600 hover:text-emerald-500">

                Login

            </a>

        </div>

    </div>

</body>
</html>