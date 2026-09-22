<?php

session_start();

include '../config/database.php';

$username = mysqli_real_escape_string(
    $conn,
    $_POST['username']
);

$password = $_POST['password'];

// CEK USER
$query = mysqli_query(
    $conn,
    "SELECT * FROM users
     WHERE username='$username'
     LIMIT 1"
);

if(mysqli_num_rows($query) > 0){

    $user = mysqli_fetch_assoc($query);

    $valid = false;

    // =========================
    // PASSWORD HASH BARU
    // =========================
    if(password_verify($password, $user['password'])){

        $valid = true;

    }

    // =========================
    // SUPPORT PASSWORD LAMA MD5
    // =========================
    elseif(md5($password) == $user['password']){

        $valid = true;

    }

    // =========================
    // LOGIN BERHASIL
    // =========================
    if($valid){

        $_SESSION['login'] = true;

        $_SESSION['id_user'] =
            $user['id_user'];

        $_SESSION['nama'] =
            $user['nama'];

        $_SESSION['username'] =
            $user['username'];

        $_SESSION['role'] =
            $user['role'];

        // REDIRECT ROLE
        if($user['role'] == 'admin'){

            header(
                "Location: ../admin/dashboard.php"
            );

        }else{

            header(
                "Location: ../views/dashboard.php"
            );
        }

        exit;

    }else{

        echo "
        <script>
            alert('Password salah!');
            window.location='../index.php';
        </script>
        ";

    }

}else{

    echo "
    <script>
        alert('Username tidak ditemukan!');
        window.location='../index.php';
    </script>
    ";
}
?>