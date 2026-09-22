<?php
session_start();
include '../config/database.php';

$username = mysqli_real_escape_string($conn, $_POST['nip']);
$password = $_POST['password'];

// CEK USER
$query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){

    $user = mysqli_fetch_assoc($result);

    // CEK PASSWORD
    if(md5($password) == $user['password']){

        $_SESSION['login'] = true;

        // SESSION
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        // REDIRECT BERDASARKAN ROLE
        if($user['role'] == 'admin'){

            header("Location: ../admin/dashboard.php");
            exit;

        } else if($user['role'] == 'guru'){

            header("Location: ../views/dashboard.php");
            exit;

        } else {

            echo "
            <script>
                alert('Role tidak dikenali!');
                window.location='../index.php';
            </script>
            ";

        }

    } else {

        echo "
        <script>
            alert('Password salah!');
            window.location='../index.php';
        </script>
        ";

    }

} else {

    echo "
    <script>
        alert('User tidak ditemukan!');
        window.location='../index.php';
    </script>
    ";

}
?>